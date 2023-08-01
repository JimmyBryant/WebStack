<?php
/*
 * @Theme Name:WebStack
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2020-02-22 21:26:05
 * @LastEditors: iowen
 * @LastEditTime: 2023-02-20 22:31:44
 * @FilePath: \WebStack\single-sites.php
 * @Description: 
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header(); ?>


<?php
$categories = get_categories(array(
    'taxonomy'     => 'favorites',
    'meta_key'     => '_term_order',
    'orderby'      => 'meta_value_num',
    'order'        => 'desc',
    'hide_empty'   => 0,
));

include('templates/header-nav.php');
?>
<div class="main-content page">

    <?php include('templates/header-banner.php'); ?>

    <div class="container">
        <div class="row mt-5 mt-sm-0">
            <div class="col-12 mx-auto">
                <div class="panel panel-default">
                    <div class="panel-body my-4 ">
                        <?php while (have_posts()) : the_post(); ?>
                            <div class="row">
                                <div class="col-12 col-sm-4 col-lg-3">
                                    <?php
                                    $m_link_url  = get_post_meta($post->ID, '_sites_link', true);
                                    $m_thumbnail = get_post_meta(get_the_ID(), '_thumbnail', true);
                                    if ($m_thumbnail == '' && $m_link_url == '')
                                        $imgurl = get_theme_file_uri('/images/favicon.png');
                                    else
                                        $imgurl = $m_thumbnail ? $m_thumbnail : (io_get_option('ico_url') . format_url($m_link_url) . io_get_option('ico_png'));
                                    $sitetitle = get_the_title();
                                    ?>
                                    <div class="siteico">
                                        <div class="blur blur-layer" style="background: transparent url(<?php echo $imgurl ?>) no-repeat center center;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;animation: rotate 30s linear infinite;"></div>
                                        <img class="img-cover" src="<?php echo $imgurl ?>" alt="<?php echo $sitetitle ?>" title="<?php echo $sitetitle ?>">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-8 col-lg-5 mt-4 mt-md-0">
                                    <div class="site-body p-xl-4">
                                        <?php
                                        $terms = get_the_terms(get_the_ID(), 'favorites');
                                        if (!empty($terms)) {
                                            foreach ($terms as $term) {
                                                $name = $term->name;
                                                $link = esc_url(get_term_link($term, 'res_category'));
                                                echo " <a class='btn-cat' href='$link'>" . $name . "</a>";
                                            }
                                        }
                                        ?>
                                        <div class="site-name h3"><?php echo $sitetitle ?></div>
                                        <div class="mt-2">

                                            <p><?php echo get_post_meta(get_the_ID(), '_sites_sescribe', true) ?></p>
                                            <?php
                                            $m_post_link_url = $m_link_url ?: get_permalink($post->ID);
                                            $qrurl = "//api.qrserver.com/v1/create-qr-code/?size=150x150&margin=10&data=" . $m_post_link_url;
                                            $qrname = __("手机查看", "i_theme");
                                            if (get_post_meta(get_the_ID(), '_wechat_qr', true)) {
                                                $qrurl = get_post_meta(get_the_ID(), '_wechat_qr', true);
                                                $qrname = __("公众号", "i_theme");
                                            }
                                            ?>
                                            <div class="site-go mt-3">
                                                <?php if ($m_link_url != "") : ?>
                                                    <a style="margin-right: 10px;" href="<?php echo io_get_option('is_go') ? home_url() . '/go/?url=' . base64_encode($m_link_url) : $m_link_url ?>" title="<?php echo $sitetitle ?>" target="_blank" class="btn btn-arrow"><span><?php _e('链接直达', 'i_theme') ?><i class="fa fa-angle-right"></i></span></a>
                                                <?php endif; ?>
                                                <a href="javascript:" class="btn btn-arrow" data-toggle="tooltip" data-placement="bottom" title="" data-html="true" data-original-title="<img src='<?php echo $qrurl ?>' width='150'>"><span><?php echo $qrname ?><i class="fa fa-qrcode"></i></span></a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-lg-4 mt-4 mt-lg-0">

                                    <?php if (io_get_option('ad_right_s')) echo '<div class="ad ad-right">' . stripslashes(io_get_option('ad_right')) . '</div>'; ?>

                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-top">
                                <?php
                                $contentinfo = get_the_content();
                                if ($contentinfo) {
                                    the_content();
                                } else {
                                    echo get_post_meta(get_the_ID(), '_sites_sescribe', true);
                                }
                                ?>

                            </div>
                        <?php endwhile; ?>
                    </div>
                    <?php edit_post_link(__('编辑', 'i_theme'), '<span class="edit-link">', '</span>'); ?>
                </div>

                <h4 class="text-gray mt-4"><i class="icon-io-tag" style="margin-right: 27px;" id="relevant_c"></i><?php _e('相关导航', 'i_theme') ?></h4>
                <div class="row mb-5">
                    <?php
                    $post_num = 6;
                    $i = 0;
                    if ($i < $post_num) {
                        $custom_taxterms = wp_get_object_terms($post->ID, 'favorites', array('fields' => 'ids'));
                        $args = array(
                            'post_type' => 'sites', // 文章类型
                            'post_status' => 'publish',
                            'posts_per_page' => 6, // 文章数量
                            'orderby' => 'rand', // 随机排序
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'favorites', // 分类法
                                    'field' => 'id',
                                    'terms' => $custom_taxterms
                                )
                            ),
                            'post__not_in' => array($post->ID), // 排除当前文章
                        );
                        $related_items = new WP_Query($args);
                        if ($related_items->have_posts()) :
                            while ($related_items->have_posts()) : $related_items->the_post();
                                $link_url = get_post_meta($post->ID, '_sites_link', true);
                                $default_ico = get_theme_file_uri('/images/favicon.png');
                                if (current_user_can('level_10') || get_post_meta($post->ID, '_visible', true) == "") :
                    ?>
                                    <div class="xe-card col-sm-6 col-md-4 <?php echo get_post_meta($post->ID, '_wechat_qr', true) ? 'wechat' : '' ?>">
                                        <?php include('templates/site-card.php'); ?>
                                    </div>
                    <?php endif;
                                $i++;
                            endwhile;
                        endif;
                        wp_reset_postdata();
                    }
                    if ($i == 0) echo '<div class="col-lg-12"><div class="nothing">' . __('没有相关内容!', 'i_theme') . '</div></div>';
                    ?>
                </div>
                <style>
                    .post-list{
                        padding: 0;
                        list-style: none;
                        margin: 20px 0;
                    }
                    .post-list .post-item{
                        display: flex;
                        margin-bottom: 20px;
                        background: #FFF;
                        padding: 15px;
                        box-shadow: 0 0 5px 0px rgb(0 0 0 /.1);
                    }
                    .post-item .post-thumbnail{
                        flex: none;
                        width: 150px;
                        height: 150px;
                        margin-right: 20px;
                    }
                    .post-item .post-content{
                        position: relative;
                    }
                    .post-item .post-title{
                        margin-top: 0;
                        margin-bottom: 20px;
                    }
                    .post-item .post-title:hover a{
                        color: #000;
                    }
                    .post-item .post-meta{
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        color: #999;
                    }
                    @media screen and (max-width:768px) {
                        .post-item .post-title{
                            font-size: 18px;
                            margin: 0;
                            font-weight: 400;
                            line-height: 24px;

                        }
                    }
                </style>
                <?php


                // 设置查询参数，获取与当前“sites”文章相关的普通文章
                $args = array(
                    'post_type' => 'post', // 普通文章类型
                    'post_status' => 'publish',
                    'posts_per_page' => 12, // 显示的相关文章数量
                    'post__not_in' => array($post->ID), // 排除当前“sites”文章
                    'orderby' => 'rand', // 随机排序
                );

                $related_posts_query = new WP_Query($args);
                // 输出相关文章
                if ($related_posts_query->have_posts()) : ?>
                    <div class="related-posts-bottom">
                        <h4 class="text-gray mt-4"><i class="icon-io-tag" style="margin-right: 27px;"></i><?php _e('推荐资讯', 'i_theme')?></h4>
                        <ul class="post-list post-list-flex">
                            <?php
                            while ($related_posts_query->have_posts()) :
                                $related_posts_query->the_post();
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
                                        <h3 class="post-title">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>
                                        <div class="post-meta">
                                            <span class="posted-on"><time><?php the_time('Y-m-d');?></time></span>
                                        </div>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php
                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
                ?>
            </div>

            <?php if (io_get_option('ad_footer_s')) echo '<div class="ad ad-footer">' . stripslashes(io_get_option('ad_footer')) . '</div>'; ?>

        </div>

    </div>
    <?php get_footer(); ?>