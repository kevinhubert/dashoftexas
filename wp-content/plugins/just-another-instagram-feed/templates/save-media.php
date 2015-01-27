<?php 
/*
  Media Save Template

  @description: Sets up the page for proper saving of the selected Intsagram media
*/
if( !isset( $this ) ) return;

$url = $this->media->url;
jaif_dl_file($url);
?>