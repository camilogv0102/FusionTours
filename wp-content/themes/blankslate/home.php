<?php
/**
 * Posts index template (Blog page).
 *
 * @package BlankSlate
 */

get_header();

$upload_dir      = wp_upload_dir();
$hero_image_url  = trailingslashit( $upload_dir['baseurl'] ) . 'snc-media/faqs.png';
$fallback_thumb  = trailingslashit( $upload_dir['baseurl'] ) . 'snc-media/contact.png';
$posts_page_id   = (int) get_option( 'page_for_posts' );
$page_title      = $posts_page_id ? get_the_title( $posts_page_id ) : __( 'Blog', 'blankslate' );
$page_excerpt    = $posts_page_id ? get_post_field( 'post_excerpt', $posts_page_id ) : '';
$hero_description = '' !== trim( $page_excerpt ) ? wp_kses_post( wpautop( $page_excerpt ) ) : '';
?>

<section class="blog-archive-hero" style="background-image:url('<?php echo esc_url( $hero_image_url ); ?>');">
	<div class="blog-archive-hero__overlay"></div>
	<div class="blog-archive-hero__inner">
		<h1 class="blog-archive-hero__title"><?php echo esc_html( $page_title ); ?></h1>
		<?php if ( '' !== $hero_description ) : ?>
			<div class="blog-archive-hero__description">
				<?php echo $hero_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<main class="blog-archive">
	<div class="blog-archive__inner">
		<?php if ( have_posts() ) : ?>
			<div class="blog-archive__grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$thumb_id  = get_post_thumbnail_id();
					$image_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'large' ) : '';
					$image_url = $image_url ? $image_url : $fallback_thumb;
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card' ); ?>>
						<a class="blog-card__link" href="<?php the_permalink(); ?>">
							<div class="blog-card__media" style="background-image:url('<?php echo esc_url( $image_url ); ?>');"></div>
							<div class="blog-card__content">
								<h2 class="blog-card__title"><?php the_title(); ?></h2>
								<p class="blog-card__excerpt">
									<?php
									if ( has_excerpt() ) {
										echo esc_html( get_the_excerpt() );
									} else {
										echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 20, '…' ) );
									}
									?>
								</p>
							</div>
						</a>
					</article>
				<?php endwhile; ?>
			</div>

			<nav class="blog-archive__pagination">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 2,
						'prev_text' => __( 'Anterior', 'blankslate' ),
						'next_text' => __( 'Siguiente', 'blankslate' ),
					)
				);
				?>
			</nav>
		<?php else : ?>
			<p class="blog-archive__empty"><?php esc_html_e( 'No hay entradas disponibles en este momento.', 'blankslate' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
