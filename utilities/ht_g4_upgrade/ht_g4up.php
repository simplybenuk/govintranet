<?php
/*
Plugin Name: GovIntranet 4 upgrade
Plugin URI: https://help.govintra.net
Description: Upgrades GovIntranet from version 3 (Pods) to version 4 (ACF)
Author: Luke Oatham
Version: 0.2
Author URI: https://www.agentodigital.com
*/

add_action('admin_menu', 'ht_g4up_menu');

	add_action('init', 'cptui_register_my_taxes_news_type');
	function cptui_register_my_taxes_news_type() {
	register_taxonomy( 'news-type',array (
	  0 => 'news',
	),
	array( 'hierarchical' => true,
		'label' => 'News types',
		'show_ui' => true,
		'query_var' => true,
		'show_admin_column' => true,
		'labels' => array (
	  'search_items' => 'News type',
	  'popular_items' => '',
	  'all_items' => '',
	  'parent_item' => '',
	  'parent_item_colon' => '',
	  'edit_item' => '',
	  'update_item' => '',
	  'add_new_item' => '',
	  'new_item_name' => '',
	  'separate_items_with_commas' => '',
	  'add_or_remove_items' => '',
	  'choose_from_most_used' => '',
	)
	) );
	}
  ob_start();

function ht_g4up_menu() {
  add_submenu_page('tools.php','GovIntranet 4 upgrade', 'GovIntranet 4 upgrade', 'manage_options', 'g4up', 'ht_g4up_options');
}

function ht_g4up_options() {

  if (!current_user_can('manage_options'))  {
	wp_die( __('You do not have sufficient permissions to access this page.') );
  }

  ob_start();

	echo "<div class='wrap'>";
//	screen_icon();
	echo "<h2>" . __( ' GovIntranet 4 upgrade' ) . "</h2>";

  if ($_REQUEST['action'] == "processimport") {

		echo "<br><strong>Upgrading tasks</strong> ";

		global $wpdb;
/*
		$query = $wpdb->get_results("select ID, post_title from $wpdb->posts where post_status = 'publish' and post_type = 'task' and post_title like '[%%]%%';");
		if ($query):
			foreach ((array)$query as $q){
				  $my_post = array(
				      'ID'           => intval($q->ID),
				      'post_title' => trim(preg_replace('/\[.*\]/i','',$q->post_title))
				  );
				  wp_update_post( $my_post ); echo ".";		ob_flush();
			}
		endif;
		echo "<br>Upgraded ".count($query)." task titles";
		ob_flush();
		unset($query);
*/
		$query = $wpdb->query("UPDATE $wpdb->posts, $wpdb->postmeta SET post_parent = $wpdb->postmeta.meta_value WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id and $wpdb->postmeta.meta_key='parent_guide' and $wpdb->posts.post_type = 'task';");
		echo "<br>Upgraded ".$query." guide chapters";

		$query = $wpdb->get_results("select post_id, meta_value from $wpdb->postmeta where meta_key='_pods_children_chapters';");
		if ($query):
			foreach ((array)$query as $q){
				$cs = get_post_meta($q->post_id,'children_chapters',false); //print_r($cs);
				$ordercount = 0;
				foreach ($cs as $key => $oldchapter){
					$o = $oldchapter['ID'];
					$ordercount = $ordercount + 10;
					$query2 = $wpdb->query("UPDATE $wpdb->posts SET menu_order = ".$ordercount." WHERE $wpdb->posts.ID = ".$o.";");
				}
			}
		endif;
		ob_flush();
		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->postmeta set meta_key = 'related' WHERE meta_key = 'related_tasks';");
		echo "<br>Upgraded ".$query." related task records";
		ob_flush();
		unset($query);
		$query = $wpdb->get_results("select distinct post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'document_attachments' and $wpdb->postmeta.meta_value <> '';");
		if ($query):
			foreach ((array)$query as $q){
				$attachcount = 0;
				$cpost = get_post($q->post_id);
				$cposttype = $cpost->post_type;
				$att = new pod ($cposttype,$q->post_id);
				$docs = $att->get_field('document_attachments');
				if ($docs):
					$attachtotal = count($docs);
					foreach ((array)$docs as $d){ //print_r($d); echo "<br>";
						add_post_meta($q->post_id,'document_attachments_'.$attachcount.'_document_attachment',$d['ID']);
						add_post_meta($q->post_id,'_document_attachments_'.$attachcount.'_document_attachment','field_53bd6e229b9b3');
						delete_post_meta($q->post_id,'document_attachments',$d['ID']);
						$attachcount++;
					}
					add_post_meta($q->post_id,'document_attachments',$attachtotal);
					add_post_meta($q->post_id,'_document_attachments','field_536ec90dc8419');
				endif;
			}
		endif;
		echo "<br>Upgraded ".count($query)." document records";
		ob_flush();
		unset($query);
		$query = $wpdb->get_results("select post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'external_link' and $wpdb->postmeta.meta_value <> '';");
		if ($query):
			foreach ((array)$query as $q){
				set_post_format($q->post_id, 'link' );
			}
		endif;
		echo "<br>Upgraded ".count($query)." external links";
		ob_flush();

		echo "<br><br><strong>Upgrading news</strong> ";
		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->postmeta set meta_key = 'related' WHERE meta_key = 'related_stories';");
		echo "<br>Upgraded ".$query." related news records";
		ob_flush();
		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->postmeta set meta_key = 'news_expiry_action' WHERE meta_key = 'expiry_action';");
		echo "<br>Upgraded ".$query." news expiry actions";
		ob_flush();
		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->postmeta set meta_key = 'news_expiry_date' WHERE meta_key = 'expiry_date';");
		echo "<br>Upgraded ".$query." news expiry dates";
		ob_flush();
		unset($query);
		$query = $wpdb->query("update $wpdb->postmeta set meta_value = DATE_FORMAT(meta_value, '%Y%m%d') where  meta_key = 'news_expiry_date';");
		echo "<br>Formatted ".$query." news expiry dates";
		ob_flush();
		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->postmeta set meta_key = 'news_expiry_time' WHERE meta_key = 'expiry_time';");
		echo "<br>Upgraded ".$query." news expiry times";
		ob_flush();
		unset($query);
		$query = $wpdb->get_results("select post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'news_listing_type' and $wpdb->postmeta.meta_value=1;");
		if ($query):
			foreach ((array)$query as $q){
				set_post_format($q->post_id, 'status' );
			}
		endif;
		echo "<br>Upgraded ".count($query)." need to know news records";
		ob_flush();
		unset($query);




		$newsquery = get_posts("post_type=news&posts_per_page=-1");
		if ($newsquery):
			foreach ($newsquery as $n){
				$newsterms = get_the_terms($n->ID, 'category');
				if (count($newsterms) > 0):
					foreach ($newsterms as $nt){
						if ($nt->slug == 'uncategorized' ) continue;
						$term_title = $nt->slug;
						$term_desc = $nt->description;
						unset($new);
						if ($term_title && strtolower($term_title) != 'uncategorized'):
							$termslug = "news-".$term_title;
							$new = term_exists( $termslug, 'news-type');
						endif;
						if (!is_array($new)):
							$new = wp_insert_term( $nt->name, 'news-type', array('slug'=>$termslug,'description'=>$description) );
							$newid = $new['term_id'];
						else:
							$newid = $new['term_id'];
						endif;
						wp_set_object_terms($n->ID, $termslug, 'news-type', true);
						wp_remove_object_terms( $n->ID, $term_title, 'category' );
						sleep(0.1);
					}
				endif;
			}
		endif;

		echo "<br>Upgraded ".count($newsquery)." news story categories";

		echo "<br><br><strong>Upgrading events</strong> ";

		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->postmeta set meta_key = 'event_gravityforms_id' WHERE meta_key = 'event_booking_form_id';");
		echo "<br>Upgraded ".$query." booking form entries";
		ob_flush();
		unset($query);
		$query = $wpdb->get_results("select post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'event_start_date';");
		if ($query):
			foreach ((array)$query as $q){
				$eventdate = get_post_meta($q->post_id,'event_start_date',true);
				add_post_meta($q->post_id, 'event_start_time',date('H:i',strtotime($eventdate)));
				delete_post_meta($q->post_id, 'event_start_date');
				add_post_meta($q->post_id, 'event_start_date',date('Ymd',strtotime($eventdate)));
			}
		endif;
		echo "<br>Upgraded ".count($query)." event start dates and times";
		ob_flush();
		unset($query);
		$query = $wpdb->get_results("select post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'event_end_date';");
		if ($query):
			foreach ((array)$query as $q){
				$eventdate = get_post_meta($q->post_id,'event_end_date',true);
				delete_post_meta($q->post_id, 'event_end_time');
				add_post_meta($q->post_id, 'event_end_time',date('H:i',strtotime($eventdate)));
				delete_post_meta($q->post_id, 'event_end_date');
				add_post_meta($q->post_id, 'event_end_date',date('Ymd',strtotime($eventdate)));
			}
		endif;
		echo "<br>Upgraded ".count($query)." event end dates and times";
		ob_flush();


		echo "<br><br><strong>Upgrading casestudies</strong> ";

		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->posts, $wpdb->postmeta SET post_parent = $wpdb->postmeta.meta_value WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id and $wpdb->postmeta.meta_key='parent_casestudy' and $wpdb->posts.post_type = 'casestudy';");
		echo "<br>Upgraded ".$query." parent casestudies";
		ob_flush();
		unset($query);
		$query = $wpdb->get_results("select post_id, meta_value from $wpdb->postmeta where meta_key='_pods_children_pages';");
		if ($query):
			foreach ((array)$query as $q){
				$cs = get_post_meta($q->post_id,'children_pages',false); //print_r($cs);
				$ordercount = 0;
				foreach ($cs as $key => $oldchapter){
					$o = $oldchapter['ID'];
					$ordercount = $ordercount + 10;
					$query2 = $wpdb->query("UPDATE $wpdb->posts SET menu_order = ".$ordercount." WHERE $wpdb->posts.ID = ".$o.";");
				}
			}
		endif;
		$query = $wpdb->query("UPDATE $wpdb->postmeta set meta_key = 'related' WHERE meta_key = 'related_casestudies';");
		echo "<br>Upgraded ".$query." related casestudy records";
		ob_flush();
		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->postmeta set meta_key = 'casestudy_policy_link' WHERE meta_key = 'policy_link';");
		echo "<br>Upgraded ".$query." policy links";
		ob_flush();
		unset($query);
		$query = $wpdb->get_results("select post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'casestudy_start_date';");
		if ($query):
			foreach ((array)$query as $q){
				$eventdate = get_post_meta($q->post_id,'casestudy_start_date',true);
				add_post_meta($q->post_id, 'casestudy_start_time',date('H:i',strtotime($eventdate)));
				delete_post_meta($q->post_id, 'casestudy_start_date');
				add_post_meta($q->post_id, 'casestudy_start_date',date('Ymd',strtotime($eventdate)));
			}
		endif;
		echo "<br>Upgraded ".count($query)." casestudy start dates and times";
		ob_flush();
		unset($query);
		$query = $wpdb->get_results("select post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'casestudy_end_date';");
		if ($query):
			foreach ((array)$query as $q){
				$eventdate = get_post_meta($q->post_id,'casestudy_end_date',true);
				delete_post_meta($q->post_id, 'casestudy_end_time');
				add_post_meta($q->post_id, 'casestudy_end_time',date('H:i',strtotime($eventdate)));
				delete_post_meta($q->post_id, 'casestudy_end_date');
				add_post_meta($q->post_id, 'casestudy_end_date',date('Ymd',strtotime($eventdate)));
			}
		endif;
		echo "<br>Upgraded ".count($query)." casestudy end dates and times";
		ob_flush();
		$allcasestudy = get_posts(array('post_type'=>'casestudies','posts_per_page'=>-1));
		if ($allcasestudy):
			foreach ($allcasestudy as $a){
					$my_post = array(
			      'ID'           => $a->ID,
			      'post_content' => $a->post_content.get_post_meta($a->ID,'team_members',true)
				  );
				  wp_update_post( $my_post );
			}
		endif;
		echo "<br>Moved ".count($allcasestudy)." casestudy team entries to main content area";
		ob_flush();
		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->posts set post_type = 'casestudy' WHERE post_type = 'casestudies';");
		echo "<br>Upgraded ".$query." casestudy post types";
		ob_flush();

		echo "<br><br><strong>Upgrading vacancies</strong> ";

		unset($query);
		$query = $wpdb->get_results("select post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'closing_date';");
		if ($query):
			foreach ((array)$query as $q){
				$eventdate = get_post_meta($q->post_id,'closing_date',true);
				delete_post_meta($q->post_id, 'closing_date');
				add_post_meta($q->post_id, 'vacancy_closing_date',date('Ymd',strtotime($eventdate)));
			}
		endif;
		echo "<br>Upgraded ".count($query)." vacancy closing dates";
		$allcasestudy = get_posts(array('post_type'=>'vacancy','posts_per_page'=>-1));
		if ($allcasestudy):
			$relatedcount=0;
			foreach ($allcasestudy as $a){
					$extras = '';
					if (get_post_meta($a->ID,'background',true)) $extras.="<h2>Background</h2>".get_post_meta($a->ID,'background',true);
					if (get_post_meta($a->ID,'job_specification',true)) $extras.="<h2>Job specification</h2>".get_post_meta($a->ID,'job_specification',true);
					if (get_post_meta($a->ID,'requirements',true)) $extras.="<h2>Requirements</h2>".get_post_meta($a->ID,'requirements',true);
					if (get_post_meta($a->ID,'how_to_apply',true)) $extras.="<h2>How to apply</h2>".get_post_meta($a->ID,'how_to_apply',true);
					$my_post = array(
			      'ID'           => $a->ID,
			      'post_content' => $a->post_content.$extras,
				  );
				  wp_update_post( $my_post );
				if ($relatedcasestudy = get_post_meta($a->ID,'casestudy',true)):
					add_post_meta($a->ID, 'related', $relatedcasestudy);
					$relatedcount++;
				endif;
			}
		endif;
		echo "<br>Moved ".count($allcasestudy)." vacancy details to main content area";
		echo "<br>Upgraded ".$relatedcount." related casestudies";
		ob_flush();
		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->posts set post_type = 'vacancy' WHERE post_type = 'vacancies';");
		echo "<br>Upgraded ".$query." vacancy post types";

		echo "<br><br><strong>Upgrading taxonomies</strong> ";
		$terms = get_terms('category');
		if ($terms) {
	  		foreach ($terms as $taxonomy ) {
	  		    $themeid = $taxonomy->term_id;
	  			$thistheme = new pod('category', $themeid);
	  			$background=$thistheme->get_field('cat_background_colour');
	  			$foreground=$thistheme->get_field('cat_foreground_colour');
	  			$description=$thistheme->get_field('category_page_description');
				add_option( 'category_'.$themeid.'_cat_foreground_colour', $foreground, '', 'yes' );
				add_option( 'category_'.$themeid.'_cat_background_colour', $background, '', 'yes' );
				add_option( 'category_'.$themeid.'_cat_long_description', $description, '', 'yes' );
			}
		}
		echo "<br>Upgraded ".count($terms)." categories";
		ob_flush();

		$terms = get_terms('grade');
		if ($terms) {
	  		foreach ($terms as $taxonomy ) {
	  		    $themeid = $taxonomy->term_id;
	  			$thistheme = new pod('grade', $themeid);
	  			$code=$thistheme->get_field('grade_code');
				add_option( 'grade_'.$themeid.'_grade_code', $code, '', 'yes' );
			}
		}

		echo "<br>Upgraded ".count($terms)." grades";
		ob_flush();




// get all terms for parent teams
// create team post and save array with old term id and corresponding team post id

		$team_map = array();

		$terms = get_terms('team',array('parent'=>0,'hide_empty'=>false));
		if ($terms) :
	  		foreach ($terms as $taxonomy ) {
	  		    $themeid = $taxonomy->term_id;
	  			$thistheme = new pod('team', $themeid);
	  			$teamhead=$thistheme->get_field('team_head');
				// Create post object
				$my_post = array(
				  'post_title'    => $taxonomy->name,
				  'post_content'  => $taxonomy->description,
				  'post_status'   => 'publish',
				  'post_type'	=> 'team',
				  'post_author'   => 1,
				);

				// Insert the post into the database
				$newteamid = wp_insert_post( $my_post );
				if ($newteamid && $teamhead):
					add_post_meta($newteamid,'team_head',$teamhead);
				endif;
				$team_map[$themeid] = $newteamid;
			}
		endif;


// get all terms and skip  parents
// lookup old parent id and get new parent id to save with new team post
// create team post and save array with old term id an corresponding team post id

		$terms = get_terms('team',array('hide_empty'=>false));
		if ($terms) :
	  		foreach ($terms as $taxonomy ) {
		  		if ($taxonomy->parent==0) continue;
	  		    $themeid = $taxonomy->term_id;
	  		    $themeparentid = $taxonomy->parent;
	  			$thistheme = new pod('team', $themeid);
	  			$teamhead=$thistheme->get_field('team_head');
				// Create post object
				$my_post = array(
				  'post_title'    => $taxonomy->name,
				  'post_content'  => $taxonomy->description,
				  'post_status'   => 'publish',
				  'post_type'	=> 'team',
				  'post_author'   => 1,
				  'post_parent'		=> $team_map[$themeparentid]
				);

				// Insert the post into the database
				$newteamid = wp_insert_post( $my_post );
				if ($newteamid && $teamhead):
					add_post_meta($newteamid,'team_head',$teamhead);
				endif;
				$team_map[$themeid] = $newteamid;
			}
		endif;


// get all terms
// for each term, get list of users in the team
// update user with user meta using new team post id
		if ($terms) {
	  		foreach ($terms as $taxonomy ) {
	  		    $themeid = $taxonomy->term_id;

	  		    $q = "select distinct user_id from $wpdb->usermeta join wp_terms on $wpdb->terms.term_id = $wpdb->usermeta.meta_value where user_id in (select user_id from $wpdb->usermeta as a where a.meta_key = 'user_team' and a.meta_value = ".$themeid." ) ;
		 ";
		 			$user_query = $wpdb->get_results($q);

		 			foreach ($user_query as $u){//print_r($u);
			 			$uq = "update $wpdb->usermeta set meta_value = ".$team_map[$themeid]." where user_id = ".$u->user_id." and meta_key='user_team' and meta_value = ".$themeid;
						$wpdb->query($uq);
		 			}

			}
		}
		echo "<br>Upgraded ".count($terms)." teams";
		ob_flush();

//NEED TO CONVERT EXISTING TAXONOMY RELATIONSHIPS TO POST META XXXxxx

		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->term_taxonomy set taxonomy = 'document-type' WHERE taxonomy = 'document_type';");
		echo "<br>Upgraded ".$query." document types";
		ob_flush();

		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->term_taxonomy set taxonomy = 'event-type' WHERE taxonomy = 'event_type';");
		echo "<br>Upgraded ".$query." event types";
		ob_flush();

		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->term_taxonomy set taxonomy = 'a-to-z' WHERE taxonomy = 'atoz';");
		echo "<br>Upgraded ".$query." A to Z items";
		ob_flush();

		echo "<br><br><strong>Upgrading settings</strong> ";

		unset($query);
		$query = $wpdb->query("UPDATE $wpdb->options SET option_name = REPLACE(option_name, 'general_intranet', 'options');");
		echo "<br>Upgraded ".$query." general intranet settings";
		ob_flush();

		$homepage = get_page_by_title( 'Home', OBJECT, 'page' );
		if (!$homepage) $homepage = get_page_by_title( 'Homepage', OBJECT, 'page' );
		if ($homepage->ID):
			add_post_meta($homepage->ID,'top_news_stories',get_option('homepage_control_top_news_story'));
			add_post_meta($homepage->ID,'top_pages',get_option('homepage_control_top_pages'));
			add_post_meta($homepage->ID,'emergency_message_style',get_option('homepage_control_emergency_message_style'));
			add_post_meta($homepage->ID,'emergency_message',get_option('homepage_control_emergency_message'));
			add_post_meta($homepage->ID,'campaign_message',get_option('homepage_control_campaign_message'));
			echo "<br>Upgraded homepage configuration";
		else:
			echo "<br>Couldn't locate the homepage. You'll need to manually update your homepage settings.";
		endif;

		ob_flush();

		$headerbackgroundcolour = get_option('options_header_background');
		set_theme_mod( 'header_background', $headerbackgroundcolour );
		set_theme_mod( 'header_textcolor', '#ffffff' );
		echo "<br>Upgraded colour configuration";

		$pageitem = get_page_by_title( 'how-do-i', OBJECT, 'page' );
		if (!$pageitem) $pageitem = get_page_by_title( 'How do I', OBJECT, 'page' );
		if (!$pageitem) $pageitem = get_page_by_title( 'How to', OBJECT, 'page' );
		if ($pageitem->ID):
			add_option('options_module_tasks', 1);
			add_option('options_module_tasks_page', $pageitem->ID);
			echo "<br>Setup the Tasks module";
		else:
			echo "<br>Skipping Tasks module";
		endif;

		$pageitem = get_page_by_title( 'newspage', OBJECT, 'page' );
		if (!$pageitem) $pageitem = get_page_by_title( 'News', OBJECT, 'page' );
		if ($pageitem->ID):
			add_option('options_module_news', 1);
			add_option('options_module_news_page', $pageitem->ID);
			echo "<br>Setup the News module";
		else:
			echo "<br>Skipping News module";
		endif;

		$pageitem = get_page_by_title( 'blog', OBJECT, 'page' );
		if (!$pageitem) $pageitem = get_page_by_title( 'Blogs', OBJECT, 'page' );
		if ($pageitem->ID):
			add_option('options_module_blog', 1);
			add_option('options_module_blog_page', $pageitem->ID);
			echo "<br>Setup the Blog module";
		else:
			echo "<br>Skipping Blog module";
		endif;

		$pageitem = get_page_by_title( 'events', OBJECT, 'page' );
		if (!$pageitem) $pageitem = get_page_by_title( 'Events', OBJECT, 'page' );
		if ($pageitem->ID):
			add_option('options_module_events', 1);
			add_option('options_module_event_page', $pageitem->ID);
			echo "<br>Setup the Events module";
		else:
			echo "<br>Skipping Events module";
		endif;

		$pageitem = get_page_by_title( 'jargon-buster', OBJECT, 'page' );
		if (!$pageitem) $pageitem = get_page_by_title( 'Jargon buster', OBJECT, 'page' );
		if ($pageitem->ID):
			add_option('options_module_jargon_buster', 1);
			echo "<br>Setup the Jargon Buster module";
		else:
			echo "<br>Skipping Jargon Buster module";
		endif;

		$pageitem = get_page_by_title( 'casestudies', OBJECT, 'page' );
		if (!$pageitem) $pageitem = get_page_by_title( 'casestudies', OBJECT, 'page' );
		if ($pageitem->ID):
			add_option('options_module_casestudies', 1);
			add_option('options_module_casestudy_page', $pageitem->ID);
			echo "<br>Setup the casestudies module";
		else:
			echo "<br>Skipping casestudies module";
		endif;

		$pageitem = get_page_by_title( 'vacancies', OBJECT, 'page' );
		if (!$pageitem) $pageitem = get_page_by_title( 'Vacancies', OBJECT, 'page' );
		if ($pageitem->ID):
			add_option('options_module_vacancies', 1);
			add_option('options_module_vacancies_page', $pageitem->ID);
			echo "<br>Setup the Vacancies module";
		else:
			echo "<br>Skipping Vacancies module";
		endif;

		$pageitem = get_posts('post_type=team&posts_per_page=-1');
		if ( count($pageitem) > 1) :
			add_option('options_module_teams', 1);
			echo "<br>Setup the Teams module";
		endif;

		if ( get_option('options_forum_support') ):
			$pageitem = get_page_by_title( 'staff-directory', OBJECT, 'page' );
			if (!$pageitem) $pageitem = get_page_by_title( 'Staff directory', OBJECT, 'page' );
			if ($pageitem->ID):
				add_option('options_module_staff_directory', 1);
				add_option('options_module_staff_directory_page', $pageitem->ID);
				echo "<br>Setup the Staff directory module";
			else:
				echo "<br>Skipping Staff directory module";
			endif;
		endif;


		unset($query);
		$query = $wpdb->get_results("select post_id, count(meta_value) from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'related' group by post_id;");
		if ($query):
			foreach ((array)$query as $q){
				$query2 = $wpdb->get_results("select meta_value from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'related' and post_id =".$q->post_id);
				$relarray = array();
				foreach ((array)$query2 as $q2){
					$relarray[] = $q2->meta_value;
				}
				$query3 = $wpdb->get_results("delete from $wpdb->postmeta where $wpdb->postmeta.meta_key = 'related' and post_id =".$q->post_id);
				add_post_meta($q->post_id, 'related',$relarray);
			}
		endif;

		ob_flush();
		echo "<br><br><strong>Cleaning database</strong> ";

  		global $wpdb;

		$query = $wpdb->query("DELETE from $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='_parent_guide';");
		$query = $wpdb->query("DELETE from $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='parent_guide';");
		$query = $wpdb->query("DELETE from $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='children_chapters';");
		$query = $wpdb->query("DELETE from $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='page_type';");
		$query = $wpdb->query("DELETE from $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='video_still';");
		$query = $wpdb->query("DELETE from $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='news_listing_type';");
		$query = $wpdb->query("DELETE from $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='parent_casestudy';");
		$query = $wpdb->query("DELETE from $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='children_pages';");
		$query = $wpdb->query("DELETE from $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='casestudy';");
		$query = $wpdb->query("DELETE from $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='related' AND $wpdb->postmeta.meta_value='';");

	echo "<h1>Finished</h1>";
	echo "<h3>Almost there! Before you activate the new theme:</h3>";
	echo "<p>Print this page as a record.</p>";
	echo "<p>Go to Pods Admin, Settings, Cleanup and reset tab. Choose to Deactivate and Delete Pods data.</p>";
	echo "<p>When done, activate the Advanced Custom Fields plugin.</p>";
	echo "<p>Deactivate this upgrade widget.</p>";
	echo "<p>Install and activate new HT plugins.</p>";
	echo "<p>Activate version 4 of the GovIntranet theme.</p>";
	echo "<p>Check Appearance, Menus, Manage locations and set primary navigation</p>";
	echo "<p>Check Appearance, Widgets, and shift down where necessary.</p>";
	echo "<p>Add Google account details to Most active widget were necessary.</p>";
	echo "<p>Check Appearance, Customise, and set header colours.</p>";
	echo "<p>Check Options, Modules, and setup your content modules.</p>";
	echo "<p>Regenerate your image thumbnails.</p>";
	echo "<p>Flush permalinks.</p>";
 	ob_end_flush();


  } else {

	if (function_exists('pods')):
		echo "
		<p></p>
		 <form method='post'>
		 	<p>This action will upgrade your database to GovIntranet 4.</p>
		 	<p>Backup your complete database before using.</p>
		 	<p>Keep a note of your Google Analytics settings.</p>
		 	<p>Keep a note of your header colour settings.</p>
			<p><input type='submit' value='Upgrade now' class='button-primary' /></p>
			<input type='hidden' name='page' value='g4up' />
			<input type='hidden' name='action' value='processimport' />
		  </form><br />
		";
	else:
		echo "
		<p></p>
		<p>You must activate the Advanced Custom Fields Pro plugin.</p>
		";
	endif;
  }

	echo "</div>";

 	ob_end_flush();
}

?>
