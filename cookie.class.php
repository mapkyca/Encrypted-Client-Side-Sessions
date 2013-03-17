<?php

/**
 * Example for storing PHP Sessions in an encrypted client side cookie.
 * 
 * Limitations:
 * 		- Sessions can't contain a lot of data, ~4000 bytes is a sensible maximum
 * 		- You must be done with sessions (i.e. call session_write_close() before you write any non-header output. 
 *                This is less of a problem if you're using output buffering (like Elgg's template system)
 */
class ClientSideCookieSession 
    // extends SessionHandler { // Implements the php 5.4 interface
{


    // The encryption key, you probably want to replace this with something more secure, or even use session keys encrypted with a site certificate.
    private static $key = "somesitesecret";  
    private static $iv = "krobsnwr"; // Random IV dependant on cypher 

    /* These are meaningless in this context, but need to be implemented */

    public static function open_53($save_path, $session_id) {
        return true;
    }

    public static function close_53() {
        return true;
    }

    public static function gc_53($maxlifetime) {
        return true;
    }

    /* */

    /**
     * Read the encrypted cookie.
     */
    public static function read_53($session_id) {
        if (isset($_COOKIE[$session_id])) {
            
            // Check we have mcrypt module installed
            if (!is_callable('mcrypt_encrypt'))
                throw new Exception("Mcrypt module required");
            
            return mcrypt_decrypt(MCRYPT_BLOWFISH, self::$key, base64_decode($_COOKIE[$session_id]), MCRYPT_MODE_CBC, self::$iv );
        }
        
        return ""; // Return empty string if nothing was read
    }

    /**
     * Write session to the encrypted cookie. 
     * Note the maximum size of a cookie is limited, a sensible maximum for most browsers is 
     * about 4000 bytes.
     */
    public static function write_53($session_id, $session_data) {
        if (!empty($session_data)) {

            // Check we have mcrypt module installed
            if (!is_callable('mcrypt_encrypt'))
                throw new Exception("Mcrypt module required");
            
            // Encrypt session
            $encrypted_data = base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, self::$key, $session_data, MCRYPT_MODE_CBC, self::$iv));
            
            // Check size
            if (strlen($encrypted_data) > 4000) 
                throw new Exception("Encrypted session data is too big for the cookie");
            
            // Save in cookie using cookie defaults
            setcookie($session_id, $encrypted_data);
            
            return true;
        }
        
        return false;
    }

    /**
     * Destroy the session by unsetting the cookie.
     */
    public static function destroy_53($session_id) {
        setcookie($session_id, '', time() - 3600);
        unset($_COOKIE[$session_id]);
    }

    public function close() {
        return self::close_53();
    }

    public function destroy($session_id) {
        return self::destroy_53($session_id);
    }

    public function gc($maxlifetime) {
        return self::gc_53($maxlifetime);
    }

    public function open($save_path, $name) {
        return self::open_53($save_path, $name);
    }

    public function read($session_id) {
        return self::read_53($session_id);
    }

    public function write($session_id, $session_data) {
        return self::write_53($session_id, $session_data);
    }

    
    

}
