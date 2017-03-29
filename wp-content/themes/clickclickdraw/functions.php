<?php

add_action( 'after_setup_theme', 'ccd_setup' );
function ccd_setup(){
  load_theme_textdomain( 'ccd', get_template_directory() . '/languages' );
  add_theme_support( 'title-tag' );
  add_theme_support( 'automatic-feed-links' );
  add_theme_support( 'post-thumbnails' );
  global $content_width;
  if ( ! isset( $content_width ) ) $content_width = 640;
  register_nav_menus(
    array( 'main-menu' => __( 'Main Menu', 'ccd' ) )
  );
}

add_action( 'wp_footer', 'ccd_load_scripts' );
function ccd_load_scripts(){
  wp_enqueue_script('jquery');
  wp_enqueue_script('main', get_template_directory_uri().'/js/main.js');
}

add_action( 'widgets_init', 'ccd_widgets_init' );
function ccd_widgets_init(){
  register_sidebar(array(
    'name' => __( 'Sidebar Widget Area', 'ccd' ),
    'id' => 'primary-widget-area',
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => "</li>",
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));
}

add_action( 'init', 'add_category_taxonomy' );
function add_category_taxonomy() {
	register_taxonomy_for_object_type( 'category', 'work' );
}

//Post formats
add_theme_support( 'post-formats', array(
  'video',
  'audio',
  'gallery'
));

//Custom post types
function work_content_type() {
  $labels = array(
    'name' => _x( 'Work', 'post type general name' ),
    'singular_name' => _x( 'Work', 'post type singular name' ),
    'add_new' => _x( 'Add New', 'work' ),
    'add_new_item' => __( 'Add New Piece' ),
    'edit_item' => __( 'Edit Piece' ),
    'new_item' => __( 'New Piece' ),
    'all_items' => __( 'All Work' ),
    'search_items' => __( 'Search Work' ),
    'not_found' => __( 'No work found' ),
    'not_found_in_trash' => __( 'No work found in the Trash' ),
    'parent_item_colon' => '',
    'menu_name' => 'Work'
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Holds the work',
    'public' => true,
    'taxonomies' => array('category'),
    'menu_position' => 21,
    'rewrite' => array( 'slug' => 'work', 'with_front' => false ),
    'menu_icon' => 'dashicons-images-alt',
    'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    'has_archive' => true,
  );
  register_post_type( 'work', $args );
}
add_action( 'init', 'work_content_type' );

//Custom admin panel
function my_login_logo() { ?>
    <style type="text/css">
      body.login {}
      @keyframes zoomIn{
        from{
          transform: scale(0) translateY(-50%);;
        }
        to{
          transform: scale(1) translateY(-50%);;
        }
      }
      body.login div#login {
        width: 300px;
        padding: 0;
        position: relative;
        display: table;
        top: 50%;
        transform: translateY(-50%);
        transform-origin: top center;
        animation: zoomIn 0.5s forwards;
      }
      body.login div#login h1 {}
      body.login div#login h1 a {
        background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo_full.png);
        outline: 0;
        border: 0;
        box-shadow: none;
      }
      body.login div#login_error{
        width: 250px;
        margin: 0 20px;
      }
      body.login div#login form#loginform {
        margin-top: 0;
        padding-top: 10px;
      }
      body.interim-login{
        height: inherit!important;
      }
      #wpadminbar .quicklinks li .blavatar{
        display: none;
      }
      body.login div#login form#loginform p {}
      body.login div#login form#loginform p label {}
      body.login div#login form#loginform input {}
      body.login div#login form#loginform input#user_login {}
      body.login div#login form#loginform input#user_pass {}
      body.login div#login form#loginform p.forgetmenot {}
      body.login div#login form#loginform p.forgetmenot input#rememberme:focus {
        border-color: #ff6666;
      }
      body.login div#login form#loginform p.submit {}
      body.login div#login form#loginform p.submit input#wp-submit {}
      body.login #nav a{
        font-size: 12px;
        transition: color 0.2s ease-out
      }
      body.login #nav{
        margin: 0 0 20px;
        padding: 0 20px;
        display: inline-block;
        vertical-align: top;
        float: right;
      }
      body.login #nav a:focus{
        box-shadow: none;
      }
      body.login #nav a:hover{
        color: #ff6666;
      }
      body.login #backtoblog a{
        display: none;
      }
      body.login h1 a{
        width: 145px;
        height: 142px;
        padding: 0;
        background-size: contain;
        background-position: top center;
      }
      body.wp-core-ui{
        background-color: #f5f5f5;
      }
      body.wp-core-ui.login form{
        background: #f5f5f5;
        box-shadow: none;
        padding-bottom: 20px;
      }
      body.wp-core-ui.login input{
        color: #FFF;
        background: #231f20;
        transition: border-color 0.2s ease-out;
      }
      body.wp-core-ui.login input:focus, body.wp-core-ui.login input:active{
        border-color: #ff6666;
      }
      body.wp-core-ui.login input:-webkit-autofill{
        -webkit-box-shadow: 0 0 0px 1000px #FFF inset;
        -webkit-text-fill-color: #000;
      }
      body.wp-core-ui.login input[type=checkbox]{
        background-color: #f5f5f5;
      }
      body.wp-core-ui.login input[type=checkbox]:focus, body.wp-core-ui.login input[type=checkbox]:active{
        box-shadow: none;
      }
      body.wp-core-ui.login input[type=checkbox]:checked:before{
        color: #ff6666;
      }
      body.wp-core-ui .wp-core-ui .button-group.button-large .button, body.wp-core-ui .button.button-large{
        background-color: #f5f5f5;
        font-size: 12px;
        color: #ff6666;
        text-transform: uppercase;
        transition: color 0.2s ease-out, background 0.2s ease-out;
        border-radius: 0;
        box-shadow: none;
        text-shadow: none;
        border: 1px solid #ff6666;
        outline: 0;
        font-weight: 600;
        padding: 0.15em 2em;
        height: auto;
      }
      body.wp-core-ui .wp-core-ui .button-group.button-large .button:hover, body.wp-core-ui .button.button-large:hover{
        background: #ff6666;
        color: #f5f5f5;
      }
      ::selection {
        background: #ff6666;
      }
      ::-moz-selection {
        background: #ff6666;
      }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

add_filter( 'document_title_separator', 'cyb_document_title_separator' );
function cyb_document_title_separator( $sep ) {
    $sep = "|";
    return $sep;
}
