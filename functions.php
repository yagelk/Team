<?php
/*
 *
 * Text Domain: wmgroup
 *
 */

/**
 * Load the parent style.css file
 *
 */
function oceanwp_child_enqueue_parent_style() {
	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
	$theme   = wp_get_theme( 'OceanWP' );
	$version = $theme->get( 'Version' );
	// Load the stylesheet
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'oceanwp-style' ), $version );
	
}
add_action( 'wp_enqueue_scripts', 'oceanwp_child_enqueue_parent_style' );


/* Custom functions for wmgroup Test - by yagelk */

define ('PATH_TEMP', get_stylesheet_directory());
define ('PLUGIN_NAME_ASSETS', 'assets-yk');

define ('BOOTSTRAP_RTL_CSS', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.min.css');
define( 'BOOTSTRAP_CSS', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');

define ('BOOTSTRAP_JS', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');

function style_and_scripts() {  

    // BOOTSTRAP CSS
    wp_register_style('yk_bootstrap', BOOTSTRAP_CSS);
    wp_enqueue_style('yk_bootstrap');
	
	// BOOTSTRAP CSS RTL
    wp_register_style('yk_bootstrap_rtl', BOOTSTRAP_RTL_CSS);
    wp_enqueue_style('yk_bootstrap');
    // BOOTSTRAP JS
    wp_register_script('yk_bootstrap', BOOTSTRAP_JS);
    wp_enqueue_script('yk_bootstrap');
}

add_action('wp_head' , 'style_and_scripts'); 

/**
* Create Custom Post Team
* Built and design by Yagel Kahalani
*/

function custom_post_type_team() {

	// Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Member', 'Post Type General Name' ),
			'singular_name'       => _x( 'Member', 'Post Type Singular Name' ),
			'menu_name'           => __( 'Team' ),
			'parent_item_colon'   => __( 'Parent Member' ),
			'all_items'           => __( 'All Members' ),
			'view_item'           => __( 'View Member' ),
			'add_new_item'        => __( 'Add New Member' ),
			'add_new'             => __( 'Add New' ),
			'edit_item'           => __( 'Edit Member' ),
			'update_item'         => __( 'Update Member' ),
			'search_items'        => __( 'Search Member' ),
			'not_found'           => __( 'Not Found' ),
			'not_found_in_trash'  => __( 'Not found in Trash' ),
		);
		
	// Set other options for Custom Post Type
		
		$args = array(
			'label'               => __( 'team' ),
			'description'         => __( 'Create your team' ),
			'labels'              => $labels,
			// Features this CPT supports in Post Editor
			'supports'            => array( 'title', 'editor', 'thumbnail', ),
			// You can associate this CPT with a taxonomy or custom taxonomy. 
			'taxonomies'          => array( 'genres' ),
			/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/	
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 50,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite'             => array( 'slug' => 'team' ),
			
	//        'register_meta_box_cb' => 'add_business_card_metaboxes',
		);
		
		// Registering your Custom Post Type
		register_post_type( 'team', $args );
	}
	
	/* Hook into the 'init' action so that the function
	* Containing our post type registration is not 
	* unnecessarily executed. 
	*/
	
	add_action( 'init', 'custom_post_type_team', 0 );
	
	
	/**
	* Create metabox for custom post item
	*/
	add_action( 'add_meta_boxes', 'add_box_item');
	
	$prefix = '_yk_';
	
	$metabox_details_member = array(
		'id' => 'yk_details',
		'title' => 'Details Of Asset',
		'page' => 'team',
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' => 'Phone',
				'desc' => 'Phone of a member',
				'id' => $prefix . 'phone',
				'type' => 'text',
				'std' => 'Enter a phone'
			),
			array(
				'name' => 'Role',
				'desc' => 'Role of a member',
				'id' => $prefix . 'role',
				'type' => 'text',
				'std' => 'Enter a role'
			)
		)
	);
	
	function add_box_item() {
		global $metabox_details_member;
	
		add_meta_box($metabox_details_member['id'], $metabox_details_member['title'], 'mytheme_show_box_item', $metabox_details_member['page'], $metabox_details_member['context'], $metabox_details_member['priority']); 
	}
	
	// Callback function to show fields in meta box
	function mytheme_show_box_item() {
		global $metabox_details_member, $post;
	
		// Use nonce for verification
		wp_nonce_field( 'meta_box_nonce_asset', wp_create_nonce(basename(__FILE__)) );
	//    echo '<input type="hidden" name="mytheme_meta_box_nonce_asset" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
		echo '<table class="form-table">';
	
		foreach ($metabox_details_member['fields'] as $field) {
			// get current post meta data
			$meta = get_post_meta($post->ID, $field['id'], true);
	
			echo '<tr>',
					'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
					'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:50%" />', '<br />', $field['desc'];
			echo     '</td><td>',
				'</td></tr>';
		}
		echo '</table>';
	}
	
	// Save data from meta box
	function save_data_asset($post_id) {
		global $metabox_details_member;
		
		// verify nonce
		if (isset( $_POST['meta_box_nonce_asset'] ) && !wp_verify_nonce($_POST['meta_box_nonce_asset'], basename(__FILE__))) {
			return;
		}
	
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
	
		// check permissions
		if ('page' == $_POST['assets']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
			
		foreach ($metabox_details_member['fields'] as $field) {
			$old = get_post_meta($post_id, $field['id'], true);
			$new = $_POST[$field['id']];
	
			if ($new && $new != $old) {
				update_post_meta($post_id, sanitize_text_field($field['id']), $new);
			} elseif ('' == $new && $old) {
				delete_post_meta($post_id, sanitize_text_field($field['id']), $old);
			}
		}
	} 
	add_action('save_post', 'save_data_asset', 1, 2); // save the custom fields
	// End create custom post Asset



	/**
* Function for show files with page single-CPT.php
*/
add_filter('single_template', 'yk_custom_single_template_member');

function yk_custom_single_template_member( $single ) {
	global $post;
	
	if ( $post->post_type == 'team' ) {
		 $template_path = PATH_TEMP . '/templates/single-member.php';
		  
		if ( file_exists( $template_path ) ) {
			return $template_path;
		}
	}
return $single;
}

/**
* Function for show files with page archive-CPT.php
*/
add_filter('archive_template', 'yk_custom_archive_template_team');

    function yk_custom_archive_template_team( $archive ) {
        global $post;

        if ( $post->post_type == 'team'  ) {
             $template_path = PATH_TEMP . '/templates/archive-member.php';
              
            if ( file_exists( $template_path ) ) {
                return $template_path;
            }
        }
    return $archive;
}


