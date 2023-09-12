<?php

namespace Wer\PartnerSlider;

use WP_Query;

class Slider
{
    private static $slide_total_amount = 10;
    private static $slider_attributes = [
        'amount_of_posts' => 10,
        'space_between' => 20,
        'slides_mobile' => 1,
        'slides_tablet' => 2,
        'slides_desktop' => 3,
        'show_navigation' => false,
        'show_pagination' => false,
        'speed' => 2000,
    ];

    private static $default_breakpoints = [
        'slides_mobile' => 768,
        'slides_tablet' => 990,
        'slides_desktop' => 1200,
    ];

    /**
     * Initialize the post types
     *
     * @return void
     */
    public function __construct()
    {
        add_shortcode('partner_slider', [$this, 'renderSlider']);
    }

    /**
     * render the slider structure when calling the partner_slider shortcode
     *
     * @return void
     */
    public static function renderSlider($attributes)
    {
        // Inside your shortcode function
        $attributes = shortcode_atts(self::$slider_attributes, $attributes);

        $show_pagination = self::checkBoolean($attributes['show_pagination']);
        $show_nagination = self::checkBoolean($attributes['show_navigation']);

        $query = new WP_Query([
            'post_type' => 'logos',
            'showposts' => self::$slide_total_amount,
        ]);

        if ($query->have_posts()) {
            $options = [
                'slidesPerView' => $attributes['slides_mobile'],
                'spaceBetween' =>  $attributes['space_between'],
                'autoplay' => [
                    'delay' => $attributes['speed']
                ],
                'speed' => $attributes['speed'],
                'pagination' => self::showPagination($show_pagination),
                'navigation' => self::showNavigation($show_nagination),
                'breakpoints' => self::setupSliderBreakpoints($attributes),
            ];
            $swiper_options_json = json_encode($options);

            $output = "<section class='swiper partner__slider' data-swiper-options={$swiper_options_json}>";
            $output .= "<div class='swiper-wrapper'>";

            while ($query->have_posts()) : $query->the_post();
                $output .= self::generateSlides(get_the_ID());
            endwhile;
            wp_reset_postdata();

            $output .= '</div>';

            if ($show_pagination) :
                $output .= '<div class="swiper-pagination"></div>';
            endif;

            if ($show_nagination) :
                $output .= '<div class="swiper-pagintation">';
                $output .= '<div class="swiper-button-prev"></div>';
                $output .= '<div class="swiper-button-next"></div>';
                $output .= '</div>';
            endif;

            $output .= '</section>';

            // Finally add styles and scripts to load only when shortcode is beging used.
            self::loadPluginAssets();

            return $output;
        }
        return;
    }

    /**
     * Generate the slides output
     *
     * @return void
     */
    private static function generateSlides($postID)
    {
        $output = '<div class="swiper-slide">';
        $output .= '<div class="slide-inner">';
        $output .= self::getPartnerLogo($postID);
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    /**
     * Get the thumbnail if it exists 
     * If there is no thumbnail show a fallback image
     *
     * @param [type] $postID
     * @return void
     */
    private static function getPartnerLogo($postID)
    {
        if (has_post_thumbnail()) :
            $image = get_the_post_thumbnail($postID, 'full', ['class' => 'image--cover']);
        else :
            $image_path = WER_SLIDER_PLUGIN_URL . 'assets/images/fallback-image.png';
            $image = "<img class='image--cover' src='{$image_path}' alt='' />";
        endif;

        return $image;
    }

    /**
     * show pagination if the parameter is true
     *
     * @param [type] $attribute
     * @return void
     */
    private static function showPagination($show_pagination)
    {
        return self::checkBoolean($show_pagination) ? [
            'el' => '.swiper-pagination',
            'clickable' => true,
        ] : false;
    }

    /**
     * show navigation if the parameter is true
     *
     * @param [type] $attribute
     * @return void
     */
    private static function showNavigation($show_navigation)
    {
        return self::checkBoolean($show_navigation) ? [
            'nextEl' => '.swiper-button-next',
            'prevEl' => '.swiper-button-prev',
        ] : false;
    }

    /**
     * Setup breakpoints for slider and make them adjustable for users
     *
     * @return void
     */
    private static function setupSliderBreakpoints($attributes)
    {
        $breakpoints = [];

        $default_breakpoints = self::$default_breakpoints;

        foreach ($default_breakpoints as $breakpoint_name => $default_breakpoint) {
            $breakpoint_size = intval(str_replace('breakpoint_', '', $default_breakpoint));

            if (array_key_exists($breakpoint_name, $attributes) && $attributes[$breakpoint_name] !== null) {
                $breakpoints[$breakpoint_size] = [
                    'slidesPerView' => $attributes[$breakpoint_name]
                ];
            }
        }

        return $breakpoints;
    }

    /**
     * load styles from source
     *
     * @return void
     */
    public static function loadPluginAssets()
    {
        wp_enqueue_style('swiper-slider-css',  '//cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
        wp_enqueue_script('swiper-slider-js',  '//cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', [], '', true);

        wp_enqueue_style('wer-partnerslider-css',  WER_SLIDER_PLUGIN_URL  . 'dist/css/main.css');
        wp_enqueue_script('wer-partnerslider-s',  WER_SLIDER_PLUGIN_URL . 'dist/js/bundle.js', [], '', true);
    }

    /**
     * return boolean value from strings
     *
     * @param [type] $value
     * @return void
     */
    private static function checkBoolean($value)
    {
        if (!$value) return;
        return json_decode(strtolower($value));
    }
}
