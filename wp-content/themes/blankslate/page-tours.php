<?php
/**
 * Template Name: Tours (Lovable PLP)
 *
 * Reproduces the Lovable tours listing layout as static markup.
 *
 * @package BlankSlate
 */

defined( 'ABSPATH' ) || exit;

$assets_dir = trailingslashit( ABSPATH . 'FusionToursLovable/dist/assets' );
$css_files  = glob( $assets_dir . 'index-*.css' );

$dist_url   = home_url( trailingslashit( 'FusionToursLovable/dist' ) );
$assets_url = $dist_url . 'assets/';
$fonts_url  = $dist_url . 'fonts/';
$images_url = $dist_url . 'images/';

$css_content = '';
if ( ! empty( $css_files ) ) {
	$css_raw = file_get_contents( $css_files[0] );
	if ( false !== $css_raw ) {
		$css_content = str_replace(
			array( '/assets/', '/fonts/', '/images/' ),
			array( $assets_url, $fonts_url, $images_url ),
			$css_raw
		);
	}
}

get_header();

if ( $css_content ) :
	?>
	<style><?php echo $css_content; ?></style>
	<?php
endif;
?>

<main class="min-h-screen bg-white">
	<section class="relative h-screen overflow-hidden">
		<div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo esc_url( $images_url . 'hero-tours.jpg' ); ?>');">
			<div class="absolute inset-0 bg-black/40"></div>
		</div>
		<div class="relative z-10 h-full max-w-7xl mx-auto px-20 max-md:px-10 max-sm:px-5">
			<div class="absolute bottom-8 left-20 max-md:left-10 max-sm:left-5">
				<h1 class="text-[120px] leading-none font-bold text-white max-md:text-8xl max-sm:text-6xl">TOURS</h1>
			</div>
			<div class="absolute bottom-8 right-20 max-w-xs text-right max-md:right-10 max-sm:right-5 max-sm:max-w-[200px]">
				<p class="text-base text-white leading-relaxed max-sm:text-sm">
					Desde ruinas mayas hasta islas paradisíacas, elige la próxima aventura con Fusion Tours Riviera Maya
				</p>
			</div>
		</div>
	</section>

	<section class="max-w-7xl mx-auto px-20 py-12 relative z-20 max-md:px-10 max-sm:px-5">
		<div class="flex items-center gap-4 max-md:flex-col">
			<div class="flex-1 flex items-center gap-3 bg-[#E1EAF1] border-2 border-[#0070C0] rounded-full px-6 py-4 max-md:w-full">
				<svg class="text-[#0070C0]" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M21 21L16.657 16.657M16.657 16.657C17.3998 15.9141 17.9891 15.0322 18.3912 14.0615C18.7932 13.0909 19.0002 12.0506 19.0002 11C19.0002 9.9494 18.7932 8.90908 18.3912 7.93845C17.9891 6.96782 17.3998 6.08589 16.657 5.343C15.9141 4.60011 15.0321 4.01082 14.0615 3.60877C13.0909 3.20673 12.0506 2.99979 11 2.99979C9.94936 2.99979 8.90905 3.20673 7.93842 3.60877C6.96779 4.01082 6.08585 4.60011 5.34296 5.343C3.84263 6.84333 2.99976 8.87821 2.99976 11C2.99976 13.1218 3.84263 15.1567 5.34296 16.657C6.84329 18.1573 8.87818 19.0002 11 19.0002C13.1217 19.0002 15.1566 18.1573 16.657 16.657Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<input type="text" placeholder="BUSCA TU PRÓXIMA EXPERIENCIA" class="flex-1 outline-none text-sm font-medium text-[#0070C0] placeholder:text-[#0070C0]/60 bg-transparent" />
			</div>
			<button class="flex items-center gap-2 bg-[#E1EAF1] border-2 border-[#0070C0] text-[#0070C0] px-6 py-4 rounded-full font-medium text-sm uppercase hover:bg-[#0070C0] hover:text-white transition-colors max-md:w-full max-md:justify-center">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 4H20M6 11H18M10 18H14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				FILTROS
			</button>
		</div>
	</section>

	<div class="max-w-7xl mx-auto px-20 max-md:px-10 max-sm:px-5">
		<div class="h-[1px] bg-[#0070C0]"></div>
	</div>

	<section class="max-w-7xl mx-auto px-20 py-20 max-md:px-10 max-sm:px-5">
		<h2 class="text-4xl font-bold text-black mb-12 max-md:text-3xl">LOS MÁS BUSCADOS</h2>
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12" data-static-tours>
			<?php
			$query = new WP_Query(
				[
					'post_type'      => 'product',
					'posts_per_page' => 3,
					'tax_query'      => [
						[
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => [ 'tours', 'actividades' ],
						],
					],
				]
			);

			if ( $query->have_posts() ) :
				while ( $query->have_posts() ) :
					$query->the_post();
					$image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
					?>
					<a href="<?php the_permalink(); ?>" class="group relative h-[450px] rounded-2xl overflow-hidden cursor-pointer hover:scale-[1.02] transition-transform duration-300">
						<div class="absolute inset-0 bg-cover bg-center group-hover:scale-110 transition-transform duration-500" style="background-image: url('<?php echo esc_url( $image ? $image : $images_url . 'placeholder.jpg' ); ?>');"></div>
						<div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/80"></div>
						<div class="absolute top-6 left-6 z-10">
							<span class="bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full text-xs font-bold text-[#0070C0] uppercase">
								<?php echo esc_html( wc_get_product_category_list( get_the_ID() ) ); ?>
							</span>
						</div>
						<div class="absolute bottom-0 left-0 right-0 p-6 z-10">
							<h3 class="text-3xl font-bold text-white mb-2"><?php the_title(); ?></h3>
							<p class="text-white/90 text-sm"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
						</div>
					</a>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>

		<div class="h-[1px] bg-[#0070C0] mb-12"></div>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
			<?php
			$grid_query = new WP_Query(
				[
					'post_type'      => 'product',
					'posts_per_page' => 9,
					'paged'          => max( 1, get_query_var( 'paged' ) ),
					'tax_query'      => [
						[
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => [ 'tours', 'actividades' ],
						],
					],
				]
			);

			if ( $grid_query->have_posts() ) :
				while ( $grid_query->have_posts() ) :
					$grid_query->the_post();
					$image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
					?>
					<a href="<?php the_permalink(); ?>" class="group relative h-[450px] rounded-2xl overflow-hidden cursor-pointer hover:scale-[1.02] transition-transform duration-300">
						<div class="absolute inset-0 bg-cover bg-center group-hover:scale-110 transition-transform duration-500" style="background-image: url('<?php echo esc_url( $image ? $image : $images_url . 'placeholder.jpg' ); ?>');"></div>
						<div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/80"></div>
						<div class="absolute top-6 left-6 z-10">
							<span class="bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full text-xs font-bold text-[#0070C0] uppercase">
								<?php echo esc_html( wc_get_product_category_list( get_the_ID() ) ); ?>
							</span>
						</div>
						<div class="absolute bottom-0 left-0 right-0 p-6 z-10">
							<h3 class="text-3xl font-bold text-white mb-2"><?php the_title(); ?></h3>
							<p class="text-white/90 text-sm"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
						</div>
					</a>
					<?php
				endwhile;

				echo '<div class="flex justify-center mt-10">';
				the_posts_pagination(
					[
						'mid_size'           => 2,
						'prev_text'          => __( '« Anterior', 'blankslate' ),
						'next_text'          => __( 'Siguiente »', 'blankslate' ),
						'screen_reader_text' => __( 'Navegación de resultados', 'blankslate' ),
					]
				);
				echo '</div>';

				wp_reset_postdata();
			endif;
			?>
		</div>

		<div class="flex justify-center">
			<a href="<?php echo esc_url( get_term_link( 'tours', 'product_cat' ) ); ?>" class="border-2 border-black px-12 py-3 rounded-full font-bold text-sm uppercase transition-colors text-zinc-500 bg-neutral-50 hover:bg-black hover:text-white">
				VER MÁS
			</a>
		</div>
	</section>
</main>

<?php
get_footer();
