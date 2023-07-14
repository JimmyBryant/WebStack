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
include('templates/header-nav.php');
?>
<style>
    article{
        font: 18px/34px Arial,Hiragino Sans GB,STHeiti,Helvetica Neue,Helvetica,Microsoft Yahei,WenQuanYi Micro Hei,sans-serif;
    }
    article h1{
        margin:20px 0;
        font: 700 38px/48px MicrosoftYaHei Bold,MicrosoftYaHei,Arial,Hiragino Sans GB,STHeiti,Helvetica Neue,Helvetica,Microsoft Yahei,WenQuanYi Micro Hei,sans-serif;
        color: #404040;
    }
    .page .panel article p,article p{
        color: #404040;
        font-size: 18px;
        font-family: inherit;
    }
    article p:has(img){
        text-align: center;
    }
</style>
<div class="main-content page">
    <?php include( 'templates/header-banner.php' ); ?>
    <div class="container">
        <div class="row">
            <div class="col-12 mx-auto">
                <div class="panel panel-default">
                    <article>
                        <h1><?php echo get_the_title() ?></h1>
                        <?php while (have_posts()) : the_post(); ?>
                            <?php the_content(); ?>
                            <?php edit_post_link(__('编辑', 'i_theme'), '<span class="edit-link">', '</span>'); ?>
                        <?php endwhile; ?>
                    </article>
                </div>
                <?php
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </div>
        </div>
    </div>
    <?php get_footer(); ?>