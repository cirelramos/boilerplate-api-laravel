<?php

namespace App\Core\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class OauthAccessToken extends Model
{

    /**
     * Database table name
     */
    protected $table = 'oauth_access_tokens';

    /**
     * Mass assignable columns
     */
    protected $fillable = [
        'expires_at',
        'user_id',
        'client_id',
        'name',
        'scopes',
        'revoked',
        'expires_at',
    ];

    /**
     * Date time columns.
     */
    protected $dates = [ 'expires_at' ];

}
