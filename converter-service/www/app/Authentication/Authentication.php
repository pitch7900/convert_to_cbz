<?php

namespace App\Authentication;


class Authentication
{

  
    /**
     * If the user is authenticated, return true, else if the user is not authenticated, return false.
     */
    public static function IsAuthentified($logger): bool
    {
        $headers = getallheaders();
        if (is_null(getenv('apptoken'))) {
            $logger->error("Authentication::IsAuthentified apptoken not set");
            $logger->error("Authentication::IsAuthentified Headers : ".var_export($headers,true));
            $logger->error("Authentication::IsAuthentified ENV : ".$_ENV);
            return false;
        } else {
            $logger->debug("Apptoken env value is ".getenv('apptoken'));
            $logger->error("Authentication::IsAuthentified Headers : ".var_export($headers,true));
            foreach ($headers as $name => $value) {
                if (strcasecmp($name, "token") == 0) {
                    $logger->debug("Authentication::IsAuthentified token found with value :".$value);
                    if (strcasecmp($value,getenv('apptoken'))==0) {
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
