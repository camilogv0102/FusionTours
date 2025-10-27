<?php
/**
 * Single post template.
 *
 * @package BlankSlate
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		$upload_dir     = wp_upload_dir();
        $default_hero   = trailingslashit( $upload_dir['baseurl'] ) . 'snc-media/faqs.png';
		$hero_image_url = has_post_thumbnail() ? wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' ) : '';
		$hero_image_url = $hero_image_url ? $hero_image_url : $default_hero;

		$categories    = get_the_category();
		$primary_cat   = ! empty( $categories ) ? $categories[0]->name : '';
		$published_on  = get_the_date();
		$author_name   = get_the_author();
		$reading_time  = blankslate_estimated_reading_time( get_post_field( 'post_content', get_the_ID() ) );
		?>

		<section class="single-blog-hero" style="background-image:url('<?php echo esc_url( $hero_image_url ); ?>');">
			<div class="single-blog-hero__overlay"></div>
			<div class="single-blog-hero__inner">
				<?php if ( '' !== $primary_cat ) : ?>
					<span class="single-blog-hero__category"><?php echo esc_html( $primary_cat ); ?></span>
				<?php endif; ?>
				<h1 class="single-blog-hero__title"><?php the_title(); ?></h1>
				<div class="single-blog-hero__meta">
					<span><?php echo esc_html( $published_on ); ?></span>
					<span>•</span>
					<span><?php echo esc_html( $author_name ); ?></span>
					<?php if ( '' !== $reading_time ) : ?>
						<span>•</span>
						<span><?php echo esc_html( $reading_time ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-blog' ); ?>>
			<div class="single-blog__inner">
				<div class="single-blog__content">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before'      => '<div class="single-blog__pages"><span>' . esc_html__( 'Páginas:', 'blankslate' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
						)
					);
					?>
				</div>

				<footer class="single-blog__footer">
					<div class="single-blog__tags">
						<?php the_tags( '<ul><li>', '</li><li>', '</li></ul>' ); ?>
					</div>
				</footer>
			</div>
		</article>

		<section class="single-blog__nav">
			<div class="single-blog__nav-inner">
				<?php get_template_part( 'nav', 'below-single' ); ?>
			</div>
		</section>

		<?php if ( comments_open() || get_comments_number() ) : ?>
			<section class="single-blog__comments">
				<div class="single-blog__comments-inner">
					<?php comments_template(); ?>
				</div>
			</section>
		<?php endif; ?>

	<?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();
