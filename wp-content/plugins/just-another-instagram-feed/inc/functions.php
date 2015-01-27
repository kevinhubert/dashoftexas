<?php 
/*
  Just Another Instagram Feed Main Functions
*/

if( !function_exists( 'jaif' ) ){
function jaif( $vars = array() ){
  global $jaif;

  $jaif->count++;

  if( $jaif->the_content == '' ){ 
    $vars = wp_parse_args( $vars, $jaif->get_defaults() );
    $jaif->get_request( $vars );
  }
  $content = $jaif->the_content;

  $jaif->reset_content();

  return $content;
}
}

if( !function_exists( 'jaif_enqueue_assets' ) ){
/*
  Outputs current page's assets for the specified section
*/
function jaif_enqueue_assets( $page ){
  global $jaif;
    
  if( is_admin() && strcmp( $page, 'settings_page_'.JAIF_SETTINGS_PAGE ) === 0 ){
    //Admin scripts and styles    
    wp_register_style( 'jaif_admin_css', JAIF_URL . '/css/admin.css' );
    wp_enqueue_style( 'jaif_admin_css' );
  }
  
  if( !wp_script_is( 'jquery-ui-widget', 'enqueued' ) )
    wp_enqueue_script( 'jquery-ui-widget' );
  
  if( !wp_script_is( 'carousel', 'enqueued' ) ){
    /*//Carousel
    //wp_enqueue_script( 'carousel', plugins_url( 'js/jquery.rs.carousel-min.js', JAIF_MAIN ), array( 'jquery' ) );
    
    //Touch
    //wp_enqueue_script( 'carousel-touch', plugins_url( 'js/jquery.rs.carousel-touch-min.js', JAIF_MAIN ), array( 'jquery' ) );*/
  }
  
  //if( !wp_script_is( 'fancybox', 'enqueued' ) ){
  //Fancybox Scripts and Styles
  wp_enqueue_script( 'fancybox', plugins_url( 'js/fancybox/jquery.fancybox-1.3.4.js', JAIF_MAIN ), array( 'jquery' ) );
  wp_enqueue_style( 'fancybox', plugins_url( 'js/fancybox/jquery.fancybox-1.3.4.css', JAIF_MAIN ) );
  //}
  
  //Frontend scripts and styles
  wp_enqueue_script( 'jaif',  plugins_url( 'js/jaif.js', JAIF_MAIN ), array( 'jquery', 'fancybox' ) );
  
  wp_enqueue_style( 'jaif',  plugins_url( 'css/style.css' , JAIF_MAIN ) );
  
  wp_localize_script( 'jaif', 'jaif_vars', $jaif->localize );
}
}

if( !function_exists( 'jaif_register_widget' ) ){
/*
  Register the Just Another Instagram Feed Widget
*/
function jaif_register_widget(){
  register_widget( 'jaif_widget' );
}
}

if( !function_exists( 'jaif_shortcode' ) ){
function jaif_shortcode( $atts, $content = '' ){
  global $jaif;
  $atts = shortcode_atts( $jaif->get_defaults(), $atts );

  $output = '';
  
  if( $content != '' ) $output .= '<p class="jaif-description">'.$content.'</p>';
  
  $output .= jaif( $atts );

  return $output;
}
}

if( !function_exists( 'jaif_get_url' ) ){
/*
  Creates a URL to this site with the $query appended to it
*/
function jaif_get_url( $query=null ){
  $pageURL = ( @$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
  $pageURL .= $_SERVER["SERVER_NAME"]; 
  $pageURL .= preg_replace('/\?.*/', '',$_SERVER['REQUEST_URI'] );
  $pageURL .= preg_replace('/\?.*/', '',$_SERVER['REQUEST_URI'] );
  $pageURL .= $query;
  return $pageURL;
}
}

if( !function_exists( 'jaif_get_query' ) ){
function jaif_get_query( $get_args = array() ){
  $keys = array_keys($get_args);
  $vals = array_values($get_args);
  $query = '';
  for($k=0;$k<count($get_args);$k++){
    if($k == 0){
      $query .= '?';
    }elseif( $k < count($get_args) ){
      $query .= '&';
    }
    $query .= $keys[$k].'='.$vals[$k];
  }
  return $query;
}
}

if( !function_exists( 'jaif_dl_file' ) ){
function jaif_dl_file($file, $is_resume=TRUE){
  //Gather relevent info about file
  $size = jaif_remotefilesize($file);
  $fileinfo = pathinfo($file);

  //workaround for IE filename bug with multiple periods / multiple dots in filename
  //that adds square brackets to filename - eg. setup.abc.exe becomes setup[1].abc.exe
  $filename = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ? preg_replace('/\./', '%2e', $fileinfo['basename'], substr_count($fileinfo['basename'], '.') - 1) : $fileinfo['basename'];

  $file_extension = strtolower($fileinfo['extension']);

  //This will set the Content-Type to the appropriate setting for the file
  switch($file_extension){
    case 'jpeg': 
    case 'jpg': $ctype='image/jpg'; break; 
    case 'mp4': $ctype='video/mp4'; break;
    default:    $ctype='application/force-download';
  }

  //check if http_range is sent by browser (or download manager)
  if($is_resume && isset($_SERVER['HTTP_RANGE'])){
    list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);

    if ($size_unit == 'bytes') {
      //multiple ranges could be specified at the same time, but for simplicity only serve the first range
      //http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
      list($range, $extra_ranges) = explode(',', $range_orig, 2);
    }else{
      $range = '';
    }
  }else{
    $range = '';
  }

  //figure out download piece from range (if set)
  if($range != ''){
    list($seek_start, $seek_end) = explode('-', $range, 2);    
  }
    
  //set start and end based on range (if set), else set defaults
  //also check for invalid ranges.
  $seek_end = ( empty( $seek_end ) ) ? ( $size - 1 ) : min(abs(intval($seek_end)), ( $size - 1 ) );
  $seek_start = ( empty( $seek_start ) || $seek_end < abs(intval($seek_start)) ) ? 0 : max( abs( intval($seek_start) ),0 );
  
  //add headers if resumable
  if ($is_resume){
    //Only send partial content header if downloading a piece of the file (IE workaround)
    if ($seek_start > 0 || $seek_end < ($size - 1)){
      header('HTTP/1.1 206 Partial Content');
    }

    header('Accept-Ranges: bytes');
    header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$size);
  }

  //headers for IE Bugs (is this necessary?)
  header("Cache-Control: cache, must-revalidate");   
  header("Pragma: public");

  header( 'Content-Type: ' . $ctype );
  header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
  header( 'Content-Length: ' . ( $seek_end - $seek_start + 1 ) );
  
  ob_start();
  
  //Open the file
  $fp = fopen($file, 'rb');
  if($fp){
    //seek to start of missing part
    //fseek($fp, $seek_start);

    //start buffered download
    while(!feof($fp)){
      //reset time limit for big files
      set_time_limit(0);
      print(fread($fp, 1024*8));
      flush();
      ob_flush();
    }
    fclose($fp);    
  }
  exit;
}
}

if( !function_exists( 'jaif_remotefilesize' ) ){
function jaif_remotefilesize($url, $user = "", $pw = ""){
  ob_start();
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_NOBODY, 1);

  if(!empty($user) && !empty($pw)){
    $headers = array('Authorization: Basic ' .  base64_encode("$user:$pw"));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  }

  $ok = curl_exec($ch);
  curl_close($ch);
  $head = ob_get_contents();
  ob_end_clean();

  $regex = '/Content-Length:\s([0-9].+?)\s/';
  $count = preg_match($regex, $head, $matches);

  return isset($matches[1]) ? $matches[1] : "unknown";
}
}

if( !function_exists( 'jaif_debug' ) ){
function jaif_debug( $arr = array() ){
  ob_start();
  echo '<pre>';
  print_r( $arr );
  echo '</pre>';
  echo ob_get_clean();
}
}

if( !function_exists( 'jaif_get_domain'  ) ){
function jaif_get_domain(){
  $url = get_site_url();

  //$url = preg_replace( '/(http:\/\/)/', '', $url );
  //$url = preg_replace( '/(www.)/', '', $url );

  return $url;
}
}
?>