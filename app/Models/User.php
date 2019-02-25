<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App\Models
 *
 * @property-read integer id
 * @property string name
 * @property string email
 * @property string phone
 * @property string password
 * @property string remember_token
 * @property bool is_admin
 * @property bool activated
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class User extends Authenticatable
{
    use Notifiable;

    public const IS_NOT_ACTIVATED = 0;
    public const IS_ACTIVATED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_admin', 'activated'
    ];

    public function isActivated(): bool
    {
        return $this->activated === self::IS_ACTIVATED;
    }

    public function activate(): void
    {
        $this->activated = self::IS_ACTIVATED;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function scopeActivated(Builder $query): Builder
    {
        return $query->where('activated', self::IS_ACTIVATED);
    }
}
