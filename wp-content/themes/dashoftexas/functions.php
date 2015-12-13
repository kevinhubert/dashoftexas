<?php

// DISABLE ADMIN BAR
	show_admin_bar( false );

// READ MORE LINK
	add_filter( 'the_content_more_link', 'modify_read_more_link' );
	function modify_read_more_link() {
		return '<p class="continue"><a class="continue__link" href="' . get_permalink() . '">Read More</a></p>';
	}
?>