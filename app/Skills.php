<?php

namespace App;

use App\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    protected $table = 'skills';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company', 'location', 'country','title','role','description',
    ];

    public function User() {
        return $this->belongsTo('App\User');
    }

}