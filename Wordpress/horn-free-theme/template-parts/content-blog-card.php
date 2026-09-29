<?php
/** Reusable blog card. */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card' ); ?>>
	<a class="blog-card-image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read %s', 'horn-free-theme' ), get_the_title() ) ); ?>">
		<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); else : ?><span class="blog-card-placeholder" aria-hidden="true">ॐ</span><?php endif; ?>
	</a>
	<div class="blog-card-body">
		<div class="blog-meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time><span aria-hidden="true">&middot;</span><span><?php echo esc_html( horn_free_theme_reading_time() ); ?></span></div>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25 ) ); ?></p>
		<a class="blog-read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read story', 'horn-free-theme' ); ?> <span aria-hidden="true">&rarr;</span></a>
	</div>
</article>
