<?php

namespace Wer\PartnerSlider;

use WP_Query;

class Slider
{
    private static $slide_total_amount = 10;
    private static $slide_breakpoints = [
        'desktop' => 6,
        'tablet' => 4,
        'mobile' => 2,
    ];

    /**
     * Initialize the post types
     *
     * @return void
     */
    public function __construct()
    {
        add_shortcode('partner_slider', [$this, 'renderSlider']);

        add_action('wp_enqueue_scripts', [$this, 'loadPluginAssets']);
    }

    /**
     * render the slider structure when calling the partner_slider shortcode
     *
     * @return void
     */
    public static function renderSlider()
    {
        $query = new WP_Query([
            'post_type' => 'logos',
            'showposts' => self::$slide_total_amount,
        ]);

        if ($query->have_posts()) {
            $options = [
                'slidesPerView' => 2,
                'spaceBetween' => 20,
                'autoplay' => [
                    'delay' => 3000
                ],
                'speed' => 400,
                'pagination' => [
                    'el' => '.swiper-pagination',
                    'clickable' => true
                ],
                // 'navigation' => [
                //     'nextEl' => '.swiper-button-next',
                //     'prevEl' => '.swiper-button-prev'
                // ],
                'breakpoints' => [
                    768 => [
                        'slidesPerView' => 4
                    ]
                ]
            ];
            $swiper_options_json = json_encode($options);

            $output = "<section class='swiper partner__slider' data-swiper-options={$swiper_options_json}>";
            $output .= "<div class='swiper-wrapper'>";

            while ($query->have_posts()) : $query->the_post();
                $output .= self::generateSlides(get_the_ID());
            endwhile;
            wp_reset_postdata();

            $output .= '</div>';
            $output .= '<div class="swiper-pagination"></div>';

            $output .= ' <div class="swiper-button-prev"></div><div class="swiper-button-next"></div>';

            $output .= '</section>';

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
            $image_path = WER_PLUGIN_URL . 'assets/images/fallback-image.png';
            $image = "<img class='image--cover' src='{$image_path}' alt='' />";
        endif;

        return $image;
    }

    /**
     * load styles from source
     *
     * @return void
     */
    function loadPluginAssets()
    {
        
        wp_enqueue_style('swiper-slider-css',  '//cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
        wp_enqueue_style('wer-partnerslider-css',  WER_PLUGIN_URL  . 'dist/css/main.css');

        wp_enqueue_script('swiper-slider-js',  '//cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', [], '', true);
        wp_enqueue_script('wer-partnerslider-s',  WER_PLUGIN_URL . 'dist/js/bundle.js', [], '', true);
    }
}
