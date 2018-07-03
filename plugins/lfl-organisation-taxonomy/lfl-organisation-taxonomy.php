<?php
/*
Plugin Name: Organisation Type Taxonomy for Learning from Local
Description: Creates a organisational type taxonomy for the Learning from Local website
*/
/* Start Adding Functions Below this Line */

//hook into the init action and call create_organisational_type_nonhierarchical_taxonomy when it fires

add_action( 'init', 'create_organisational_type_nonhierarchical_taxonomy', 0 );

function create_organisational_type_nonhierarchical_taxonomy() {

// Labels part for the GUI

  $labels = array(
    'name' => _x( 'Organisation type', 'taxonomy general name' ),
    'singular_name' => _x( 'Organisation type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Organisation types' ),
    'popular_items' => null,
    'all_items' => __( 'All Organisation types' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Organisation type' ),
    'update_item' => __( 'Update Organisation type' ),
    'add_new_item' => __( 'Add New Organisation type' ),
    'new_item_name' => __( 'New Organisation Name type' ),
    'separate_items_with_commas' => __( 'Separate organisation types with commas' ),
    'add_or_remove_items' => __( 'Add or remove organisation types' ),
    'choose_from_most_used' => __( 'Choose from the most used organisation types' ),
    'menu_name' => __( 'Organisation type' ),
  );

// Now register the non-hierarchical taxonomy like tag

  register_taxonomy('organisationtype','post',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'organisation-type' ),
  ));
}


/* Stop Adding Functions Below this Line */
?>
