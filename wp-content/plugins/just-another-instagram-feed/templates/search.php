<?php 
/*
  Instagram Feed Search Form 
*/

if( !isset( $this ) ) return false;

?>
<form id="search-form" action="" method="GET">
  <div id="search-elems-wrap">
    <label for="search_type">User</label>
    <input class="search-checkbox" type="radio" name="search_type" value="user" <?php if( isset($this->getVars['search_type']) && $this->getVars['search_type'] === 'user') echo 'checked'; ?> />
    <label for="search_type">Tag</label>
    <input class="search-checkbox" type="radio" name="search_type" value="tag" <?php if( isset($this->getVars['search_type']) && $this->getVars['search_type'] === 'tag') echo 'checked'; ?> />
    <input class="search-text wide" type="text" name="search" id="search" value="<?php echo isset($this->getVars['search']) ? $this->getVars['search'] : ''; ?>" />
    <button class="search-button" type="submit">Search</button>
  </div>
</form>