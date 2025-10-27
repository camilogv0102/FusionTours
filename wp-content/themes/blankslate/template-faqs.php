<?php
/**
 * Template Name: FAQs
 * Description: Plantilla personalizada para la página de preguntas frecuentes.
 *
 * @package BlankSlate
 */

get_header();

$hero_image   = function_exists( 'get_field' ) ? get_field( 'faq_hero_imagen' ) : null;
$hero_title   = function_exists( 'get_field' ) ? get_field( 'faq_hero_titulo' ) : '';
$intro_text   = function_exists( 'get_field' ) ? get_field( 'faq_intro_texto' ) : '';
$faq_items    = function_exists( 'get_field' ) ? get_field( 'faq_items' ) : array();

$upload_dir     = wp_upload_dir();
$default_hero   = trailingslashit( $upload_dir['baseurl'] ) . 'snc-media/faqs.png';
$hero_image_url = '';

if ( is_array( $hero_image ) && isset( $hero_image['url'] ) ) {
	$hero_image_url = $hero_image['url'];
} elseif ( is_string( $hero_image ) && '' !== $hero_image ) {
	$hero_image_url = $hero_image;
} else {
	$hero_image_url = $default_hero;
}

if ( '' === trim( $hero_title ) ) {
	$hero_title = __( 'Preguntas frecuentes', 'blankslate' );
}

$faq_items = is_array( $faq_items ) ? $faq_items : array();
if ( empty( $faq_items ) ) {
	$faq_items = function_exists( 'blankslate_faq_default_items' ) ? blankslate_faq_default_items() : array();
}

$intro_text = wp_kses_post( $intro_text );
?>

<section class="faq-hero" style="background-image:url('<?php echo esc_url( $hero_image_url ); ?>');">
	<div class="faq-hero__overlay"></div>
	<div class="faq-hero__inner">
		<h1 class="faq-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
		<?php if ( '' !== $intro_text ) : ?>
			<p class="faq-hero__intro"><?php echo $intro_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>
	</div>
</section>

<section class="faq-content">
	<div class="faq-content__inner">
		<?php if ( ! empty( $faq_items ) ) : ?>
			<div class="faq-accordion" role="tablist">
				<?php foreach ( $faq_items as $index => $item ) : ?>
					<?php
					$question = isset( $item['pregunta'] ) ? $item['pregunta'] : '';
					$answer   = isset( $item['respuesta'] ) ? $item['respuesta'] : '';
					if ( '' === trim( $question ) ) {
						continue;
					}
					$id_suffix  = $index + 1;
					$heading_id = 'faq-heading-' . $id_suffix;
					$content_id = 'faq-panel-' . $id_suffix;
					$is_open    = 0 === $index;
					?>
					<details class="faq-accordion__item"<?php echo $is_open ? ' open' : ''; ?>>
						<summary class="faq-accordion__summary" id="<?php echo esc_attr( $heading_id ); ?>">
							<span class="faq-accordion__question"><?php echo esc_html( $question ); ?></span>
							<span class="faq-accordion__icon" aria-hidden="true"></span>
						</summary>
						<div class="faq-accordion__panel" id="<?php echo esc_attr( $content_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
							<div class="faq-accordion__answer">
								<?php echo wp_kses_post( wpautop( $answer ) ); ?>
							</div>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
