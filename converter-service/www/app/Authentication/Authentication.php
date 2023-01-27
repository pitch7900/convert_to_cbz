<?php

namespace App\Authentication;


class Authentication
{

  
    /**
     * If the user is authenticated, return true, else if the user is not authenticated, return false.
     */
    public static function IsAuthentified($logger): bool
    {
        
        if (is_null(getenv('apptoken'))) {
            $logger->debug("Authentication::IsAuthentified apptoken not set");
            return false;
        } else {
            $headers = getallheaders();
            $logger->debug("Authentication::IsAuthentified ".var_export($headers,true));
            foreach ($headers as $name => $value) {
                if (strcmp($name, "token") == 0) {
                    $logger->debug("Authentication::IsAuthentified token found with value :".$value);
                    if (strcmp($value,getenv('apptoken'))==0) {
                        $logger->debug("Authentication::IsAuthentified token match");
                        return true;
                    } else {
                        $logger->debug("Authentication::IsAuthentified token mismatch :".$value." <> " . getenv('apptoken'));
                        return false;
                    }
                } 
            
            }
            return false;
        }
       return false;
    }


}
