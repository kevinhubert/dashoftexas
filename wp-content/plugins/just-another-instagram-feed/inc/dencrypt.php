<?php 
class dencrypt{
  private $hash_lock; 
  private $hash_key;
  
  function __construct( $class = null ){
    $this->hash_lock = JAIF_ROOT.'/inc/.lock';
    if( !file_exists( $this->hash_lock )  ){
      $this->generate_key( $this->hash_lock );
    }
  }

  private function decrypt( $pass = null ){
    $this->hash_key = file_get_contents( $this->hash_lock );
    $encrypted = urldecode( $pass );
    return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->hash_key, $encrypted, MCRYPT_MODE_ECB);
  }

  private function encrypt( $pass = null ){
    $this->hash_key = file_get_contents( $this->hash_lock );
    $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->hash_key, $pass, MCRYPT_MODE_ECB);
    return urlencode( $encrypted );
  }
  
  private static function generate_key( $lock ){
    $random_string = self::randString(32);
    file_put_contents( $lock, $random_string );
    chmod( $lock, 0400 ); 
  }
  
  private static function delete_key( $lock ){
    chmod( $lock, 0600 ); 
    unset( $lock );
  }
  
  private static function randString( $length ){
    $str = '';
    $keys = range('!', '~');
    do{ $str .= $keys [array_rand($keys) ]; }while( strlen($str) < $length );
    return $str;
  }
  
  protected function get_encrypted( $key = null ){
    return ( !$key || $key == null ) ? false : self::encrypt( $key );
  }
  
  protected function get_decrypted( $key = null ){
    return ( !$key || $key == null ) ? false : self::decrypt( $key ); 

  }
  
  protected function clean_up(){
    self::delete_key( $this->hash_lock );
  }
}
?>