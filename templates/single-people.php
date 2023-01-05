<?php
	
	$id = get_the_ID();
	$email = get_post_meta( $id, '_cu_people_email', TRUE );
	$phone = get_post_meta( $id, '_cu_people_phone', TRUE );
	$title = get_post_meta( $id, '_cu_people_title', TRUE );
 	$has_thumbnail = ( $args['thumbnail'] !== FALSE && has_post_thumbnail() ) ? TRUE : FALSE;

	get_header();
?>
<main>
	<article class="h-card clarku-people">

			<?php the_title('<div class="title p-name"><h1>', '</h1></div>'); ?>
		

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry">
				

					<?php if ( $has_thumbnail ) : ?>
					<figure class="person-photo">
						<?php if ( $args['link'] === TRUE ): ?>
							<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( $args['thumbnail'], array( 'class' => 'u-photo ' . $args['thumbnail'] )); ?></a>
						<?php else: ?>
							<?php the_post_thumbnail( $args['thumbnail'], array( 'class' => 'u-photo ' . $args['thumbnail'] )); ?>
						<?php endif; ?>
					</figure>
					<?php else: ?>
						<figure class="initials">
							<?php
								$name_words = explode( ' ', get_the_title() );
								foreach( $name_words as $n ) {
									echo $n[0];
								}
							?>
						</figure>
					<?php endif; ?>

					
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


