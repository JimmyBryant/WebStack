<?php
/*
 * @Theme Name:WebStack
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2019-02-22 21:26:02
 * @LastEditors: iowen
 * @LastEditTime: 2022-07-25 18:11:27
 * @FilePath: \WebStack\templates\header-banner.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>
<nav class="navbar user-info-navbar" role="navigation">
    <div class="navbar-content">
      <ul class="user-info-menu list-inline list-unstyled">
        <li class="hidden-xs">
            <a href="#" data-toggle="sidebar">
                <i class="fa fa-bars"></i>
            </a>
        </li>
        <?php
            if(function_exists('wp_nav_menu')) wp_nav_menu( array('container' => false, 'items_wrap' => '<li id="%1$s" class="%2$s">%3$s</li>', 'theme_location' => 'nav_primary',) ); 
        ?>
      </ul>
    </div>
</nav>