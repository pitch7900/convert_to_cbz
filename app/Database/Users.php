<?php

namespace App\Database;

use App\Authentication\Authentication;

/**
 * Class User for Illuminate (DB) queries
 */
class Users extends AbstractModel
{

    protected $table = 'users';
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'login',
        'password',
        'firstname',
        'lastname',
        'darktheme'
    ];

    /**
     * isDarkTheme - Tell if dark Theme is enabled for current user. Default is false
     *
     * @return bool
     */
    public static function isDarkTheme(): bool
    {
        if (Authentication::IsAuthentified()) {
            return boolval(Users::find(Authentication::CurrentUserID())->darktheme);
        } else {
            return false;
        }
    }


    /**
     * getUserName - return user's id name properly formated for display
     *
     * @param  mixed $id
     * @return void
     */
    public static function getUserName($id)
    {
        if (!(is_null($id) || $id == -1)) {
            $user = Users::find($id);
            return $user->firstname . " " . $user->lastname;
        } else {
            return "";
        }
    }
}
