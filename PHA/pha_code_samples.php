<?php
/**
 * PHA Website - WordPress Theme Functions
 * functions.php - Core theme functionality
 */

// Theme setup
function pha_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'pha-theme'),
        'footer'  => __('Footer Menu', 'pha-theme'),
        'utility' => __('Utility Menu', 'pha-theme'),
    ));
    
    // Add image sizes
    add_image_size('pha-featured', 1200, 600, true);
    add_image_size('pha-thumbnail', 400, 300, true);
    add_image_size('pha-news', 600, 400, true);
}
add_action('after_setup_theme', 'pha_theme_setup');

// Enqueue scripts and styles
function pha_enqueue_scripts() {
    // Styles
    wp_enqueue_style('pha-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_style('pha-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    
    // Scripts
    wp_enqueue_script('pha-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '1.0.0', true);
    wp_enqueue_script('pha-accessibility', get_template_directory_uri() . '/assets/js/accessibility.js', array(), '1.0.0', true);
    
    // Conditional scripts
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'pha_enqueue_scripts');

// Register widget areas
function pha_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'pha-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'pha-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer 1', 'pha-theme'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'pha-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer 2', 'pha-theme'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here to appear in your footer.', 'pha-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer 3', 'pha-theme'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here to appear in your footer.', 'pha-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'pha_widgets_init');

// Custom Post Types
function pha_register_post_types() {
    // Housing Programs
    register_post_type('housing_program', array(
        'labels' => array(
            'name'               => __('Housing Programs', 'pha-theme'),
            'singular_name'      => __('Housing Program', 'pha-theme'),
            'add_new'            => __('Add New Program', 'pha-theme'),
            'add_new_item'       => __('Add New Housing Program', 'pha-theme'),
            'edit_item'          => __('Edit Housing Program', 'pha-theme'),
            'new_item'           => __('New Housing Program', 'pha-theme'),
            'view_item'          => __('View Housing Program', 'pha-theme'),
            'search_items'       => __('Search Housing Programs', 'pha-theme'),
            'not_found'          => __('No housing programs found', 'pha-theme'),
            'not_found_in_trash' => __('No housing programs found in Trash', 'pha-theme'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-admin-home',
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite'      => array('slug' => 'housing-programs'),
    ));
    
    // News & Events
    register_post_type('pha_event', array(
        'labels' => array(
            'name'               => __('Events', 'pha-theme'),
            'singular_name'      => __('Event', 'pha-theme'),
            'add_new'            => __('Add New Event', 'pha-theme'),
            'add_new_item'       => __('Add New Event', 'pha-theme'),
            'edit_item'          => __('Edit Event', 'pha-theme'),
            'new_item'           => __('New Event', 'pha-theme'),
            'view_item'          => __('View Event', 'pha-theme'),
            'search_items'       => __('Search Events', 'pha-theme'),
            'not_found'          => __('No events found', 'pha-theme'),
            'not_found_in_trash' => __('No events found in Trash', 'pha-theme'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-calendar-alt',
        'supports'     => array('title', 'editor', 'thumbnail'),
        'rewrite'      => array('slug' => 'events'),
    ));
    
    // Procurement Opportunities
    register_post_type('procurement', array(
        'labels' => array(
            'name'               => __('Procurement Opportunities', 'pha-theme'),
            'singular_name'      => __('Procurement', 'pha-theme'),
            'add_new'            => __('Add New Opportunity', 'pha-theme'),
            'add_new_item'       => __('Add New Procurement Opportunity', 'pha-theme'),
            'edit_item'          => __('Edit Procurement', 'pha-theme'),
            'new_item'           => __('New Procurement', 'pha-theme'),
            'view_item'          => __('View Procurement', 'pha-theme'),
            'search_items'       => __('Search Procurements', 'pha-theme'),
            'not_found'          => __('No procurements found', 'pha-theme'),
            'not_found_in_trash' => __('No procurements found in Trash', 'pha-theme'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => array('title', 'editor', 'thumbnail'),
        'rewrite'      => array('slug' => 'procurement'),
    ));
}
add_action('init', 'pha_register_post_types');

// Security enhancements
function pha_security_enhancements() {
    // Remove WordPress version info
    remove_action('wp_head', 'wp_generator');
    
    // Disable XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');
    
    // Remove REST API links
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // Disable file editing
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
}
add_action('init', 'pha_security_enhancements');

// Add security headers
function pha_add_security_headers() {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}
add_action('send_headers', 'pha_add_security_headers');

// Performance optimization
function pha_performance_optimizations() {
    // Defer non-critical CSS
    function defer_css($html, $handle) {
        if (in_array($handle, array('pha-main', 'wp-block-library'))) {
            $html = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
        }
        return $html;
    }
    add_filter('style_loader_tag', 'defer_css', 10, 2);
    
    // Remove query strings from static resources
    function remove_query_strings($src) {
        if (strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    add_filter('script_loader_src', 'remove_query_strings', 15, 1);
    add_filter('style_loader_src', 'remove_query_strings', 15, 1);
    
    // Disable emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
}
add_action('init', 'pha_performance_optimizations');

// Accessibility features
function pha_accessibility_features() {
    // Add skip to content link
    function add_skip_link() {
        echo '<a class="skip-link screen-reader-text" href="#main-content">' . __('Skip to content', 'pha-theme') . '</a>';
    }
    add_action('wp_body_open', 'add_skip_link');
    
    // Add ARIA landmarks
    function add_aria_landmarks($content) {
        // Add main landmark
        if (is_main_query() && in_the_loop()) {
            $content = '<main id="main-content" role="main">' . $content . '</main>';
        }
        return $content;
    }
    add_filter('the_content', 'add_aria_landmarks');
}
add_action('init', 'pha_accessibility_features');

// Custom search function
function pha_custom_search($query) {
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', array('post', 'page', 'housing_program', 'pha_event', 'procurement'));
    }
    return $query;
}
add_filter('pre_get_posts', 'pha_custom_search');

// Breadcrumb function
function pha_breadcrumb() {
    $separator = ' &raquo; ';
    $home_title = 'Home';
    
    // Start the breadcrumb with a link to the homepage
    echo '<nav class="breadcrumb" aria-label="Breadcrumb">';
    echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';
    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a itemprop="item" href="' . home_url('/') . '">';
    echo '<span itemprop="name">' . $home_title . '</span></a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</li>';
    
    // Check if it's not the homepage
    if (!is_front_page()) {
        echo $separator;
        
        // Archive pages
        if (is_archive()) {
            $position = 2;
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<span itemprop="name">' . get_the_archive_title() . '</span>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
        }
        
        // Single post or page
        elseif (is_single() || is_page()) {
            $position = 2;
            
            // Get post type
            $post_type = get_post_type();
            
            // If it's a custom post type, show the archive link
            if ($post_type !== 'post' && $post_type !== 'page') {
                $post_type_object = get_post_type_object($post_type);
                $archive_link = get_post_type_archive_link($post_type);
                
                if ($archive_link) {
                    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                    echo '<a itemprop="item" href="' . $archive_link . '">';
                    echo '<span itemprop="name">' . $post_type_object->labels->name . '</span></a>';
                    echo '<meta itemprop="position" content="' . $position . '" />';
                    echo '</li>';
                    echo $separator;
                    $position++;
                }
            }
            
            // Current page/post
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<span itemprop="name">' . get_the_title() . '</span>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
        }
        
        // Search results
        elseif (is_search()) {
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<span itemprop="name">Search results for: ' . get_search_query() . '</span>';
            echo '<meta itemprop="position" content="2" />';
            echo '</li>';
        }
        
        // 404
        elseif (is_404()) {
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<span itemprop="name">404 - Page Not Found</span>';
            echo '<meta itemprop="position" content="2" />';
            echo '</li>';
        }
    }
    
    echo '</ol>';
    echo '</nav>';
}

// Event meta boxes (for custom post type)
function pha_add_event_meta_boxes() {
    add_meta_box(
        'pha_event_details',
        __('Event Details', 'pha-theme'),
        'pha_event_details_callback',
        'pha_event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pha_add_event_meta_boxes');

function pha_event_details_callback($post) {
    wp_nonce_field('pha_save_event_details', 'pha_event_details_nonce');
    
    $event_date = get_post_meta($post->ID, '_pha_event_date', true);
    $event_time = get_post_meta($post->ID, '_pha_event_time', true);
    $event_location = get_post_meta($post->ID, '_pha_event_location', true);
    $event_contact = get_post_meta($post->ID, '_pha_event_contact', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="pha_event_date"><?php _e('Event Date', 'pha-theme'); ?></label></th>
            <td>
                <input type="date" id="pha_event_date" name="pha_event_date" 
                       value="<?php echo esc_attr($event_date); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="pha_event_time"><?php _e('Event Time', 'pha-theme'); ?></label></th>
            <td>
                <input type="time" id="pha_event_time" name="pha_event_time" 
                       value="<?php echo esc_attr($event_time); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="pha_event_location"><?php _e('Event Location', 'pha-theme'); ?></label></th>
            <td>
                <input type="text" id="pha_event_location" name="pha_event_location" 
                       value="<?php echo esc_attr($event_location); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="pha_event_contact"><?php _e('Contact Information', 'pha-theme'); ?></label></th>
            <td>
                <input type="text" id="pha_event_contact" name="pha_event_contact" 
                       value="<?php echo esc_attr($event_contact); ?>" class="regular-text">
            </td>
        </tr>
    </table>
    <?php
}

function pha_save_event_details($post_id) {
    // Check nonce
    if (!isset($_POST['pha_event_details_nonce']) || 
        !wp_verify_nonce($_POST['pha_event_details_nonce'], 'pha_save_event_details')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save event details
    if (isset($_POST['pha_event_date'])) {
        update_post_meta($post_id, '_pha_event_date', sanitize_text_field($_POST['pha_event_date']));
    }
    
    if (isset($_POST['pha_event_time'])) {
        update_post_meta($post_id, '_pha_event_time', sanitize_text_field($_POST['pha_event_time']));
    }
    
    if (isset($_POST['pha_event_location'])) {
        update_post_meta($post_id, '_pha_event_location', sanitize_text_field($_POST['pha_event_location']));
    }
    
    if (isset($_POST['pha_event_contact'])) {
        update_post_meta($post_id, '_pha_event_contact', sanitize_text_field($_POST['pha_event_contact']));
    }
}
add_action('save_post_pha_event', 'pha_save_event_details');

// Custom excerpt length
function pha_custom_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'pha_custom_excerpt_length');

// Custom excerpt more
function pha_excerpt_more($more) {
    return '... <a href="' . get_permalink() . '" class="read-more">' . __('Read More', 'pha-theme') . '</a>';
}
add_filter('excerpt_more', 'pha_excerpt_more');

// Add custom body classes
function pha_body_classes($classes) {
    // Add page slug
    if (is_singular()) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }
    
    // Add post type
    if (is_singular()) {
        $classes[] = 'post-type-' . get_post_type();
    }
    
    return $classes;
}
add_filter('body_class', 'pha_body_classes');

// Custom admin styles
function pha_admin_styles() {
    echo '<style>
        .post-type-housing_program .dashicons-admin-home:before {
            color: #0073aa;
        }
        .post-type-pha_event .dashicons-calendar-alt:before {
            color: #d54e21;
        }
        .post-type-procurement .dashicons-portfolio:before {
            color: #46b450;
        }
    </style>';
}
add_action('admin_head', 'pha_admin_styles');

// Register custom REST API endpoints
function pha_register_rest_routes() {
    // Get latest events
    register_rest_route('pha/v1', '/events', array(
        'methods'  => 'GET',
        'callback' => 'pha_get_events',
        'permission_callback' => '__return_true',
    ));
    
    // Get housing programs
    register_rest_route('pha/v1', '/programs', array(
        'methods'  => 'GET',
        'callback' => 'pha_get_programs',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'pha_register_rest_routes');

function pha_get_events($request) {
    $args = array(
        'post_type'      => 'pha_event',
        'posts_per_page' => 10,
        'meta_key'       => '_pha_event_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => '_pha_event_date',
                'value'   => date('Y-m-d'),
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        ),
    );
    
    $events = get_posts($args);
    $data = array();
    
    foreach ($events as $event) {
        $data[] = array(
            'id'       => $event->ID,
            'title'    => $event->post_title,
            'content'  => $event->post_content,
            'date'     => get_post_meta($event->ID, '_pha_event_date', true),
            'time'     => get_post_meta($event->ID, '_pha_event_time', true),
            'location' => get_post_meta($event->ID, '_pha_event_location', true),
            'contact'  => get_post_meta($event->ID, '_pha_event_contact', true),
            'link'     => get_permalink($event->ID),
        );
    }
    
    return rest_ensure_response($data);
}

function pha_get_programs($request) {
    $args = array(
        'post_type'      => 'housing_program',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );
    
    $programs = get_posts($args);
    $data = array();
    
    foreach ($programs as $program) {
        $data[] = array(
            'id'       => $program->ID,
            'title'    => $program->post_title,
            'excerpt'  => $program->post_excerpt,
            'content'  => $program->post_content,
            'link'     => get_permalink($program->ID),
            'thumbnail' => get_the_post_thumbnail_url($program->ID, 'pha-thumbnail'),
        );
    }
    
    return rest_ensure_response($data);
}

// AJAX search functionality
function pha_ajax_search() {
    check_ajax_referer('pha_search_nonce', 'nonce');
    
    $search_query = sanitize_text_field($_POST['search']);
    
    $args = array(
        's'              => $search_query,
        'post_type'      => array('post', 'page', 'housing_program', 'pha_event', 'procurement'),
        'posts_per_page' => 10,
    );
    
    $search_results = new WP_Query($args);
    $results = array();
    
    if ($search_results->have_posts()) {
        while ($search_results->have_posts()) {
            $search_results->the_post();
            $results[] = array(
                'title'   => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'link'    => get_permalink(),
                'type'    => get_post_type(),
            );
        }
        wp_reset_postdata();
    }
    
    wp_send_json_success($results);
}
add_action('wp_ajax_pha_search', 'pha_ajax_search');
add_action('wp_ajax_nopriv_pha_search', 'pha_ajax_search');

// Contact form submission
function pha_contact_form_submission() {
    check_ajax_referer('pha_contact_nonce', 'nonce');
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Validate
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => 'Please fill in all required fields.'));
    }
    
    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Please enter a valid email address.'));
    }
    
    // Send email
    $to = get_option('admin_email');
    $subject = 'New Contact Form Submission from PHA Website';
    $body = "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n\n";
    $body .= "Message:\n$message";
    
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    if (wp_mail($to, $subject, $body, $headers)) {
        wp_send_json_success(array('message' => 'Thank you for your message. We will get back to you soon.'));
    } else {
        wp_send_json_error(array('message' => 'There was an error sending your message. Please try again.'));
    }
}
add_action('wp_ajax_pha_contact_form', 'pha_contact_form_submission');
add_action('wp_ajax_nopriv_pha_contact_form', 'pha_contact_form_submission');

// Theme customizer
function pha_customize_register($wp_customize) {
    // Add section for contact information
    $wp_customize->add_section('pha_contact_info', array(
        'title'    => __('Contact Information', 'pha-theme'),
        'priority' => 30,
    ));
    
    // Phone number
    $wp_customize->add_setting('pha_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('pha_phone', array(
        'label'   => __('Phone Number', 'pha-theme'),
        'section' => 'pha_contact_info',
        'type'    => 'text',
    ));
    
    // Email address
    $wp_customize->add_setting('pha_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('pha_email', array(
        'label'   => __('Email Address', 'pha-theme'),
        'section' => 'pha_contact_info',
        'type'    => 'email',
    ));
    
    // Address
    $wp_customize->add_setting('pha_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('pha_address', array(
        'label'   => __('Physical Address', 'pha-theme'),
        'section' => 'pha_contact_info',
        'type'    => 'textarea',
    ));
    
    // Social media links
    $wp_customize->add_section('pha_social_media', array(
        'title'    => __('Social Media', 'pha-theme'),
        'priority' => 35,
    ));
    
    // Facebook
    $wp_customize->add_setting('pha_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('pha_facebook', array(
        'label'   => __('Facebook URL', 'pha-theme'),
        'section' => 'pha_social_media',
        'type'    => 'url',
    ));
    
    // Twitter
    $wp_customize->add_setting('pha_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('pha_twitter', array(
        'label'   => __('Twitter URL', 'pha-theme'),
        'section' => 'pha_social_media',
        'type'    => 'url',
    ));
    
    // Instagram
    $wp_customize->add_setting('pha_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('pha_instagram', array(
        'label'   => __('Instagram URL', 'pha-theme'),
        'section' => 'pha_social_media',
        'type'    => 'url',
    ));
}
add_action('customize_register', 'pha_customize_register');

// Database optimization (scheduled weekly)
function pha_optimize_database() {
    global $wpdb;
    
    // Delete expired transients
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%' AND option_value < UNIX_TIMESTAMP()");
    
    // Delete post revisions older than 30 days
    $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type = 'revision' AND post_modified < DATE_SUB(NOW(), INTERVAL 30 DAY)");
    
    // Delete trashed posts older than 30 days
    $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'trash' AND post_modified < DATE_SUB(NOW(), INTERVAL 30 DAY)");
    
    // Optimize tables
    $wpdb->query("OPTIMIZE TABLE $wpdb->posts");
    $wpdb->query("OPTIMIZE TABLE $wpdb->postmeta");
    $wpdb->query("OPTIMIZE TABLE $wpdb->options");
}

// Schedule database optimization
if (!wp_next_scheduled('pha_weekly_optimization')) {
    wp_schedule_event(time(), 'weekly', 'pha_weekly_optimization');
}
add_action('pha_weekly_optimization', 'pha_optimize_database');

// Logging function for debugging
function pha_log($message) {
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }
    }
}

// Custom login page logo
function pha_custom_login_logo() {
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_template_directory_uri(); ?>/assets/images/pha-logo.png);
            height: 80px;
            width: 320px;
            background-size: contain;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'pha_custom_login_logo');

// Custom login logo URL
function pha_custom_login_url() {
    return home_url();
}
add_filter('login_headerurl', 'pha_custom_login_url');

// Custom login logo title
function pha_custom_login_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'pha_custom_login_title');

?>