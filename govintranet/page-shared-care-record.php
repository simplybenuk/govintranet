<?php /* Template Name: Shared Care Record */ ?>

<?php get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="col-lg-8 col-md-8 col-sm-8 white">
		<div class="row">
			<div class='breadcrumbs'>
				<?php if(function_exists('bcn_display') && !is_front_page()) {
					bcn_display();
					}?>
			</div>
		</div>
		<article class="clearfix">
		<h1><?php the_title(); ?></h1>
		<?php if( have_rows('shared_care_record_contact_information')):
			while( have_rows('shared_care_record_contact_information')) : the_row();

				//vars
				$website = get_sub_field('shared_care_record_website');

				?>
				<?php if( $website ): ?>
					<p><a href="<?php echo $website; ?>" target="_blank"><span class="dashicons dashicons-admin-site"></span> Visit <?php the_title(); ?> website</a>
				<?php endif; ?>
			<?php endwhile; ?>
		<?php endif; ?>

		<?php the_content(); ?>
		</article>
		<?php
		get_template_part("part", "downloads");
		if ('open' == $post->comment_status) {
			 comments_template( '', true );
		}
		?>

		<article>

			<?php if( have_rows('shared_care_record_contact_information')):
				while( have_rows('shared_care_record_contact_information')) : the_row();

					//vars
					$email = get_sub_field('shared_care_record_contact_email');
					$telephone = get_sub_field('shared_care_record_contact_number');
					$address = get_sub_field('shared_care_record_postal_address');
					$twitter = get_sub_field('shared_care_record_twitter_account');

					?>
					<?php if( $email ): ?>
						<hr><h3>Contact Information</h3>
					<?php elseif ( $telephone ): ?>
						<hr><h3>Contact Information</h3>
					<?php elseif ( $address ): ?>
						<hr><h3>Contact Information</h3>
					<?php elseif ( $twitter ): ?>
						<hr><h3>Contact Information</h3>
					<?php endif; ?>
					<?php if( $email ): ?>
						<p><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?> </a></p>
					<?php endif; ?>
					<?php if( $telephone ): ?>
						<p><a href="tel:<?php echo $telephone; ?>" target="_blank"><span class="dashicons dashicons-phone"></span> <?php echo $telephone; ?></a></p>
					<?php endif; ?>
					<?php if( $address ): ?>
						<p><span class="dashicons dashicons-admin-home"></span> <?php echo $address; ?></p>
					<?php endif; ?>
					<?php if( $twitter ): ?>
						<p><a href="<?php echo $twitter; ?>" target="_blank"><span class="dashicons dashicons-twitter"></span> View Twitter Page</a></p>
					<?php endif; ?>

				<?php endwhile; ?>
			<?php endif; ?>


	<div class='row'>
		<?php if( have_rows('primary_contact_information')):
			while( have_rows('primary_contact_information')) : the_row();

				//Vars
				$primaryContactName = get_sub_field('primary_contact_name');
				$primaryContactPosition = get_sub_field('primary_contact_position');
				$primaryContactEmail = get_sub_field('primary_contact_email');
				$primaryContactPhoneNumber = get_sub_field('primary_contact_phone_number');

				?>
				<?php if( $primaryContactName ): ?>
					<div class="col-md-12">
						<hr>
						<h3>Individual contacts</h3>
					</div>
				<?php endif; ?>
			<div class="col-md-6">
					<?php if( $primaryContactName ): ?>
						<p><strong> <?php echo $primaryContactName; ?></strong></p>
					<?php endif; ?>
					<?php if( $primaryContactPosition ): ?>
						<p><?php echo $primaryContactPosition; ?></p>
					<?php endif; ?>
					<?php if( $primaryContactEmail ): ?>
						<p><a href="mailto:<?php echo $primaryContactEmail; ?>"><?php echo $primaryContactEmail; ?> </a></p>
					<?php endif; ?>
					<?php if( $primaryContactPhoneNumber ): ?>
						<p><a href="tel:<?php echo $primaryContactPhoneNumber; ?>" target="_blank"><span class="dashicons dashicons-phone"></span> <?php echo $primaryContactPhoneNumber; ?></a></p>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php endif; ?>
			</div>
		<div class="col-md-6">
			<?php if( have_rows('secondary_contact_information')):
				while( have_rows('secondary_contact_information')) : the_row();

				//Vars
				$secondaryContactName = get_sub_field('secondary_contact_name');
				$secondaryContactPosition = get_sub_field('secondary_contact_position');
				$secondaryContactEmail = get_sub_field('secondary_contact_email');
				$secondaryContactPhoneNumber = get_sub_field('secondary_contact_phone_number');

				?>

					<?php if( $secondaryContactName ): ?>
						<p><strong> <?php echo $secondaryContactName; ?></strong></p>
					<?php endif; ?>
					<?php if( $secondaryContactPosition ): ?>
						<p><?php echo $secondaryContactPosition; ?></p>
					<?php endif; ?>
					<?php if( $secondaryContactEmail ): ?>
						<p><a href="mailto:<?php echo $secondaryContactEmail; ?>"><?php echo $secondaryContactEmail; ?> </a></p>
					<?php endif; ?>
					<?php if( $secondaryContactPhoneNumber ): ?>
						<p><a href="tel:<?php echo $secondaryContactPhoneNumber; ?>" target="_blank"><span class="dashicons dashicons-phone"></span> <?php echo $secondaryContactPhoneNumber; ?></a></p>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</div>

		</article>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4" id="sidebar">
		<h2 class="sr-only">Sidebar</h2>
		<h3>Latest News</h3>
		<?php

		// the query
		$the_query = new WP_Query( array(
			'post_type' => 'news',
		 	'tag' => get_field('related-news-tag'),
			'posts_per_page' => 5
		) ); ?>

		<?php if ( $the_query->have_posts() ) : ?>

			<!-- pagination here -->

			<!-- the loop -->
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
			<?php endwhile; ?>
			<!-- end of the loop -->

			<!-- pagination here -->

			<?php wp_reset_postdata(); ?>

		<?php else : ?>
			<p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
		<?php endif; ?>

		<?php
		if ( has_post_thumbnail( $id )){
			the_post_thumbnail('large', array('class'=>'img img-responsive'));
			echo wpautop( "<span class='news_date'>".get_post_thumbnail_caption()."</span>" );
		}
		get_template_part("part", "sidebar");
		get_template_part("part", "related");
		?>
	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
