<?php
/*
 * Template Name: Custom Projects
 * Description: A custom template to display projects.
 */

get_header();

// Custom Query to retrieve all posts from the 'project' post type
$custom_query = new WP_Query(array(
    'post_type'      => 'project',  // Replace 'project' with your custom post type slug
    'posts_per_page' => -1,  // -1 will display all posts
    'order'          => 'DESC',  // You can change this to 'ASC' if you want ascending order
));

// The Loop
if ($custom_query->have_posts()) :
    while ($custom_query->have_posts()) : $custom_query->the_post();
        // Display the post content here
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h2 class="entry-title"><?php the_title(); ?></h2>
            </header>
            <div class="entry-content">
                <?php the_content(); ?>
                <!-- Display additional fields as needed -->
                <p>Social Media Input: <?php echo esc_html(get_post_meta(get_the_ID(), '_socialmedia_input', true)); ?></p>
                <p>Button Text: <?php echo esc_html(get_post_meta(get_the_ID(), '_button_text', true)); ?></p>
                <p>Button URL: <?php echo esc_url(get_post_meta(get_the_ID(), '_button_url', true)); ?></p>
                <p>Additional Text: <?php echo esc_html(get_post_meta(get_the_ID(), '_additional_text', true)); ?></p>
            </div>
        </article>
        <?php
    endwhile;
    wp_reset_postdata();  // Reset the post data to the main loop
else :
    echo 'No posts found';
endif;

get_footer();
