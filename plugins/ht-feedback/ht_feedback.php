<?php
/*
Plugin Name: HT Feedback
Plugin URI: https://help.govintra.net
Description: Widget to display feedback form
Author: Luke Oatham
Version: 1.1
Author URI: https://www.agentodigital.com
*/

class htFeedback extends WP_Widget {

	function __construct() {
		
		parent::__construct(
			'htFeedback',
			__( 'HT Feedback' , 'govintranet'),
			array( 'description' => __( 'Display feedback form. Requires Gravity Forms plugin' , 'govintranet') )
		);      
    }

    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $formid = ($instance['formid']);
		echo $before_widget; 
		if ( !$title ) {
			$title=__("Is something wrong with this page?",'govintranet');
		}
		$path = plugin_dir_url( __FILE__ );
        wp_enqueue_script( 'ht_feedback', $path.'js/ht_feedback.js' );
		?>

		<div id="accordion">
		      <h3>
		        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseHTFeedback">
					<?php echo $title ; ?> <small><span class="glyphicon glyphicon-menu-down"></span></small>
		        </a>
		      </h3>
		    </div>
		    <div id="collapseHTFeedback" class="xpanel-collapse collapse out">
		      <div class="xpanel-body">
				<?php gravity_form($formid, false, true, false, '', true); ?>
			</div>
		</div>

		<?php
		echo $after_widget; 
    }

    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['formid'] = strip_tags($new_instance['formid']);

       return $instance;
    }

    function form($instance) {
        $title = esc_attr($instance['title']);
        $formid = esc_attr($instance['formid']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','govintranet'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
          <label for="<?php echo $this->get_field_id('formid'); ?>"><?php _e('Gravity Form ID:','govintranet'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('formid'); ?>" name="<?php echo $this->get_field_name('formid'); ?>" type="text" value="<?php echo $formid; ?>" />
        </p>

        <?php 
    }

}

add_action('widgets_init', create_function('', 'return register_widget("htFeedback");'));

?>