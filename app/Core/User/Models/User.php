<?php

namespace App\Core\User\Models;

use App\Core\Companies\Models\Company;
use App\Core\Roles\Models\Role;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;

/**
 * App\Core\User\Models\User
 *
 * @property int
 *               $id
 * @property string
 *               $name
 * @property string
 *               $email
 * @property Carbon|null
 *               $email_verified_at
 * @property string
 *               $password
 * @property string|null
 *               $remember_token
 * @property Carbon|null
 *               $created_at
 * @property Carbon|null
 *               $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[]
 *                $notifications
 * @property-read int|null
 *                    $notifications_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt( $value )
 * @method static Builder|User whereEmail( $value )
 * @method static Builder|User whereEmailVerifiedAt( $value )
 * @method static Builder|User whereId( $value )
 * @method static Builder|User whereName( $value )
 * @method static Builder|User wherePassword( $value )
 * @method static Builder|User whereRememberToken( $value )
 * @method static Builder|User whereUpdatedAt( $value )
 * @mixin Eloquent
 * @property-read Collection|Client[]
 *                    $clients
 * @property-read int|null
 *                    $clients_count
 * @property-read Collection|Token[]
 *                    $tokens
 * @property-read int|null
 *                    $tokens_count
 * @property-read OauthAccessToken
 *                    $accessToken
 * @property-read int|null $access_token_count
 * @property int|null      $id_type_user relation users_types
 * @method static Builder|User whereIdTypeUser( $value )
 * @property Carbon|null   $deleted_at
 * @property int|null      $created_by
 * @property int|null      $updated_by
 * @property int|null      $deleted_by
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static Builder|User whereCreatedBy( $value )
 * @method static Builder|User whereDeletedAt( $value )
 * @method static Builder|User whereDeletedBy( $value )
 * @method static Builder|User whereUpdatedBy( $value )
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @property-read Collection|Company[] $companies
 * @property-read int|null             $companies_count
 * @property-read Collection|Role[]    $roles
 * @property-read int|null             $roles_count
 * @property int $confirmed
 * @property string|null $confirmation_code
 * @property string|null $confirmation_code_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereConfirmationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereConfirmationCodeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereConfirmed($value)
 * @property int $disabled
 * @property string|null $disabled_at
 * @property string|null $confirmed_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereDisabledAt($value)
 * @property string|null $code_recover_password
 * @property string|null $code_recover_password_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereTokenRecoverPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereTokenRecoverPasswordAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereCodeRecoverPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereCodeRecoverPasswordAt($value)
 * @property string|null $set_password_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Core\User\Models\User whereSetPasswordAt($value)
 */
class User extends Authenticatable
{

    use SoftDeletes;
    use HasApiTokens;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'id_type_user',
        'email',
        'password',
        'confirmation_code_at',
        'confirmation_code',
        'confirmed',
        'confirmed_at',
        'disabled',
        'disabled_at',
        'code_recover_password',
        'code_recover_password_at',
        'set_password_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function accessToken()
    {
        return $this->hasMany(OauthAccessToken::class, 'user_id', 'id')
            ->orderBy('oauth_access_tokens.created_at', 'desc');
    }

    /**
     * @param null $filter
     * @return MorphMany
     */
    public function getBellsNotifications($filter = null)
    {
        switch ($filter) {
            case 'unread':
                $notifications = $this->notifications()->whereNull('read_at');
                break;

            case 'visited':
                $notifications = $this->notifications()->whereNull('visited');
                break;

            default :
                $notifications = $this->notifications();
                break;
        }

        return $notifications->whereIn('type', [
            'App\Core\Notes\Notifications\NoteStoreNotification',
        ]);

    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_has_users', 'id_user', 'id_role')
            ->whereNull('roles_has_users.deleted_at');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'companies_has_users', 'id_user', 'id_company')
            ->whereNull('companies_has_users.deleted_at');
    }
}
