<?php 
/*
  Media Save Form

  @description Outputs the media save form
*/

if( !isset($data) || !isset($type) ) return;

$type_index = $type.'s';
?>
<form action="<?php jaif_get_domain(); ?>" method="GET">
  <select name="save">
  <?php foreach($data->$type_index as $size => $media) { ?>
    <option value="<?php echo $size; ?>" ><?php echo $media->width . ' x ' . $media->height;   ?></option>
    <?php $media_urls .= '<p>
      <span class="media-size">'.$size.' ('.$media->width . ' x ' . $media->height.')</span>
      <code class="media-size-url">'.$media->url.'</code>
    </p>'; ?>
  <?php  } ?>
  </select>
  <input type="hidden" name="media" value="<?php echo $data->id; ?>" />
  <button class="button save-button" type="submit">Save</button>
</form>