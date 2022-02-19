<?php

require get_theme_file_path('/inc/search-route.php');

function pageBanner($args = NULL) 
{
  
    if (!isset($args['title'])) {
      $args['title'] = get_the_title();
    }
  
    if (!isset($args['subtitle'])) {
      $args['subtitle'] = get_field('page_banner_subtitle');
    }
  
    if (!isset($args['photo'])) {
      if (get_field('page_banner_background_image')) {
        $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
      } else {
        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
      }
    }
  
    ?>
    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $args['subtitle']; ?></p>
        </div>
      </div>  
    </div>

    
  <?php }



function university_custom_rest()
{
    register_rest_field('post', 'authorName', array(
        'get_callback' => function() {return get_the_author(); }
    ));
}

add_action('rest_api_init', 'university_custom_rest');

function university_files()
{
    wp_enqueue_style('custom-google-fonts','https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyCGmtu3gSxnk8dpJfuVlLacoJEZDZYipoE', NULL, '1.0', true);

    // wp_enqueue_script('main-university-search-js', get_template_directory_uri() .'/js/modules/search.js');
    // wp_enqueue_script( 'main-university-js', get_template_directory_uri() . '/js/scripts.js', array(), '1.0.0', true );
    // wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.8e0130949977e3a36265.css'));
    if(strstr($_SERVER['SERVER_NAME'], 'http://localhost/flame/wordpress/'))
    {
        wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
    }
    else
    {
        wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.6f2a199eeaa958cb8356.js'), NULL, '1.0', true);
        wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.8e0130949977e3a36265.js'), NULL, '1.0', true);
        wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.8e0130949977e3a36265.css'));
    }


    wp_localize_script('main-university-js', 'universityData', array(
        'root_url' => get_site_url()
    ) );
}


add_action('wp_enqueue_scripts','university_files');

function university_features()
{

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
    register_nav_menus( array(
        'primary_menu' => __( 'Primary Menu', 'text_domain' ),
        'footer_menu_one'  => __( 'Footer Menu One', 'text_domain' ),
        'footer_menu_two'  => __( 'Footer Menu Two', 'text_domain' ),
    ) );
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query)
{

    if (!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
      $query->set('posts_per_page', -1);
    }

    if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
      }

    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query())
    {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }
}

add_action('pre_get_posts','university_adjust_queries');


function universityMapKey($api) {
    $api['key'] = 'AIzaSyCGmtu3gSxnk8dpJfuVlLacoJEZDZYipoE';
    return $api;
  }
  
  add_filter('acf/fields/google_map/api', 'universityMapKey');

?>


<?php

//Redirect subscriber accounts out of admin and onto homepage

add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend()
{
  $ourCurrentUser = wp_get_current_user();

  if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') 
  {
    wp_redirect(site_url('/'));
    exit;
  }
}


add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar()
{
  $ourCurrentUser = wp_get_current_user();

  if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') 
  {
    show_admin_bar(false);
  }
}


// Customize Login Screen

add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl()
{
  return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS()
{
  wp_enqueue_style('custom-google-fonts','https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.8e0130949977e3a36265.css'));
}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle()
{
  return get_bloginfo('name');
}




?>