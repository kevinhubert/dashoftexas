<?php
/**
 * Instagram API class
 * API Documentation: http://instagram.com/developer/
 * Class Documentation: https://github.com/cosenary/Instagram-PHP-API/blob/master/README.markdown
 * 
 * @author Christian Metz
 * @since 30.10.2011
 * @copyright Christian Metz - MetzWeb Networks 2012
 * @version 1.8
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 **/

class Instagram_API {

  /**
   * The API base URL
   **/
  const API_URL = 'https://api.instagram.com/v1/';

  /**
   * The API OAuth URL
   **/
  const API_OAUTH_URL = 'https://api.instagram.com/oauth/authorize';

  /**
   * The OAuth token URL
   **/
  const API_OAUTH_TOKEN_URL = 'https://api.instagram.com/oauth/access_token';

  /**
   * The Instagram API Key
   * 
   * @var string
   **/
  private $_apikey;

  /**
   * The Instagram OAuth API secret
   * 
   * @var string
   **/
  private $_apisecret;

  /**
   * The callback URL
   * 
   * @var string
   **/
  private $_callbackurl;

  /**
   * The user access token
   * 
   * @var string
   **/
  private $_accesstoken;

  /**
   * Available scopes
   * 
   * @var array
   **/
  private $_scopes = array('basic', 'likes', 'comments', 'relationships');

  /**
   * Available actions
   * 
   * @var array
   **/
  private $_actions = array('follow', 'unfollow', 'block', 'unblock', 'approve', 'deny');
  
  /**
   * Nullified default configuration values
   * 
   * @var array
   **/
  static private $defaultConfig = array( 'apiKey' => null, 'apiSecret' => null, 'apiCallback' => null, 'cacheCalls' => false );

  /**
   * Default constructor
   *
   * @param array|string $config Instagram configuration data
   * @return void
   **/
  public function __construct($config) {
    if (true === is_array($config)) {
      
      $config = array_merge( self::$defaultConfig, $config ); 
      
      // Check the variables are valid
      if( empty( $config['apiKey'] ) || empty( $config['apiSecret'] ) || empty( $config['apiCallback'] ) ){
	      throw new Exception( "Instagram API Error: __construct() - Configuration data is invalid and/or missing." );
	      return;
      }

      // if you want to access user data
      $this->setApiKey( $config['apiKey'] );
      $this->setApiSecret( $config['apiSecret'] );
      $this->setApiCallback( $config['apiCallback'] );

      $this->cacheCalls = (bool)$config['cacheCalls'];
      //var_dump($this);


    } else if (true === is_string($config)) {
      // if you only want to access public data
      $this->setApiKey($config);
    } else {
      throw new Exception( "Instagram API Error: __construct() - Configuration data is missing." );
    }
    
    $this->cacheDir = dirname(__FILE__).DIRECTORY_SEPARATOR.'instagram_cache'.DIRECTORY_SEPARATOR;
    if( !is_dir($this->cacheDir) ){
      mkdir($this->cacheDir);    
    }
    
    return;
  }

  /**
   * Generates the OAuth login URL
   *
   * @param array [optional] $scope       Requesting additional permissions
   * @return string                       Instagram OAuth login URL
   **/
  public function getLoginUrl( $scope = array( 'basic' ) ) {
    if (is_array($scope) && count(array_intersect($scope, $this->_scopes)) === count($scope)) {
      return self::API_OAUTH_URL . '?client_id=' . $this->getApiKey() . '&redirect_uri=' . $this->getApiCallback() . '&scope=' . implode('+', $scope) . '&response_type=code';
    } else {
      throw new Exception("Error: getLoginUrl() - The parameter isn't an array or invalid scope permissions used.");
    }
  }

  public function ping(){
    $cacheCalls = $this->cacheCalls; 
    $this->cacheCalls = false;
    $results = $this->getTagMedia( array('tag'=>'test','count'=>1) );
    $this->cacheCalls = $cacheCalls;
    return $results;
  }

  /**
   * Search for a user
   *
   * @param string $name                  Instagram username
   * @param integer [optional] $limit     Limit of returned results
   * @return mixed
   **/
  public function searchUser($name, $limit = 0) {
    return $this->_makeCall('users/search', false, array('q' => $name, 'count' => $limit));
  }

  /**
   * Get user info
   *
   * @param integer [optional] $id        Instagram user id
   * @return mixed
   **/
  public function getUser($id = 0) {
    $auth = false;
    if ($id === 0 && isset( $this->_accesstoken ) ) { 
      $id = 'self'; 
      $auth = true; 
    }
    return $this->_makeCall('users/' . $id, $auth);
  }

  /**
   * Get user activity feed
   *
   * @param integer [optional] $limit     Limit of returned results
   * @return mixed
   **/
  public function getUserFeed($limit = 0) {
    return $this->_makeCall('users/self/feed', true, array('count' => $limit));
  }

  /**
   * Get user recent media
   *
   * @param integer [optional] $id        Instagram user id
   * @param integer [optional] $limit     Limit of returned results
   * @return mixed
   **/
  public function getUserMedia( $args ) {
    extract( $args );
    return $this->_makeCall('users/' . $id . '/media/recent', false, array( 'count' => $limit, 'max_id' => $max_id ) );
  }

  /**
   * Get the liked photos of a user
   *
   * @param array [optional] - Default: count = 0
   * @return mixed
   **/
  public function getUserLikes( $args ) {
    $default = array('count' => 0);
    $args = array_merge( $default, $args );
    return $this->_makeCall('users/self/media/liked', true, $args );
  }

  /**
   * Get the list of users this user follows
   *
   * @param integer [optional] $id        Instagram user id
   * @param integer [optional] $limit     Limit of returned results
   * @return mixed
   **/
  public function getUserFollows($id = 'self', $limit = 0) {
    return $this->_makeCall('users/' . $id . '/follows', true, array('count' => $limit));
  }

  /**
   * Get the list of users this user is followed by
   *
   * @param integer [optional] $id        Instagram user id
   * @param integer [optional] $limit     Limit of returned results
   * @return mixed
   **/
  public function getUserFollower($id = 'self', $limit = 0) {
    return $this->_makeCall('users/' . $id . '/followed-by', true, array('count' => $limit));
  }

  /**
   * Get information about a relationship to another user
   *
   * @param integer $id                   Instagram user id
   * @return mixed
   **/
  public function getUserRelationship($id) {
    return $this->_makeCall('users/' . $id . '/relationship', true);
  }

  /**
   * Modify the relationship between the current user and the target user
   *
   * @param string $action                Action command (follow/unfollow/block/unblock/approve/deny)
   * @param integer $user                 Target user id
   * @return mixed
   **/
  public function modifyRelationship($action, $user) {
    if (true === in_array($action, $this->_actions) && isset($user)) {
      return $this->_makeCall('users/' . $user . '/relationship', true, array('action' => $action), 'POST');
    }
    throw new Exception("Error: modifyRelationship() | This method requires an action command and the target user id.");
  }

  /**
   * Search media by its location
   *
   * @param float $lat                    Latitude of the center search coordinate
   * @param float $lng                    Longitude of the center search coordinate
   * @param integer [optional] $distance  Distance in meter (max. distance: 5km = 5000)
   * @return mixed
   **/
  public function searchMedia($lat, $lng, $distance = 1000) {
    return $this->_makeCall('media/search', false, array('lat' => $lat, 'lng' => $lng, 'distance' => $distance));
  }

  /**
   * Get media by its id
   *
   * @param integer $id                   Instagram media id
   * @return mixed
   **/
  public function getMedia($id) {
    return $this->_makeCall('media/' . $id);
  }

  /**
   * Get the most popular media
   *
   * @return mixed
   **/
  public function getPopularMedia() {
    return $this->_makeCall('media/popular');
  }

  /**
   * Search for tags by name
   *
   * @param string $name                  Valid tag name
   * @return mixed
   **/
  public function searchTags($name) {
    return $this->_makeCall('tags/search', false, array('q' => $name));
  }

  /**
   * Get info about a tag
   *
   * @param string $name                  Valid tag name
   * @return mixed
   **/
  public function getTag($name) {
    return $this->_makeCall('tags/' . $name);
  }

  /**
   * Get recently tagged media
   * Checks to make sure the correct number of posts are returned  
   *
   * @param string $name                  Valid tag name
   * @param integer [optional] $limit     Limit of returned results
   * @return mixed
   **/
  public function getTagMedia( $args = array() ) {
    extract($args);
    $tag = isset( $tag ) ? $tag : '';
    $result = $this->_makeCall('tags/' . $tag . '/media/recent', false, array(
      'count' => isset( $limit ) ? $limit : null,
      'min_tag_id' => isset($min_id) ? $min_id : null,
      'max_tag_id' => isset($max_id) ? $max_id : null
    ) );
    
    if( !isset( $result->data ) ){
      //No Data found, return result due to possible error
      return $result;
    }elseif( !isset( $full_result ) ){
      $full_result = $result;
      $full_limit = $limit;
    }elseif( isset($full_result) ){
      foreach( $result->data as $data ){
	      $full_result->data[] = $data;  
      } 
      $full_result->pagination->next_max_tag_id = $result->pagination->next_max_tag_id;
      $full_result->pagination->next_max_id = $result->pagination->next_max_id;
      $full_result->pagination->next_url = $result->pagination->next_url;
      $full_result->meta->data_found += $result->meta->data_found;
    }
    
    if( isset( $result->pagination->next_max_tag_id ) && isset( $result->meta->data_found ) && $result->meta->data_found < $limit ){ 
      while( $full_result->meta->data_found < $full_limit ){
	      $pagination = $result->pagination;
	      $num_data = count( $result->data );
	      $remain = ( $num_data > 0 ) ? $limit - $num_data : $limit + 1; 
	      $new_args = array(
	        'tag' => $tag,
	        'limit' => $remain,
	        'min_id' => null,
	        'max_id' => $pagination->next_max_tag_id,
	        'full_result' => $full_result,
	        'full_limit' => $full_limit, 
	      );
	      $full_result = $this->getTagMedia( $new_args );
      } 
      
      if( $full_result->meta->data_found == $full_limit ){
	      $full_result->pagination->next_max_tag_id = $result->pagination->next_max_tag_id;
	      $full_result->pagination->next_max_id = $result->pagination->next_max_id;
	      $full_result->pagination->next_url = $result->pagination->next_url;
	      return $full_result;
      }
    }else{
      return $full_result; 
    }
  }
  
  /**
   * Get a list of users who have liked this media
   *
   * @param integer $id                   Instagram media id
   * @return mixed
   **/
  public function getMediaLikes($id) {
    return $this->_makeCall('media/' . $id . '/likes', true);
  }

  /**
   * Set user like on a media
   *
   * @param integer $id                   Instagram media id
   * @return mixed
   **/
  public function likeMedia($id) {
    return $this->_makeCall('media/' . $id . '/likes', true, null, 'POST');
  }

  /**
   * Remove user like on a media
   *
   * @param integer $id                   Instagram media id
   * @return mixed
   **/
  public function deleteLikedMedia($id) {
    return $this->_makeCall('media/' . $id . '/likes', true, null, 'DELETE');
  }

  /**
   * Get the OAuth data of a user by the returned callback code
   *
   * @param string $code                  OAuth2 code variable (after a successful login)
   * @param boolean [optional] $token     If it's true, only the access token will be returned
   * @return mixed
   **/
  public function getOAuthToken($code, $token = false) {
    $apiData = array(
      'grant_type'      => 'authorization_code',
      'client_id'       => $this->getApiKey(),
      'client_secret'   => $this->getApiSecret(),
      'redirect_uri'    => $this->getApiCallback(),
      'code'            => $code
    );
    
    $result = $this->_makeOAuthCall($apiData);
    return (false === $token) ? $result : $result->access_token;
  }

  /**
   * The call operator
   *
   * @param string $function              API resource path
   * @param array [optional] $params      Additional request parameters
   * @param boolean [optional] $auth      Whether the function requires an access token
   * @param string [optional] $method     Request type GET|POST
   * @return mixed
   **/
  private function _makeCall($function, $auth = false, $params = null, $method = 'GET') {
    if (false === $auth && false === isset($this->_accesstoken)) {
      // if the call doesn't require authentication and client has not authenticated
      $authMethod = 'client_id=' . $this->getApiKey();  
      
      // allow cache 
      $cache = true;
    } else {
      // if the call needs an authenticated user
      if (true === isset($this->_accesstoken)) {
        $authMethod = 'access_token=' . $this->getAccessToken();
      } else {  
        throw new Exception( "Error: _makeCall() | $function - This method requires an authenticated users access token." );
      }
      
      // disallow cache 
      $cache = false;
    }
    
    if (isset($params) && is_array($params)) {
      $paramString = http_build_query( $params ).'&';
    } else {
      $paramString = null;
    }

    $apiCall = self::API_URL . $function .'?'. (('GET' === $method) ? $paramString : null)  . $authMethod ;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiCall);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    
    if ('POST' === $method) {
      curl_setopt($ch, CURLOPT_POST, count($params));
      curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim($paramString, '&'));
    } else if ('DELETE' === $method) {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    if( $this->cacheCalls === true && $cache === true && $method === 'GET' ){
      return $this->getCachedCall( $apiCall, $ch );
    }else{
      return $this->_execCall( $ch ); 
    } 
  }
  
  /**
   * The OAuth call operator
   *
   * @param array $apiData                The post API data
   * @return mixed
   **/
  private function _makeOAuthCall($apiData) {
    $apiHost = self::API_OAUTH_TOKEN_URL;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiHost);
    curl_setopt($ch, CURLOPT_POST, count($apiData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    return $this->_execCall( $ch );
  }
  
  /**
   * Universal curl_exec handler
   * 
   * @param curl Object $ch
   * @param int $calls
   * @return stdClass Object   
   **/
  public function _execCall( $ch, $calls=0 ){
    $jsonData = curl_exec($ch);

    $result = json_decode( $jsonData );

    if( is_object( $result ) ){
      $result->meta->calls = $calls;
      $result->meta->query = curl_getinfo($ch);
      $result->meta->calls = $calls;
      $result->meta->data_found = isset($result->data) ? count($result->data) : 0; 
      $result->meta->query = curl_getinfo($ch);
      curl_close($ch);
      return $result;
    }elseif( !is_null($result) && $calls < 3){
      return $this->_execCall( $ch,$calls++ );
    }else{
      $return = new stdClass;
      $return->meta = new stdClass;
      $return->meta->query = curl_getinfo($ch);
      $return->meta->error_type = 'Unknown Caught Exception';
      $return->meta->code = -1;
      $return->meta->error_message = "Unknown";
      $return->meta->calls = $calls;	
      curl_close($ch);
      return $return;
    }    
  }
  
  /**
   * API Call Cache Retrieval/Creation
   * 
   * 
   * @param curl Object $ch 
   * @return stdClass Object   
   **/
  public function getCachedCall( $APIcall, $ch ){  
    $cache_file = $this->cacheDir . self::getFileName($APIcall) .'.json';
    if( file_exists( $cache_file ) && filesize($cache_file) > 0 && filemtime($cache_file) > ( time() - 1200 ) ){ 
      return json_decode( file_get_contents( $cache_file ) );
    }else{
      $result = $this->_execCall( $ch );
      if( isset($result->meta->code) && $result->meta->code == 200 ){
        $encoded = json_encode($result);
	      file_put_contents( $cache_file, $encoded );
      }
      return $result;
    }  
  }

  static function getFileName($APIcall){
     return preg_replace("%[^A-z0-9\_\-]%","",$APIcall);
  }

  /**
   * Access Token Setter
   * 
   * @param object|string $data
   * @return void
   **/
  public function setAccessToken( $data=false ) {
    $token = null; 
    if( $data ){
      $crypt = new dencrypt();
      $token = ( true === is_object($data) ) ? $data->access_token : $data;
      setcookie( 'jaif_access_token', $crypt->get_encrypted( $token ), time() + (86400 * 2) );
    }elseif( isset( $_COOKIE['jaif_access_token'] ) ){
      $crypt = new dencrypt();
      $token = $crypt->get_decrypted( $_COOKIE['jaif_access_token'] ); 
    }
    $this->_accesstoken = $token;
  }

  /**
   * Access Token Revoker
   * 
   * @return string
   **/
  public function unsetAccessToken( $data=false ){
    setcookie( 'instafeed_access_token', '', 0 );
    $this->_accesstoken = null;
  }

  /**
   * Access Token Getter
   * 
   * @return string
   **/
  public function getAccessToken() {
    return $this->_accesstoken;
  }

  /**
   * API-key Setter
   * 
   * @param string $apiKey
   * @return void
   **/
  public function setApiKey($apiKey) {
    $this->_apikey = $apiKey;
  }

  /**
   * API Key Getter
   * 
   * @return string
   **/
  public function getApiKey() {
    return $this->_apikey;
  }

  /**
   * API Secret Setter
   * 
   * @param string $apiSecret 
   * @return void
   **/
  public function setApiSecret($apiSecret) {
    $this->_apisecret = $apiSecret;
  }

  /**
   * API Secret Getter
   * 
   * @return string
   **/
  public function getApiSecret() {
    return $this->_apisecret;
  }
  
  /**
   * API Callback URL Setter
   * 
   * @param string $apiCallback
   * @return void
   **/
  public function setApiCallback($apiCallback) {
    $this->_callbackurl = $apiCallback;
  }

  /**
   * API Callback URL Getter
   * 
   * @return string
   **/
  public function getApiCallback() {
    return $this->_callbackurl;
  }
}
?>