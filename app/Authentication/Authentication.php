<?php

namespace App\Authentication;


class Authentication
{

  
    /**
     * If the user is authenticated, return true, else if the user is not authenticated, return false.
     */
    public static function IsAuthentified(): bool
    {
        die($_ENV['apptoken']);
        if (!isset($_ENV['apptoken'])) {
            return false;
        } else {
            $headers = getallheaders();
            foreach ($headers as $name => $value) {
                if (strcmp($name, "token") == 0) {
                    if (strcmp($value,$_ENV['apptoken'])==0) {
                        return true;
                    }
                } 
            
            }
            return false;
        }
       return false;
    }


}
