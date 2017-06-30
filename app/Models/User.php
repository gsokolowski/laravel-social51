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

    public function getAvatarUrl() {

        // mm -     mistery men
        // s -      size
        return "https://www.gravatar.com/avatar/{{ md5($this->email) }}?d=mm&s=40";

    }

    public function friendsOfMine() {
        // set relationship between users table and pivot table friends
        // One user can have many friends

        return $this->belongsToMany('Social\Models\User', 'friends', 'user_id', 'friend_id');

    }

    public function friendOf() {

        // returns friends of this user
        return $this->belongsToMany('Social\Models\User', 'friends', 'friend_id', 'user_id' );
    }

    public function friends() {
        // returns collection of confirmend users

        // This method is to return all friends of a current user ($this->user)
        // where on pivot table 'friends' boolien field accepted is true
        // and from a friend perspective you need to accept him or her too so it need to be
        // both ways acceptence to be able to be a friends

        return $this->friendsOfMine()->wherePivot('accepted', true)->get()
                    ->merge( $this->friendOf()->wherePivot('accepted', true)->get() );
    }

    public function friendRequests() {

        // get all friend requests
        return $this->friendsOfMine()->wherePivot('accepted', false)->get();
    }

    public function friendRequestPending() {
        // get pending friends requests using friendOf relations
        return $this->friendOf()->wherePivot('accepted', false)->get();
    }


    public function hasFriendRequestPending(User $user) {
        // if user has a friend request pending from another user.
        // Another user is passed to method()

        // so return true or false if current user has panding requests from passed in method  user
        // get friendRequestPending where id = passed $user->id and do count() on this
        // if count is 1 then you have pending request from that user if 0 then you don't have it
        // boolien makes true or false
        return (bool) $this->friendRequestPending()->where('id', $user->id)->count();

    }

    public function hasFriendRequestReceived(User $user) {

        return (bool) $this->friendRequests()->where('id', $user->id)->count();
    }

    public function addFriend(User $user) {

        $this->friendOf()->attach($user->id);
    }

    public function acceptFriendRequest(User $user) {

        $this->friendRequests()
            ->where('id', $user->id)
            ->first()
            ->pivot->update([
            'accepted' => true,
        ]);
    }


    public function isFriendsWith(User $user) {

        // if we are friends with particulary user
        return (bool) $this->friends()->where('id', $user->id)->count();
    }

}
