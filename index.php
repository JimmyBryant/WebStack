<?php if (!defined('ABSPATH')) {
  exit;
} ?>
<?php get_header(); ?>


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
  }
  ?>

  <section class="section">
    <div class="section-title">
     <h4 class="text-gray"><i class="icon-io-tag" style="margin-right: 27px;" id="term-news"></i>AI资讯</h4>
    </div>
    <div class="section-content">
      <div class="post-gallery">
        <?php
        $args = array(
          'post_type' => 'post',
          'numberposts' => 4,
          'orderby' => 'date',
          'order' => 'DESC',
        );

        $latest_posts = get_posts($args);
        $current_date = current_time('Y-m-d H:i:s'); // 获取当前时间
        foreach ($latest_posts as $post) :
          setup_postdata($post);
          // 获取文章的封面图片
          $thumbnail = get_the_post_thumbnail_url();
          $post_date = get_the_date('Y-m-d H:i:s'); // 获取文章的发布时间
          // 计算发布时间与当前时间的差异
          $time_difference = abs(strtotime($current_date) - strtotime($post_date));
          // 使用human_time_diff()函数显示比较友好的时间格式
          $friendly_time = human_time_diff(strtotime($post_date), strtotime($current_date)) . '前';
        ?>
          <div class="post-item">
            <a class="link" href="<?php the_permalink() ?>" title="<?php the_title() ?>">
              <!-- <img src="<?php echo $thumbnail ?>" alt="<?php the_title() ?>" /> -->
              <span class="thumb" style="background-image: url(<?php echo $thumbnail ?>);"></span>
              <div class="info">
                <div class="date"><i class="fa fa-calendar"></i><?php echo $friendly_time?></div>
                <h2 class="title"><?php the_title(); ?></h2>
              </div>

            </a>
          </div>
        <?php
        endforeach;
        wp_reset_postdata();
        ?>
      </div>
    </div>
  </section>

  <div class="sites-list" style="margin-bottom: 8.5rem;">
    <?php if (!wp_is_mobile() && io_get_option('ad_home_s')) echo '<div class="row"><div class="ad ad-home col-md-6">' . stripslashes(io_get_option('ad_home')) . '</div><div class="ad ad-home col-md-6 visible-md-block visible-lg-block">' . stripslashes(io_get_option('ad_home')) . '</div></div>'; ?>

    <?php
    foreach ($categories as $category) {
      if ($category->category_parent == 0) {
        $children = get_categories(
          array(
            'taxonomy'   => 'favorites',
            'meta_key'   => '_term_order',
            'orderby'    => 'meta_value_num',
            'order'      => 'desc',
            'child_of'   => $category->term_id,
            'hide_empty' => 0
          )
        );
        if (empty($children)) {
          fav_con($category);
        } else {
          foreach ($children as $mid) {
            fav_con($mid);
          }
        }
      }
    }
    get_template_part('templates/friendlink');
    ?>
  </div>
  <?php
  get_footer();
