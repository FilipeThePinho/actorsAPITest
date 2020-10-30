<?php

namespace App\Models;

use App\Models\Traits\PrimaryAsUuid;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use PrimaryAsUuid;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = ['name', 'genre_id', 'year', 'synopsis', 'runtime', 'released_at', 'cost', 'created_at', 'updated_at'];

    public function actors()
    {
        return $this->hasMany('App\Models\Actor');
    }

    public function genre()
    {
        return $this->hasOne('App\Models\Genre');
    }

    public function getCostAttribute($value)
    {
        return $value * 0.01;
    }

    public function setCostAttribute($value)
    {
        $this->attributes['cost'] = $value * 100;
    }

}
