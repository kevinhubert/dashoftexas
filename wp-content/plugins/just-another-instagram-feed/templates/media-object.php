<?php 
/*
  The actual media object template
*/
global $jaif;

if( !isset( $data ) || !isset( $data->type ) ) return;

$type = $data->type;
$size = $jaif->media_size;
$media = ( isset( $data->images->$size ) ) ? $data->images->$size : $data->images->low_resolution;
$image_urls = '';
$media_style = "max-width:{$media->width}px; max-height:{$media->height}px;";
$class = 'jaif-media-link';
$add_vars = ( $jaif->link_target == true ) ? ' target="_blank"' : '';
$media_url = ( isset( $jaif->media_link ) && $jaif->media_link != '' ) ? $jaif->media_link : $data->link;
$rel = '';
if( $jaif->fancybox == true ){
  $type_s = $type.'s';
  $src_url = $data->$type_s->standard_resolution->url;
  $add_vars .= ' data-fancybox-href="' . $src_url . '"';	
  $class .= ' jaif-media-link-fancybox';
  $add_vars .= ' rel="jaif-media-item-' . $jaif->count . '"'; 
}
?>

<a class="<?php echo $class; ?>" <?php echo $add_vars; ?> href="<?php echo $media_url; ?>">
<?php printf( '<img class="%s" src="%s" alt="" style="%s"/>', 'media-'.$type, $media->url, $media_style ); ?>
</a>