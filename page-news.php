<?php

/**
 * Template Name:资讯模板
 * Type: post, page
 */
?>
<?php if (!defined('ABSPATH')) {
    exit;
} ?>
<?php get_header(); ?>

<style>
    .post-list {
        max-width: 1280px;
        min-width: 320px;
        margin: 0 auto;
    }

    .post-item {
        display: flex;
        margin-bottom: 20px;
        background-color: #FFF;
    }

    .post-thumbnail {
        flex-basis: 30%;
        margin-right: 20px;
    }
    .post-thumbnail a{
        display: block;
    }
    .post-thumbnail svg{
        width: 100%;
        height: auto;
    }
    .post-thumbnail img {
        width: 100%;
        height: auto;
    }

    .post-content {
        flex-grow: 1;
    }

    .entry-title a {
        color: #333;
        text-decoration: none;
    }

    .entry-meta {
        margin-bottom: 10px;
    }

    .post-date {
        margin-right: 10px;
    }

    .post-tags a {
        margin-right: 5px;
    }

    .entry-summary {
        color: #666;
    }
</style>
<?php
$categories = get_categories(
    array(
        'taxonomy'     => 'favorites',
        'meta_key'     => '_term_order',
        'orderby'      => 'meta_value_num',
        'order'        => 'desc',
        'hide_empty'   => 0,
    )
);
include('templates/header-nav.php');
?>
<div class="main-content">

    <?php include('templates/header-banner.php'); ?>

    <?php get_template_part('templates/bulletin'); ?>

    <?php
    if (io_get_option('is_search')) {
        include('search-tool.php');
    } else { ?>
        <div class="no-search"></div>
    <?php
    }
    ?>

    <div class="sites-list" style="margin-bottom: 8.5rem;">
        <div class="post-list">
            <?php if (!wp_is_mobile() && io_get_option('ad_home_s')) echo '<div class="row"><div class="ad ad-home col-md-6">' . stripslashes(io_get_option('ad_home')) . '</div><div class="ad ad-home col-md-6 visible-md-block visible-lg-block">' . stripslashes(io_get_option('ad_home')) . '</div></div>'; ?>
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 10, // 可以根据需要调整每页显示的文章数量
                'paged' => $paged
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) :
                while ($query->have_posts()) :
                    $query->the_post();
            ?>
                    <div <?php post_class('post-item'); ?> id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                <?php else : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 100">
                                        <rect width="100%" height="100%" fill="#CCCCCC" />
                                        <text x="50%" y="50%" fill="#FFFFFF" text-anchor="middle" dominant-baseline="middle" font-family="Arial" font-size="20">
                                            ainav-pro.com
                                        </text>
                                    </svg>
                                <?php endif; ?>
                            </a>
                        </div>


                        <div class="post-content">
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <div class="entry-meta">
                                    <span class="post-date"><?php the_time('Y-m-d'); ?></span>
                                    <span class="post-tags"><?php the_tags('', ', ', ''); ?></span>
                                </div>
                            </header>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    </div>
            <?php
                endwhile;

                // 显示翻页链接
                echo '<div class="pagination">';
                echo paginate_links(array(
                    'total' => $query->max_num_pages
                ));
                echo '</div>';
            endif;

            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
    get_footer();
