<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles {
        assignRole as protected traitAssignRole;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * Method used in the vendor jeremykenedy/laravel-users to assign a Role to a user.
     * Even if the doc said that this vendor is compatible is compatible with spatie/laravel-permission
     * It is not. The trait Spatie\Permission\Traits\HasRoles has a method assignRole, and the class 
     * jeremykenedy/laravel-users/src/App/Http/Controllers/UsersManagementController.php uses the method $user->attachRole(...roles)
     * which generate an error, because the method does not exist.
     */
    public function attachRole(...$roles) {
        $this->traitAssignRole(...$roles);
    }

    /**
     * Same story for detachAllRoles as attachRole...
     */
    public function detachAllRoles() {
        $this->roles()->detach();
    }
}
