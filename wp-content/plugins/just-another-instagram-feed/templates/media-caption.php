<?php 
/*
  The media object caption template
*/

if( !isset( $data ) || !isset( $data->type ) ) return;
?>
<div class="jaif-media-caption hidden">
<p>
<?php echo ( isset( $data->caption ) ) ? $data->caption->text : ''; ?>
</p>
</div>