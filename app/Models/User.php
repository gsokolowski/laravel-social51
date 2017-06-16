<?php

namespace Social\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;


    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'location',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    // get username or first_name and last_name if available for top menu navigation

    public function getName () {

        if( $this->first_name && $this->last_name ) {
            $fullName = $this->first_name.' '.$this->last_name;
            return $fullName;
        }

        if($this->first_name) {
            return $this->first_name;
        }
        // otherwise
        return null;
    }

    public function getNameOrUsername() {
        // return getName()  or if is not awailable return only username
        return $this->getName() ?: $this->username;
    }

    public function getFirstNameOrUsername() {
        return $this->first_name ?: $this->username;
    }
}
