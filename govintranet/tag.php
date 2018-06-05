<?php
get_header();
?>
<?php if ( have_posts() )  : the_post(); ?>

			<div class="col-lg-7 col-md-8 col-sm-12 white">
				<div class="row">
					<div class='breadcrumbs'>
						<?php if(function_exists('bcn_display') && !is_front_page()) {
							bcn_display();
							}?>
					</div>
				</div>
				<h1><?php
				$posttype = '';
				if ( isset( $_GET['type'] ) ) $posttype = $_GET['type'];
				$thistagid = get_queried_object()->term_id;
				$thistagslug = get_queried_object()->slug;
				$thistag = get_queried_object()->name;
				if ($posttype == 'task'){
					printf( __( 'Tasks/guides tagged: %s', 'govintranet' ), '' . $thistag. '' );
				}
				elseif ($posttype == 'casestudy'){
					printf( __( 'casestudies tagged: %s', 'govintranet' ), '' . $thistag . '' );
				}
				elseif ($posttype == 'vacancy'){
					printf( __( 'Job vacancies tagged: %s', 'govintranet' ), '' . $thistag . '' );
				}
				elseif ($posttype == 'news'){
					printf( __( 'News tagged: %s', 'govintranet' ), '' . $thistag . '' );
				}
				elseif ($posttype == 'news-update'){
					printf( __( 'News updates tagged: %s', 'govintranet' ), '' . $thistag . '' );
				}
				elseif ($posttype == 'blog'){
					printf( __( 'Blog posts tagged: %s', 'govintranet' ), '' . $thistag . '' );
				}
				elseif ($posttype == 'event'){
					printf( __( 'Events tagged: %s', 'govintranet' ), '' . $thistag . '' );
				}
				elseif ($posttype == 'page'){
					printf( __( 'Pages tagged: %s', 'govintranet' ), '' . $thistag . '' );
				}
				else
				{
					printf( __( 'Everything tagged: %s', 'govintranet' ), '' . $thistag . '' );
				}
				?></h1>

				<?php
				/* Run the loop for the tag archive to output the posts
				 * If you want to overload this in a child theme then include a file
				 * called loop-tag.php and that will be used instead.
				 */
				//$paged=$_GET['paged'];
				$posts_per_page = get_option('posts_per_page',10);
				$pt=$posttype;
				if (!$pt){
					$pt='any';
				}
				$tq =  	array(
 						'tax_query'=> array (array(
 						'terms'=>$thistagid,
 						'taxonomy'=>'post_tag',
 						'field'=>'term_id',
 						)),
 						'post_type'=>$pt,
 						'paged'=>$paged,
 						'posts_per_page'=> $posts_per_page,
 						'orderby'=>'name',
 						'order'=>'ASC'
 						);
 				if ($pt=='any'){
	 				$tagquery=
					"select object_id from $wpdb->term_relationships , $wpdb->term_taxonomy, $wpdb->terms, $wpdb->posts
	where $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id AND
	$wpdb->term_taxonomy.term_id = $wpdb->terms.term_id and
	$wpdb->terms.term_id = ".$thistagid." and
	$wpdb->term_relationships.object_id = $wpdb->posts.id and
	$wpdb->posts.post_status = 'publish'
						";

 				} else {
	 				$tagquery=
					"select post_title,object_id from $wpdb->term_relationships , $wpdb->term_taxonomy, $wpdb->terms, $wpdb->posts
	where $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id AND
	$wpdb->term_taxonomy.term_id = $wpdb->terms.term_id and
	$wpdb->terms.term_id = ".$thistagid." and
	$wpdb->term_relationships.object_id = $wpdb->posts.id and
	$wpdb->posts.post_status = 'publish' and
	$wpdb->posts.post_type='" . $pt . "'";
				}
				$testtag = $wpdb->get_results($tagquery);
				if (count($testtag) > 0){
					$postsfound=true;
					$carray = array();
					foreach ($testtag as $tt){
						$carray[]=$tt->object_id;
					}
				} else { $postsfound=false;
					echo "<h2>" . __('Nothing on the intranet with this tag' , 'govintranet') . ".</h2>";
				}
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				if ($pt =='task'): // tasks sorted alphabetically
					$tagged = new WP_Query(array(
		 			'post_type'=>array("task"),
		 			'post__in'=>$carray,
		 			'paged'=>$paged,
		 			'posts_per_page'=>$posts_per_page,
		 			'orderby'=>'name',
		 			'order'=>'ASC',
		 			));
				elseif ($pt =='event'): // tasks sorted by start date
					$tagged = new WP_Query(array(
		 			'post_type'=>array("event"),
		 			'post__in'=>$carray,
		 			'paged'=>$paged,
		 			'posts_per_page'=>$posts_per_page,
		 			'orderby'=>'meta_value',
		 			'order'=>'ASC',
		 			'meta_key' => 'event_start_date',
		 			));
				else: // everything else sorted by date
					$tagged = new WP_Query(array(
		 			'post_type'=>array("task","vacancy","casestudy","news","news-update","event","blog","page"),
		 			'post__in'=>$carray,
		 			'paged'=>$paged,
		 			'posts_per_page'=>$posts_per_page,
		 			'orderby'=>'date',
		 			'order'=>'DESC'
		 			));
	 			endif;
				$counter = 0;

	 			while ($tagged->have_posts() && $postsfound ) {
					$tagged->the_post();
					$post_type = ucwords($post->post_type);
					$image_url = get_the_post_thumbnail($id, 'thumbnail', array('class' => 'alignright'));
					$ext_icon = '';
					$title_context='';
					$context='';
					$titlecontext='';
					$posttax='';
					echo "<div class='media'>" ;
					if ($post_type=='Post_tag') $icon = "tag";
					$post_cat = array();
					if ($post_type=='Task'){
						$post_cat = get_the_category();
						$posttax='Category';
						$taskpod = $post->post_parent;
						if ( !$taskpod ){
							$context = __("task","govintranet");
							$icon = "question-sign";
						} else {
							$context = __("guide","govintranet");
							$icon = "book";
							$taskparent=get_post($taskpod);
							if ($taskparent){
								$title_context=" (".get_the_title($taskparent->ID).")";
							}
						}
					}
					if ($post_type=='casestudy'){
						$context = __("casestudy","govintranet");
						$posttax='Category';
						$icon = "road";
						$projparent=$post->post_parent;
						if ($projparent){
							$projparent = get_post($projparent);
							$title_context=" (".get_the_title($projparent->ID).")";
						}
					}
					if ($post_type=='News'){
							$post_cat = get_the_terms($post->ID, 'news-type');
							$context = __("news","govintranet");
							$posttax='Type';
							$icon = "star-empty";
					}
					if ($post_type=='News-update'){
							$post_cat = get_the_terms($post->ID, 'news-update-type');
							$context = __("news update","govintranet");
							$posttax='Type';
							$icon = "star-empty";
					}
					if ($post_type=='Vacancy'){
							$post_cat = get_the_category();
							$context = __("job vacancy","govintranet");
							$icon = "random";
					}
					if ($post_type=='Blog'){
							$post_cat = get_the_terms($post->ID, 'blog-category');
							$context = __("blog","govintranet");
							$posttax='Category';
							$icon = "comment";
					}
					if ($post_type=='Event'){
							$post_cat = get_the_terms($post->ID, 'event-type');
							$context = __("event","govintranet");
							$posttax='Type';
							$icon = "calendar";
					}
					if ($post_type=='Jargon-buster'){
							$context = __("jargon buster","govintranet");
							$icon = "th-list";
					}
					if ($post_type=='User'){
							$context = __("staff","govintranet");
							$icon = "user";
					}
					if ($post_type=='Page'){
							$context = __("Page","govintranet");
							$icon = "page";
					}
					if ($post_type=='Attachment'):
						$context= __('download',"govintranet");
						$icon = "download";
						?>
						<h3>
						<a href="<?php echo wp_get_attachment_url( $post->id ); ?>" title="<?php the_title_attribute( 'echo=1' ); ?>" rel="bookmark"><?php the_title();  ?></a></h3>
						<?php
					elseif ($post_type=='User'):
						?>
						<h3>
						<a href="<?php echo $userurl; ?>" title="<?php the_title_attribute( 'echo=1' ); ?>" rel="bookmark"><?php the_title();  ?></a></h3>

						<?php
					else: ?>
						<h3>
						<a href="<?php echo get_the_permalink(get_the_id()); ?>" title="<?php the_title_attribute( 'echo=1' ); ?>" rel="bookmark"><?php echo get_the_title($post->ID); echo "</a> <small>".$title_context."</small>"; ?><?php echo $ext_icon; ?>
						</h3>
						<?php
					endif;

					$terms = '';
					$termsfound = array();
					if ( $post_cat ) foreach($post_cat as $cat){
						if ($cat->term_id != 1 ){
							$termsfound[]= "<span>".esc_html($cat->name)."</span>";
						}
					}
					if ( count($termsfound) > 0 ){
						if ( $posttax ) $posttax.=": ";
						$terms = "<span class='listglyph'>(" . $posttax . implode(" | ", $termsfound) . ")</span>&nbsp;";
					}
					echo "<a href='";
					the_permalink();
					echo "'><div class='hidden-xs'>".$image_url."</div></a>" ;
					echo "<div class='media-body'>";

					if (($post_type=="Task")){
						the_excerpt();
						echo "<div class='cat-context-list'><p>";
						echo '<span class="listglyph">'.ucfirst($context).'</span>&nbsp;';
						echo $terms;
						echo "</p></div>";
					} elseif (($post_type=="News" || $post_type=="Blog" || $post_type=="News-update" )){
						the_excerpt();
						echo "<div class='cat-context-list'><p>";
						echo '<span class="listglyph">'.ucfirst($context).'</span>&nbsp;';
					   $thisdate= $post->post_date;
					   $thisdate=date(get_option('date_format'),strtotime($thisdate));
					   echo "<span class='listglyph'>".$thisdate."</span>&nbsp;";
						echo $terms;
					   echo "</p></div>";
					} elseif ($post_type=="Event" ){
						the_excerpt();
						echo "<div class='cat-context-list'><p>";
						$thisdate= get_post_meta($post->ID, 'event_start_date', true);
						$thisdate=date(get_option('date_format'),strtotime($thisdate));
						echo '<span class="listglyph">'.ucfirst($context).'&nbsp;'.$thisdate.'</span>&nbsp;';
						echo $terms;
						echo "</p></div>";
					} elseif ($post_type=="Vacancy" ){
						the_excerpt();
						echo "<div class='cat-context-list'><p>";
						$thisdate = get_post_meta($post->ID, 'vacancy_closing_date', true);
						$thisdate = date(get_option('date_format'),strtotime($thisdate));
						$thistime = get_post_meta($post->ID, 'vacancy_closing_time', true);
						$thistime = date(get_option('time_format'),strtotime($thistime));
						echo '<span class="listglyph">'.ucfirst($context).'&nbsp;Closing: '.$thisdate.' '.$thistime.'</span>&nbsp;';
						echo $terms;
						echo "</p></div>";
					} elseif ($post_type!="Page" ) {
						the_excerpt();
						echo "<div class='cat-context-list'><p>";
						echo '<span class="listglyph">'.ucfirst($context).'</span>&nbsp;';
						$thisdate= $post->post_modified;
						$thisdate=date(get_option('date_format'),strtotime($thisdate));
						echo "<span class='listglyph'>" . __('Updated','govintranet') . " ".$thisdate."</span> ";
						echo $terms;
						echo "</p></div>";
					} else {
						the_excerpt();
					}
					?>
					</div>
					<?php
					if ($post_type=='Post_tag') {
						printf( __("All intranet pages tagged with \"%s\"" , 'govintranet' ), get_the_title() );
					} else if ($post_type=='Category') {
						printf( __("All intranet pages categorised as \"%s\"" , 'govintranet' ), get_the_title() );

					}

					//for rating stories
			 		if (function_exists('wp_gdsr_render_article')){
				 		wp_gdsr_render_article(44, true, 'soft', 16);
					}

					?>
					</div>
					<?php
					echo "<hr>";

				}
				if (  $tagged->max_num_pages > 1 && $postsfound ) : ?>
				<?php if (function_exists('wp_pagenavi')) : ?>
					<?php wp_pagenavi(array('query' => $tagged)); ?>
					<?php else : ?>
					<?php next_posts_link(__('&larr; Older items','govintranet'), $tagged->max_num_pages); ?>
					<?php previous_posts_link(__('Newer items &rarr;','govintranet'), $tagged->max_num_pages); ?>
				<?php endif; ?>
				<?php endif;
				wp_reset_query();
				?>

				</div>

				<div class="col-lg-4 col-lg-offset-1 col-md-4 col-sm-12" id="sidebar">
					<div class="widget-box list">
						<h3 class="widget-title">
						<?php if (!$posttype){
							echo __("Filter","govintranet");
						} else {
							echo __("More","govintranet");
						}
						?>
						</h3>
						<ul>
						<?php
						$t=get_tag($thistagid);
						$t=$thistagslug;
						if ($posttype == 'task'){
							$landingpage = get_option('options_module_tasks_page');
							if ( !$landingpage ):
								$landingpage_link_text = 'tasks and guides';
								$landingpage = site_url().'/how-do-i/';
							else:
								$landingpage_link_text = get_the_title( $landingpage[0] );
								$landingpage = get_permalink( $landingpage[0] );
							endif;
							echo "<li><a href='".$landingpage."'>" . sprintf ( __('Go to %s' , 'govintranet') , $landingpage_link_text ) ."</a></li>";
						}
						if ($posttype == 'casestudy'){
							$landingpage = get_option('options_module_casestudies_page');
							if ( !$landingpage ):
								$landingpage_link_text = 'casestudies';
								$landingpage = site_url().'/casestudies/';
							else:
								$landingpage_link_text = get_the_title( $landingpage[0] );
								$landingpage = get_permalink( $landingpage[0] );
							endif;
							echo "<li><a href='".$landingpage."'>" . sprintf ( __('Go to %s' , 'govintranet') , $landingpage_link_text ) ."</a></li>";
						}
						if ($posttype == 'event'){
							$landingpage = get_option('options_module_events_page');
							if ( !$landingpage ):
								$landingpage_link_text = 'events';
								$landingpage = site_url().'/events/';
							else:
								$landingpage_link_text = get_the_title( $landingpage[0] );
								$landingpage = get_permalink( $landingpage[0] );
							endif;
							echo "<li><a href='".$landingpage."'>" . sprintf ( __('Go to %s' , 'govintranet') , $landingpage_link_text ) ."</a></li>";
						}
						if ($posttype == 'blog'){
							$landingpage = get_option('options_module_blog_page');
							if ( !$landingpage ):
								$landingpage_link_text = 'blogposts';
								$landingpage = site_url().'/blogs/';
							else:
								$landingpage_link_text = get_the_title( $landingpage[0] );
								$landingpage = get_permalink( $landingpage[0] );
							endif;
							echo "<li><a href='".$landingpage."'>" . sprintf ( __('Go to %s' , 'govintranet') , $landingpage_link_text ) ."</a></li>";
						}
						if ($posttype == 'news'){
							$landingpage = get_option('options_module_news_page');
							if ( !$landingpage ):
								$landingpage_link_text = 'news';
								$landingpage = site_url().'/newspage/';
							else:
								$landingpage_link_text = get_the_title( $landingpage[0] );
								$landingpage = get_permalink( $landingpage[0] );
							endif;
							echo "<li><a href='".$landingpage."'>" . sprintf ( __('Go to %s' , 'govintranet') , $landingpage_link_text ) ."</a></li>";
						}
						if ($posttype == 'news-update'){
							$landingpage = get_option('options_module_news_update_page');
							if ( !$landingpage ):
								$landingpage_link_text = 'news updates';
								$landingpage = site_url().'/news-updates/';
							else:
								$landingpage_link_text = get_the_title( $landingpage[0] );
								$landingpage = get_permalink( $landingpage[0] );
							endif;
							echo "<li><a href='".$landingpage."'>" . sprintf ( __('Go to %s' , 'govintranet') , $landingpage_link_text ) ."</a></li>";
						}
						if ($posttype == 'vacancy'){
							$landingpage = get_option('options_module_vacancies_page');
							if ( !$landingpage ):
								$landingpage_link_text = 'vacancies';
								$landingpage = site_url().'/vacancies/';
							else:
								$landingpage_link_text = get_the_title( $landingpage[0] );
								$landingpage = get_permalink( $landingpage[0] );
							endif;
							echo "<li><a href='".$landingpage."'>";
							printf( __('Go to %s' , 'govintranet' ) , $landingpage_link_text );
							echo "</a></li>";
						}
						if ($posttype != ''){
						echo "<li><a href='".get_tag_link( $thistagid )."'>";
							printf( __( '<strong>Everything</strong> tagged: %s', 'govintranet' ), '' . $thistag . '' );
						echo "</a></li>";
						}
						if ($posttype != 'task'){
							$tagquery=
								"select count(distinct $wpdb->posts.id) as numtags from $wpdb->posts
								 join $wpdb->term_relationships on $wpdb->term_relationships.object_id = $wpdb->posts.id
								 join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
								 join $wpdb->terms on $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
								where $wpdb->terms.slug='".$t."' AND
								$wpdb->posts.post_type='task' AND
								$wpdb->posts.post_status = 'publish'
							";
							$testtag = $wpdb->get_results($tagquery);
							if ($testtag[0]->numtags > 0){
								echo "<li><a href='".get_tag_link( $thistagid )."?type=task'>";
								printf( __( '<strong>Tasks and guides</strong> tagged: %s', 'govintranet' ), '' . $thistag . '' );
								echo "</a></li>";
							}
						}
						if ($posttype != 'casestudy'){
							$tagquery=
							"select count(distinct $wpdb->posts.id) as numtags from $wpdb->posts
							 join $wpdb->term_relationships on $wpdb->term_relationships.object_id = $wpdb->posts.id
							 join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
							 join $wpdb->terms on $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
							where $wpdb->terms.slug='".$t."' AND
							$wpdb->posts.post_type='casestudy' AND
							$wpdb->posts.post_status = 'publish'
								";
							$testtag = $wpdb->get_results($tagquery);
							if ($testtag[0]->numtags > 0){
								echo "<li><a href='".get_tag_link( $thistagid )."?type=casestudy'>";
								printf( __( '<strong>casestudies</strong> tagged: %s', 'govintranet' ), '' . $thistag . '' );
								echo "</a></li>";
							}
						}
						if ($posttype != 'vacancy'){
							$tagquery=
							"select count(distinct $wpdb->posts.id) as numtags from $wpdb->posts
							 join $wpdb->term_relationships on $wpdb->term_relationships.object_id = $wpdb->posts.id
							 join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
							 join $wpdb->terms on $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
							where $wpdb->terms.slug='".$t."' AND
							$wpdb->posts.post_type='vacancy' AND
							$wpdb->posts.post_status = 'publish'					";
							$testtag = $wpdb->get_results($tagquery);
							if ($testtag[0]->numtags > 0){
								echo "<li><a href='".get_tag_link( $thistagid )."?type=vacancy'>";
								printf( __( '<strong>Job vacancies</strong> tagged: %s', 'govintranet' ), '' . $thistag . '' );
								echo "</a></li>";
							}
						}
						if ($posttype != 'news'){
							$tagquery=
							"select count(distinct $wpdb->posts.id) as numtags from $wpdb->posts
							 join $wpdb->term_relationships on $wpdb->term_relationships.object_id = $wpdb->posts.id
							 join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
							 join $wpdb->terms on $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
							where $wpdb->terms.slug='".$t."' AND
							$wpdb->posts.post_type='news' AND
							$wpdb->posts.post_status = 'publish'
								";
							$testtag = $wpdb->get_results($tagquery);
							if ($testtag[0]->numtags > 0){
								echo "<li><a href='".get_tag_link( $thistagid )."?type=news'>";
								printf( __( '<strong>News</strong> tagged: %s', 'govintranet' ), '' . $thistag . '' );
								echo "</a></li>";
							}
						}
						if ($posttype != 'news-update'){
							$tagquery=
							"select count(distinct $wpdb->posts.id) as numtags from $wpdb->posts
							 join $wpdb->term_relationships on $wpdb->term_relationships.object_id = $wpdb->posts.id
							 join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
							 join $wpdb->terms on $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
							where $wpdb->terms.slug='".$t."' AND
							$wpdb->posts.post_type='news-update' AND
							$wpdb->posts.post_status = 'publish'
								";
							$testtag = $wpdb->get_results($tagquery);
							if ($testtag[0]->numtags > 0){
								echo "<li><a href='".get_tag_link( $thistagid )."?type=news-update'>";
								printf( __( '<strong>News updates</strong> tagged: %s', 'govintranet' ), '' . $thistag . '' );
								echo "</a></li>";
							}
						}
						if ($posttype != 'blog'){
							$tagquery=
							"select count(distinct $wpdb->posts.id) as numtags from $wpdb->posts
							 join $wpdb->term_relationships on $wpdb->term_relationships.object_id = $wpdb->posts.id
							 join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
							 join $wpdb->terms on $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
							where $wpdb->terms.slug='".$t."' AND
							$wpdb->posts.post_type='blog' AND
							$wpdb->posts.post_status = 'publish'
								";
							$testtag = $wpdb->get_results($tagquery);
							if ($testtag[0]->numtags > 0){
								echo "<li><a href='".get_tag_link( $thistagid )."?type=blog'>";
								printf( __( '<strong>Blog posts</strong> tagged: %s', 'govintranet' ), '' . $thistag . '' );
								echo "</a></li>";
							}
						}
						if ($posttype != 'page'){
							$tagquery=
							"select count(distinct $wpdb->posts.id) as numtags from $wpdb->posts
							 join $wpdb->term_relationships on $wpdb->term_relationships.object_id = $wpdb->posts.id
							 join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
							 join $wpdb->terms on $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
							where $wpdb->terms.slug='".$t."' AND
							$wpdb->posts.post_type='page' AND
							$wpdb->posts.post_status = 'publish'
								";
							$testtag = $wpdb->get_results($tagquery);
							if ($testtag[0]->numtags > 0){
								echo "<li><a href='".get_tag_link( $thistagid )."?type=page'>";
								printf( __( '<strong>Pages</strong> tagged: %s', 'govintranet' ), '' . $thistag . '' );
								echo "</a></li>";
							}
						}
						if ($posttype != 'event'){
							$tagquery=
							"select count(distinct $wpdb->posts.id) as numtags from $wpdb->posts
							 join $wpdb->term_relationships on $wpdb->term_relationships.object_id = $wpdb->posts.id
							 join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
							 join $wpdb->terms on $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
							where $wpdb->terms.slug='".$t."' AND
							$wpdb->posts.post_type='event' AND
							$wpdb->posts.post_status = 'publish'
								";
							$testtag = $wpdb->get_results($tagquery);
							if ($testtag[0]->numtags > 0){
								echo "<li><a href='".get_tag_link( $thistagid )."?type=event'>";
								printf( __( '<strong>Events</strong> tagged: %s', 'govintranet' ), '' . $thistag . '' );
								echo "</a></li>";
							}
						}
						?>
					</ul>
				</div>
			</div>
<?php endif; ?>

<?php get_footer(); ?>
