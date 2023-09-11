<?php

namespace Wer\PartnerSlider;

class PostTypeManager
{

    /**
     * Initialize the post types
     *
     * @return void
     */
    public static function initialize()
    {
        self::initializePostTypes();
    }

    /**
     * setup and initilize custom post types
     */
    public static function initializePostTypes() {
        add_action('init', function() {
            self::registerPostType('logos', 'Logo', 'Logo\'s');
        });

        add_action('admin_notices', function() {
            self::showAdminNotice();
        });

    }

    /**
     * Register post types
     *
     * @param [type] $postType
     * @param [type] $singularName
     * @param [type] $pluralName
     * @param integer $position
     * @param string $icon
     */
    public static function registerPostType($postType, $singularName, $pluralName, $position = 100, $icon = 'dashicons-format-image')
    {
        $labels = array(
            'name' => _x($pluralName, 'Post Type General Name', 'wer-logoslider'),
            'singular_name' => _x($singularName, 'Post Type Singular Name', 'wer-logoslider'),
            'menu_name' => __($pluralName, 'wer-logoslider'),
            'name_admin_bar' => __($singularName, 'wer-logoslider'),
            'archives' => __($singularName . ' Archives', 'wer-logoslider'),
        );

        $args = array(
            'label' => __($singularName, 'wer-logoslider'),
            'description' => __($singularName . ' Description', 'wer-logoslider'),
            'labels' => $labels,
            'supports' => [
                'title',
                'editor',
                'thumbnail'
            ],
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => $position,
            'menu_icon' => $icon,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
        );

        register_post_type($postType, $args);
    }

    /**
     * Show an notice with the correct shortcode
     *
     * @return void
     */
    private static function showAdminNotice() {
        // Add an admin notice for the "logos" custom post type
        global $pagenow;

        // Check if we are on the edit screen of the "logos" custom post type
        if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'logos') {
            echo '<div class="notice notice-info">';
            echo '<p>You can show the logo\'s with shortcode <strong>[partner_slider]</strong><br />
            Optional parameters are: <i>amount_of_posts, slides_mobile, slides_tablet, slides_desktop, show_navigation, show_pagination and speed</i><br /><br /></p>';
            echo '</div>';
        }
    }
}
