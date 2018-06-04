<?php
/**
 * The Template for displaying all single task posts.
 *
 * @package WordPress
 */

if ( get_post_format($post->ID) == 'link' ){
	$external_link = get_post_meta($post->ID,'external_link',true);
	if ($external_link){
		wp_redirect($external_link); 
		exit;
	}	
}

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); 	?>
	
	<div class="col-lg-7 col-md-8 col-sm-8 white">
		<div class="row">
			<div class='breadcrumbs'>
				<?php if(function_exists('bcn_display') && !is_front_page()) {
					bcn_display();
					}?>
			</div>
		</div>						
		
		<?php get_template_part("part", "task");?>

	</div>
	
	<div class="col-lg-4 col-lg-offset-1 col-md-4 col-sm-4" id="sidebar">	
		<h2 class="sr-only">Sidebar</h2>

		<?php 
		get_template_part("part", "sidebar");
		
		dynamic_sidebar('task-widget-area'); 
		
		get_template_part("part", "related");
	
		$post_categories = wp_get_post_categories( $post->ID ); 
		$cats = array();
		$catsfound = false;	
		$catshtml='';
		if ($post_categories){
			foreach($post_categories as $c){
				$cat = get_category( $c );
				if ( $c < 2 ) continue;
				$catsfound = true;
				$catshtml.= "<span><a class='wptag t".$cat->term_id."' href='".get_term_link($cat->slug, 'category')."'>".str_replace(" ","&nbsp;",$cat->name)."</a></span> ";
			}
		}
			
		if ($catsfound){
			echo "<div class='widget-box'><h3>" . __('Categories' , 'govintranet') . "</h3><p class='taglisting page'>".$catshtml."</p></div>";
		}
		
		$posttags = get_the_tags();
		if ($posttags) {
			$foundtags=false;	
			$tagstr="";
		  	foreach($posttags as $tag) {
	  			$foundtags=true;
	  			$tagurl = $tag->term_id;
		    	$tagstr=$tagstr."<span><a class='label label-default' href='".get_tag_link($tagurl)."?type=task'>" . str_replace(' ', '&nbsp' , $tag->name) . '</a></span> '; 
		  	}
		  	if ($foundtags){
			  	echo "<div class='widget-box'><h3>" . __('Tags' , 'govintranet') . "</h3><p> "; 
			  	echo $tagstr;
			  	echo "</p></div>";
		  	}
		}
	 	?>			
	</div> 
			
	<?php 
	endwhile; // end of the loop. 

	get_footer(); 
	?>