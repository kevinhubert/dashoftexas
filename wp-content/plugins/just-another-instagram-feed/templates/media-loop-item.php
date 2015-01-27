<?php 
/*
  Media Loop Item

  @description Displays the current single media
*/

//Check if the Instagram Query is present and that the data object exists within it
if( !isset( $data ) ) return;

$authUser = jaif_authuser( $data );

do_action( 'jaif-before-loop-item', $data );

do_action( 'jaif-loop-item', $data );

do_action( 'jaif-after-loop-item', $data );
?>