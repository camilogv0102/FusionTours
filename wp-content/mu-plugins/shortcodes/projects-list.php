<?php
/**
 * Shortcode: [projects_list]
 * Renderiza un listado de proyectos con previsualización al hover.
 *
 * Requisitos:
 *  - CPT `project`
 *  - Taxonomía `project_tag`
 *  - Campo ACF `project_hero_imagen_fondo`
 */

if ( ! defined('ABSPATH') ) exit;

if ( ! function_exists('snc_projects_render_list_shortcode') ) {
    function snc_projects_render_list_shortcode($atts = [], $content = null, $shortcode_tag = '') {
        $atts = shortcode_atts([
            'posts_per_page' => -1,
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        ], $atts, $shortcode_tag);

        $query = new WP_Query([
            'post_type'      => 'project',
            'post_status'    => 'publish',
            'posts_per_page' => intval($atts['posts_per_page']),
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
            'no_found_rows'  => true,
        ]);

        if ( ! $query->have_posts() ) {
            return '';
        }

        ob_start();

        static $has_printed_styles = false;
        if ( ! $has_printed_styles ) {
            $has_printed_styles = true;
            ?>
            <style>
                .snc-projects-list {
                    display: flex;
                    flex-direction: column;
                }
                .snc-projects-list__item {
                    text-decoration: none;
                    color: inherit;
                    position: relative;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 32px;
                    padding: 44px 0;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                }
                .snc-projects-list__item:first-child {
                    border-top: 1px solid rgba(255, 255, 255, 0.08);
                }
                .snc-projects-list__title {
                    font-size: clamp(1rem, 1.5vw, 1.375rem);
                    font-weight: 600;
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                    color: #fff;
                    margin: 0;
                    flex: 1 1 35%;
                }
                .snc-projects-list__tags {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 12px;
                    justify-content: flex-end;
                    flex: 1 1 auto;
                }
                .snc-projects-list__tag {
                    display: inline-flex;
                    align-items: center;
                    padding: 6px 16px;
                    border-radius: 999px;
                    background: rgba(255, 255, 255, 0.08);
                    color: #fff;
                    font-size: 0.75rem;
                    letter-spacing: 0.04em;
                    text-transform: uppercase;
                    transition: background 0.2s ease;
                }
                .snc-projects-list__tag:hover {
                    background: rgba(255, 255, 255, 0.16);
                }
                .snc-projects-list__preview {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 240px;
                    height: 240px;
                    border-radius: 24px;
                    overflow: hidden;
                    background-size: cover;
                    background-position: center;
                    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(0.97);
                    transition: opacity 0.3s ease, transform 0.3s ease;
                    pointer-events: none;
                }
                .snc-projects-list__item:hover,
                .snc-projects-list__item:focus-visible {
                    outline: none;
                }
                .snc-projects-list__item:hover .snc-projects-list__preview,
                .snc-projects-list__item:focus-within .snc-projects-list__preview {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(1);
                }
                @media (max-width: 1024px) {
                    .snc-projects-list__item {
                        flex-direction: column;
                        align-items: flex-start;
                        gap: 20px;
                    }
                    .snc-projects-list__preview {
                        position: static;
                        width: 100%;
                        height: 220px;
                        opacity: 1;
                        transform: none;
                        order: -1;
                        margin: 0 0 16px 0;
                        transition: none;
                    }
                    .snc-projects-list__tags {
                        justify-content: flex-start;
                        gap: 10px;
                    }
                    .snc-projects-list__item:hover .snc-projects-list__preview,
                    .snc-projects-list__item:focus-within .snc-projects-list__preview {
                        opacity: 1;
                        transform: none;
                    }
                }
            </style>
            <?php
        }

        ?>
        <div class="snc-projects-list">
            <?php
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_id   = get_the_ID();
                $title     = get_the_title();
                $tags      = get_the_terms($post_id, 'project_tag');
                $hero_img  = get_field('project_hero_imagen_fondo', $post_id);
                $image_url = '';

                if ( is_array($hero_img) && isset($hero_img['url']) ) {
                    $image_url = $hero_img['url'];
                } elseif ( is_string($hero_img) && ! empty($hero_img) ) {
                    $image_url = $hero_img;
                }

                ?>
                <a class="snc-projects-list__item" href="<?php echo esc_url(get_permalink($post_id)); ?>" target="_blank" rel="noopener noreferrer">
                    <h3 class="snc-projects-list__title"><?php echo esc_html($title); ?></h3>
                    <div class="snc-projects-list__tags">
                        <?php
                        if ( $tags && ! is_wp_error($tags) ) {
                            foreach ( $tags as $tag ) {
                                echo '<span class="snc-projects-list__tag">' . esc_html($tag->name) . '</span>';
                            }
                        }
                        ?>
                    </div>
                    <?php if ( $image_url ) : ?>
                        <div class="snc-projects-list__preview" style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
                    <?php endif; ?>
                </a>
                <?php
            }
            ?>
        </div>
        <?php

        wp_reset_postdata();

        return ob_get_clean();
    }

    add_shortcode('projects_list', 'snc_projects_render_list_shortcode');
}
