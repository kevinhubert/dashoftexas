<?php 
/*
  Media Like Form
*/

if( !isset( $data ) || !isset($authUser) ) return;

?>
<form action="<?php echo jaif_get_url( jaif_get_query($_GET) ); ?>" method="POST">
  <input type="hidden" name="id" value="<?php echo $data->id; ?>" />
  <input type="hidden" name="insta_action" value="<?php echo $authUser->liked['rev_action']; ?>" />
  <button type="submit" class="button if-user-action if-action-<?php echo $authUser->liked['action']; ?>">
  <?php //echo $authUser->liked['name']; ?>
  </button>
</form>