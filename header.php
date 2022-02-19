<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    <header class="site-header">
      <div class="container">
          <?php get_template_part( 'template-parts/header/logo' ); ?>
        
        <a href="<?php echo esc_url(site_url('/search')); ?>" class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
        <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
        <div class="site-header__menu group">
        <?php
        wp_nav_menu( array(
            
            'container'         => "nav", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
            'container_class'   => "main-navigation", // (string) Class that is applied to the container. Default 'menu-{menu slug}-container'.
          
            'depth'             => 10, // (int) How many levels of the hierarchy are to be included. 0 means all. Default 0.
            'theme_location'    => "primary_menu" // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
            
            ));
          ?>
<!-- 
          <nav class="main-navigation">
            <ul>
              <li><a href="">About Us</a></li>
              <li><a href="#">Programs</a></li>
              <li><a href="#">Events</a></li>
              <li><a href="#">Campuses</a></li>
              <li><a href="#">Blog</a></li>
            </ul>
          </nav> -->
          <div class="site-header__util">

              
          <?php if(is_user_logged_in()) { ?>
            <a href="<?php echo wp_logout_url();  ?>" class="btn btn--small  btn--dark-orange float-left btn--with-photo">
            <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
            <span class="btn__text">Log Out</span>
            </a>
            <?php } 
            else { ?>
              <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
              <a href="<?php echo wp_registration_url(); ?>" class="btn btn--small  btn--dark-orange float-left">Sign Up</a>
             <?php } ?>

            <a href="<?php echo esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
          </div>
        </div>
      </div>
    </header>