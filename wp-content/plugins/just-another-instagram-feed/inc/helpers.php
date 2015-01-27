<?php
/*
  Just Another Instagram Plugin Template Helper Functions
*/

function jaif_template( $file = '' ){
  if( $file == '' ) return false;
    $template_dir = get_template_directory() . '/jaif/';

  if( file_exists( $template_dir . $file ) ) 
    return $template_dir . $file;
  elseif( file_exists( JAIF_TEMPLATE . $file ) )
    return JAIF_TEMPLATE . $file;
  else 
    return false; 
}

function jaif_authuser( $data = null ){
  global $jaif;

  if( !is_object( $data ) ) return false;
  
  $authUser = $jaif->authUser;
  
  $liked = false;
  if( isset( $data->user_has_liked ) && $data->user_has_liked == 1 ){
    $liked = true;
  }
  $authUser->liked = $jaif->getLikeAction( $liked );
  
  return $authUser;
}

function jaif_media_loop_item( $data = null ){
  $authUser = jaif_authuser( $data );
  
  if( is_object( $data ) ) include( jaif_template( 'media-loop-item.php' ) ); 
}

function jaif_before_loop( $jaif = null ){
  if( !is_null( $jaif ) ) include( jaif_template( 'before-loop.php' ) );
}

function jaif_after_loop( $jaif=null ){
  if( !is_null( $jaif ) ) include( jaif_template( 'after-loop.php' ) );
}

function jaif_end( $jaif=null ){
  if( !is_null( $jaif ) ) include( jaif_template( 'end.php' ) );
}

function jaif_before_loop_item( $data = null ){
  if( !is_null( $data ) ) include( jaif_template( 'before-item.php' ) );
}

function jaif_after_loop_item( $data = null ){
  if( !is_null( $data ) ) include( jaif_template( 'after-item.php' ) );
}

function jaif_media_object( $data = null ){
  if( !is_null( $data ) ) include( jaif_template( 'media-object.php' ) );
}

function jaif_media_save_form( $data = null ){
  if( !is_null( $data ) ) include( jaif_template( 'media-save-form.php' ) );
}

function jaif_media_like_form( $data = null ){
  $authUser = jaif_authuser( $data );
  
  if( !is_null( $data ) ) include( jaif_template( 'media-like-form.php' ) );
}

function jaif_media_tags( $data = null ){
  if( !is_null( $data ) ) include( jaif_template( 'media-tags.php' ) );
}

function jaif_media_user( $data = null, $authUser = false ){
  if( !is_null( $data ) ) include( jaif_template( 'media-user.php' ) );
}

function jaif_media_social_wrap( $data = null, $authUser = false ){
  if( !is_null( $data ) ) include( jaif_template( 'social-wrap.php' ) );
}

function jaif_media_caption( $data ){
  if( !is_null( $data ) ) include( jaif_template( 'media-caption.php' ) );
}

function jaif_pagination( $jaif = null ){
  if( !is_null( $jaif ) ) include( jaif_template( 'pagination.php' ) );
}

function jaif_follow_link( $jaif = null ){
  if( !is_null( $jaif ) ) include( jaif_template( 'media-loop-follow.php' ) );
}

/* Template Default Actions */
//Media Loop
add_action( 'jaif-before-loop', 'jaif_before_loop', 5, 1 );

add_action( 'jaif-after-loop', 'jaif_after_loop', 5, 1 );

add_action( 'jaif-end', 'jaif_pagination', 5, 1 );
add_action( 'jaif-end', 'jaif_follow_link', 10, 1 );
add_action( 'jaif-end', 'jaif_end', 10, 1 );

//Single Media

//User Loop

//Single User Loop

/* Media Loop Item */
add_action( 'jaif-before-loop-item', 'jaif_before_loop_item', 5, 1 );

add_action( 'jaif-loop-item', 'jaif_media_object', 5, 1 );
add_action( 'jaif-loop-item', 'jaif_media_caption', 10, 1 );
add_action( 'jaif-loop-item', 'jaif_media_social_wrap', 15, 1 );
//add_action( 'jaif-loop-item', 'jaif_media_save_form', 10, 2 );
//add_action( 'jaif-loop-item', 'jaif_media_like_form', 15, 1 );
//add_action( 'jaif-loop-item', 'jaif_media_tags', 20, 2 );	
//add_action( 'jaif-loop-item', 'jaif_media_user', 25, 2 );

add_action( 'jaif-after-loop-item', 'jaif_after_loop_item', 5, 1 );
?>