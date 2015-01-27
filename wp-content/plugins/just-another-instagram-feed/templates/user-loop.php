<?php 
/*
  User Search Loop Template
*/

if( !isset( $data_obj ) || !isset( $this ) ) return;

?>
<?php if(count($data_obj) == 0) : ?>

<div class="jaif-auth-dialog">
  <h3><?php echo $this->messages['nothing']; ?> </h3>
  <p><?php echo $this->messages['noUsers']; ?></p>
</div>

<?php else : ?>

<?php foreach($data_obj as $index => $data) { ?> 
<div class="jaif-loop-item user" id="user-<?php echo $data->id; ?>">
  <a href="<?php echo instafeed_get_url( '?user=' . $data->id ); ?>">
    <img class="media-img" src="<?php echo $data->profile_picture; ?>" alt="" />
  </a>
  <p><?php echo $data->username; ?></p>
</div>
<?php } ?>

<?php endif; ?>