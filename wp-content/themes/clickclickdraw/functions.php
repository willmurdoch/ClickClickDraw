<?php

add_action( 'after_setup_theme', 'nll_setup' );
function nll_setup(){
  load_theme_textdomain( 'nll', get_template_directory() . '/languages' );
  add_theme_support( 'title-tag' );
  add_theme_support( 'automatic-feed-links' );
  add_theme_support( 'post-thumbnails' );
  global $content_width;
  if ( ! isset( $content_width ) ) $content_width = 640;
  register_nav_menus(
    array( 'main-menu' => __( 'Main Menu', 'nll' ) )
  );
}

//Remove emoji stuff, since implementation is against W3C specs
function disable_wp_emojicons() {
  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );
function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}
add_filter( 'emoji_svg_url', '__return_false' );


add_action( 'wp_footer', 'nll_load_scripts' );
function nll_load_scripts(){
  wp_enqueue_script('jquery');
  wp_enqueue_script('pointstreak', get_template_directory_uri().'/js/pointstreak.js');
  wp_enqueue_script('ads', get_template_directory_uri().'/js/ads.js');
  wp_enqueue_script('main', get_template_directory_uri().'/js/main.js');
}

add_filter('pre_get_document_title', 'nll_title');
function nll_title() {
  if(is_front_page()){
    return esc_attr(get_bloginfo('name'));
  }
  elseif(is_home()){
    return 'News | '.esc_attr(get_bloginfo('name'));
  }
  elseif(is_page_template('templates/player.php')){
    global $playerJson;
    return $playerJson['firstname'].' '.$playerJson['lastname'].' | '.esc_attr(get_bloginfo('name'));
  }
  elseif(is_page_template('templates/game.php')){
    global $currentGame;
    return $currentGame['away']['mascot'].' at '.$currentGame['home']['mascot'].' | '.esc_attr(get_bloginfo('name'));
  }
}


add_action( 'widgets_init', 'nll_widgets_init' );
function nll_widgets_init(){
  register_sidebar(array(
    'name' => __( 'Sidebar Widget Area', 'nll' ),
    'id' => 'primary-widget-area',
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => "</li>",
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));
}

//Add tags to pages
// add tag support to pages
function tags_support_all() {
	register_taxonomy_for_object_type('post_tag', 'page');
}

// ensure all tags are included in queries
function tags_support_query($wp_query) {
	if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
}

// tag hooks
add_action('init', 'tags_support_all');
add_action('pre_get_posts', 'tags_support_query');

//Post formats
add_theme_support( 'post-formats', array(
  'video',
  'audio',
  'gallery'
));

//Custom Taxonomies
add_action( 'init', 'create_team_taxonomy', 0 );
function create_team_taxonomy() {
  $labels = array(
    'name' => _x( 'Team Tags', 'taxonomy general name' ),
    'singular_name' => _x( 'Team', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Teams' ),
    'all_items' => __( 'All Teams' ),
    'edit_item' => __( 'Edit Team' ),
    'update_item' => __( 'Update Team' ),
    'add_new_item' => __( 'Add New Team' ),
    'new_item_name' => __( 'New Team Name' ),
    'menu_name' => __( 'Team Tags' )
  );
  register_taxonomy('teams',array('post'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'team' )
  ));
}
add_action( 'init', 'create_season_taxonomy', 0 );
function create_season_taxonomy() {
  $labels = array(
    'name' => _x( 'Season Tags', 'taxonomy general name' ),
    'singular_name' => _x( 'Season', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Seasons' ),
    'all_items' => __( 'All Seasons' ),
    'edit_item' => __( 'Edit Season' ),
    'update_item' => __( 'Update Season' ),
    'add_new_item' => __( 'Add New Season' ),
    'new_item_name' => __( 'New Season Name' ),
    'menu_name' => __( 'Season Tags' )
  );
  register_taxonomy('seasons',array('post'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'season' )
  ));
}

//Custom post types
function player_content_type() {
  $labels = array(
    'name' => _x( 'Players', 'post type general name' ),
    'singular_name' => _x( 'Player', 'post type singular name' ),
    'add_new' => _x( 'Add New', 'book' ),
    'add_new_item' => __( 'Add New Player' ),
    'edit_item' => __( 'Edit Player' ),
    'new_item' => __( 'New Player' ),
    'all_items' => __( 'All Players' ),
    'search_items' => __( 'Search Players' ),
    'not_found' => __( 'No players found' ),
    'not_found_in_trash' => __( 'No players found in the Trash' ),
    'parent_item_colon' => '',
    'menu_name' => 'Players'
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Holds our players',
    'public' => true,
    'menu_position' => 21,
    'menu_icon' => 'dashicons-universal-access',
    'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    'has_archive' => false,
  );
  register_post_type( 'player', $args );
}
add_action( 'init', 'player_content_type' );

function team_content_type() {
  $labels = array(
    'name' => _x( 'Teams', 'post type general name' ),
    'singular_name' => _x( 'Team', 'post type singular name' ),
    'add_new' => _x( 'Add New', 'book' ),
    'add_new_item' => __( 'Add New Team' ),
    'edit_item' => __( 'Edit Team' ),
    'new_item' => __( 'New Team' ),
    'all_items' => __( 'All Teams' ),
    'search_items' => __( 'Search Teams' ),
    'not_found' => __( 'No teams found' ),
    'not_found_in_trash' => __( 'No teams found in the Trash' ),
    'parent_item_colon' => '',
    'menu_name' => 'Teams'
  );
  $args = array(
    'labels' => $labels,
    'rewrite' => array( 'slug' => 'teams', 'with_front' => false ),
    'description' => 'Holds our teams',
    'public' => true,
    'menu_position' => 22,
    'menu_icon' => 'dashicons-groups',
    'supports' => array( 'title' ),
    'has_archive' => false,
  );
  register_post_type( 'team', $args );
}
add_action( 'init', 'team_content_type' );

function ad_content_type() {
  $labels = array(
    'name' => _x( 'Advertisements', 'post type general name' ),
    'singular_name' => _x( 'Advertisement', 'post type singular name' ),
    'add_new' => _x( 'Add New', 'book' ),
    'add_new_item' => __( 'Add New Advertisement' ),
    'edit_item' => __( 'Edit Advertisement' ),
    'new_item' => __( 'New Advertisement' ),
    'all_items' => __( 'All Ads' ),
    'search_items' => __( 'Search Advertisements' ),
    'not_found' => __( 'No advertisements found' ),
    'not_found_in_trash' => __( 'No advertisements found in the Trash' ),
    'parent_item_colon' => '',
    'menu_name' => 'Ads'
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Holds our advertisements',
    'public' => true,
    'publicly_queryable'  => false,
    'menu_position' => 23,
    'menu_icon' => 'dashicons-editor-contract',
    'supports' => array( 'title' ),
    'has_archive' => false,
  );
  register_post_type( 'advertisement', $args );
}
add_action( 'init', 'ad_content_type' );

function promo_content_type() {
  $labels = array(
    'name' => _x( 'Promotions', 'post type general name' ),
    'singular_name' => _x( 'Promotion', 'post type singular name' ),
    'add_new' => _x( 'Add New', 'book' ),
    'add_new_item' => __( 'Add New Promotion' ),
    'edit_item' => __( 'Edit Promotion' ),
    'new_item' => __( 'New Promotion' ),
    'all_items' => __( 'All Promos' ),
    'search_items' => __( 'Search Promotions' ),
    'not_found' => __( 'No promotions found' ),
    'not_found_in_trash' => __( 'No promotions found in the Trash' ),
    'parent_item_colon' => '',
    'menu_name' => 'Promos'
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Holds our promotions',
    'public' => true,
    'exclude_from_search' => true,
    'publicly_queryable'  => false,
    'menu_position' => 24,
    'menu_icon' => 'dashicons-align-left',
    'supports' => array( 'title' ),
    'has_archive' => false,
  );
  register_post_type( 'promotion', $args );
}
add_action( 'init', 'promo_content_type' );

function event_content_type() {
  $labels = array(
    'name' => _x( 'Events', 'post type general name' ),
    'singular_name' => _x( 'Event', 'post type singular name' ),
    'add_new' => _x( 'Add New', 'Event' ),
    'add_new_item' => __( 'Add New Event' ),
    'edit_item' => __( 'Edit Event' ),
    'new_item' => __( 'New Event' ),
    'all_items' => __( 'All Events' ),
    'search_items' => __( 'Search Events' ),
    'not_found' => __( 'No events found' ),
    'not_found_in_trash' => __( 'No events found in the Trash' ),
    'parent_item_colon' => '',
    'menu_name' => 'Events'
  );
  $args = array(
    'labels' => $labels,
    'rewrite' => array( 'slug' => 'events', 'with_front' => false ),
    'description' => 'Holds our events',
    'public' => true,
    'menu_position' => 15,
    'menu_icon' => 'dashicons-tickets-alt',
    'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    'has_archive' => false,
  );
  register_post_type( 'event', $args );
}
add_action( 'init', 'event_content_type' );

function gallery_content_type() {
  $labels = array(
    'name' => _x( 'Galleries', 'post type general name' ),
    'singular_name' => _x( 'Gallery', 'post type singular name' ),
    'add_new' => _x( 'Add New', 'gallery' ),
    'add_new_item' => __( 'Add New Gallery' ),
    'edit_item' => __( 'Edit Gallery' ),
    'new_item' => __( 'New Gallery' ),
    'all_items' => __( 'All Galleries' ),
    'search_items' => __( 'Search Galleries' ),
    'not_found' => __( 'No galleries found' ),
    'not_found_in_trash' => __( 'No galleries found in the Trash' ),
    'parent_item_colon' => '',
    'menu_name' => 'Galleries'
  );
  $args = array(
    'labels' => $labels,
    'rewrite' => array( 'slug' => 'gallery', 'with_front' => false ),
    'description' => 'Holds our galleries',
    'public' => true,
    'menu_position' => 15,
    'menu_icon' => 'dashicons-images-alt',
    'supports' => array( 'title' ),
    'has_archive' => false,
  );
  register_post_type( 'gallery', $args );
}
add_action( 'init', 'gallery_content_type' );

//Custom column for Galleries
add_filter( 'manage_edit-gallery_columns', 'add_id_column', 5 );
add_action( 'manage_posts_custom_column', 'id_column_content', 5, 2 );
function add_id_column( $columns ) {
   $columns['nll_id'] = 'Shortcode';
   return $columns;
}
function id_column_content( $column, $id ) {
  if( 'nll_id' == $column ) {
    echo '<input type="text" value="[nll_gallery id='.$id.']" readonly="readonly">';
  }
}


//Custom shortcodes
add_shortcode('nll_gallery', 'nll_gallery_query');
function nll_gallery_query($atts){
   $args = array(
     'post_count' => 1,
     'post_id' => $atts['id'],
     'post_type' => 'gallery'
   );
   $myGallery = get_posts($args);
   foreach($myGallery as $gallery){
     if(have_rows('images', $atts['id'])):
       $x = 0;
       $out = '<div class="gallery-wrap"><div class="gallery">';
       while ( have_rows('images', $atts['id']) ) : the_row();
        if($x == 0) $out .= '<div class="slide current"><img src="'.get_sub_field('image').'" alt="" />';
        else $out .= '<div class="slide"><img src="'.get_sub_field('image').'" alt="" />';
        if(get_sub_field('attribution') != '') $out .= '<p>'.get_sub_field('attribution').'</p>';
        $out .= '</div>';
        $x++;
       endwhile;
       $out .= '</div>';
     endif;
     $out .= '<div class="gallery-nav">';
     for($i = 0; $i < $x; $i++){
      if ($i == 0) $out .= '<div class="nav-dot current"></div>';
      else $out .= '<div class="nav-dot"></div>';
     }
     $out .= '</div>';
     $out .= '<div class="arrow lArrow">&lsaquo;</div><div class="arrow rArrow">&rsaquo;</div></div>';
   }
   return $out;
}

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
        background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/header/nll_logo.png);
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
        border-color: #8e292e;
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
        color: #8e292e;
      }
      body.login #backtoblog a{
        display: none;
      }
      body.login h1 a{
        width: 106px;
        height: 142px;
        padding: 0;
        background-size: contain;
        background-position: top center;
      }
      body.wp-core-ui{
        background-color: #231f20;
      }
      body.wp-core-ui.login form{
        background: #231f20;
        box-shadow: none;
        padding-bottom: 20px;
      }
      body.wp-core-ui.login input{
        color: #FFF;
        background: #231f20;
        transition: border-color 0.2s ease-out;
      }
      body.wp-core-ui.login input:focus, body.wp-core-ui.login input:active{
        border-color: #8e292e;
      }
      body.wp-core-ui.login input:-webkit-autofill{
        -webkit-box-shadow: 0 0 0px 1000px #231f20 inset;
        -webkit-text-fill-color: #FFF;
      }
      body.wp-core-ui.login input[type=checkbox]{
        background-color: #231f20;
      }
      body.wp-core-ui.login input[type=checkbox]:focus, body.wp-core-ui.login input[type=checkbox]:active{
        box-shadow: none;
      }
      body.wp-core-ui.login input[type=checkbox]:checked:before{
        color: #8e292e;
      }
      body.wp-core-ui .wp-core-ui .button-group.button-large .button, body.wp-core-ui .button.button-large{
        background-color: #8e292e;
        font-size: 12px;
        color: #FFF;
        text-transform: uppercase;
        transition: color 0.3s ease-out, background 0.3s ease-out;
        border-radius: 0;
        box-shadow: none;
        text-shadow: none;
        border: 0;
        outline: 0;
        font-weight: 600;
        padding: 0.15em 2em;
        height: auto;
      }
      ::selection {
        background: #8e292e;
      }
      ::-moz-selection {
        background: #8e292e;
      }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

//Pre-populate new player pages
add_filter( 'default_title', 'my_editor_title' );
function my_editor_title($content){
  if(isset($_GET['firstname']) && isset($_GET['lastname'])){
    $content = $_GET['firstname'].' '.$_GET['lastname'];
    return $content;
  }
}

add_filter( 'document_title_separator', 'cyb_document_title_separator' );
function cyb_document_title_separator( $sep ) {
    $sep = "|";
    return $sep;
}

add_filter('acf/prepare_field/name=player_id', 'my_acf_prepare_field');
function my_acf_prepare_field($field) {
  if($_GET['playerID'])
	 $field['value'] = $_GET['playerID'];
   return $field;
}

//Get server-relative file path
function getPath(){
  $path = getenv('HTTP_HOST');
  if($path == 'localhost'){
    $path = realpath(dirname(__FILE__).'/..').'\pointstreak\/';
  }
  else{
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
      $protocol  = "https://";
    else
      $protocol  = "http://";
    $path = $protocol.$path.'/wp-content/themes/nll/inc/pointstreak/';
  }
  return $path;
}

//Fetch data from URL if not on localhost
function get_data($url) {
 $ch = curl_init();
 $timeout = 5;
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
 $data = curl_exec($ch);
 curl_close($ch);
 return json_decode($data,true);
}

remove_action('template_redirect', 'redirect_canonical');

add_action( 'after_setup_theme', 'wpdocs_theme_setup' );
function wpdocs_theme_setup() {
    add_image_size( 'logo-thumb', 100, 100, true ); // 300 pixels wide (and unlimited height)
}

class MySettingsPage{
  private $options;
  public function __construct(){
    add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'page_init' ) );
  }
  public function add_plugin_page(){
    // This page will be under "Settings"
    add_options_page(
        'League Admin',
        'League',
        'manage_options',
        'league-admin',
        array($this, 'create_admin_page')
    );
  }
  public function create_admin_page(){
    // Set class property
    $this->options = get_option('league_options');
    ?>
    <div class="wrap">
        <h1>League Settings</h1>
        <form method="post" action="options.php">
        <?php
            // This prints out all hidden setting fields
            settings_fields( 'my_option_group' );
            do_settings_sections( 'my-setting-admin' );
            submit_button();
        ?>
        </form>
    </div>
    <?php
  }
  public function page_init(){
    register_setting(
      'my_option_group', // Option group
      'league_options' // Option name
    );
    add_settings_section(
      'league_section', // ID
      '', // Title
      '', // Callback
      'my-setting-admin' // Page
    );
    add_settings_field(
      'season_id', // ID
      'Season ID', // Title
      array( $this, 'season_id_callback' ), // Callback
      'my-setting-admin', // Page
      'league_section' // Section
    );
    add_settings_field(
      'enable_schedule',
      'Enable Schedule',
      array( $this, 'schedule_callback' ),
      'my-setting-admin',
      'league_section'
    );
    add_settings_field(
      'enable_standings',
      'Enable Standings',
      array( $this, 'standings_callback' ),
      'my-setting-admin',
      'league_section'
    );
    add_settings_field(
      'schedule_sponsor',
      'Enable Schedule Sponsor',
      array( $this, 'sponsor_callback' ),
      'my-setting-admin',
      'league_section'
    );
    add_settings_field(
      'sponsor_url',
      'Sponsor URL',
      array( $this, 'sponsor_url_callback' ),
      'my-setting-admin',
      'league_section'
    );
    add_settings_field(
      'sponsor_logo',
      'Sponsor Logo',
      array( $this, 'sponsor_logo_callback' ),
      'my-setting-admin',
      'league_section'
    );
    add_settings_field(
      'sponsor_color',
      'Sponsor Backdrop',
      array( $this, 'sponsor_color_callback' ),
      'my-setting-admin',
      'league_section'
    );
    add_settings_field(
      'featured_game',
      'Featured Game',
      array( $this, 'featured_game_callback' ),
      'my-setting-admin',
      'league_section'
    );
  }
  public function season_id_callback(){
    //echo 's: '.$this->options['season_id'].' ';
    $scheduleSeasons = file_get_contents(get_template_directory_uri().'/inc/pointstreak/json/seasonList.json');
    $allSeasons = json_decode($scheduleSeasons,true);
    echo '<select name="league_options[season_id]">';
    foreach($allSeasons as $season){
  	?>
      <option value="<?php echo $season['id']; ?>" <?php if($this->options['season_id'] == $season['id']) echo 'selected="selected"'; ?>><?php echo $season['id'].' | '.$season['name']; ?></option>
    <?php }
    echo '</select>';
  }
  public function schedule_callback(){
    if(isset($this->options['enable_schedule'])){
      echo '<input type="checkbox" id="enable_schedule" name="league_options[enable_schedule]" checked="checked" value="1">';
    }
    else{
      echo '<input type="checkbox" id="enable_schedule" name="league_options[enable_schedule]" value="1">';
    }
  }
  public function standings_callback(){
    if(isset($this->options['enable_standings'])){
      echo '<input type="checkbox" id="enable_standings" name="league_options[enable_standings]" checked="checked" value="1">';
    }
    else{
      echo '<input type="checkbox" id="enable_standings" name="league_options[enable_standings]" value="1">';
    }
  }
  public function sponsor_callback(){
    if(isset($this->options['enable_sponsor'])){
      echo '<input type="checkbox" id="enable_sponsor" name="league_options[enable_sponsor]" checked="checked" value="1">';
    }
    else{
      echo '<input type="checkbox" id="enable_sponsor" name="league_options[enable_sponsor]" value="1">';
    }
  }
  public function sponsor_url_callback(){
    printf(
        '<input type="url" id="sponsor_url" name="league_options[sponsor_url]" value="%s" />',
        isset( $this->options['sponsor_url'] ) ? esc_attr( $this->options['sponsor_url']) : ''
    );
  }
  public function sponsor_logo_callback(){
    printf(
      '<input type="url" id="sponsor_logo" name="league_options[sponsor_logo]" value="%s" />',
      isset( $this->options['sponsor_logo'] ) ? esc_attr( $this->options['sponsor_logo']) : ''
    );
  }
  public function sponsor_color_callback(){ ?>
    <style>
      input[type="color"]{
        height: 45px;
      }
    </style><?php
    printf(
      '<input type="color" id="sponsor_color" name="league_options[sponsor_color]" value="%s" />',
      isset( $this->options['sponsor_color'] ) ? esc_attr( $this->options['sponsor_color']) : ''
    );
  }
  public function featured_game_callback(){
    printf(
        '<input type="text" id="featured_game" name="league_options[featured_game]" value="%s" />',
        isset( $this->options['featured_game'] ) ? esc_attr( $this->options['featured_game']) : ''
    );
  }
}
if(is_admin()) $my_settings_page = new MySettingsPage();

//Get submenus for nav
add_filter( 'wp_nav_menu_objects', 'submenu_limit', 10, 2 );
function submenu_limit($items, $args){
  if(empty($args->submenu))
    return $items;

  $myArray = wp_filter_object_list($items, array('title' => $args->submenu), 'and', 'ID');
  $parent_id = array_pop($myArray);
  $children  = submenu_get_children_ids($parent_id, $items);
  if(count($children) > 0){
    foreach ($items as $key => $item) {
      if(!in_array($item->ID, $children))
      unset($items[$key]);
    }
    return $items;
  }
}
function submenu_get_children_ids($id, $items){
  $ids = wp_filter_object_list($items, array( 'menu_item_parent' => $id ), 'and', 'ID');
  if(isset($ids)){
    foreach($ids as $id){
      $ids = array_merge( $ids, submenu_get_children_ids( $id, $items ) );
    }
    return $ids;
  }
}

//Don't show shortcodes in previews
function wpdocs_remove_shortcode_from_index( $content ) {
  if(is_home() || is_search() || is_archive()){
    $content = strip_shortcodes($content);
  }
  return $content;
}
add_filter('the_content', 'wpdocs_remove_shortcode_from_index');

//Exclude data pages from search results
add_filter('pre_get_posts', 'search_exc_pages');
function search_exc_pages($query){
  if($query->is_search){
    $query->set('post__not_in' , array(6183));
  }
  return $query;
}
