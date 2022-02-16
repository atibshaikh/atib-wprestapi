<?php 
get_header();

if(have_posts()){

	while(have_posts()){

		the_post();

		echo "test";
		the_post_thumbnail();


	}
	wp_reset_postdata();
	wp_reset_query();

}

get_footer();