<?php

namespace App\Exceptions;

use Cirelramos\ErrorNotification\Services\GetInfoFromExceptionService;
use Cirelramos\ErrorNotification\Services\SendEmailNotificationService;
use Cirelramos\ErrorNotification\Services\SendSlackNotificationService;
use Cirelramos\ExternalRequest\Services\CatchExternalRequestService;
use Cirelramos\Languages\Services\LanguageService;
use Cirelramos\Logs\Facades\LogConsoleFacade;
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
use Illuminate\Support\Str;
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

        LogConsoleFacade::full()->tracker()->log('error: ' . $exception->getMessage(), $infoEndpoint);

        if (env('APP_ENV') === 'local' && env('NOT_NOTIFICATION_LOCAL') !== null) {
            SendEmailNotificationService::execute($exception, true);
            SendSlackNotificationService::execute($exception, true);
        }

        if ($exception instanceof HttpException) {

            if($exception->getCode() === Response::HTTP_FORBIDDEN){
                $data = array(
                    $exception->getMessage(),
                    $exception->getCode()
                );

                return response()->json($data, $exception->getCode());
            }
            if ($exception->getCode() !== 0) {
                return $this->errorCatchResponse($exception, $exception->getMessage());
            }
        }

        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if($exception->getCode() === Response::HTTP_UNPROCESSABLE_ENTITY){
            return $this->errorCatchResponse(
                $exception,
                $exception->getMessage(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if($exception instanceof ServiceUnavailableHttpException){
            return $this->errorCatchResponse(
                $exception,
                $exception->getMessage()
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorCatchResponse($exception,translateText('There is no instance of') . " {$model} " . translateText('with the specified id'), \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorCatchResponse(
                $exception,
                translateText('You do not have permissions to execute this action'),
                Response::HTTP_FORBIDDEN,
            );
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorCatchResponse($exception, translateText('The specified URL was not found'), \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorCatchResponse($exception,translateText('The specified method is invalid'), \Symfony\Component\HttpFoundation\Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof HttpException) {
            SendSlackNotificationService::execute($exception);
            return $this->errorCatchResponse(
                $exception,
                $exception->getMessage(),
                $exception->getStatusCode(),
            );
        }

        if ($exception instanceof QueryException) {
            SendEmailNotificationService::execute($exception);
            SendSlackNotificationService::execute($exception);
            $code = $exception->getCode();
            if ($code == 1451) {
                return $this->errorCatchResponse($exception,'You can not delete the resource because the resource is related with someone else.', \Symfony\Component\HttpFoundation\Response::HTTP_CONFLICT);
            }
            return $this->errorCatchResponse($exception,'Query error. ' . $exception->getMessage(), Response::HTTP_SERVICE_UNAVAILABLE);
        }

        if ($exception instanceof TokenMismatchException) {
            return $this->errorCatchResponse(
                $exception,
                $exception->getMessage(),
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        if ($exception instanceof ClientException) {
            $data = CatchExternalRequestService::execute($request, $exception);

            $code = Response::HTTP_SERVICE_UNAVAILABLE;
            if(is_array($data) && array_key_exists('code', $data)) {
              $code = $data[ 'code' ];
            }
            SendEmailNotificationService::execute($exception);
            SendSlackNotificationService::execute($exception);
            return $this->errorExternalRequestMessage($data, $code );
        }

        if ($exception instanceof RequestException) {
            $data = CatchExternalRequestService::execute($request, $exception);

            $code = Response::HTTP_SERVICE_UNAVAILABLE;
            if(is_array($data) && array_key_exists('code', $data)) {
                $code = $data[ 'code' ];
            }

            return $this->errorExternalRequestMessage($data, $code);
        }

        if (env('APP_ENV', null) !== 'dev' && env('APP_ENV', null) !== 'local') {
            SendEmailNotificationService::execute($exception);
            SendSlackNotificationService::execute($exception);
        }

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
