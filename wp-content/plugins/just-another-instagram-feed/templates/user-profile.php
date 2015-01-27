<?php
/*
  User Profile Template

  @description Displays the current user's profile picture, user name, name, and other details
*/

if( !isset( $user ) || $this->ajax ) return;

?>

<div class="jaif-user-profile">
  <h3 class="jaif-user-name"><?php echo $user->username; ?></h3>
  <img src="<?php echo $user->profile_picture; ?>" />
  <div class="jaif-user-counts">
    <span class="jaif-user-media"><?php echo $user->counts->media; ?></span>
    <span class="jaif-user-followby"><?php echo $user->counts->followed_by; ?></span>
    <span class="jaif-user-follows"><?php echo $user->counts->follows; ?></span>
  </div>
  <p class="jaif-user-fullname"><?php echo $user->full_name; ?></p>
  <p class="jaif-user-bio"><?php echo $user->bio; ?></p>
  <p class="jaif-user-site"><a href="<?php echo $user->website; ?>"><?php echo $user->website  ?></a></p>

  <form action="<?php echo jaif_get_url( jaif_get_query($_GET) ); ?>" method="POST">
    <input type="hidden" name="id" value="<?php echo $user->id; ?>" />
    <input type="hidden" name="jaif_action" value="<?php echo $this->outgoing_status[$user->relations->outgoing_status]['rev_action'] ?>" />
    <button type="submit" class="button user-action action-<?php echo $user->relations->outgoing_status; ?>">
    <?php echo $this->outgoing_status[$user->relations->outgoing_status]['name'] ?>
    </button>
  </form>
</div>