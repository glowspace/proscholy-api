<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PublicUser extends Authenticatable
{
    protected $primaryKey = 'firebase_id';
    public $incrementing = false;
    
    protected $fillable = [
        'name', 'email', 'firebase_id', 'picture_url'
    ];
    
    public static function getPublicUserFromClaims($claims)
    {
        $user = PublicUser::find($claims['sub']);

        if ($user) {
            return $user;
        }

        $user = new PublicUser([
            'firebase_id' => $claims['sub'],
            'name' => $claims['name'],
            'email' => $claims['email'],
            // 'picture_url' => $claims['picture']
        ]);
        $user->save();

        return $user;
    }

    /**
      * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'sub';
    }
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return (string) $this->claims['sub'];
    }
    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        throw new \Exception('No password for Firebase User');
    }
    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        throw new \Exception('No remember token for Firebase User');
    }
    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     *
     * @return void
     */
    public function setRememberToken($value)
    {
        throw new \Exception('No remember token for Firebase User');
    }
    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        throw new \Exception('No remember token for Firebase User');
    }
}
