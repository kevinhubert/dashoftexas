<?php
/*
  Pagination form
*/

if( !isset( $jaif ) ) return;

if( (bool)$jaif->pagination == true && isset( $jaif->insta_query->pagination ) && ( !empty( $jaif->insta_query->pagination ) && isset( $jaif->insta_query->pagination->next_url ) ) ){
  $pagination = $jaif->insta_query->pagination;
}else{
  return;
}

$max_id = isset( $pagination->next_max_id ) ? $pagination->next_max_id : $pagination->next_max_tag_id;

$args = array(
  'max_id' => $max_id,
  'search_type' => $jaif->search_type,
  'search' => $jaif->search,
  'page_limit' => $jaif->page_limit,
  'layout' => $jaif->layout,
  'media_size' => $jaif->media_size,  
  'pagination' => $jaif->pagination,
  'fancybox' => $jaif->fancybox,
  'user' => ( isset( $jaif->getVars['user'] ) ) ? $jaif->getVars['user'] : null,
  'count' => $jaif->count,
);
//$link = jaif_get_url();
?>
<form class="jaif-pagination" action="<?php echo $link; ?>" method="POST">
  <?php 
  foreach( $args as $name => $value ){ 
    printf( '<input type="hidden" value="%s" name="%s"/>', $value, $name  );
  } 
  ?>
  <button class="jaif-button" type="submit" name="jaif_action" value="get">
  <?php _e(JAIF::$messages['loadmore'], 'just-another-insta-feed');?></button>
</form>