<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 */
?>

<?php 

	$pageslug = $post->post_name;

	if ( ! have_posts() ) { 
	
			echo "<h1>";
			_e( 'Not found', 'govintranet' );
			echo "</h1>";
			echo "<p>";
			_e( 'There\'s nothing to show.', 'govintranet' );
			echo "</p>";
			get_search_form(); 
	};


	while ( have_posts() ) : the_post(); //echo $post->post_type;

	$post_type = ucwords($post->post_type);
	$post_cat = '';
	if ($post_type == "Task"):
		$post_cat = get_the_category();
	elseif ($post_type == "News"):
		$post_cat = get_the_terms($post->ID,'news-type');
	elseif ($post_type == "Event"):
		$post_cat = get_the_terms($post->ID,'event-type');
	endif;
	$title_context='';		
	$context='';
	$icon='';
		$image_url = get_the_post_thumbnail($id, 'thumbnail', array('class' => 'alignright'));
	if ($post_type=='User'){
		global $foundstaff;
		$foundstaff++;
		
		$user_info = get_userdata($userid);
		$userurl = site_url().'/staff/'.$user_info->user_nicename;
		$displayname = get_user_meta($post->user_id ,'first_name',true )." ".get_user_meta($post->user_id ,'last_name',true );		
		$directorystyle = get_option('options_staff_directory_style'); // 0 = squares, 1 = circles
		$avstyle="";
		if ( $directorystyle==1 ) $avstyle = " img-circle";
		$image_url = get_avatar($post->user_id ,100);
		$image_url = str_replace(" photo", " photo alignleft".$avstyle, $image_url);
		$userurl = get_author_posts_url( $post->user_id); 
		$gis = "options_forum_support";
		$forumsupport = get_option($gis);
		$staffdirectory = get_option('options_module_staff_directory');
		if (function_exists('bp_activity_screen_index')){ // if using BuddyPress - link to the members page
			$userurl=str_replace('/author', '/members', $userurl); }
		elseif (function_exists('bbp_get_displayed_user_field') && $staffdirectory ){ // if using bbPress - link to the staff page
			$userurl=str_replace('/author', '/staff', $userurl);
		}
	} else {
		$userurl = get_permalink();
	}
	$context='';
	if ($post_type=='Post_tag') { 
		$icon = "tag"; 
	}	
	if ($post_type=='Task'){
		if ($post->post_parent){ // child chapter
			$context = "guide";
			$icon = "book";
			$taskparent=get_post($post->post_parent);
			$title_context='';
			if ($taskparent){
				$parent_guide_id = $taskparent->ID; 		
				$title_context=" (".govintranetpress_custom_title($taskparent->post_title).")"; 
			}
		} elseif ( get_posts ("post_type=task&posts_per_page=-1&post_status=publish&post_parent=".$post->ID."&orderby=menu_order&order=ASC") ){
			$context = "guide";
			$icon = "book";
		} else {
			$context = "task";
			$icon = "question-sign";
		}			
	}
	if ($post_type=='Project'){
		$context = "project";
		$icon = "road";
		$taskpod = get_post ($post->ID); 
		$projparent=get_post($post->post_parent);
		$title_context='';
		if ($projparent){
			$title_context=" (".govintranetpress_custom_title($projparent->post_title).")";
		}			
	}
	if ($post_type=='News'){
			$context = "news";
			$icon = "star-empty";			
	}
	if ($post_type=='Vacancies'){
			$context = "job vacancy";
			$icon = "random";			
	}
	if ($post_type=='Blog'){
			$context = "blog";
			$icon = "comment";			
	}
	if ($post_type=='Event'){
			$context = "event";
			$icon = "calendar";			
	}
	if ($post_type=='jargon-buster'){
			$context = "jargon buster";
			$icon = "th-list";			
	}
	if ($post_type=='User'){
			$context = get_user_meta($post->user_id,'user_job_title',true); 
			if ($context=='') $context="staff";
			$icon = "user";			
	}
	if ($post_type=='Team'){
			$context = "team";
			$icon = "list-alt";			
	}

	if ($post_type=='Page'){
			$context = "page";
			$icon = "file";
	}

	if ($post_type=='Forum'||$post_type=='Reply'||$post_type=='Topic'){
			$context = "forum";
			$icon = "comment";
	}

	if ($post_type=='Attachment'): 
		$context='document download';
		$icon = "download";			
		?>
		<h3 class='postlist'>				
		<a href="<?php echo wp_get_attachment_url( $post->id ); ?>" title="<?php the_title_attribute( 'echo=1' ); ?>" rel="bookmark"><?php the_title();  ?></a></h3>
		<?php 
	elseif ($post_type=='User'): 
		?>			
		<div class="media"><div>
		<h3 class='postlist'>				
		<a href="<?php echo $userurl; ?>" title="<?php printf( esc_attr__( '%s', 'govintranet' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); echo " (".$context.")";  ?></a></h3>
		<?php 
	elseif ($post_type != 'Category'): 
		echo "<div class='media'>" ;
		$ext_icon = '';
		$ext = '';
		if ( get_post_format($post->ID) == 'link' ):
			$ext_icon = "<span class='dashicons dashicons-migrate'></span>";
			$ext="class='external-link' ";
		endif;
		?>
		<h3 class='postlist'>				
		<a href="<?php echo get_the_permalink(get_the_id()); ?>" <?php echo $ext; ?> title="<?php printf( esc_attr__( '%s', 'govintranet' ), the_title_attribute( 'echo=0' )); ?>" rel="bookmark"><?php echo get_the_title($post->ID); echo "</a> <small>".$title_context."</small>"; ?><?php echo $ext_icon; ?></h3>
		<?php
	endif;
	
	if ( $image_url ):
		echo "<a href='";
		echo $userurl;
		echo "'><div class='hidden-xs'>".$image_url."</div></a>" ;
	endif;

//	echo "<div class='media-body'>";
	
	if (($post_type=="Task" && $pageslug!="category")){
		echo "<p>";
		echo '<span class="listglyph">'.ucfirst($context).'</span>&nbsp;&nbsp;';
		if ( $post_cat ) foreach($post_cat as $cat){
			if ($cat->term_id != 1 ){
				echo "<span class='listglyph'><span class='dashicons dashicons-category gb".$cat->term_id."'></span><a href='".get_term_link($cat->slug,$cat->taxonomy)."'>".$cat->name;
			echo "</a></span>&nbsp;";
			}
		}
	} elseif (($post_type=="News" || $post_type=="News-update" || $post_type =="Blog" || $post_type=='Forum' || $post_type=='Topic' || $post_type=='Reply' ) && $pageslug!="category"){
		echo "<div><p>";
		echo '<span class="listglyph">'.ucfirst($context).'</span>&nbsp;';
		if ( $post_cat ) foreach($post_cat as $cat){
			if ($cat->term_id != 1 ){
				echo "<span class='listglyph'><span class='dashicons dashicons-category gb".$cat->term_id."'></span><a href='".get_term_link($cat->slug,$cat->taxonomy)."'>".$cat->name;
				echo "</a></span>&nbsp;";
			}
		}
		if ( is_archive() || is_search() || is_author() ){
			$thisdate= get_the_date('j M Y');
			echo '<span class="listglyph">'.get_the_date("j M Y"); 
			echo '</span> ';
			if ( get_comments_number() ){
				echo " <a href='".get_permalink($post->ID)."#comments'>";
				printf( _n( '<span class="badge">1 comment</span>', '<span class="badge">%d comments</span>', get_comments_number(), 'govintranet' ), get_comments_number() );
				echo "</a>";
			}
		}
		if ($post_type=="Blog" && !is_author() ){
			$user_info = get_userdata($post->post_author);
			$userurl = site_url().'/staff/'.$user_info->user_nicename;
			$displayname = get_user_meta($post->post_author ,'first_name',true )." ".get_user_meta($post->post_author ,'last_name',true );		
			$directorystyle = get_option('options_staff_directory_style'); // 0 = squares, 1 = circles
			$avstyle="";
			if ( $directorystyle==1 ) $avstyle = " img-circle";
			$image_url = get_avatar($post->post_author , 32);
			$image_url = str_replace(" photo", " photo ".$avstyle, $image_url);
			$image_url = str_replace('avatar ', 'avatar32 ' , $image_url);
			echo "&nbsp;";
			echo $image_url;
			$auth = get_the_author();
			echo "<span class='listglyph'>&nbsp;".$auth."</span>";
		}
		echo "</p></div>";
	} elseif ($post_type=="Team" ){
		echo "<div><p>";
		echo '<span class="listglyph">'.ucfirst($context).'</span>&nbsp;';
		echo "</p></div>";
	} elseif ($post_type=="Event" && $pageslug!="category"){
		echo "<div><p>";
		$thisdate= get_post_meta($post->ID, 'event_start_date', true);
		$thisdate=date("j M Y",strtotime($thisdate));
		echo '<span class="listglyph">'.ucfirst($context).'&nbsp;'.$thisdate.'</span>&nbsp;&nbsp;';
		if ( $post_cat ) foreach($post_cat as $cat){
			if ($cat->term_id != 1 ){
				echo "<span class='listglyph'><span class='dashicons dashicons-category gb".$cat->term_id."'></span>".$cat->name;
			echo "</span>&nbsp;";
			}
		}
		echo "</p></div>";
	} elseif ($post_type != 'Category' && $post_type != 'User') {
		echo "<div><p>";
		echo '<span class="listglyph">'.ucfirst($context).'</span>&nbsp;';
		if ( $post_cat ) foreach($post_cat as $cat){
			if ($cat->term_id != 1 ){
				echo "<span class='listglyph'><span class='dashicons dashicons-category gb".$cat->term_id."'></span>".$cat->name;
			echo "</span>&nbsp;";
			}
		}
		echo "</p></div>";
	}

	if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. 

		if ($post_type=='Post_tag') { 
			printf( _x( 'All intranet pages tagged with %s' , 'represents the tag name' , 'govintranet' ) , "\"" . get_the_title() . "\"" );
		} elseif ($post_type=='Category') { 
			echo "<div class='media'>" ;
			?>
			<h3 class='postlist'>				
			<a href="<?php echo $post->link; ?>" title="<?php printf( esc_attr__( '%s', 'govintranet' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php echo $post->post_title; echo "</a> "; ?></h3><span class='listglyph'><?php _e('Tasks and guides category' , 'govintranet'); ?></span>
			

			<?php
		}

		if ($post_type!='User' ){
			the_excerpt(); 
		}
		
		if ($post_type=='User'){
			$user_info = get_userdata($post->user_id);?>
			<?php if ( get_user_meta($post->user_id ,'user_telephone',true )) : ?>
				<p><i class="dashicons dashicons-phone"></i> <a href="tel:<?php echo str_replace(" ", "", get_user_meta($post->user_id ,'user_telephone',true )) ; ?>"><?php echo get_user_meta($post->user_id ,'user_telephone',true ); ?></a></p>
			<?php endif; ?>
			<?php if ( get_user_meta($post->user_id ,'user_mobile',true ) ) : ?>
				<p><i class="dashicons dashicons-smartphone"></i> <a href="tel:<?php echo str_replace(" ", "", get_user_meta($post->user_id ,'user_mobile',true )) ; ?>"><?php echo get_user_meta($post->user_id ,'user_mobile',true ); ?></a></p>
			<?php endif; ?>
				<p><a href="mailto:<?php echo $user_info->user_email; ?>"><?php echo _x('Email' ,'noun' , 'govintranet'); echo " " . $user_info->user_email; ?></a></p>
			</div>
			<br class="clearfix">
			<?php
		}
		
		//for rating stories
 		if (function_exists('wp_gdsr_render_article')){
	 		wp_gdsr_render_article(44, true, 'soft', 16);
		}
		
			?>
		<?php 
		else: 
		if ($post_type=='Blog'){
			the_excerpt();
		} else {
		
	?>
			<?php the_content( __( 'Continue reading &rarr;', 'govintranet' ) ); ?>
			<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'govintranet' ), 'after' => '' ) ); ?>
	<?php }
	endif; ?>
	

	</div>


	
	<?php comments_template( '', true ); ?>

<?php endwhile; // End the loop. Whew. ?>
<hr>
<?php if (  $wp_query->max_num_pages > 1  ) : ?>
	<?php if (function_exists('wp_pagenavi')) : ?>
		<?php wp_pagenavi(); ?>
	<?php else : ?>
		<?php next_posts_link('&larr; Older items', $wp_query->max_num_pages); ?>
		<?php previous_posts_link('Newer items &rarr;', $wp_query->max_num_pages);
			 endif; 
		 endif; ?>