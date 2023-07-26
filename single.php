<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header(); ?>


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
// 获取当前文章的ID
$current_post_id = get_the_ID();

// 构建查询参数
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 14,
    'post__not_in' => array($current_post_id), // 排除当前文章
    'orderby' => 'rand', // 以随机顺序返回相关文章
    'tax_query' => array(
        'relation' => 'OR',
        array(
            'taxonomy' => 'category', // 根据分类查询
            'field' => 'id',
            'terms' => wp_get_post_categories($current_post_id),
        ),
        array(
            'taxonomy' => 'post_tag', // 根据标签查询
            'field' => 'id',
            'terms' => wp_get_post_tags($current_post_id),
        ),
    ),
);

// 创建查询
$related_query = new WP_Query($args);
include('templates/header-nav.php');
?>
<style>
    body {
        background-color: #d5d5d5;
    }

    .post-container {
        display: flex;
        flex: none;
        background-color: transparent;
        margin-bottom: 30px;
    }

    .post-container .main {
        flex: 1;
        margin-right: 30px;
    }

    article {
        font: 16px/32px Arial, Hiragino Sans GB, STHeiti, Helvetica Neue, Helvetica, Microsoft Yahei, WenQuanYi Micro Hei, sans-serif;
        padding: 30px;
        box-shadow: 0 0 8px 1px rgb(0 0 0 / .1);
        box-sizing: border-box;
        background-color: #FFF;
        max-width: 100%;
        margin-bottom: 30px;
    }

    article h1 {
        margin-bottom: 30px;
        font: 700 32px/38px MicrosoftYaHei Bold, MicrosoftYaHei, Arial, Hiragino Sans GB, STHeiti, Helvetica Neue, Helvetica, Microsoft Yahei, WenQuanYi Micro Hei, sans-serif;
        color: #404040;
    }

    .page .panel article p,
    article p {
        color: #404040;
        font-size: 16px;
        font-family: inherit;
    }

    article p:has(img) {
        text-align: center;
    }

    article img {
        width: 100%;
        height: auto;
        max-width: 100%;
        max-height: 100%;
    }

    .sidebar {
        position: sticky;
        width: 310px;
        min-height: 100vh;
    }

    .related-posts {
        background-color: #FFF;
        padding: 15px;
        position: sticky;
        top: 100px;
        box-shadow: 0 0 8px 1px rgb(0 0 0 / .1);
    }

    .related-posts h3 {
        margin: 0 0 15px 0;
    }

    .post-list {
        margin: 0;
        padding: 0;
    }

    .post-item {
        display: flex;
        flex-direction: row;
        margin-bottom: 15px;
    }

    .post-item .post-thumbnail {
        display: block;
        width: 80px;
        height: 80px;
        font-size: 0;
        flex: none;
        margin-right: 15px;
    }

    .post-item .post-thumbnail img {
        width: 100%;
        height: auto;
        max-width: 100%;
    }

    .post-item .post-content .post-title {
        margin: 0 0 10px 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 20px;
    }

    .post-item .post-content .post-title a:hover {
        color: #000;
    }

    .sidebar .related-posts .post-list {
        list-style: none;
    }

    .sidebar .related-posts .post-list li {
        display: flex;
    }

    .related-posts-bottom h3 {
        margin: 0 0 15px 0;
    }

    .post-list-flex {
        display: flex;
        flex-wrap: wrap;
        background-color: transparent;
    }

    .post-list-flex .post-item {
        display: block;
        padding: 0 15px;
        width: 25%;
    }

    .post-list-flex .post-item .post-thumbnail {
        width: 100%;
        height: auto;
    }

    .post-list-flex .post-item .post-title {
        margin: 15px 0 0;
    }

    .comments-area {
        box-shadow: 1px 2px 8px 2px rgb(0 0 0 / .1);
    }

    @media (max-width:768px) {
        .post-container .main {
            margin-right: 0;
        }

        .sidebar {
            display: none;
        }

        .post-list-flex .post-item {
            width: 50%;
        }
    }
</style>
<div class="main-content page">
    <?php include('templates/header-banner.php'); ?>
    <div class="container">
        <div class="row">
            <div class="col-12 mx-auto">
                <div class="post-container">
                    <div class="main">
                        <article>
                            <h1><?php echo get_the_title() ?></h1>
                            <?php while (have_posts()) : the_post(); ?>
                                <?php the_content(); ?>
                                <?php edit_post_link(__('编辑', 'i_theme'), '<span class="edit-link">', '</span>'); ?>
                            <?php endwhile; ?>
                        </article>
                        <?php
                        // 输出相关文章
                        if ($related_query->have_posts()) : ?>
                            <div class="related-posts-bottom">
                                <h3>相关文章</h3>
                                <ul class="post-list post-list-flex">
                                    <?php
                                    $n = 0;
                                    while ($related_query->have_posts() && $n < 8) :
                                        $related_query->the_post();
                                        $n++;
                                    ?>
                                        <li class="post-item">
                                            <a class="post-thumbnail" href="<?php the_permalink(); ?>">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail('thumbnail'); ?>
                                                <?php else : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 150">
                                                        <rect width="100%" height="100%" fill="#FFFFFF" />
                                                        <text x="50%" y="50%" fill="#333" text-anchor="middle" dominant-baseline="middle" font-family="Arial" font-size="20">
                                                            ainav-pro.com
                                                        </text>
                                                    </svg>
                                                <?php endif; ?>
                                            </a>
                                            <div class="post-content">
                                                <h4 class="post-title">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="sidebar">
                        <?php
                        // 输出相关文章
                        if ($related_query->have_posts()) : ?>
                            <div class="related-posts">
                                <h3>相关文章</h3>
                                <ul class="post-list">
                                    <?php
                                    while ($related_query->have_posts()) :
                                        $related_query->the_post();
                                    ?>
                                        <li class="post-item">
                                            <a class="post-thumbnail" href="<?php the_permalink(); ?>">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail('thumbnail'); ?>
                                                <?php else : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 150">
                                                        <rect width="100%" height="100%" fill="#CCC" />
                                                        <text x="50%" y="50%" fill="#333" text-anchor="middle" dominant-baseline="middle" font-family="Arial" font-size="20">
                                                            ainav-pro.com
                                                        </text>
                                                    </svg>
                                                <?php endif; ?>
                                            </a>
                                            <div class="post-content">
                                                <h4 class="post-title">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        <?php
                        endif;

                        // 重置查询
                        wp_reset_postdata();
                        ?>

                    </div>
                </div>
            </div>
            <div>
                <?php
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </div>
        </div>
    </div>
    <?php get_footer(); ?>