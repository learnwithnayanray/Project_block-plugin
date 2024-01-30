<?php
/*
Plugin Name: My Project
Plugin URI: https://nayanray.com/
Description: This is a custom WordPress plugin that enhances your website with amazing features. It provides tools for managing projects, displaying project blocks, and streamlining your project-related activities.
Version: 1.0
textdomain: projectposttype
Author: Nayan Ray
Author URI: https://nayanray.com/author
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/



/*Registration post type for Project*/

function custom_register_project_post_type() {
    $labels = array(
        'name'               => _x( 'Projects', 'post type general name', 'projectposttype' ),
        'singular_name'      => _x( 'Project', 'post type singular name', 'projectposttype' ),
        'menu_name'          => _x( 'Projects', 'admin menu', 'projectposttype' ),
        'name_admin_bar'     => _x( 'Project', 'add new on admin bar', 'projectposttype' ),
        'add_new'            => _x( 'Add New', 'project', 'projectposttype' ),
        'add_new_item'       => __( 'Add New Project', 'projectposttype' ),
        'new_item'           => __( 'New Project', 'projectposttype' ),
        'edit_item'          => __( 'Edit Project', 'projectposttype' ),
        'view_item'          => __( 'View Project', 'projectposttype' ),
        'all_items'          => __( 'All Projects', 'projectposttype' ),
        'search_items'       => __( 'Search Projects', 'projectposttype' ),
        'parent_item_colon'  => __( 'Parent Projects:', 'projectposttype' ),
        'not_found'          => __( 'No projects found.', 'projectposttype' ),
        'not_found_in_trash' => __( 'No projects found in Trash.', 'projectposttype' ),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description of your custom post type', 'projectposttype' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'project' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    );

    register_post_type( 'project', $args );

    // Add meta boxes for additional fields
    add_action( 'save_post', 'save_custom_meta' );
    add_action( 'add_meta_boxes', 'custom_meta_boxes' );
}

function custom_meta_boxes() {
    add_meta_box( 'socialmedia_input', 'Social Media Input', 'socialmedia_input_callback', 'project', 'normal', 'high' );
    add_meta_box( 'button_text', 'Button Text', 'button_text_callback', 'project', 'normal', 'high' );
    add_meta_box( 'button_url', 'Button URL', 'button_url_callback', 'project', 'normal', 'high' );
    add_meta_box( 'additional_text', 'Additional Text', 'additional_text_callback', 'project', 'normal', 'high' );

}

function socialmedia_input_callback( $post ) {
    $socialmedia_input_value = get_post_meta( $post->ID, '_socialmedia_input', true );
    ?>
    <label for="socialmedia_input">Social Media Input:</label>
    <input type="text" name="socialmedia_input" id="socialmedia_input" value="<?php echo esc_attr( $socialmedia_input_value ); ?>">
    <?php
}

function button_text_callback( $post ) {
    $button_text_value = get_post_meta( $post->ID, '_button_text', true );
    ?>
    <label for="button_text">Button Text:</label>
    <input type="text" name="button_text" id="button_text" value="<?php echo esc_attr( $button_text_value ); ?>">
    <?php
}

function button_url_callback( $post ) {
    $button_url_value = get_post_meta( $post->ID, '_button_url', true );
    ?>
    <label for="button_url">Button URL:</label>
    <input type="text" name="button_url" id="button_url" value="<?php echo esc_attr( $button_url_value ); ?>">
    <?php
}

function additional_text_callback( $post ) {
    $additional_text_value = get_post_meta( $post->ID, '_additional_text', true );
    ?>
    <label for="additional_text">Additional Text:</label>
    <textarea name="additional_text" id="additional_text"><?php echo esc_textarea( $additional_text_value ); ?></textarea>
    <?php
}

function save_custom_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if ( isset( $_POST['socialmedia_input'] ) ) {
        update_post_meta( $post_id, '_socialmedia_input', sanitize_text_field( $_POST['socialmedia_input'] ) );
    }

    if ( isset( $_POST['button_text'] ) ) {
        update_post_meta( $post_id, '_button_text', sanitize_text_field( $_POST['button_text'] ) );
    }

    if ( isset( $_POST['button_url'] ) ) {
        update_post_meta( $post_id, '_button_url', esc_url( $_POST['button_url'] ) );
    }
}

add_action( 'init', 'custom_register_project_post_type' );





// Enqueue the block script
function custom_projects_block_enqueue_scripts() {
    wp_enqueue_script(
        'custom-projects-block-script',
        plugins_url('block.js', __FILE__),
        array('wp-blocks', 'wp-editor', 'wp-components', 'wp-element')
    );
}

add_action('enqueue_block_editor_assets', 'custom_projects_block_enqueue_scripts');

// Enqueue styles for the block
function enqueue_custom_projects_block_styles() {
    wp_enqueue_style('custom-projects-block', plugin_dir_url(__FILE__) . 'custom-projects-block.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_projects_block_styles');

function enqueue_custom_projects_styles() {
    if (is_page_template('template-custom-projects.php')) {
        // Enqueue your custom script (optional)
        wp_enqueue_script('custom-projects-script', get_template_directory_uri() . '/path/to/your/custom-projects.js', array('jquery'), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_projects_styles');


// Activation hook
register_activation_hook(__FILE__, 'custom_projects_block_activate');

function custom_projects_block_activate() {
    // Define your initial demo data
    $demo_data = array(
        array(
            'title' => 'Demo Project 1',
            'description' => 'This is a demo project description for Project 1.',
        ),
        array(
            'title' => 'Demo Project 2',
            'description' => 'This is a demo project description for Project 2.',
        ),
        // Add more demo projects as needed
    );

    // Save the demo data to the options table
    update_option('custom_projects_block_demo_data', $demo_data);
}

// Add menu item to the admin dashboard
function custom_projects_block_menu() {
    add_menu_page(
        'Custom Projects Block Dashboard',
        'Projects Dashboard',
        'manage_options',
        'custom-projects-dashboard',
        'custom_projects_block_dashboard_page',
        'dashicons-analytics', // Icon for the menu item, you can change it
        20 // Menu position
    );
}

add_action('admin_menu', 'custom_projects_block_menu');

// Dashboard page callback
function custom_projects_block_dashboard_page() {
    // Check if the form is submitted
    if (isset($_POST['project_title']) && isset($_POST['project_description'])) {
        // Handle form submission and save project data
        $new_project = array(
            'title' => sanitize_text_field($_POST['project_title']),
            'description' => sanitize_text_field($_POST['project_description']),
        );

        // Get existing projects
        $existing_projects = get_option('custom_projects_block_demo_data', array());

        // Add the new project
        $existing_projects[] = $new_project;

        // Update the demo data
        update_option('custom_projects_block_demo_data', $existing_projects);
    }
    ?>
    <div class="wrap">
        <h2>Custom Projects Block Dashboard</h2>
        <p>Welcome to the Custom Projects Block Dashboard. You can display any information or settings here.</p>
        
        

        <!-- Project Management Section -->
        <h3>Manage Projects</h3>
        <form method="post">
            <label for="project_title">Project Title:</label>
            <input type="text" id="project_title" name="project_title" required>
            
            <label for="project_description">Project Description:</label>
            <textarea id="project_description" name="project_description" required></textarea>
            
            <input type="submit" class="button button-primary" value="Save Project">
        </form>
    </div>
    <?php
}
?>
