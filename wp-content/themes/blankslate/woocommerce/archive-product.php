<?php
/**
 * Custom product archive with Fusion Tours styling and subcategory filter.
 *
 * @package BlankSlate
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );

$term = get_queried_object();

$hero_title = $term instanceof WP_Term ? strtoupper( $term->name ) : __( 'NUESTROS TOURS', 'blankslate' );
$hero_description = $term instanceof WP_Term && ! empty( $term->description )
	? wp_strip_all_tags( $term->description )
	: __( 'Descubre experiencias cuidadosamente diseñadas para explorar la Riviera Maya con adrenalina, cultura y confort.', 'blankslate' );

$hero_image_uri = get_theme_file_uri( 'assets/images/plp-hero.jpg' );
if ( ! file_exists( get_theme_file_path( 'assets/images/plp-hero.jpg' ) ) ) {
	$hero_image_uri = get_theme_file_uri( 'assets/lovable/images/tours/whale-shark-1.jpg' );
}

$subcat_slug  = isset( $_GET['subcat'] ) ? sanitize_text_field( wp_unslash( $_GET['subcat'] ) ) : '';
$paged        = max( 1, get_query_var( 'paged' ) );
$per_page     = (int) get_option( 'posts_per_page', 12 );

$tax_query = array();
if ( $term instanceof WP_Term ) {
	$tax_query[] = array(
		'taxonomy' => 'product_cat',
		'field'    => 'term_id',
		'terms'    => $term->term_id,
	);
}

$available_subcats = array();
if ( $term instanceof WP_Term ) {
	$available_subcats = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'parent'     => $term->term_id,
		)
	);

	if ( $subcat_slug ) {
		$subcat_term = get_term_by( 'slug', $subcat_slug, 'product_cat' );
		if ( $subcat_term instanceof WP_Term && (int) $subcat_term->parent === (int) $term->term_id ) {
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $subcat_term->term_id,
			);
		} else {
			$subcat_slug = '';
		}
	}
}

$products_query = new WP_Query(
	array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'tax_query'      => $tax_query,
	)
);

$total_found = $products_query->found_posts;

?>

<style>
.fusion-plp__hero{position:relative;min-height:60vh;display:flex;align-items:flex-end;overflow:hidden;margin-bottom:2rem}
.fusion-plp__hero::before{content:"";position:absolute;inset:0;background:linear-gradient(200deg,rgba(0,0,0,0.15) 0%,rgba(0,0,0,0.65) 100%),var(--fusion-hero, rgba(0,0,0,.6));background-size:cover;background-position:center;background-repeat:no-repeat;z-index:0;transform:scale(1.02)}
.fusion-plp__hero-inner{position:relative;z-index:1;width:100%;max-width:1100px;margin:0 auto;padding:4rem 2rem 4.5rem;color:#fff;text-align:left}
.fusion-plp__hero-title{font-family:"Neue Montreal",Arial,sans-serif;font-size:5rem;font-weight:800;letter-spacing:-.05em;line-height:.95;margin:0 0 1rem;text-transform:uppercase}
.fusion-plp__hero-desc{max-width:460px;font-size:1.05rem;line-height:1.6;margin:0}
.fusion-plp__controls{display:flex;justify-content:space-between;align-items:center;gap:1.5rem;max-width:1100px;margin:0 auto 2.5rem;padding:1.4rem 2rem;border-radius:22px;background:linear-gradient(135deg,rgba(235,243,255,.82) 0%,rgba(248,251,255,.92) 100%);border:1px solid rgba(0,112,192,.12);box-shadow:0 18px 36px -24px rgba(0,36,84,.35);backdrop-filter:blur(18px)}
.fusion-plp__count{color:#0a1a2f;font-weight:700;font-size:1.05rem;letter-spacing:.02em}
.fusion-plp__count span{display:inline-flex;align-items:center;gap:.45rem;padding:.45rem .85rem;border-radius:999px;background:rgba(0,112,192,.12);color:#0070C0;font-size:.85rem;text-transform:uppercase;letter-spacing:.12em;margin-right:10px;}
.fusion-plp__filter{display:flex;align-items:center;gap:1.25rem;margin-left:auto}
.fusion-plp__filter-group{display:flex;flex-direction:row;gap:.55rem;align-items:center;}
.fusion-plp__filter span{text-transform:uppercase;font-size:.72rem;font-weight:700;letter-spacing:.18em;color:#0a1a2f;opacity:.75}
.fusion-plp__filter-select{position:relative;display:flex;align-items:center;min-width:240px;background:#fff;border-radius:999px;border:1px solid rgba(0,112,192,.25);box-shadow:0 8px 24px -16px rgba(0,36,84,.35);transition:box-shadow .2s ease,border-color .2s ease}
.fusion-plp__filter-select:focus-within{border-color:#0070C0;box-shadow:0 12px 32px -18px rgba(0,112,192,.4)}
.fusion-plp__filter-select select{appearance:none;-webkit-appearance:none;-moz-appearance:none;width:100%;padding:.65rem 3rem .65rem 1.15rem;border:none;border-radius:999px;background:transparent;font-size:.95rem;font-weight:600;color:#0a1a2f;cursor:pointer}
.fusion-plp__filter-select select:focus{outline:none}
.fusion-plp__filter-select svg{position:absolute;right:1rem;pointer-events:none;color:#0070C0;opacity:.75}
.fusion-plp__filter-clear{display:inline-flex;align-items:center;gap:.35rem;padding:.45rem .9rem;border-radius:999px;background:rgba(0,0,0,.05);color:#0a1a2f;font-size:.75rem;font-weight:600;text-decoration:none;text-transform:uppercase;letter-spacing:.1em;transition:all .2s ease}
.fusion-plp__filter-clear:hover{background:#0a1a2f;color:#fff}
.fusion-plp__filter-clear svg{width:10px;height:10px}
.fusion-plp__grid-wrapper{max-width:1100px;margin:0 auto;padding:0 0 4rem}
.fusion-plp__grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:2rem}
.fusion-plp__card{position:relative;display:flex;flex-direction:column;border-radius:20px;overflow:hidden;background:#fff;box-shadow:0 18px 35px -25px rgba(0,0,0,.55);transition:transform .3s ease,box-shadow .3s ease;text-decoration:none;color:inherit}
.fusion-plp__card:hover{transform:translateY(-6px);box-shadow:0 22px 45px -20px rgba(0,0,0,.6)}
.fusion-plp__card-img{width:100%;height:230px;object-fit:cover;background:#f1f1f1}
.fusion-plp__card-body{padding:1.75rem;display:flex;flex-direction:column;height:100%}
.fusion-plp__badge{display:inline-flex;padding:.35rem .85rem;border-radius:999px;background:rgba(0,112,192,.12);color:#0070C0;font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:1rem}
.fusion-plp__card-title{font-size:1.4rem;font-weight:700;color:#111;margin:0 0 .75rem;line-height:1.25}
.fusion-plp__card-excerpt{font-size:.95rem;color:#4a4a4a;line-height:1.5;margin:0 0 1.75rem}
.fusion-plp__cta{margin-top:auto;font-weight:700;color:#0070C0;display:flex;align-items:center;gap:.5rem;text-transform:uppercase;font-size:.8rem;letter-spacing:.1em}
.fusion-plp__pagination{display:flex;justify-content:center;margin:3rem 0 0}
.fusion-plp__pagination .page-numbers{display:inline-flex;padding:.6rem 1rem;margin:.25rem;border-radius:999px;background:#f0f4f8;color:#1a1a1a;font-weight:600;text-decoration:none;transition:all .2s ease}
.fusion-plp__pagination .page-numbers.current,
.fusion-plp__pagination .page-numbers:hover{background:#0070C0;color:#fff}
@media(max-width:1024px){.fusion-plp__hero-title{font-size:4rem}}
@media(max-width:768px){.fusion-plp__hero{min-height:55vh}.fusion-plp__hero-inner{padding:3rem 1.5rem 2.75rem}.fusion-plp__hero-title{font-size:3rem}.fusion-plp__controls{flex-direction:column;align-items:flex-start;padding:1.4rem 1.5rem}.fusion-plp__filter{width:100%;flex-direction:column;align-items:flex-start}.fusion-plp__filter-select{width:100%}.fusion-plp__filter-clear{margin-top:.75rem}}
@media(max-width:480px){.fusion-plp__hero-title{font-size:2.4rem}.fusion-plp__hero-desc{font-size:.95rem}.fusion-plp__grid-wrapper{padding:0 1.25rem 3rem}}
.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
</style>

<main class="fusion-plp">
	<section class="fusion-plp__hero" style="--fusion-hero:url('<?php echo esc_url( $hero_image_uri ); ?>');">
		<div class="fusion-plp__hero-inner">
			<h1 class="fusion-plp__hero-title"><?php echo esc_html( $hero_title ); ?></h1>
			<p class="fusion-plp__hero-desc"><?php echo esc_html( $hero_description ); ?></p>
		</div>
	</section>

	<div class="fusion-plp__controls">
	<p class="fusion-plp__count">
		<span><?php echo esc_html( number_format_i18n( $total_found ) ); ?></span>
		<?php esc_html_e( 'productos encontrados', 'blankslate' ); ?>
	</p>
	<form class="fusion-plp__filter" method="get">
		<?php
		foreach ( $_GET as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification
			if ( 'subcat' === $key ) {
				continue;
			}
			printf( '<input type="hidden" name="%1$s" value="%2$s" />', esc_attr( $key ), esc_attr( wp_unslash( $value ) ) );
		}
		?>
		<div class="fusion-plp__filter-group">
			<label for="fusion-subcat" class="sr-only"><?php esc_html_e( 'Filtrar por subcategoría', 'blankslate' ); ?></label>
			<span class="text-sm font-semibold text-[#0070C0] tracking-[.15em] uppercase"><?php esc_html_e( 'Subcategoría', 'blankslate' ); ?></span>
			<div class="fusion-plp__filter-select">
				<select id="fusion-subcat" name="subcat" onchange="this.form.submit()">
					<option value=""><?php esc_html_e( 'Todas las experiencias', 'blankslate' ); ?></option>
					<?php foreach ( (array) $available_subcats as $subcat ) : ?>
						<option value="<?php echo esc_attr( $subcat->slug ); ?>" <?php selected( $subcat_slug, $subcat->slug ); ?>><?php echo esc_html( $subcat->name ); ?></option>
					<?php endforeach; ?>
				</select>
				<svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.6667 2.33337L8.00008 8.33337L1.33341 2.33337" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</div>
		</div>
		<?php if ( $subcat_slug ) : ?>
			<a class="fusion-plp__filter-clear" href="<?php echo esc_url( remove_query_arg( array( 'subcat', 'paged' ) ) ); ?>">
				<svg viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg"><path d="M3 3l6 6m0-6L3 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
				<?php esc_html_e( 'Limpiar', 'blankslate' ); ?>
			</a>
		<?php endif; ?>
	</form>
	</div>

	<section class="fusion-plp__grid-wrapper">
		<?php if ( $products_query->have_posts() ) : ?>
			<div class="fusion-plp__grid">
				<?php
				while ( $products_query->have_posts() ) :
					$products_query->the_post();
					$image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
					$label = '';
					if ( $term instanceof WP_Term ) {
						$product_terms = wp_get_post_terms( get_the_ID(), 'product_cat' );
						foreach ( $product_terms as $product_term ) {
							if ( (int) $product_term->parent === (int) $term->term_id ) {
								$label = $product_term->name;
								break;
							}
						}
						if ( ! $label && ! empty( $product_terms ) ) {
							$label = $product_terms[0]->name;
						}
					}
					?>
					<a href="<?php the_permalink(); ?>" class="fusion-plp__card">
						<?php if ( $image ) : ?>
							<img class="fusion-plp__card-img" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
						<?php endif; ?>
						<div class="fusion-plp__card-body">
							<?php if ( $label ) : ?>
								<span class="fusion-plp__badge"><?php echo esc_html( $label ); ?></span>
							<?php endif; ?>
							<h3 class="fusion-plp__card-title"><?php the_title(); ?></h3>
							<p class="fusion-plp__card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
							<div class="fusion-plp__cta">
								<span><?php esc_html_e( 'Ver detalles', 'blankslate' ); ?></span>
								<svg width="12" height="8" viewBox="0 0 16 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 3.18201C0.223858 3.18201 0 3.40586 0 3.68201C0 3.95815 0.223858 4.18201 0.5 4.18201L0.5 3.68201L0.5 3.18201ZM15.8536 4.03556C16.0488 3.8403 16.0488 3.52372 15.8536 3.32845L12.6716 0.146474C12.4763 -0.0487882 12.1597 -0.0487882 11.9645 0.146474C11.7692 0.341736 11.7692 0.658319 11.9645 0.853581L14.7929 3.68201L11.9645 6.51043C11.7692 6.7057 11.7692 7.02228 11.9645 7.21754C12.1597 7.4128 12.4763 7.4128 12.6716 7.21754L15.8536 4.03556ZM0.5 3.68201L0.5 4.18201L15.5 4.18201L15.5 3.68201L15.5 3.18201L0.5 3.18201L0.5 3.68201Z" fill="currentColor"/></svg>
							</div>
						</div>
					</a>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'No hay productos disponibles en esta categoría por el momento.', 'blankslate' ); ?></p>
		<?php endif; ?>

		<div class="fusion-plp__pagination">
			<?php
			echo paginate_links(
				array(
					'current'   => $paged,
					'total'     => max( 1, $products_query->max_num_pages ),
					'mid_size'  => 2,
					'add_args'  => array_filter( array( 'subcat' => $subcat_slug ) ),
					'prev_text' => __( '« Anterior', 'blankslate' ),
					'next_text' => __( 'Siguiente »', 'blankslate' ),
				)
			);
			?>
		</div>
	</section>
</main>

<?php
wp_reset_postdata();

do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
