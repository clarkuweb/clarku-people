<?php
	
	$id = get_the_ID();
	$email = get_post_meta( $id, 'cu_people_email', TRUE );
	$phone = get_post_meta( $id, 'cu_people_phone', TRUE );
	$title = get_post_meta( $id, 'cu_people_title', TRUE );

	get_header();
?>
<main>
	<article class="h-card">

			<?php the_title('<div class="title p-name"><h1>', '</h1></div>'); ?>
		

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry">
				
					<div class="person-photo u-photo"><?php the_post_thumbnail('medium'); ?></div>
					
					<ul class="person-details">
						<?php if( ! empty( $title ) ) { ?><li class="p-job-title"><?php echo $title; ?></li><?php } ?>
						<?php if( ! empty( $phone ) ) { ?><li class="p-tel"><strong class="screen-reader-text">Phone:</strong> <?php echo $phone; ?></li><?php } ?>
						<?php if( ! empty( $email ) ) { ?><li class="u-email"><strong class="screen-reader-text">Email:</strong> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></li><?php } ?>
					</ul>

					<?php
						the_content();
					?>

				</div>
			</div>

	</article>
</main>

<?php get_footer(); ?>