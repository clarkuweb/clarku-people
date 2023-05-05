<?php

	$id = get_the_ID();
	$email = get_post_meta( $id, 'cu_people_email', TRUE );
	$phone = get_post_meta( $id, 'cu_people_phone', TRUE );
	$title = get_post_meta( $id, 'cu_people_title', TRUE );

	// @todo: consider an option to conditionally make a card link	
// 	if( empty( get_the_content() ) ) {
// 		$args['link'] = FALSE;
// 	}

 
 	$has_thumbnail = ( $args['thumbnail'] !== FALSE && has_post_thumbnail() ) ? TRUE : FALSE;
	

?><div class="people-card h-card <?php if ( $has_thumbnail ) { echo 'has-thumbnail'; } ?>">
	<header>

			<?php if ( $has_thumbnail ) : ?>
			<figure>
				<?php if ( $args['link'] === TRUE ): ?>
					<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( $args['thumbnail'], array( 'class' => 'u-photo ' . $args['thumbnail'] )); ?></a>
				<?php else: ?>
					<?php the_post_thumbnail( $args['thumbnail'], array( 'class' => 'u-photo ' . $args['thumbnail'] )); ?>
				<?php endif; ?>
			</figure>
			<?php else: ?>
				<?php
					$name_words = explode( ' ', get_the_title() );
					$initials = '<figure class="initials">';
					foreach( $name_words as $n ) {
						$initials .= $n[0];
					}
					$initials .= '</figure>';
				?>
				<?php if ( $args['link'] === TRUE ): ?>
					<a class="initials-link" href="<?php the_permalink() ?>"><?php echo $initials; ?></a>
				<?php else: ?>
					<?php echo $initials; ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $args['link'] === TRUE ): ?>
				<h3 class="p-name"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
			<?php else: ?>
				<h3 class="p-name"><?php the_title(); ?></h3>
			<?php endif; ?>

	</header>
	
	<div class="inside">

		<p class="people-title p-job-title"><?php echo $title; ?></p>
		<?php

			if( $args['phone'] === TRUE && ! empty( $phone ) ) {
				echo '<p class="p-tel">' . $phone . '</p>';
			}
			if( $args['email'] === TRUE && ! empty( $email ) ) {
				echo '<p class="email"><a class="u-email" href="mailto:' . $email . '">' . $email . '</a></p>';
			}

		
		?>

	</div>
</div>
