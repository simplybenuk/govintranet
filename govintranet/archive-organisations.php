<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 */

get_header(); ?>

	<div class="col-lg-4 col-md-4" id="sidebar">
		<h2 class="sr-only">Sidebar</h2>
		<h3 style="text-align:center;"> Search & Filter</h3>
		<span><?php do_action('show_beautiful_filters'); ?></span>
	</div>
		<div class="col-lg-8 col-md-8 white">

			<h1>Organisations</h1>
			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<h3 id="post-<?php the_ID(); ?>">
					<a role="button" data-toggle="collapse" href="#collapse<?php the_ID(); ?>" aria-expanded="false" aria-controls="collapse<?php the_ID(); ?>">
					  <?php the_title(); ?>
					</a><span class="badge"><?php echo get_the_term_list( $post->ID, 'geographicalfootprint', '<span class="geobadge">','','</span>' ) ?></span>
				</h3>
				<a href="<?php the_field('organisation-url'); ?>"><?php the_field('organisation-url'); ?></a>
				<p><?php echo get_the_term_list( $post->ID, 'organisationtype', '<small>',' | ','</small>' ) ?></p>
				<div class="collapse" id="collapse<?php the_ID(); ?>">
				<?php the_content() ?>
				</div>
				<hr>

			<?php endwhile; ?>
			<?php endif; ?>

		</div>


<?php
get_footer();
