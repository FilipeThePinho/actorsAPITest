<?php

namespace App\Models;

use App\Models\Traits\PrimaryAsUuid;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use PrimaryAsUuid;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = ['name', 'movie_id', 'actor_id', 'created_at', 'updated_at'];

    public function movie()
    {
        return $this->belongsTo('App\Models\Movie', 'movie_id', 'id');
    }

    public function actor()
    {
        return $this->belongsTo('App\Models\Actor', 'actor_id', 'id');
    }

}
