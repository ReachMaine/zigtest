<?php
// based on a snippet by Aaron Bolton
//* [woothemes_team_directory]
function woothemes_team_dir( $atts ){
	// Specify shortcode parameters
	$atts = shortcode_atts( array( 'limit' => -1 ), $atts );


	$image_width = '250px';
	$image_height = '250px';
	// Set arguments for WordPress Query using the shortcode parameters above.
	$args = array(
		'post_type' => 'team-member',
		'posts_per_page' => $atts[ 'limit' ],
		'order' => 'ASC',

	);
	if ( isset( $atts['category'] ) && is_numeric( $atts['category'] ) ) {
		$args['category'] = intval( $args['category'] );
	}

	// The Query
	$team_query = new WP_Query( $args );

	// The Loop
	if ( $team_query->have_posts() ) {
		$output = '
			<div class="widget widget_woothemes_our_team">
				<div class="team-members component effect-fade">
			';
		while( $team_query->have_posts() ) : $team_query->the_post();
			$post_ID = $team_query->post->ID;
			$team_name = $team_query->post->post_title;
			$team_content = $team_query->post->post_content;
			$team_url = get_the_permalink();
			$team_terms = wp_get_post_terms( $post_ID, 'team-member-category', '' );
			$team_category = $team_terms[0]->name;
			$team_thumbnail_id = get_post_thumbnail_id( $post_ID );
			$team_image = wp_get_attachment_image_src( $team_thumbnail_id,array($image_width, $image_height) );
			$team_image_url = $team_image[0];
			$team_image_alt = get_post_meta( $team_thumbnail_id, '_wp_attachment_image_alt', true );
			$team_role = get_post_meta( $post_ID, '_byline', true );

			// The Team Member Result
			$output .= '
					<div itemscope="" itemtype="http://schema.org/Person" class="team-member first">
						<figure itemprop="image"><a href="'. $team_url .'"><img width="'.$image_width.'" height="'.$image_height.'" src="'. $team_image_url .'" class="avatar wp-post-image" alt="'. $team_image_alt .'"></a></figure>
						<h3 itemprop="name" class="member"><a href="'. $team_url .'">'. $team_name .'</a></h3>
						<!--/.member-->
						<p class="role" itemprop="jobTitle">'. $team_role .'</p>
						<!--/.excerpt-->
					</div>';
		endwhile;
		$output .= '
				</div>
			  <!--/.team-members-->
			</div>';
	} else{
	}
	return $output;
	wp_reset_postdata();
}
add_shortcode( 'woothemes_team_directory', 'woothemes_team_dir' );
?>
