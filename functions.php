<?php
require_once('config.php');

session_start();

class googleAhtentificator
{
    static function isAuth() {
        if (isset($_SESSION['access_token']) && $_SESSION['access_token'] && googleAhtentificator::getInfoUser($_SESSION['access_token'])) {
            return true;
        }
        return false;
    }
    
    static function auth($token) {
        $userInfo = googleAhtentificator::getInfoUser($token);
        if ($userInfo) {
            $_SESSION['access_token'] = $token;
            return true;
        }
    
        return false;
    }
    
    /** 
     * EXEMPLE value returned Info User if token valid
        {
            "azp": "78378732-k22gtqgtvt4onqagalbl967qhss160js.apps.googleusercontent.com",
            "aud": "78378732-k22gtqgtvt4onqagalbl967qhss160js.apps.googleusercontent.com",
            "sub": "I7836782367237",
            "scope": "https://www.googleapis.com/auth/userinfo.email openid",
            "exp": "1696598643",
            "expires_in": "3576",
            "email": "toto@gmail.fr",
            "email_verified": "true",
            "access_type": "online"
        }
    */
    
    static function getInfoUser($token) {
        $tokenValidationUrl = 'https://www.googleapis.com/oauth2/v3/tokeninfo?access_token=' . $token;
    
        // Effectuez une requête HTTP GET pour valider le token
        $response = file_get_contents($tokenValidationUrl);
    
        if ($response) {
            // Analysez la réponse JSON
            $tokenInfo = json_decode($response, true);
            if (!isset($tokenInfo['error'])) {
                return $tokenInfo;
            }
        }
    
        return false;
    }
}

