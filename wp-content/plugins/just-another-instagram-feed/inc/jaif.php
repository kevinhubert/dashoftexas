<?php
/*
  Just Another Instagram Plugin Main Class
  
  Handles all requests for Instagram content using the Instagram API
*/

if( !defined( 'ABSPATH' ) ) return;

class JAIF{

  /* Holds an array of necessary localized data */
  public $localize = array();

  /* Define the current Instagram API insta_query object */
  public $insta_query = false;
  
  /* Holds the current JAIF content */
  public $the_content = '';
  
  public $settings;
  
  public $debug = false;

  public $count = 0;
  
  public $connected = false;
  
  public static $options_desc = array(
    'title' => 'The title to display for the current JAIF instance',
    'title_link' => 'The URL to wrap the title with as an anchor tag',
    'description' => 'The description of the JAIF instance',
    'follow_link' => 'Determines whether or not to show a follow link for the user specified by `follow_user`',
    'follow_user' => 'The Instagram username to generate the `follow_link` with',
    'follow_text' => 'The follow text to be displayed as the `follow_link`', 
    'page_limit' => 'The number of media items to display per page',
    'pagination' => 'Determines whether or not to include AJAX pagination which adds a pagination button',
    'search_type' => 'Defines the search type for the current feed query.<br/>Acceptable values include:<ul><li>`tag` - searches media by tag</li><li>`user-media` - searches media by user</li><li>`user` - searches users</li></ul>',
    'search' => 'Defines the search term to use in the query',
    'media_size' => 'Defines the image/media size to use in the output of the posts',
    'layout' => 'The layout of the feed.<br/>Acceptable values include:<ul><li>`tag` - searches media by tag</li><li>`minimal` - smaller layout, reduced to the basics, good for sidebar widgets</li><li>`full` - more robust layout, good for full page gallery style displays</li></ul>',
    'columns' => 'The number of columns to display, limited to the CSS classes defined within your theme. JAIF has up to 5 columns defined',
    'media_link' => "Define the URL to link all posts to in the feed, useful when you want users to navigate to a larger instance of the feed or navigate them to a user's Instagram profile",
    'link_target' => 'Determines whether or not to enable links wrapped around posts in the feed',
    'slider' => 'Determines whether or not to enable JQuery RS Carousel<sup>**</sup>',
    'fancybox' => 'Determines whether or not to enable JQuery Fancybox',
  ),

  $messages = array(
    'notAuth' => 'You do not seem to be authenticated to browse individual Instagram user photo streams. In order to view user profiles on please authenticate your Instagram account.', 
    'nothing' => 'Nothing Found!',
    'noUsers' => 'No users were found that match your search, please search again.',
    'noMedia' => 'No media was found for this query.',
    'loadmore' => 'Load More',
    'follow-message' => 'Follow @%s',
    'invalidCreds' => 'The Instagram API settings seem to be invalid, please check your settings.',
  ),

  $errors = array(
    '-1' => array( 
      'type' => 'Unknown',
      'message' => 'An unknown error has occurred!',
    ),
      
    '000' => array( 
      'type' => 'Settings',
      'message' => 'There seems to be an issue with your Instagram API credentials, they may be missing or invalid. Please check the JAIF Settings page.',
    ),
      
    '100' => array( 
      'type' => 'Request',
      'message' => 'An invalid request was made, check the API path requested, and try again.',
    ),
      
    '111' => array( 
      'type' => 'Authentication',
      'message' => 'You are not authorized to make this request.',
    ),
      
    '200' => array( 
       'type' => 'Request',
       'message' => 'Status OK',
    ),
      
    '400' => array( 
      'type' => 'Authentication',
      'message' => 'The requested content cannot be accessed with the currently authenticated Instagram user, the user or media requested is private.',
    ),
  ),

  $outgoing_status = array(
    'none' => array( 
      'name' => 'Follow',
      'info' => 'You are not following this user.',
      'rev_action' => 'follow', 
      'action' => 'follow'
    ),
    'follows' => array( 
      'name' => 'Following',
      'info' => 'You are following this user.',
      'rev_action' => 'unfollow', 
      'action' => 'following' 
    ),
  ),
    
  $incoming_status = array(
    'no_follow' => array( 
      'name' => 'Not Followed By',
      'info' => 'This is user is not following you.'
    ),
    'followed_by' => array( 
      'name' => 'Followed By',
      'info' => 'This is user is following you.',
    ),
  ),

  $like_actions = array(
    'liked' => array(
      'name' => 'Liked', 
      'action' => 'liked',
      'info' => 'You like this media.',
      'rev_action' => 'unlike'
    ),
    'notliked' => array(
      'name' => 'Like', 
      'action' => 'notliked', 
      'info' => 'You do not like this media.', 
      'rev_action' => 'like'
    ),
  );
  
  /*
    Constructor
    
    @return None
  */
  function __construct( $vars = array() ){
    
    $this->localize['ajaxurl'] = admin_url('admin-ajax.php');  
    
    /* Request Hook */
    add_action( 'plugins_loaded', array( $this, 'check_request' ) );
    
    /* Init Hook */
    add_action( 'init', array( $this, 'init' ) );
    
    /* Ajax Hook */
    add_action( 'wp_ajax_instafeed', array( $this, 'check_request' ) );
    
    /* Scripts and Styles Hook */
    add_action( 'wp_enqueue_scripts', 'jaif_enqueue_assets' );
    
    $this->authUser = new stdClass();
  }
  
  public function init(){
    $this->settings = wp_parse_args( get_option( JAIF_SETTINGS, jaif_admin::$defaults ), jaif_admin::$defaults );
    $this->getClient();
  }
  
  public function get_request( $vars = array() ){
    if( isset( $this->authUser ) && is_object( $this->authUser ) ){
      $this->authUser->liked = $this->like_actions['notliked'];
    }

    if( $this->connected === true ){
      //$this->instagram->setAccessToken();
    }

    $this->set_getVars($vars);
    $this->set_props($vars);
    $this->check_ajax();
    $this->request_handler();
    $this->show_debug();
  }
  
  // Creates the Instagram API instance
  private function getClient(){
    if( $this->debug == true ){
      $this->settings['cache'] = false;
    }

    try{
      $this->instagram = new Instagram_API( array( 
        'apiKey' => isset($this->settings['client_id']) ? $this->settings['client_id'] : null,
        'apiSecret' => isset($this->settings['client_secret']) ? $this->settings['client_secret'] : null,
        'apiCallback' => isset($this->settings['redirect_uri']) ? $this->settings['redirect_uri'] : null,
        'cacheCalls' => isset($this->settings['cache']) ? (bool)$this->settings['cache'] : false,
      ) );
    } catch( Exception $error ) {
      $this->instagram = false;
      if( $this->debug == true ){
        jaif_debug( $error->getMesage() );
      }
      $this->error = $this->get_error( '000' );
      return false;
    }
  }
  
  function get_error( $error_code ){
    $error_args = isset(self::$errors[$error_code]) ? self::$errors[$error_code] : self::$errors[-1];
    
    $error = new stdClass();
    $error->code = $error_code;
    if( $error_args !== false ){
      $error->error_type = __($error_args['type'],'just-another-insta-feed');
      $error->error_message = __($error_args['message'],'just-another-insta-feed');
    }
    
    return $error;
  }
  
  function show_debug(){
    if( $this->settings['debug'] == 1 ){  
      $this->the_content .= jaif_debug($this);
    }
    return;
  }
  
  public function get_defaults(){
    return array(
      'title' => 'Just Another Instagram Feed',
      'title_link' => null,
      'description' => '',
      'follow_link' => false,
      'follow_user' => $this->settings['follow_user'],
      'follow_text' => __( 'Follow @', 'just-another-insta-feed' ) . $this->settings['follow_user'], 
      'page_limit' => 12,
      'pagination' => true,
      'search_type' => 'tag',
      'search' => null,
      'media_size' => 'low_resolution',
      'layout' => 'minimal',
      'columns' => 3,
      'media_link' => null,
      'link_target' => false,
      'slider' => false,
      'fancybox' => false,
    );
  }
  
  function set_props( $args = array() ){
    $default = $this->get_defaults();
    $args = wp_parse_args( $args, $default );
    foreach( $args as $key => $val ){
      if( array_key_exists( $key, $default ) ){
        $this->$key = $val;
      }
    }
  }
  
  /*
    Sets up the getVars variable
    
    @return null
  */
  function set_getVars( $args = array() ){
    /* Default Get Vars */
    $defaults = array(
      'search' => null,
      'search_type' => null,
      'media' => null,
      'user' => null,
      'max_id' => null,
      'min_id' => null,
      'save' => null
    );
    //$this->getVars = wp_parse_args( $args, $defaults );
    foreach( $defaults as $key => $var ){
      if( isset($args[$key]) ){
        $this->getVars[$key] = htmlspecialchars_decode( $args[$key] );
      }
    }
  }
  
  function get_check(){
    $defaults = array(
      'search' => null,
      'search_type' => null,
      'media' => null,
      'user' => null,
      'max_id' => null,
      'min_id' => null,
      'save' => null
    );
    
    foreach( $_GET as $key => $get ){
      if( array_key_exists( $key, $defaults ) ){
        return true;
      }
    }
    
    return false;
  }
  
  /*
    Checks if an ajax call is being made
    
    @return boolean
  */
  public function check_ajax(){
    $this->ajax = isset( $_REQUEST['jaif_ajax'] );
    if($this->ajax === true){
      $this->count = isset($_REQUEST['count']) ? $_REQUEST['count'] : 0;
    }
  }
  
  public function check_request(){
    $this->init();

    if( isset( $_COOKIE['insta_redirect'] ) && !isset( $_GET['code'] ) )
      $this->unset_getAuth(); 
     
    if( isset( $_GET['code'] ) && isset( $_COOKIE['insta_redirect'] ) ){
      $token = $this->instagram->getOAuthToken( $_GET['code'] );
      $this->instagram->setAccessToken( $token );
      
      $redirect =  $_COOKIE['insta_redirect'];
      
      //Unset the redirect URL
      $this->unset_getAuth();
      
      //Redirect to the proper URL
      header( 'Location: ' . $redirect );
      exit();
    }

    if( isset( $_REQUEST['insta_action'] ) ){
      $action = $_REQUEST['insta_action'];
      $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
      switch($action){
      
        case 'follow':
          if( $this->instagram->getAccessToken() != null  && $id != null ) {
            $this->post_query = $this->instagram->modifyRelationship( $action, $id );
            $this->post_query->new_action = __(self::$outgoing_status['follows'],'just-another-insta-feed');
          }else{
            //Must Authenticate
            $this->set_getAuth(); 
          }
        break;
 
        case 'unfollow':
          if( $this->instagram->getAccessToken() != null  && $id != null ) {
            $this->post_query = $this->instagram->modifyRelationship( $action, $id );
            $this->post_query->new_action = __(self::$outgoing_status['none'],'just-another-insta-feed');
          }else{
            //Must Authenticate
            $this->set_getAuth();
          }
        break;
 
        case 'like':
          if( $this->instagram->getAccessToken() != null  && $id != null ) {
            $this->post_query = $this->instagram->likeMedia( $id );
            $this->post_query->new_action = $this->getLikeAction(true);
          }else{
            //Must Authenticate
            $this->set_getAuth();
          }
        break;
 
        case 'unlike':
          if( $this->instagram->getAccessToken() != null  && $id != null ) {
            $this->post_query = $this->instagram->deleteLikedMedia( $id );
            $this->post_query->new_action = $this->getLikeAction(false);
          }else{
            //Must Authenticate
            $this->set_getAuth();
          }
        break;
 
        case 'get':
          $this->get_request( $_REQUEST );
        break;
 
        default:
          $this->post_query = 000;
      }
      
      if( $this->ajax ){
        $return = $this->get_ajax_return( $this->post_query );
        echo json_encode( $return );
        exit();
      }elseif( $this->post_query === 111 ){
        return;
      }
    }elseif( $this->get_check() ){
      $this->get_request( $_GET ); 
    }
  }

  /* 
    Handles all possible requests made by the user to the application
    
    Currently Handled Requests Include:
      -Image Searches: username and/or hashtag 
      -Media Display
      -User Display
      -Media Save
      -User Authentication
      -Follows and Likes
    
    @return none
  */
  public function request_handler(){
  
    if( $this->instagram === false ){
      $this->content = __(self::$messages['invalidCreds'],'just-another-insta-feed');
      $this->loop( 'error' );
      return;
    }
  
    if( isset( $this->getVars['search'] ) && $this->getVars['search'] != null ){
      //Query Search
      switch( $this->getVars['search_type'] ){
      
        case 'user':
          //Search for user
          $this->insta_query = $this->instagram->searchUser( $this->getVars['search'] );
   
          if( false === self::check_query( $this->insta_query ) ){
            $this->loop('error');
          }else{
            $this->loop('user-search');
          }
        break;

        case 'user-media':
          //Search for user media
          $user_search = $this->instagram->searchUser($this->getVars['search']);
          if( false === self::check_query( $user_search ) ){
            $this->loop('error');
          }else{
            $id = false;
            foreach($user_search->data as $poss_user){
              if(strcmp($poss_user->username,$this->getVars['search'])===0){
                $id = $poss_user->id;
              }
            }
    
            $this->insta_query = $this->instagram->getUserMedia(array(
              'id'=>$id,
              'limit' => $this->page_limit,
              'max_id' => isset( $this->getVars['max_id'] ) ? $this->getVars['max_id'] : null 
            ));

            if( false === self::check_query( $this->insta_query ) ){
              $this->loop('error');
            }else{
              $this->loop('user-media');
            }
          }
        break;
 
        case 'tag':
          //Search by tag
          $this->insta_query = $this->instagram->getTagMedia( array(
            'tag' => $this->getVars['search'], 
            'limit' => $this->page_limit,
            'max_id' => isset( $this->getVars['max_id'] ) ? $this->getVars['max_id'] : null 
          ) );
          if( false === self::check_query( $this->insta_query ) ){
            $this->loop('error');
          }else{
            $this->loop('tag-search');
          }
        break;
 
        default:
          $this->insta_query->meta = new stdClass();
          $this->insta_query->meta->code = 100;
          $this->insta_query->meta->error_type = self::$errors[100]['type'];
          $this->insta_query->meta->error_message = self::$errors[100]['message'];
          $this->loop('error');
        break;
      }
    }elseif( isset($this->getVars['media']) ){
      //Query media
      $this->insta_query = $this->instagram->getMedia( $this->getVars['media'] );
      
      if( false === self::check_query( $this->insta_query ) ){
        $this->loop('error');
      }elseif( isset( $this->getVars['save'] ) && $this->getVars['save'] != null ){
        $this->loop('save');
      }else{
        $this->loop('image');
      }
    }elseif( isset($this->getVars['user']) && $this->getVars['user'] != null ){
      //Query user's images
      if( $this->instagram->getAccessToken() != null ) {
        $this->insta_query = $this->instagram->getUserMedia( array(
          'id' => $this->getVars['user'],
          'limit' =>  $this->page_limit,
          'max_id' => isset( $this->getVars['max_id'] ) ? $this->getVars['max_id'] : null  
        ) ); 
 
        if( !self::check_query( $this->insta_query ) ){
          $this->loop('error');
        }else{
          $this->insta_query->user = $this->get_userQuery();
          $this->loop('user');
        }
      }else{
        $this->set_getAuth();
      }
    }else{
      $this->loop(false);
    }
    
    if( $this->ajax ){
      $return = $this->get_ajax_return( $this->insta_query );
      echo json_encode( $return ); 
      exit();
    }elseif( $this->insta_query === 111 ){
      return;
    }
  }
  
  /*
    Loops through current insta_query to generate appropriate output of data
    Sends the generated HTML as a string to the_content variable
    
    @return none 
  */
  function loop( $type ){
    $data_obj = isset( $this->insta_query->data ) ? $this->insta_query->data : false;
    $meta_obj = isset( $this->insta_query->meta ) ? $this->insta_query->meta : false;
    
    //Check for the existence of a possible HTML cache file to avoid unneccessary parsing of the retrieved JSON data
    if ( $meta_obj !== false 
      && isset($meta_obj->code) 
      && $meta_obj->code == 200 
      && $this->check_cache($meta_obj) == true ) { 
        return;
    }
     
    $vars = new stdClass;
    if( isset( $this->insta_query->pagination ) ){
      $vars = $this->insta_query->pagination;
    }
    $vars->site_url = jaif_get_url();
    $vars->og_vars = $this->get_og_vars();
    $this->localize['insta_pagination'] = json_encode( $vars );
    
    if( $this->instagram !== false && $this->instagram->getAccessToken() != null){
      if( !isset( $_COOKIE['insta_user'] ) || ( isset( $_COOKIE['insta_user'] ) && $_COOKIE['insta_user'] == null ) ){
        $this->authUser = $this->instagram->getUser();
        setcookie( 'insta_user', json_encode( $this->authUser->data ) );
      }else{
        $this->authUser = json_decode( $_COOKIE['insta_user'] );
      }
      
      if( $type == 'video' || $type=='image' ) {
        $liked = false;
        if( isset( $this->insta_query->data->user_has_liked ) && $this->insta_query->data->user_has_liked == 1 ){
          $liked = true;
        }
        $this->authUser->liked = $this->getLikeAction( $liked );
      }
    }

    ob_start();
    switch($type){
      case 'user-search':
        include( jaif_template( 'user-loop.php' ) );
      break;

      case 'user-media':
      case 'tag-search':
        include( jaif_template( 'media-loop.php' ) );
      break;
      
      case 'image':
      case 'video':
        include( jaif_template( 'single-media.php' ) );
      break;
      
      case 'save':
        $media_size = $this->getVars['save'];
        if($data_obj->type == 'image'){
          $this->media = $data_obj->images->$media_size;
        }elseif($data_obj->type == 'video'){
          $this->media = $data_obj->videos->$media_size;
        }
        include( jaif_template( 'save-media.php' ) );
      break;
      
      case 'user': 
        $user = $this->insta_query->user;
        include( jaif_template( 'user-profile.php' ) );
        include( jaif_template( 'media-loop.php' ) );
      break;
      
      case 'login':
        include( jaif_template( 'auth.php' ) );
      break;
      
      case 'error':
        if( $this->instagram !== false ){
          $error = $this->insta_query->meta;
          if( !isset( $error->code ) ){
            $error->code = "-1";
          }/*elseif( $error->code == 400 ){
            //Authentication Access Token invalid
            throw new Exception();
            
            
            //Unset the access token
            $this->instagram->unsetAccessToken();
     
            //Unset the user data (Could look into Wordpress Redirect...?)
            setcookie( 'insta_user', '', 0 );
            header( 'Location: '.jaif_get_url( jaif_get_query( $this->getVars ) ) );
            exit();
          }*/
        }else{
          $error = $this->error;
        }
        include( jaif_template( 'error.php' ) );
      break;
    }
    $this->the_content .= ob_get_clean();
    
    //Check if caching is enabled
    if( $this->settings['cache'] == true ){
      //Create the cached file
      $this->create_cache( $meta_obj );
    } 
  }
  
  private function check_cache( $meta = null ){
    //Double check that the curent request was a successful 200 request and there were no errors and that cache is enabled
    if( empty( $meta ) || $meta->code != 200 || $this->settings['cache'] == false )
      return false;
    
    $file = JAIF_CACHE . 'html/' . $this->get_filename();
    
    //Check that the file exists and that the timestamp is fresh ( Settings can be altered )
    if( file_exists( $file ) && ( time() - filemtime( $file ) ) < $this->settings['cache_refresh'] ){
    
      //Cache is valid, set the_content to this cache file
      ob_start();
      include( $file );
      $this->the_content .= ob_get_clean();
      
      //Return true
      return true;
    }
    
    //Return false if all else fails
    return false;
  }
  
  private function create_cache( $meta = null ){
    //Double check that the curent request was a successful 200 request and there were no errors and that cache is enabled
    if( empty( $meta ) || $meta->code != 200 || $this->settings['cache'] == false ) 
      return false;
    
    $file = JAIF_CACHE . 'html' . DIRECTORY_SEPARATOR . $this->get_filename();
    
    if( $this->the_content != '' ){
      $compressed = preg_replace( '/\s+/', ' ', $this->the_content ); 
      $cached = file_put_contents( $file, $compressed );
    }
  }
  
  private function get_filename(){
    $file = '';
    $vars = $this->getVars;
    
    $vars['layout'] = $this->layout;
    
    $vars['page_limit'] = $this->page_limit;
    
    $vars['media_size'] = $this->media_size;
    
    $vars['columns'] = $this->columns;
    
    $vars['ajax'] = $this->ajax; 
    
    $file .= jaif_get_query( $vars ) . '.html';
    
    $file = sanitize_file_name( $file );
    
    return $file;
  }
  
  function reset_content(){
    $this->insta_query = new stdClass();
    $this->the_content = '';
  }
  
  function set_getAuth(){ 
    setcookie( 'insta_redirect', jaif_get_url( jaif_get_query( $this->getVars ) ), time() + ( 3600 * 20 ), '/' ); 
    
    $this->loginURL = $this->instagram->getLoginUrl( array('basic','likes','relationships') );
    $this->loop('login');
    $this->post_query = 111; 
  }
  
  function unset_getAuth(){
    setcookie( 'insta_redirect', '', 0 );
  }
  
  public function get_userQuery(){
    $user_query = $this->instagram->getUser( $this->getVars['user'] );
    if( self::check_query( $user_query ) ){
      $user_query = $user_query->data;
      $relations = $this->instagram->getUserRelationship( $user_query->id );
      if( self::check_query( $relations ) ){
        $user_query->relations = $relations->data;
        return $user_query;
      } 
    }
    return false;
  }
  
  /*
    Checks the API $query for errors and existence  
    
    @return: boolean 
  */
  public static function check_query( $query ){
    if( ( isset( $query->meta->code ) && $query->meta->code != 200 ) || ( !isset( $query->meta->code ) ) ){
      return false;
    }else{
      return true;
    }
  }
  
  function get_og_vars(){
    $return = array();
    $include_vars = array( 'search', 'search_type', 'media', 'user' );
    foreach($this->getVars as $key => $value){
      if( in_array($key,$include_vars) ){
 $return[$key] = $value;
      }
    }
    return $return;
  }
  
  function get_ajax_return( $query ){
    $meta = isset( $query->meta ) ? $query->meta : false;
    if( false === $query ){
      $return = array( 
        'success' => false, 
        'code' => 000, 
        'pagination' => false, 
        'error_message' => __(self::$errors[000]['message'],'just-another-insta-feed'), 
        'html' => $this->the_content 
      );
    }elseif( $meta ){
      if( self::check_query( $query ) ){
        $return = array( 
          'success' => true, 'code' => $meta->code, 'html' => $this->the_content, 
          'pagination' => isset( $query->pagination ) ? $query->pagination : false ,
          'new_action' => isset( $query->new_action ) ? $query->new_action : false );
      }else{
        $return = array( 
          'success' => false, 
          'code' => $meta->code, 
          'pagination' => false, 
          'error_message' => $meta->error_message, 
          'html' => $this->the_content 
        );
      }
    }else{
      $return = array( 
        'success' => false, 
        'code' => 111, 
        'pagination' => false, 
        'error_message' => __(self::$errors[111]['message'],'just-another-insta-feed'), 
        'html' => $this->the_content 
      );
    }
    return $return;
  }
  
  function getLikeAction( $liked ){
    return array(
      'action' => ( $liked ) ? self::$like_actions['liked']['action'] : self::$like_actions['notliked']['action'],
      'rev_action' => ( $liked ) ? self::$like_actions['liked']['rev_action'] : self::$like_actions['notliked']['rev_action'],
      'status' => ( $liked ) ? __(self::$like_actions['liked']['info'], 'just-another-insta-feed') : __(self::$like_actions['notliked']['info'] ,'just-another-insta-feed'), 
      'name' => ( $liked ) ? __(self::$like_actions['liked']['name'], 'just-another-insta-feed') : __(self::$like_actions['notliked']['name'] ,'just-another-insta-feed'),
    );
  }
}
?>