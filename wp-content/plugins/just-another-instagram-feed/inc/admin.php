<?php 
/*
Just Another Instagram Feed Admin Class

All Admin Filters, Hooks, and Functions

@since 0.1
*/

if( !defined( 'ABSPATH' ) ) return;

class jaif_admin{

  public static $defaults = array(
    'client_id' => null,
    'client_secret' => null,
    'redirect_uri' => null,
    'cache' => true,
    'cache_refresh' => 1800,
    'follow_user' => null,
    'debug' => false,
    'version' => JAIF_VERSION,
  );

  private $settings;

  private $errors = array();

  function __construct(){
    //Add admin options page
    add_action( 'admin_menu', array( $this, 'setup_admin' ) );

    add_action( 'admin_init', array( $this, 'register_settings' ) );

    add_action( 'admin_init', array( $this, 'action_handler' ) );

    add_action( 'admin_notices', array( $this, 'activate' ) );

    add_action( 'admin_enqueue_scripts', 'jaif_enqueue_assets' );
  }

  public function activate(){    
    $new = false;
    $settings_page = admin_url( "options-general.php?page=".JAIF_SETTINGS_PAGE );

    $this->settings = $this->get_settings();

    //Check if the Just Another Instagram Feed settings option is created and exists in the database
    if( array_diff( $this->settings, self::$defaults ) === 0 ){
      //Create the option with the default settings
      add_option( JAIF_SETTINGS, self::$defaults );
      $new = true;
    }else{
      if( isset( $this->settings['version'] ) && strcmp( $this->settings['version'], JAIF_VERSION ) !== 0 ){
        //Upgrade to newest version
        $this->upgrade($this->settings['version']);
      }
      //Fix the version to the currently installed version
      $this->settings['version'] = JAIF_VERSION;
    }

    if( !is_writeable( JAIF_ROOT ) ){
      $this->errors[] = __( 'The root directory <code>'.JAIF_ROOT.'</code> is not writeable by the server, caching has been disabled and cannot be re-enabled until the directory is writeable.' );
      $this->settings['cache'] = false;
    }else{
      //Check the the cache directories exist
      if( !is_dir( JAIF_CACHE ) ){
        mkdir( JAIF_CACHE );
      }

      if( !is_dir(JAIF_CACHE . 'html/' ) ){
        mkdir( JAIF_CACHE . 'html/' );
      }
    }

    if( false === $this->valid_api() ){
      $this->errors[] = __( 'The Instagram API settings appear to be invalid, please check your settings <a href="'.$settings_page.'">here</a>' );
    }

    $warning_message = null;
    if( count( $this->errors ) > 0 ){
      $warning_message = '<div class="error"><h4>Just Another Instagram Feed Plugin Error(s):</h4><ol><li>' . implode( '</li><li>', $this->errors ) . '</li></ol></div>';
    }

    if( $new === true ){
      $activate_message = __( '<div class="updated"><p>Just Another Instagram Feed has been activated! Review your settings <a href="'.$settings_page.'">here</a></p></div>' ); 
      echo $warning_message;
      echo $activate_message;
    }

    //Update the settings
    update_option( JAIF_SETTINGS, $this->save_settings( $this->settings ) );
  } 

  private function upgrade($version){
    $this->flush_cache();
  }

  public static function deactivate(){
    /* Do something when plugin gets deactivated... */
  }

  public function valid_api(){
    try{      
      $instagram_api = new Instagram_API( array( 
        'apiSecret' => $this->settings['client_secret'], 
        'apiCallback' => $this->settings['redirect_uri'],
        'apiKey' => $this->settings['client_id']
      ) );
      $query = $instagram_api->ping();

      return JAIF::check_query( $query );
    } catch ( Exception $error ) {
      $this->errors[] = $error->getMessage();
      return false;
    }
  }

  public function action_handler(){
    $action = isset( $_REQUEST['if-action'] ) ? $_REQUEST['if-action'] : false;
    switch( $action ){
      case 'flush-cache':
        $this->flush_cache();
      break;

      default:
        return;
    }
  }

  private function flush_cache( $dir = null ){
    $dir = ( $dir != null ) ? $dir.'/' : JAIF_CACHE;

    if( !is_dir( $dir ) ){ 
      return false;
    }

    $files = glob( $dir . '*' ); // get all file names

    foreach($files as $file){ // iterate files
      if( is_file( $file ) ){
        unlink($file); // delete file
      }elseif( is_dir( $file ) ){      
        //Recursively remove all files keeping the directory structure
        $this->flush_cache( $file );
      }    
    }
    return;
  } 

  public static function get_settings(){
    $settings = get_option( JAIF_SETTINGS, self::$defaults );
    return ( is_array( $settings ) ) ? wp_parse_args( $settings, self::$defaults ) : self::$defaults; 
  }

  function register_settings() { 
    register_setting( JAIF_SETTINGS.'_group', JAIF_SETTINGS, array( $this, 'save_settings' ) );

    add_settings_section( 'jaif_auth', 'Authentication Settings', null, 'just-another-insta-feed' );

    add_settings_section( 'jaif_misc', 'Misc. Settings', null, 'just-another-insta-feed' );

    add_settings_field( 'client_id', 'Client ID', array( $this, 'field_output' ), 'just-another-insta-feed', 'jaif_auth', array( 'key' => 'client_id', 'type' => 'text' ) ); 

    add_settings_field( 'client_secret', 'Client Secret', array( $this, 'field_output' ), 'just-another-insta-feed', 'jaif_auth', array( 'key' => 'client_secret', 'type' => 'text' ) );

    add_settings_field( 'redirect_uri', 'Redirect URI', array( $this, 'field_output' ), 'just-another-insta-feed', 'jaif_auth', array( 'key' => 'redirect_uri', 'type' => 'text' ) );

    add_settings_field( 'cache', 'Enable Cache', array( $this, 'field_output' ), 'just-another-insta-feed', 'jaif_misc', array( 'key' => 'cache', 'type' => 'select' ) );

    add_settings_field( 'cache_refresh', 'Cache Refresh Rate', array( $this, 'field_output' ), 'just-another-insta-feed', 'jaif_misc', array( 'key' => 'cache_refresh', 'type' => 'text' ) ); 

    add_settings_field( 'follow_user', 'Default Follow User', array( $this, 'field_output' ), 'just-another-insta-feed', 'jaif_misc', array( 'key' => 'follow_user', 'type' => 'text' ) );

    add_settings_field( 'debug', 'Debug', array( $this, 'field_output' ), 'just-another-insta-feed', 'jaif_misc', array( 'key' => 'debug', 'type' => 'select' ) );
  }

  public function field_output( $args = array() ){

    extract( $args );
    switch( $type ){
      case 'text':
        printf ( '<input name="%s" type="text" id="%s" value="%s" class="regular-text"/>', JAIF_SETTINGS . '[' . $key . ']', JAIF_SETTINGS . '[' . $key . ']', $this->settings[$key] );
      break;

      case 'select':
        printf ( '<select name="%s" type="text" id="%s"/>', JAIF_SETTINGS . '[' . $key . ']', JAIF_SETTINGS . '[' . $key . ']' );
        printf( '<option value="0" %s>Off</option>', ( ( $this->settings[$key] == 1 ) ? 'selected' : '') ); 
        printf( '<option value="1" %s>On</option>', ( ( $this->settings[$key] == 1 ) ? 'selected' : '') );
        echo '</select>'; 
      break;
    }
  }

  public function save_settings( $new_settings ){
    $settings = array();

    $settings['client_id'] = ( isset( $new_settings['client_id'] ) ) ? $new_settings['client_id'] : null;

    $settings['client_secret'] = ( isset( $new_settings['client_secret'] ) ) ? $new_settings['client_secret'] : null;

    $settings['redirect_uri'] = $new_settings['redirect_uri'];

    $settings['cache'] = $new_settings['cache'];

    $settings['cache_refresh'] = ( is_numeric( $new_settings['cache_refresh'] ) && $new_settings['cache_refresh'] > 0 ) ? $new_settings['cache_refresh'] : 1800;

    $settings['follow_user'] = $new_settings['follow_user'];

    $settings['debug'] = $new_settings['debug'];
    if($settings['debug'] == 1){
      $settings['cache'] = 0;
    }

    $settings['version'] = JAIF_VERSION;

    return $settings;
  }

  public function setup_admin(){
    $this->settings_page = add_options_page( 'Just Another Instagram Feed Settings', 'JAIF Settings', 'manage_options', JAIF_SETTINGS_PAGE, array( $this, 'get_options_page' ) );

    add_action( 'load-'.$this->settings_page, array( $this, 'add_help_tab' ) );
  }

  public function add_help_tab(){
    $screen = get_current_screen();

    if( strcmp( $screen->id, $this->settings_page ) === 0 ){
      $screen->add_help_tab( array( 
        'id' => JAIF_SETTINGS_PAGE.'_api',    
        'title' => __( 'API Settings' , 'just-another-insta-feed' ),   
        'content' => $this->get_help_text( 'api' ),  
      ) );

      $screen->add_help_tab( array( 
        'id' => JAIF_SETTINGS_PAGE.'_usage',    
        'title' => __( 'Usage' , 'just-another-insta-feed' ),   
        'content' => $this->get_help_text( 'usage' ),  
      ) );

      $screen->add_help_tab( array( 
        'id' => JAIF_SETTINGS_PAGE.'_shortcode',    
        'title' => __( 'Shortcode' , 'just-another-insta-feed' ),   
        'content' => $this->get_help_text( 'shortcode' ),  
      ) );

      $screen->add_help_tab( array( 
        'id' => JAIF_SETTINGS_PAGE.'_php',    
        'title' => __( 'PHP Code' , 'just-another-insta-feed' ),   
        'content' => $this->get_help_text( 'php' ),  
      ) );

      $screen->add_help_tab( array( 
        'id' => JAIF_SETTINGS_PAGE.'_template',    
        'title' => __( 'Templates' , 'just-another-insta-feed' ),   
        'content' => $this->get_help_text( 'template' ),  
      ) );

      
    }
  }

  private function get_help_text( $section ){
    global $jaif;

    ob_start(); 
    switch( $section ){
      case 'usage': ?>
<h3>JAIF | Usage</h3>
<p><?php _e('Just Another Instagram Feed was developed to allow for a wide range of uses. As of right now it is only able to display media based on tag or username, however in the future JAIF can be used to create a wide range of Instagram based web apps from Instagram tag search sites to personal galleries.', 'just-another-insta-feed'); ?></p>
<p><?php _e('For now JAIF can be implemented into your Wordpress site using the 3 common usages in Wordpress Plugins which are Shortcodes, Widgets, and the standard PHP code.','just-another-insta-feed'); ?></p>
<p><?php _e('JAIF keeps track of all instances created during the load of a single page, so you are able place multiple JAIF instances on the same page without any issues.', 'just-another-insta-feed'); ?></p>
<p><?php _e("In order to understand how to use JAIF, I have compiled a table of all the variables used in the common function 'jaif()' used thoughout the plugin.", 'just-another-insta-feed'); ?></p>
<p><?php _e('Below are all of the possible variables one may use within any of the 3 the JAIF use cases (Widget, Shortcode, or PHP Code). A brief description of each variable and the default variables used when/if a variable is left out or blank is provided.', 'just-another-insta-feed'); ?></p>
<table cellpadding="3" cellspacing="0" class="jaif-info-table">
  <tr class="jaif-info-header">
    <th><?php _e( 'Variable', 'just-another-insta-feed'); ?></th>
    <th><?php _e( 'Description', 'just-another-insta-feed'); ?></th>
    <th><?php _e( 'Default Value', 'just-another-insta-feed'); ?></th>
  </tr>

<?php foreach( $jaif->get_defaults() as $variable => $default ){ ?>
  <tr class="jaif-info-row">
    <td class="jaif-var-name"><?php echo $variable ?> </td>
    <td><?php  _e( $jaif::$options_desc[$variable], 'just-another-insta-feed' ) ?></td>
    <td><?php 
      $type = gettype($default);
      print("$default ($type)"); ?></td>
  </tr>
<?php }?>
</table>
<p><sup>**Coming Soon...</sup></p>
      <?php break; ?>
      
      <?php case 'shortcode': ?>
<h3>JAIF | Shortcode</h3>
<p><?php _e('The JAIF shortcode <code>[jaif]</code> offers several variables to customize the query and output of each individual shortcode instance.', 'just-another-insta-feed'); ?></p>
<p><?php _e('Like any shortcode, the JAIF shortcode can be placed almost anywhere within a post, page, and even a template file (if you use <code>do_shortcode();</code>)', 'just-another-insta-feed'); ?></p>
<div id="jaif-shortcode-example">
<h3><?php _e('Example Usage:','just-another-insta-feed'); ?></h3>
<?php $shortcode = '[jaif page_limit=4 columns=4 search="pugs" follow_user="nihcssabaes" follow_link=1 pagination=1 layout="full" fancybox=1]'; ?>
<h4><?php _e('Shortcode','just-another-insta-feed'); ?>:</h4>
<pre><?php echo $shortcode; ?></pre>
<h4>Result:</h4>
<?php echo do_shortcode( $shortcode ); ?>
</div>
<?php break; ?>

<?php case 'api': ?>
<h3>JAIF | API Settings</h3>
<p><?php _e( 'In order to properly get started using JAIF, please follow the 4 easy steps below:', 'just-another-insta-feed' ); ?></p>
<ol class="jaif-setup-steps">
  <li><?php _e( 'Login or register a new account at the <a href="http://instagram.com/developer/" target="_blank">Instagram developers portal', 'just-another-insta-feed' ); ?></a></li>
  <li><?php _e( '<a href="http://instagram.com/developer/clients/manage/" target="_blank">Register a new Instagram application</a>, make sure to set the `REDIRECT URI` to', 'just-another-insta-feed' );?><code><?php echo get_site_url( get_current_blog_id() ); ?></code></li>
  <li><?php _e( "Copy and paste the `CLIENT ID`, `CLIENT SECRET`, and `REDIRECT URI` into their respective JAIF setting fields below", 'just-another-insta-feed' );?><a href="" target="_blank"></a></li>
  <li><?php _e( 'Save your JAIF settings', 'just-another-insta-feed' ); ?></li>
</ol>
<?php break; ?>

<?php case 'template':?>
<h3>JAIF | Templates</h3>
<h4>Coming Soon...</h4>
<?php break; ?>

<?php case 'php':?>
<h3>JAIF | PHP Code</h3>
<div id="jaif-php-code-example">
  <p><?php _e('JAIF can be called directly within your template, custom plugin, etc. using the <code>jaif()</code> function','just-another-insta-feed'); ?></p>
  <p><?php _e('This function takes in parameters defined in the `Usage` tab.','just-another-insta-feed'); ?></p>
  <p><?php _e('For instance if you wanted to display media from the user `obeygiant` you could use something like this:','just-another-insta-feed'); ?></p>
  <pre><code>/* Somewhere in a plugin or template file (ie. functions.php) */
  $jaif_vars = array(
    'search' => 'obeygiant',
    'search_type' => 'user-media',
    'page_limit' => 3,
    'layout' => 'full',
    'columns' => 3,  
  );
  print( jaif($jaif_vars) );</code></pre>
  <p><h3><?php _e('Result','just-another-insta-feed'); ?>:</h3></p>
  <div><?php 
    $jaif_vars = array(
      'search' => 'obeygiant',
      'search_type' => 'user-media',
      'page_limit' => 3,
      'layout' => 'full',
      'columns' => 3,  
    );
    print( jaif($jaif_vars) );
  ?></div>
</div>
<?php break; ?>

<?php }?>

<?php return ob_get_clean();
  } 

  public function get_options_page(){
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    $this->settings = $this->get_settings(); ?>
<div class="wrap">
  <?php screen_icon(); ?>
  <h2>Just Another Instagram Feed Settings</h2>
  <form method="post" action="options.php"> 
<?php settings_fields( JAIF_SETTINGS.'_group' ); do_settings_sections( JAIF_SETTINGS_PAGE ); ?>
    <p class="submit">
      <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  />
      <button type="submit" class="button" name="if-action" value="flush-cache">Flush Cache</button> 
    </p>
  </form>
</div>
<?php 
  }
}

if( is_admin() ){ 
  $jaif_admin = new jaif_admin(); 

  //Deactivation Hook 
  register_deactivation_hook( JAIF_MAIN, array( $jaif_admin, 'deactivate' ) );
}
?>