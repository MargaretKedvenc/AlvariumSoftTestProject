<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait; // <------------------------------- this one
//use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;
    use CrudTrait; // <----- this
    //use HasRoles; // <------ and this

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
      'email',
      'password',
      'last_name',
      'phone',
      'is_admin',
      'email_verified_at',
      'api_token',
      'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token));
    }

    public function setNewApiToken() {
        $this->api_token = Str::uuid();
        $this->save();
    }
}
