<?php
/**
 * The template for displaying oembed programs
 *
 */

function clarku_people_oembed_styles() {
	print '
	<style>
		@import url("' . get_stylesheet_uri() . '");
	</style>
	';
}
//add_action( 'embed_head', 'clarku_people_oembed_styles' );

get_header( 'embed' );
?>
		<?php
		
		while ( have_posts() ) :
			the_post();
			$vars = array(
				'thumbnail' => '',
				'phone' => true,
				'email' => true,
				'link' => true,
			);
			uri_people_tool_get_template( 'person-card.php', $vars );

		?>

		<?php endwhile; // End of the loop. ?>

<?php
get_footer( 'embed' );
