<?php

namespace src\client\models;
use src\vendor\database\Database as Database;

class UserModel {
    
    const USERNAME  = 'username';
    const USERPASS  = 'password';
    const EMAIL     = 'email';
    
    public static function getId() {
        // return $_SESSION['user_data']['id'];
        return 1;
    }

    public static function auth($userData, $userRoleCollection) {
        
        $_SESSION['user_data']      = array(
            'id'    => $userData['id']
        );
        
        // 
        $_SESSION['user_role']      = $userRoleCollection;
        
        $_SESSION['is_loged_in']    = true;        
    }
    
    
    public static function isAuthenticated() {
        return isset($_SESSION['is_loged_in']) && 
               $_SESSION['is_loged_in'] == true;
    }
    
    public static function isGuest() {
        return !self::isAuthenticated();
    }
    
    public static function hasRoleUser() {
        return self::getRole() == 'USER';
    }
    
    public static function hasRoleModerator() {
        return self::getRole() == 'MODERATOR';
    }    
    
    public static function hasRoleAdmin() {
        return self::getRole() == 'ADMIN';
    }        
    
    public static function getAllUsers($dataCollection) {
        
        return Database::select('tb_users')::where(array(
            'username'  => Database::str($dataCollection[self::USERNAME]),
            'password'  => Database::str($dataCollection[self::USERPASS])
        ))::fetch();
    }

    public static function createNewUser($dataCollection) {
        
        // TODO : process based on transaction 
        Database::insert('tb_users', array(
            'username'  => Database::str($dataCollection[self::USERNAME]),
            'password'  => Database::str(md5($dataCollection[self::USERPASS])),
            'mail'      => Database::str($dataCollection[self::EMAIL])
        ));

        Database::insert('tm_users__user_role', array(
            'user_id'   => Database::getLastInsertedId(),
            'role_id'   => 1 // TODO : Think about something better
        ));        
        
        return !Database::hasError();
    }
    
    public static function registerTokken() {
                // generate uniq id 
        $accessKeyTokken = uniqid();

        $currentTimestamp   = time();
        $padding            = 60;
        $expireIn           = $currentTimestamp + $padding;            
        
        Database::insert('tm_access_tokken', [
           'tokken'     => Database::str($accessKeyTokken),
            'expire_in' => $expireIn
        ]);
        
        return $accessKeyTokken;
    }
    
    
    public static function isAccessTokkenValid($tokken) {
        
        $currentTimestamp = time();
        $collection = Database::select('tm_access_tokken')::where([
            'tokken' => Database::str($tokken), 
            '@expresion' => Database::expression('expire_in', '>', $currentTimestamp)
        ])::fetch();
        
        return count($collection) == 1;
    }
    

    private static function getRole() {
        
        if(array_key_exists('user_role', $_SESSION)) {
            return $_SESSION['user_role'][0]['title'];
        }
    }
    
    
}