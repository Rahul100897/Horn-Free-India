<?php
/**
 * Horn-Free-Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Horn-Free-Theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.5.7' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function horn_free_theme_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Horn-Free-Theme, use a find and replace
		* to change 'horn-free-theme' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'horn-free-theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'horn-free-theme' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'horn_free_theme_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'horn_free_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function horn_free_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'horn_free_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'horn_free_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function horn_free_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'horn-free-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'horn-free-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'horn_free_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function horn_free_theme_scripts() {
	wp_enqueue_style( 'horn-free-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'horn-free-theme-style', 'rtl', 'replace' );
	wp_enqueue_style( 'horn-free-theme-fonts', 'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..600&family=Inter:wght@400;500;600&family=Noto+Sans+Devanagari:wght@400;600&display=swap', array(), null );
	wp_enqueue_style( 'horn-free-theme-site', get_template_directory_uri() . '/assets/css/site.css', array( 'horn-free-theme-style', 'horn-free-theme-fonts' ), _S_VERSION );

	wp_enqueue_script( 'horn-free-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'horn-free-theme-site', get_template_directory_uri() . '/assets/js/site.js', array(), _S_VERSION, true );
	wp_localize_script( 'horn-free-theme-site', 'hfiSettings', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'hfi_join_movement' ),
		'siteUrl' => home_url( '/' ), 'genericError' => __( 'Something went wrong. Please try again.', 'horn-free-theme' ),
		'duplicateText' => __( 'Your voice was already counted. Your email app will open now.', 'horn-free-theme' ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'horn_free_theme_scripts' );

/** Register private supporter submissions. */
function horn_free_theme_register_supporters() {
	register_post_type( 'hfi_supporter', array(
		'labels' => array( 'name' => __( 'Supporters', 'horn-free-theme' ), 'singular_name' => __( 'Supporter', 'horn-free-theme' ) ),
		'public' => false, 'show_ui' => true, 'menu_icon' => 'dashicons-megaphone', 'supports' => array( 'title' ),
		'exclude_from_search' => true, 'show_in_rest' => false,
	) );
}
add_action( 'init', 'horn_free_theme_register_supporters' );

/** Return the embedded ISO 3166 country/region list for the signup form. */
function horn_free_theme_countries() {
	$encoded = 'eyJBRCI6IkFuZG9ycmEiLCJBRSI6IlVuaXRlZCBBcmFiIEVtaXJhdGVzIiwiQUYiOiJBZmdoYW5pc3RhbiIsIkFHIjoiQW50aWd1YSAmIEJhcmJ1ZGEiLCJBSSI6IkFuZ3VpbGxhIiwiQUwiOiJBbGJhbmlhIiwiQU0iOiJBcm1lbmlhIiwiQU8iOiJBbmdvbGEiLCJBUSI6IkFudGFyY3RpY2EiLCJBUiI6IkFyZ2VudGluYSIsIkFTIjoiU2Ftb2EgKEFtZXJpY2FuKSIsIkFUIjoiQXVzdHJpYSIsIkFVIjoiQXVzdHJhbGlhIiwiQVciOiJBcnViYSIsIkFYIjoiw4VsYW5kIElzbGFuZHMiLCJBWiI6IkF6ZXJiYWlqYW4iLCJCQSI6IkJvc25pYSAmIEhlcnplZ292aW5hIiwiQkIiOiJCYXJiYWRvcyIsIkJEIjoiQmFuZ2xhZGVzaCIsIkJFIjoiQmVsZ2l1bSIsIkJGIjoiQnVya2luYSBGYXNvIiwiQkciOiJCdWxnYXJpYSIsIkJIIjoiQmFocmFpbiIsIkJJIjoiQnVydW5kaSIsIkJKIjoiQmVuaW4iLCJCTCI6IlN0IEJhcnRoZWxlbXkiLCJCTSI6IkJlcm11ZGEiLCJCTiI6IkJydW5laSIsIkJPIjoiQm9saXZpYSIsIkJRIjoiQ2FyaWJiZWFuIE5MIiwiQlIiOiJCcmF6aWwiLCJCUyI6IkJhaGFtYXMiLCJCVCI6IkJodXRhbiIsIkJWIjoiQm91dmV0IElzbGFuZCIsIkJXIjoiQm90c3dhbmEiLCJCWSI6IkJlbGFydXMiLCJCWiI6IkJlbGl6ZSIsIkNBIjoiQ2FuYWRhIiwiQ0MiOiJDb2NvcyAoS2VlbGluZykgSXNsYW5kcyIsIkNEIjoiQ29uZ28gKERlbS4gUmVwLikiLCJDRiI6IkNlbnRyYWwgQWZyaWNhbiBSZXAuIiwiQ0ciOiJDb25nbyAoUmVwLikiLCJDSCI6IlN3aXR6ZXJsYW5kIiwiQ0kiOiJDw7R0ZSBk4oCZSXZvaXJlIiwiQ0siOiJDb29rIElzbGFuZHMiLCJDTCI6IkNoaWxlIiwiQ00iOiJDYW1lcm9vbiIsIkNOIjoiQ2hpbmEiLCJDTyI6IkNvbG9tYmlhIiwiQ1IiOiJDb3N0YSBSaWNhIiwiQ1UiOiJDdWJhIiwiQ1YiOiJDYXBlIFZlcmRlIiwiQ1ciOiJDdXJhw6dhbyIsIkNYIjoiQ2hyaXN0bWFzIElzbGFuZCIsIkNZIjoiQ3lwcnVzIiwiQ1oiOiJDemVjaCBSZXB1YmxpYyIsIkRFIjoiR2VybWFueSIsIkRKIjoiRGppYm91dGkiLCJESyI6IkRlbm1hcmsiLCJETSI6IkRvbWluaWNhIiwiRE8iOiJEb21pbmljYW4gUmVwdWJsaWMiLCJEWiI6IkFsZ2VyaWEiLCJFQyI6IkVjdWFkb3IiLCJFRSI6IkVzdG9uaWEiLCJFRyI6IkVneXB0IiwiRUgiOiJXZXN0ZXJuIFNhaGFyYSIsIkVSIjoiRXJpdHJlYSIsIkVTIjoiU3BhaW4iLCJFVCI6IkV0aGlvcGlhIiwiRkkiOiJGaW5sYW5kIiwiRkoiOiJGaWppIiwiRksiOiJGYWxrbGFuZCBJc2xhbmRzIiwiRk0iOiJNaWNyb25lc2lhIiwiRk8iOiJGYXJvZSBJc2xhbmRzIiwiRlIiOiJGcmFuY2UiLCJHQSI6IkdhYm9uIiwiR0IiOiJCcml0YWluIChVSykiLCJHRCI6IkdyZW5hZGEiLCJHRSI6Ikdlb3JnaWEiLCJHRiI6IkZyZW5jaCBHdWlhbmEiLCJHRyI6Ikd1ZXJuc2V5IiwiR0giOiJHaGFuYSIsIkdJIjoiR2licmFsdGFyIiwiR0wiOiJHcmVlbmxhbmQiLCJHTSI6IkdhbWJpYSIsIkdOIjoiR3VpbmVhIiwiR1AiOiJHdWFkZWxvdXBlIiwiR1EiOiJFcXVhdG9yaWFsIEd1aW5lYSIsIkdSIjoiR3JlZWNlIiwiR1MiOiJTb3V0aCBHZW9yZ2lhICYgdGhlIFNvdXRoIFNhbmR3aWNoIElzbGFuZHMiLCJHVCI6Ikd1YXRlbWFsYSIsIkdVIjoiR3VhbSIsIkdXIjoiR3VpbmVhLUJpc3NhdSIsIkdZIjoiR3V5YW5hIiwiSEsiOiJIb25nIEtvbmciLCJITSI6IkhlYXJkIElzbGFuZCAmIE1jRG9uYWxkIElzbGFuZHMiLCJITiI6IkhvbmR1cmFzIiwiSFIiOiJDcm9hdGlhIiwiSFQiOiJIYWl0aSIsIkhVIjoiSHVuZ2FyeSIsIklEIjoiSW5kb25lc2lhIiwiSUUiOiJJcmVsYW5kIiwiSUwiOiJJc3JhZWwiLCJJTSI6IklzbGUgb2YgTWFuIiwiSU4iOiJJbmRpYSIsIklPIjoiQnJpdGlzaCBJbmRpYW4gT2NlYW4gVGVycml0b3J5IiwiSVEiOiJJcmFxIiwiSVIiOiJJcmFuIiwiSVMiOiJJY2VsYW5kIiwiSVQiOiJJdGFseSIsIkpFIjoiSmVyc2V5IiwiSk0iOiJKYW1haWNhIiwiSk8iOiJKb3JkYW4iLCJKUCI6IkphcGFuIiwiS0UiOiJLZW55YSIsIktHIjoiS3lyZ3l6c3RhbiIsIktIIjoiQ2FtYm9kaWEiLCJLSSI6IktpcmliYXRpIiwiS00iOiJDb21vcm9zIiwiS04iOiJTdCBLaXR0cyAmIE5ldmlzIiwiS1AiOiJLb3JlYSAoTm9ydGgpIiwiS1IiOiJLb3JlYSAoU291dGgpIiwiS1ciOiJLdXdhaXQiLCJLWSI6IkNheW1hbiBJc2xhbmRzIiwiS1oiOiJLYXpha2hzdGFuIiwiTEEiOiJMYW9zIiwiTEIiOiJMZWJhbm9uIiwiTEMiOiJTdCBMdWNpYSIsIkxJIjoiTGllY2h0ZW5zdGVpbiIsIkxLIjoiU3JpIExhbmthIiwiTFIiOiJMaWJlcmlhIiwiTFMiOiJMZXNvdGhvIiwiTFQiOiJMaXRodWFuaWEiLCJMVSI6Ikx1eGVtYm91cmciLCJMViI6IkxhdHZpYSIsIkxZIjoiTGlieWEiLCJNQSI6Ik1vcm9jY28iLCJNQyI6Ik1vbmFjbyIsIk1EIjoiTW9sZG92YSIsIk1FIjoiTW9udGVuZWdybyIsIk1GIjoiU3QgTWFydGluIChGcmVuY2gpIiwiTUciOiJNYWRhZ2FzY2FyIiwiTUgiOiJNYXJzaGFsbCBJc2xhbmRzIiwiTUsiOiJOb3J0aCBNYWNlZG9uaWEiLCJNTCI6Ik1hbGkiLCJNTSI6Ik15YW5tYXIgKEJ1cm1hKSIsIk1OIjoiTW9uZ29saWEiLCJNTyI6Ik1hY2F1IiwiTVAiOiJOb3J0aGVybiBNYXJpYW5hIElzbGFuZHMiLCJNUSI6Ik1hcnRpbmlxdWUiLCJNUiI6Ik1hdXJpdGFuaWEiLCJNUyI6Ik1vbnRzZXJyYXQiLCJNVCI6Ik1hbHRhIiwiTVUiOiJNYXVyaXRpdXMiLCJNViI6Ik1hbGRpdmVzIiwiTVciOiJNYWxhd2kiLCJNWCI6Ik1leGljbyIsIk1ZIjoiTWFsYXlzaWEiLCJNWiI6Ik1vemFtYmlxdWUiLCJOQSI6Ik5hbWliaWEiLCJOQyI6Ik5ldyBDYWxlZG9uaWEiLCJORSI6Ik5pZ2VyIiwiTkYiOiJOb3Jmb2xrIElzbGFuZCIsIk5HIjoiTmlnZXJpYSIsIk5JIjoiTmljYXJhZ3VhIiwiTkwiOiJOZXRoZXJsYW5kcyIsIk5PIjoiTm9yd2F5IiwiTlAiOiJOZXBhbCIsIk5SIjoiTmF1cnUiLCJOVSI6Ik5pdWUiLCJOWiI6Ik5ldyBaZWFsYW5kIiwiT00iOiJPbWFuIiwiUEEiOiJQYW5hbWEiLCJQRSI6IlBlcnUiLCJQRiI6IkZyZW5jaCBQb2x5bmVzaWEiLCJQRyI6IlBhcHVhIE5ldyBHdWluZWEiLCJQSCI6IlBoaWxpcHBpbmVzIiwiUEsiOiJQYWtpc3RhbiIsIlBMIjoiUG9sYW5kIiwiUE0iOiJTdCBQaWVycmUgJiBNaXF1ZWxvbiIsIlBOIjoiUGl0Y2Fpcm4iLCJQUiI6IlB1ZXJ0byBSaWNvIiwiUFMiOiJQYWxlc3RpbmUiLCJQVCI6IlBvcnR1Z2FsIiwiUFciOiJQYWxhdSIsIlBZIjoiUGFyYWd1YXkiLCJRQSI6IlFhdGFyIiwiUkUiOiJSw6l1bmlvbiIsIlJPIjoiUm9tYW5pYSIsIlJTIjoiU2VyYmlhIiwiUlUiOiJSdXNzaWEiLCJSVyI6IlJ3YW5kYSIsIlNBIjoiU2F1ZGkgQXJhYmlhIiwiU0IiOiJTb2xvbW9uIElzbGFuZHMiLCJTQyI6IlNleWNoZWxsZXMiLCJTRCI6IlN1ZGFuIiwiU0UiOiJTd2VkZW4iLCJTRyI6IlNpbmdhcG9yZSIsIlNIIjoiU3QgSGVsZW5hIiwiU0kiOiJTbG92ZW5pYSIsIlNKIjoiU3ZhbGJhcmQgJiBKYW4gTWF5ZW4iLCJTSyI6IlNsb3Zha2lhIiwiU0wiOiJTaWVycmEgTGVvbmUiLCJTTSI6IlNhbiBNYXJpbm8iLCJTTiI6IlNlbmVnYWwiLCJTTyI6IlNvbWFsaWEiLCJTUiI6IlN1cmluYW1lIiwiU1MiOiJTb3V0aCBTdWRhbiIsIlNUIjoiU2FvIFRvbWUgJiBQcmluY2lwZSIsIlNWIjoiRWwgU2FsdmFkb3IiLCJTWCI6IlN0IE1hYXJ0ZW4gKER1dGNoKSIsIlNZIjoiU3lyaWEiLCJTWiI6IkVzd2F0aW5pIChTd2F6aWxhbmQpIiwiVEMiOiJUdXJrcyAmIENhaWNvcyBJcyIsIlREIjoiQ2hhZCIsIlRGIjoiRnJlbmNoIFMuIFRlcnIuIiwiVEciOiJUb2dvIiwiVEgiOiJUaGFpbGFuZCIsIlRKIjoiVGFqaWtpc3RhbiIsIlRLIjoiVG9rZWxhdSIsIlRMIjoiRWFzdCBUaW1vciIsIlRNIjoiVHVya21lbmlzdGFuIiwiVE4iOiJUdW5pc2lhIiwiVE8iOiJUb25nYSIsIlRSIjoiVHVya2V5IiwiVFQiOiJUcmluaWRhZCAmIFRvYmFnbyIsIlRWIjoiVHV2YWx1IiwiVFciOiJUYWl3YW4iLCJUWiI6IlRhbnphbmlhIiwiVUEiOiJVa3JhaW5lIiwiVUciOiJVZ2FuZGEiLCJVTSI6IlVTIG1pbm9yIG91dGx5aW5nIGlzbGFuZHMiLCJVUyI6IlVuaXRlZCBTdGF0ZXMiLCJVWSI6IlVydWd1YXkiLCJVWiI6IlV6YmVraXN0YW4iLCJWQSI6IlZhdGljYW4gQ2l0eSIsIlZDIjoiU3QgVmluY2VudCIsIlZFIjoiVmVuZXp1ZWxhIiwiVkciOiJWaXJnaW4gSXNsYW5kcyAoVUspIiwiVkkiOiJWaXJnaW4gSXNsYW5kcyAoVVMpIiwiVk4iOiJWaWV0bmFtIiwiVlUiOiJWYW51YXR1IiwiV0YiOiJXYWxsaXMgJiBGdXR1bmEiLCJXUyI6IlNhbW9hICh3ZXN0ZXJuKSIsIllFIjoiWWVtZW4iLCJZVCI6Ik1heW90dGUiLCJaQSI6IlNvdXRoIEFmcmljYSIsIlpNIjoiWmFtYmlhIiwiWlciOiJaaW1iYWJ3ZSJ9';
	$countries = json_decode( base64_decode( $encoded ), true );
	return is_array( $countries ) ? $countries : array();
}

/** Make the supporter count auditable in the WordPress dashboard. */
function horn_free_theme_supporter_columns( $columns ) {
	return array(
		'cb' => $columns['cb'], 'title' => __( 'Supporter', 'horn-free-theme' ),
		'hfi_email' => __( 'Email', 'horn-free-theme' ), 'hfi_location' => __( 'State / Country', 'horn-free-theme' ),
		'hfi_counted' => __( 'Counted', 'horn-free-theme' ), 'date' => __( 'Submitted', 'horn-free-theme' ),
	);
}
add_filter( 'manage_hfi_supporter_posts_columns', 'horn_free_theme_supporter_columns' );

function horn_free_theme_supporter_column_value( $column, $post_id ) {
	if ( 'hfi_email' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_hfi_email', true ) );
	} elseif ( 'hfi_location' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_hfi_state', true ) . ', ' . get_post_meta( $post_id, '_hfi_country', true ) );
	} elseif ( 'hfi_counted' === $column ) {
		$counted = '1' === get_post_meta( $post_id, '_hfi_email_clicked', true );
		echo $counted ? esc_html__( 'Yes — email button clicked', 'horn-free-theme' ) : esc_html__( 'Pending', 'horn-free-theme' );
	}
}
add_action( 'manage_hfi_supporter_posts_custom_column', 'horn_free_theme_supporter_column_value', 10, 2 );

function horn_free_theme_supporter_count( $page_id = 0 ) {
	$page_id = $page_id ? absint( $page_id ) : absint( get_option( 'page_on_front' ) );
	$base = function_exists( 'get_field' ) ? absint( get_field( 'movement_starting_count', $page_id ) ) : 0;
	$query = new WP_Query( array(
		'post_type' => 'hfi_supporter', 'post_status' => 'publish', 'posts_per_page' => 1, 'fields' => 'ids',
		'meta_key' => '_hfi_email_clicked', 'meta_value' => '1', 'no_found_rows' => false,
	) );
	return $base + absint( $query->found_posts );
}

/** Save validated details as pending; the count changes only after the email CTA click. */
function horn_free_theme_join_movement() {
	check_ajax_referer( 'hfi_join_movement', 'nonce' );
	$name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$state = isset( $_POST['state'] ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : '';
	$country_code = isset( $_POST['country'] ) ? strtoupper( sanitize_key( wp_unslash( $_POST['country'] ) ) ) : '';
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$countries = horn_free_theme_countries();
	if ( ! $name || ! $state || ! isset( $countries[ $country_code ] ) || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please provide a valid full name, state, country and email.', 'horn-free-theme' ) ), 422 );
	}
	$country = $countries[ $country_code ];
	$existing = get_posts( array(
		'post_type' => 'hfi_supporter', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids',
		'meta_key' => '_hfi_email_hash', 'meta_value' => hash( 'sha256', strtolower( $email ) ),
	) );
	$post_id = $existing ? absint( $existing[0] ) : wp_insert_post( array( 'post_type' => 'hfi_supporter', 'post_status' => 'publish', 'post_title' => $name . ' — ' . $country ), true );
	if ( is_wp_error( $post_id ) ) {
		wp_send_json_error( array( 'message' => __( 'We could not save your support. Please try again.', 'horn-free-theme' ) ), 500 );
	}
	update_post_meta( $post_id, '_hfi_name', $name ); update_post_meta( $post_id, '_hfi_state', $state );
	update_post_meta( $post_id, '_hfi_country', $country ); update_post_meta( $post_id, '_hfi_country_code', $country_code ); update_post_meta( $post_id, '_hfi_email', $email );
	update_post_meta( $post_id, '_hfi_email_hash', hash( 'sha256', strtolower( $email ) ) );
	$token = wp_generate_password( 32, false, false );
	update_post_meta( $post_id, '_hfi_confirmation_hash', hash_hmac( 'sha256', $token, wp_salt( 'auth' ) ) );
	wp_send_json_success( array(
		'count' => horn_free_theme_supporter_count(), 'supporterId' => $post_id, 'token' => $token,
		'confirmed' => '1' === get_post_meta( $post_id, '_hfi_email_clicked', true ),
	) );
}
add_action( 'wp_ajax_hfi_join_movement', 'horn_free_theme_join_movement' );
add_action( 'wp_ajax_nopriv_hfi_join_movement', 'horn_free_theme_join_movement' );

/** Count a supporter once when the personalized Ministry email button is clicked. */
function horn_free_theme_confirm_email_click() {
	check_ajax_referer( 'hfi_join_movement', 'nonce' );
	$post_id = isset( $_POST['supporterId'] ) ? absint( $_POST['supporterId'] ) : 0;
	$token = isset( $_POST['token'] ) ? sanitize_text_field( wp_unslash( $_POST['token'] ) ) : '';
	if ( ! $post_id || 'hfi_supporter' !== get_post_type( $post_id ) || ! $token ) {
		wp_send_json_error( array( 'message' => __( 'Invalid supporter confirmation.', 'horn-free-theme' ) ), 400 );
	}
	$expected = (string) get_post_meta( $post_id, '_hfi_confirmation_hash', true );
	$provided = hash_hmac( 'sha256', $token, wp_salt( 'auth' ) );
	if ( ! $expected || ! hash_equals( $expected, $provided ) ) {
		wp_send_json_error( array( 'message' => __( 'This confirmation link has expired. Please submit your details again.', 'horn-free-theme' ) ), 403 );
	}
	$duplicate = '1' === get_post_meta( $post_id, '_hfi_email_clicked', true );
	if ( ! $duplicate ) {
		update_post_meta( $post_id, '_hfi_email_clicked', '1' );
		update_post_meta( $post_id, '_hfi_email_clicked_at', current_time( 'mysql', true ) );
	}
	delete_post_meta( $post_id, '_hfi_confirmation_hash' );
	wp_send_json_success( array( 'count' => horn_free_theme_supporter_count(), 'duplicate' => $duplicate ) );
}
add_action( 'wp_ajax_hfi_confirm_email_click', 'horn_free_theme_confirm_email_click' );
add_action( 'wp_ajax_nopriv_hfi_confirm_email_click', 'horn_free_theme_confirm_email_click' );

require get_template_directory() . '/inc/acf-fields.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
