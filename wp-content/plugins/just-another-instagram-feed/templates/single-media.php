<?php 
/*
  Single Media Display
  
  @decsription Displays a single Instagram media object
*/

//Check if the Instagram Query is present and that the data object exists within it
if( !isset( $this->insta_query->data ) ) return;

$data = $this->insta_query->data;
if( isset( $this->authUser ) ){
  $authUser = $this->authUser;
}

$type = isset( $data->type ) ? $data->type : false;
$type_index = $type.'s';
$media = $data->$type_index->standard_resolution;
$image_urls = '';
?>
<div class="media-item" id="media-<?php echo $data->id; ?>">
  <?php if( $type == 'image' ) :  ?>

  <a href="<?php instafeed_get_url( '?media=' .$data->id ); ?>">
    <img class="media-img" src="<?php echo $media->url; ?>" alt="" />
  </a>

  <?php elseif( $type == 'video' ) : ?>

  <div class="media-video">
  <video controls>
    <source src="<?php echo $media->url; ?>" type="video/mp4"/>
  </video>
  </div>

  <?php endif; ?>

  <?php 
  add_action( 'instafeed-after-media-item', 'instafeed_media_save_form', 5, 2 );
  add_action( 'instafeed-after-media-item', 'instafeed_media_like_form', 10, 2 );
  add_action( 'instafeed-after-media-item', 'instafeed_media_tags', 15, 2 );
  add_action( 'instafeed-after-media-item', 'instafeed_media_user', 20, 2 );

  do_action( 'instafeed-after-media-item', $data, $authUser ); 
  ?>
</div>