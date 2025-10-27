<?php
/**
 * Template Name: Contacto
 * Description: Plantilla personalizada para la página de contacto.
 *
 * @package BlankSlate
 */

get_header();

$hero_image   = function_exists( 'get_field' ) ? get_field( 'contacto_hero_imagen' ) : null;
$hero_title   = function_exists( 'get_field' ) ? get_field( 'contacto_hero_titulo' ) : '';
$form_shortcode = function_exists( 'get_field' ) ? get_field( 'contacto_form_shortcode' ) : '';
$info_blocks  = function_exists( 'get_field' ) ? get_field( 'contacto_info_grupos' ) : [];
$social_links = function_exists( 'get_field' ) ? get_field( 'contacto_social' ) : [];

$upload_dir     = wp_upload_dir();
$default_hero   = trailingslashit( $upload_dir['baseurl'] ) . 'snc-media/contact.png';
$hero_image_url = '';

if ( is_array( $hero_image ) && isset( $hero_image['url'] ) ) {
	$hero_image_url = $hero_image['url'];
} elseif ( is_string( $hero_image ) && '' !== $hero_image ) {
	$hero_image_url = $hero_image;
} else {
	$hero_image_url = $default_hero;
}

if ( '' === $hero_title ) {
	$hero_title = __( 'Contáctanos', 'blankslate' );
}

if ( '' === $form_shortcode ) {
	$form_shortcode = '[elementor-template id="67"]';
}

$info_blocks = is_array( $info_blocks ) ? $info_blocks : [];
$social_links = is_array( $social_links ) ? $social_links : [];

if ( empty( $info_blocks ) ) {
	$info_blocks = [
		[
			'titulo' => __( 'Emails', 'blankslate' ),
			'items'  => [
				[
					'label' => '',
					'valor' => 'reservationsfusiontoursrvm@gmail.com',
					'enlace' => 'mailto:reservationsfusiontoursrvm@gmail.com',
				],
				[
					'label' => '',
					'valor' => 'fusiontoursrvm2025@gmail.com',
					'enlace' => 'mailto:fusiontoursrvm2025@gmail.com',
				],
			],
		],
		[
			'titulo' => __( 'WhatsApp - Ventas / Sales', 'blankslate' ),
			'items'  => [
				[
					'label' => '',
					'valor' => '+52 984 254 9858',
					'enlace' => 'https://wa.me/529842549858',
				],
				[
					'label' => '',
					'valor' => '+52 322 229 0911',
					'enlace' => 'https://wa.me/523222290911',
				],
			],
		],
		[
			'titulo' => __( 'Atención al Cliente / Customer Service', 'blankslate' ),
			'items'  => [
				[
					'label' => '',
					'valor' => '+52 984 131 2269',
					'enlace' => 'tel:+529841312269',
				],
			],
		],
	];
}

if ( empty( $social_links ) ) {
	$social_links = [
		[
			'nombre' => 'Facebook',
			'url'    => 'https://www.facebook.com/',
		],
		[
			'nombre' => 'Twitter',
			'url'    => 'https://x.com/',
		],
		[
			'nombre' => 'Instagram',
			'url'    => 'https://www.instagram.com/',
		],
	];
}

/**
 * Maps a network name to a modifier class.
 *
 * @param string $label Network label.
 * @return string
 */
if ( ! function_exists( 'blankslate_contact_network_class' ) ) {
	function blankslate_contact_network_class( $label ) {
		$slug = sanitize_title( $label );
		$map  = [
			'facebook'  => 'facebook',
			'instagram' => 'instagram',
			'twitter'   => 'twitter',
			'x'         => 'twitter',
			'youtube'   => 'youtube',
			'tiktok'    => 'tiktok',
			'whatsapp'  => 'whatsapp',
			'linkedin'  => 'linkedin',
		];
		return isset( $map[ $slug ] ) ? $map[ $slug ] : 'generic';
	}
}
?>

<section class="contact-hero" style="background-image:url('<?php echo esc_url( $hero_image_url ); ?>');">
	<div class="contact-hero__overlay"></div>
	<div class="contact-hero__inner">
		<h1 class="contact-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
	</div>
</section>

<section class="contact-content">
	<div class="contact-content__inner">
		<div class="contact-content__form">
			<?php echo do_shortcode( $form_shortcode ); ?>
		</div>
		<aside class="contact-content__info">
			<div class="contact-card">
				<?php if ( ! empty( $info_blocks ) ) : ?>
					<?php foreach ( $info_blocks as $block ) : ?>
						<?php
						$title  = isset( $block['titulo'] ) ? $block['titulo'] : '';
						$items  = isset( $block['items'] ) && is_array( $block['items'] ) ? $block['items'] : [];
						if ( '' === trim( $title ) && empty( $items ) ) {
							continue;
						}
						?>
						<div class="contact-card__block">
							<?php if ( '' !== trim( $title ) ) : ?>
								<h2 class="contact-card__heading"><?php echo esc_html( $title ); ?></h2>
							<?php endif; ?>
							<?php if ( ! empty( $items ) ) : ?>
								<ul class="contact-card__list">
									<?php foreach ( $items as $item ) : ?>
										<?php
										$label = isset( $item['label'] ) ? $item['label'] : '';
										$value = isset( $item['valor'] ) ? $item['valor'] : '';
										$link  = isset( $item['enlace'] ) ? $item['enlace'] : '';
										if ( '' === trim( $value ) ) {
											continue;
										}
										?>
										<li>
											<?php if ( '' !== trim( $link ) ) : ?>
												<a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener">
													<?php echo esc_html( $value ); ?>
												</a>
											<?php else : ?>
													<?php echo esc_html( $value ); ?>
											<?php endif; ?>
											<?php if ( '' !== trim( $label ) ) : ?>
												<span class="contact-card__item-label"><?php echo esc_html( $label ); ?></span>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php if ( ! empty( $social_links ) ) : ?>
					<div class="contact-card__social">
						<?php foreach ( $social_links as $social ) : ?>
							<?php
							$name  = isset( $social['nombre'] ) ? $social['nombre'] : '';
							$url   = isset( $social['url'] ) ? $social['url'] : '';
							if ( '' === $name || '' === $url ) {
								continue;
							}
							$modifier = blankslate_contact_network_class( $name );
							?>
							<a class="contact-card__social-link contact-card__social-link--<?php echo esc_attr( $modifier ); ?>" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $name ); ?>">
								<span class="screen-reader-text"><?php echo esc_html( $name ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</aside>
	</div>
</section>

<?php
get_footer();
