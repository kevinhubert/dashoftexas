<?php
/* 
	Single Media Tags
	
	@description: Loops through current $data to output search links for every tag
*/

//Check if the Instagram Query $data variable is present and that the tags object exists
if( !isset( $data->tags ) ) return;

?>
<div class="media-tags">
  <?php foreach( $data->tags as $tag ) {
    $query = jaif_get_query(	array(
      'search_type' => 'tag',
      'search' => $tag 
    ) );
    ?>
    <a href="<?php echo jaif_get_url($query); ?>" class="media-tag"><?php echo $tag; ?></a>
  <?php } ?>
</div>