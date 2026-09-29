<?php
/** Main blog listing page template. */
get_header();
?>
<main id="main" class="blog-index">
	<header class="blog-index-hero section-tint"><div class="container narrow"><p class="eyebrow"><?php esc_html_e( 'Horn Free India Journal', 'horn-free-theme' ); ?></p><h1><?php echo esc_html( single_post_title( '', false ) ?: __( 'Stories for quieter roads', 'horn-free-theme' ) ); ?></h1><p class="blog-index-intro"><?php esc_html_e( 'Ideas, evidence and voices behind the movement to make India’s roads calmer, safer and healthier.', 'horn-free-theme' ); ?></p></div></header>
	<section class="section"><div class="container">
	<?php if ( have_posts() ) : ?><div class="blog-grid"><?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/content', 'blog-card' ); endwhile; ?></div><?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '&larr; ' . __( 'Previous', 'horn-free-theme' ), 'next_text' => __( 'Next', 'horn-free-theme' ) . ' &rarr;' ) ); ?><?php else : get_template_part( 'template-parts/content', 'none' ); endif; ?>
	</div></section>
</main>
<?php get_footer(); ?>
