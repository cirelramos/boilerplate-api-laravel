<?php

namespace App\Exceptions;

use Cirelramos\ErrorNotification\Services\GetInfoFromExceptionService;
use Cirelramos\ErrorNotification\Services\SendEmailNotificationService;
use Cirelramos\ErrorNotification\Services\SendSlackNotificationService;
use Cirelramos\ExternalRequest\Services\CatchExternalRequestService;
use Cirelramos\Languages\Services\LanguageService;
use Cirelramos\Logs\Services\SendLogConsoleService;
use Cirelramos\Response\Traits\ResponseTrait;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Throwable;

class Handler extends ExceptionHandler
{

    use ResponseTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //commented for debugging
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * @param Throwable $exception
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     */
    public function render($request, Throwable $exception)
    {
        $response = $this->handlerException($request, $exception);

        return $response;
    }

    public function handlerException($request, Throwable $exception)
    {
        $location = new LanguageService();
        $location->execute($request);

        $infoEndpoint = GetInfoFromExceptionService::execute( $exception);

        $sendLogConsoleService = new SendLogConsoleService();
        $sendLogConsoleService->execute('error:' . $exception->getMessage(), $infoEndpoint);

        if (env('APP_ENV') === 'local' && env('NOT_NOTIFICATION_LOCAL') !== null) {
            SendEmailNotificationService::execute($exception, true);
            SendSlackNotificationService::execute($exception, true);
        }

        if ($exception instanceof HttpException) {
            if($exception->getCode() === Response::HTTP_FORBIDDEN){
                return response()->json([], $exception->getCode());
            }
            if ($exception->getCode() !== 0) {
                return $this->errorResponseWithMessage(
                    message: $exception->getMessage(),
                    code   : $exception->getCode()
                );
            }
        }

        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception->getCode() === Response::HTTP_UNPROCESSABLE_ENTITY) {
            return $this->errorResponseWithMessage(
                message: $exception->getMessage(),
                code   : Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($exception instanceof ServiceUnavailableHttpException) {
            return $this->errorResponseWithMessage(message: $exception->getMessage());
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponseWithMessage(
                message: translateText(
                             'There is no instance of'
                         ) . " {$model} " .
                         translateText('with the specified id'),
                code   : \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof AuthenticationException) {
            SendSlackNotificationService::execute($exception);
            return $this->errorResponseWithMessage(
                message: translateText('Unauthenticated'),
                code   : Response::HTTP_UNAUTHORIZED
            );
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponseWithMessage(
                message: translateText('You do not have permissions to execute this action'),
                code   : Response::HTTP_FORBIDDEN
            );
        }

        if ($exception instanceof NotFoundHttpException) {
            $data = [ 'url' => $request->fullUrl(), 'method' => $request->method() ];
            return $this->errorResponseWithMessage($data, translateText('The specified URL was not found'), \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponseWithMessage(
                message: translateText('The specified method is invalid'),
                code   : \Symfony\Component\HttpFoundation\Response::HTTP_METHOD_NOT_ALLOWED
            );
        }

        if ($exception instanceof HttpException) {
            SendSlackNotificationService::execute($exception);
            return $this->errorResponseWithMessage($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException) {
            $sendLogConsoleService->execute('error:' . $exception->getMessage(), $infoEndpoint);
            SendEmailNotificationService::execute($exception);
            SendSlackNotificationService::execute($exception);
            $code = $exception->getCode();
            if ($code == 1451) {
                return $this->errorResponseWithMessage(
                    'You can not delete the resource because the resource is related with someone else.',
                    \Symfony\Component\HttpFoundation\Response::HTTP_CONFLICT
                );
            }
            return $this->errorResponseWithMessage(
                'Query error. ' . $exception->getMessage(), Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        if ($exception instanceof TokenMismatchException) {
            return $this->errorCatchResponse($exception, $exception->getMessage());
        }

        if ($exception instanceof ClientException) {
            $data = CatchExternalRequestService::execute($request, $exception);

            return $this->errorExternalRequestMessage($data, $data[ 'code' ]);
        }

        if ($exception instanceof RequestException) {
            $data = CatchExternalRequestService::execute($request, $exception);

            return $this->errorExternalRequestMessage($data, $data[ 'code' ]);
        }

        if (env('APP_ENV', null) !== 'dev' && env('APP_ENV', null) !== 'local') {
            SendEmailNotificationService::execute($exception);
            SendSlackNotificationService::execute($exception);
        }

        $sendLogConsoleService->execute('error:' . $exception->getMessage(), $infoEndpoint);

        return $this->errorCatchResponse($exception, translateText('Unexpected failure. Try later'));
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        if ($this->isFrontend($request)) {
            return $request->ajax()
                ? response()->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY) : redirect()
                    ->back()
                    ->withInput($request->input())
                    ->withErrors($errors);
        }
        $messageTranslate = __($e->getMessage());
        return $this->errorResponseWithMessage(
            $errors,
            $messageTranslate,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
