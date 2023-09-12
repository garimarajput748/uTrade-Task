<?php
/*
Plugin Name: AJAX Post Search
Description: A custom WordPress plugin for searching posts using AJAX.
Version: 1.0
Author: Garima Rajput
*/

// Enqueue JavaScript
function enqueue_ajax_post_search_script()
{
    if (!is_admin()) {

        wp_enqueue_script('search-posts', plugin_dir_url(__FILE__) . 'search-posts.js', array('jquery'), '1.0', true);

        // Pass the AJAX URL to the script
        wp_localize_script('search-posts', 'ajax_post_search_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_post_search_script');

// Include the HTML form template
function include_search_form_template()
{
    if (!is_admin()) {
        include(plugin_dir_path(__FILE__) . 'search-form.php');
    }
}
add_action('init', 'include_search_form_template');

// Function to fetch posts
function search_posts()
{
    $search_term = sanitize_text_field($_POST['search_term']);
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        's' => $search_term
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            // Output the post content
?>
            <div class="search-result">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div class="excerpt"><?php the_excerpt(); ?></div>
            </div>
<?php
        endwhile;
        wp_reset_postdata();
    else :
        echo 'No posts found.';
    endif;

    die();
}

add_action('wp_ajax_search_posts', 'search_posts');
add_action('wp_ajax_nopriv_search_posts', 'search_posts'); // For non-logged-in users
