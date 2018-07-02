<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0
 */

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	
		<div class="col-lg-8 col-md-8 col-sm-8 white ">
			<div class="row">
				<div class='breadcrumbs'>
					<?php if(function_exists('bcn_display') && !is_front_page()) {
						bcn_display();
						}?>
				</div>
			</div>
			<article class="clearfix">
			<h1><?php the_title(); ?></h1>

			<?php the_date(get_option('date_format'), '<p class="news_date">', '</p>' , 1) ?>

			<?php the_content(); ?>
			</article>
			<?php
			if ('open' == $post->comment_status) {
				 comments_template( '', true ); 
			}
		 	?>

			<?php wp_reset_query();?>
			
		</div> <!--end of first column-->
		
		<div class="col-lg-4" id="sidebar">	
			
		</div> <!--end of second column-->

			
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>