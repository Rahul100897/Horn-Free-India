<?php
/** Single blog post template. */
get_header();
?>
<main id="main" class="blog-single">
<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-article' ); ?>>
		<header class="blog-single-header section-tint">
			<div class="container blog-single-header-shell">
				<a class="blog-back" href="<?php echo esc_url( horn_free_theme_blog_url() ); ?>">&larr; <?php esc_html_e( 'All stories', 'horn-free-theme' ); ?></a>
				<div class="blog-reading-width blog-single-title">
					<p class="eyebrow"><?php esc_html_e( 'Horn Free India Journal', 'horn-free-theme' ); ?></p>
					<h1><?php the_title(); ?></h1>
					<div class="blog-meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time><span aria-hidden="true">&middot;</span><span><?php echo esc_html( horn_free_theme_reading_time() ); ?></span></div>
					<?php
					$share_url   = get_permalink();
					$share_title = wp_strip_all_tags( get_the_title() );
					?>
					<nav class="blog-share" aria-label="<?php esc_attr_e( 'Share this story', 'horn-free-theme' ); ?>">
						<span><?php esc_html_e( 'Share', 'horn-free-theme' ); ?></span>
						<a href="<?php echo esc_url( 'https://wa.me/?text=' . rawurlencode( $share_title . ' ' . $share_url ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'WhatsApp', 'horn-free-theme' ); ?></a>
						<a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $share_url ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Facebook', 'horn-free-theme' ); ?></a>
						<a href="<?php echo esc_url( 'https://twitter.com/intent/tweet?text=' . rawurlencode( $share_title ) . '&url=' . rawurlencode( $share_url ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'X', 'horn-free-theme' ); ?></a>
						<button type="button" data-blog-copy data-copy-url="<?php echo esc_url( $share_url ); ?>"><?php esc_html_e( 'Copy link', 'horn-free-theme' ); ?></button>
						<span class="blog-share-status" data-blog-share-status role="status" aria-live="polite"></span>
					</nav>
				</div>
			</div>
		</header>
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="container blog-featured-image"><?php the_post_thumbnail( 'full', array( 'loading' => 'eager' ) ); ?></figure>
		<?php endif; ?>
		<div class="container blog-reading-width blog-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div>
	</article>

	<section class="blog-post-cta section-dark">
		<div class="container narrow">
			<p class="eyebrow"><?php esc_html_e( 'Turn reading into action', 'horn-free-theme' ); ?></p>
			<h2><?php esc_html_e( 'Add your voice for quieter roads.', 'horn-free-theme' ); ?></h2>
			<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/#action' ) ); ?>"><?php esc_html_e( 'Join the movement', 'horn-free-theme' ); ?></a>
		</div>
	</section>

	<?php
	$related = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'ignore_sticky_posts' => true,
		)
	);
	if ( $related->have_posts() ) :
		?>
		<section class="section blog-related"><div class="container"><div class="blog-section-heading"><div><p class="eyebrow"><?php esc_html_e( 'Keep reading', 'horn-free-theme' ); ?></p><h2><?php esc_html_e( 'More from the movement', 'horn-free-theme' ); ?></h2></div></div><div class="blog-grid">
		<?php while ( $related->have_posts() ) : $related->the_post(); get_template_part( 'template-parts/content', 'blog-card' ); endwhile; ?>
		</div></div></section>
	<?php endif; wp_reset_postdata(); ?>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
