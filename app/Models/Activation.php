<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Activation
 * @package App\Models
 * @property string token
 * @property string entity_type
 * @property int entity_id
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Activation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'entity_id', 'entity_type'
    ];

    public function scopeTypeUser(Builder $query): Builder
    {
        return $query->where('entity_type', '=', 'user');
    }
}
