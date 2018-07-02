<?php
/*
Plugin Name: Geography Taxonomy for Learning from Local
Description: Creates a geographical footprint taxonomy for the Learning from Local website
*/
/* Start Adding Functions Below this Line */

//hook into the init action and call create_geographical_nonhierarchical_taxonomy when it fires

add_action( 'init', 'create_geographical_nonhierarchical_taxonomy', 0 );

function create_geographical_nonhierarchical_taxonomy() {

// Labels part for the GUI

  $labels = array(
    'name' => _x( 'Geographical footprint', 'taxonomy general name' ),
    'singular_name' => _x( 'Geographical footprint', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Geographical footprints' ),
    'popular_items' => __( 'Popular Geographical footprints' ),
    'all_items' => __( 'All Geographical footprint' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Geographical footprint' ),
    'update_item' => __( 'Update Geographical footprint' ),
    'add_new_item' => __( 'Add New Geographical footprint' ),
    'new_item_name' => __( 'New Geographical footprint type' ),
    'separate_items_with_commas' => __( 'Separate Geographical footprints with commas' ),
    'add_or_remove_items' => __( 'Add or remove Geographical footprints' ),
    'choose_from_most_used' => __( 'Choose from the most used geographical footprints' ),
    'menu_name' => __( 'Geographical footprint' ),
  );

// Now register the non-hierarchical taxonomy like tag

  register_taxonomy('geographicalfootprint','post',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'geographical-footprint' ),
  ));
}


/* Stop Adding Functions Below this Line */
?>
