<?php /** Site header. */ ?>
<!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'horn-free-theme' ); ?></a>
<header class="site-header" id="top"><div class="container header-inner">
<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><img class="brand-logo" src="<?php echo esc_url( 'https://www.hornfreeindia.org/wp-content/uploads/2026/09/hornfreeindia-logo.png' ); ?>" alt="" width="98" height="98"></a>
<nav class="nav" aria-label="<?php esc_attr_e( 'Primary', 'horn-free-theme' ); ?>"><button class="nav-toggle" aria-expanded="false" aria-controls="nav-menu"><span class="sr-only"><?php esc_html_e( 'Menu', 'horn-free-theme' ); ?></span><span class="nav-bars" aria-hidden="true"></span></button>
<?php if ( has_nav_menu( 'menu-1' ) ) : wp_nav_menu( array( 'theme_location'=>'menu-1', 'container'=>false, 'menu_class'=>'nav-menu', 'menu_id'=>'nav-menu', 'fallback_cb'=>false ) ); else : ?><ul class="nav-menu" id="nav-menu"><li><a href="#problem">The Problem</a></li><li><a href="#ask">Our Ask</a></li><li><a href="#action">Take Action</a></li><li><a href="#story">Our Story</a></li><li><a href="#spread">Spread the Word</a></li></ul><?php endif; ?></nav>
<div class="header-cta"><span class="header-counter"><strong data-counter data-target="<?php echo esc_attr( horn_free_theme_supporter_count() ); ?>"><?php echo esc_html( number_format_i18n( horn_free_theme_supporter_count() ) ); ?></strong> voices</span><a class="btn btn-primary btn-sm" href="#action"><span class="cta-full">Join the movement</span><span class="cta-short">Join</span></a></div>
</div></header>
