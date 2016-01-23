<?php

// DISABLE ADMIN BAR
	show_admin_bar( false );

// READ MORE LINK
	add_filter( 'the_content_more_link', 'modify_read_more_link' );
	function modify_read_more_link() {
		return '<p class="continue"><a class="continue__link" href="' . get_permalink() . '">Read More</a></p>';
	}

//CHANGE NUMBER OF POSTS ON CATEGORIES PAGE
    function category_archive_all_posts($query) {
    if ( !is_admin() && $query->is_main_query() ) {
        if ( $query->is_category ) {
        $query->set( 'posts_per_page', 18 );
        }
    }
    }

    add_action('pre_get_posts','category_archive_all_posts');

?>