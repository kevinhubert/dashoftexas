<?php 
/* 
  Before loop template
*/

if( !isset( $jaif ) ) return;

$class = $jaif->layout;
$class .= ( $jaif->slider== true ) ? ' carousel-'.$jaif->layout : '';
$class .= ( $jaif->columns > 0 ) ? ' columns-'.$jaif->columns : '';
?>

<div class="jaif-media-loop <?php echo $class; ?>">
  <ul class="jaif-media-loop-list">