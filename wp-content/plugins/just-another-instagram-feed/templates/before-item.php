<?php 
/*
  Before Media Item Template File
    
  It is important to maintain the general structure for media loop items, that is they should be wrapped in a list item
  
  @since 0.1
*/
if( !isset( $data ) ) return;

global $jaif;

$class = 'jaif-loop-item';
$class .= ( $jaif->ajax == true ) ? ' ajax-loaded-item' : '';
$class .= ' '.$data->type;
?>
<li class="<?php echo $class; ?>" id="media-<?php echo $data->id; ?>" data-id="<?php echo $data->id; ?>">