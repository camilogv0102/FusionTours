<?php
/**
 * Template Name: Fusion Home
 * Description: Plantilla que replica la home generada en Lovable para Fusion Tours.
 *
 * @package BlankSlate
 */

get_header();

if ( have_posts() ) {
	the_post();
}

$theme_uri   = get_template_directory_uri();
$assets_base = trailingslashit( $theme_uri ) . 'assets/lovable';
$images_base = trailingslashit( $assets_base ) . 'images';

$hero_image_url = 'https://api.builder.io/api/v1/image/assets/TEMP/c2617f60c3d2434f45a8cfeabbb96b65faa762e9?width=3024';

$tours = [];

if ( function_exists( 'wc_get_products' ) ) {
	$tour_parent_term = get_term_by( 'slug', 'tours', 'product_cat' );

	if ( $tour_parent_term instanceof WP_Term ) {
		$tour_products = wc_get_products(
			[
				'status'      => 'publish',
				'limit'       => 10,
				'orderby'     => 'menu_order',
				'order'       => 'ASC',
				'tax_query'   => [
					[
						'taxonomy'         => 'product_cat',
						'field'            => 'term_id',
						'terms'            => $tour_parent_term->term_id,
						'include_children' => true,
					],
				],
			]
		);

		foreach ( $tour_products as $tour_product ) {
			$image_id  = $tour_product->get_image_id();
			$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';

			if ( ! $image_url ) {
				$image_url = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : '';
			}

			$description = $tour_product->get_short_description();
			if ( ! $description ) {
				$description = $tour_product->get_description();
			}
			$description = wp_trim_words( wp_strip_all_tags( $description ), 22 );

			$tours[] = [
				'name'        => $tour_product->get_name(),
				'description' => $description,
				'image'       => $image_url,
				'link'        => $tour_product->get_permalink(),
			];
		}
	}
}

if ( empty( $tours ) ) {
	$tours = [
		[
			'category'    => 'MAR CARIBE',
			'name'        => 'Tiburón Ballena',
			'description' => 'Nada con el pez más grande del mundo en aguas cristalinas del Caribe Mexicano.',
			'image'       => $images_base . '/tours/whale-shark-1.jpg',
			'link'        => home_url( '/tours/tiburon-ballena' ),
		],
		[
			'category'    => 'TIERRA',
			'name'        => 'Xcaret Plus',
			'description' => 'Parque eco-arqueológico con más de 50 atracciones naturales y culturales.',
			'image'       => $images_base . '/tours/xcaret.jpg',
			'link'        => home_url( '/tours/xcaret-plus' ),
		],
		[
			'category'    => 'AGUA',
			'name'        => 'Cenotes Sagrados',
			'description' => 'Explora los cenotes más hermosos y místicos de la Riviera Maya.',
			'image'       => $images_base . '/tours/cenote-snorkel.jpg',
			'link'        => home_url( '/tours/cenotes-sagrados' ),
		],
		[
			'category'    => 'AVENTURA',
			'name'        => 'Holbox',
			'description' => 'Descubre la isla paradisíaca del Caribe Mexicano con playas vírgenes.',
			'image'       => $images_base . '/tours/holbox.jpg',
			'link'        => home_url( '/tours/holbox' ),
		],
		[
			'category'    => 'MAR CARIBE',
			'name'        => 'Whale Shark Adventure',
			'description' => 'Experiencia única nadando con tiburones ballena en su hábitat natural.',
			'image'       => $images_base . '/tours/whale-shark-2.jpg',
			'link'        => home_url( '/tours/whale-shark-adventure' ),
		],
	];
}

$activities = [];

if ( function_exists( 'wc_get_products' ) ) {
	$activities_parent_term = get_term_by( 'slug', 'actividades', 'product_cat' );

	if ( $activities_parent_term instanceof WP_Term ) {
		$activity_products = wc_get_products(
			[
				'status'      => 'publish',
				'limit'       => 8,
				'orderby'     => 'menu_order',
				'order'       => 'ASC',
				'tax_query'   => [
					[
						'taxonomy'         => 'product_cat',
						'field'            => 'term_id',
						'terms'            => $activities_parent_term->term_id,
						'include_children' => true,
					],
				],
			]
		);

		foreach ( $activity_products as $activity_product ) {
			$image_id  = $activity_product->get_image_id();
			$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';

			if ( ! $image_url ) {
				$image_url = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : '';
			}

			$description = $activity_product->get_short_description();
			if ( ! $description ) {
				$description = $activity_product->get_description();
			}
			$description = wp_trim_words( wp_strip_all_tags( $description ), 22 );

			$category_label = $activities_parent_term->name;
			$product_terms  = get_the_terms( $activity_product->get_id(), 'product_cat' );

			if ( ! is_wp_error( $product_terms ) && ! empty( $product_terms ) ) {
				foreach ( $product_terms as $product_term ) {
					if ( (int) $product_term->parent === (int) $activities_parent_term->term_id ) {
						$category_label = $product_term->name;
						break;
					}
				}

				if ( $category_label === $activities_parent_term->name ) {
					$category_label = $product_terms[0]->name;
				}
			}

			$activities[] = [
				'category'    => $category_label,
				'name'        => $activity_product->get_name(),
				'description' => $description,
				'image'       => $image_url,
			];
		}
	}
}

if ( empty( $activities ) ) {
	$activities = [
		[
			'category'    => 'ACUÁTICA',
			'name'        => 'Snorkel en Cenote',
			'description' => 'Explora las aguas cristalinas de los cenotes sagrados mayas.',
			'image'       => $images_base . '/tours/cenote-snorkel.jpg',
		],
		[
			'category'    => 'TIERRA',
			'name'        => 'Xcaret',
			'description' => 'Parque eco-arqueológico con espectáculos y naturaleza.',
			'image'       => $images_base . '/tours/xcaret.jpg',
		],
		[
			'category'    => 'AVENTURA',
			'name'        => 'Holbox',
			'description' => 'Descubre la isla paradisíaca del Caribe Mexicano.',
			'image'       => $images_base . '/tours/holbox.jpg',
		],
		[
			'category'    => 'MAR CARIBE',
			'name'        => 'Tiburón Ballena',
			'description' => 'Nada con el pez más grande del mundo en su hábitat natural.',
			'image'       => $images_base . '/tours/whale-shark-1.jpg',
		],
		[
			'category'    => 'MAR CARIBE',
			'name'        => 'Whale Shark',
			'description' => 'Experiencia única nadando con tiburones ballena.',
			'image'       => $images_base . '/tours/whale-shark-2.jpg',
		],
	];
}

$stats = [
	[
		'number'      => '+20',
		'description' => 'AÑOS DE EXPERIENCIA EN MERCADO',
	],
	[
		'number'      => '+120',
		'description' => 'CLIENTES SATISFECHOS EN 5 AÑOS',
	],
	[
		'number'      => '+40',
		'description' => 'TOURS Y ACTIVIDADES VERIFICADAS',
	],
];

$clients_left = [
	[ 'src' => $images_base . '/clients/client-1.jpg', 'alt' => 'Cliente feliz 1' ],
	[ 'src' => $images_base . '/clients/client-2.jpg', 'alt' => 'Cliente feliz 2' ],
	[ 'src' => $images_base . '/clients/client-3.jpg', 'alt' => 'Cliente feliz 3' ],
	[ 'src' => $images_base . '/clients/client-4.jpg', 'alt' => 'Cliente feliz 4' ],
	[ 'src' => $images_base . '/clients/client-5.jpg', 'alt' => 'Cliente feliz 5' ],
];

$clients_right = [
	[ 'src' => $images_base . '/clients/client-6.jpg', 'alt' => 'Cliente feliz 6' ],
	[ 'src' => $images_base . '/clients/client-7.jpg', 'alt' => 'Cliente feliz 7' ],
	[ 'src' => $images_base . '/clients/client-8.jpg', 'alt' => 'Cliente feliz 8' ],
	[ 'src' => $images_base . '/clients/client-9.jpg', 'alt' => 'Cliente feliz 9' ],
	[ 'src' => $images_base . '/clients/client-10.jpg', 'alt' => 'Cliente feliz 10' ],
];

$faqs = [
	[
		'question' => '¿Cómo reservo un tour con Fusion Tours?',
		'answer'   => 'Puedes reservar directamente desde la web o escribiéndonos por WhatsApp. Confirmamos tu fecha y enviamos la orden de pago en minutos.',
	],
	[
		'question' => '¿Qué incluye mi tour?',
		'answer'   => 'Cada experiencia detalla lo que incluye en la ficha del tour: traslados, guías certificados, alimentos, equipo y seguros según aplique.',
	],
	[
		'question' => '¿Puedo modificar o cancelar mi reserva?',
		'answer'   => 'Sí, contamos con políticas flexibles. Escríbenos al menos 48 h antes de tu salida y buscamos la mejor alternativa.',
	],
	[
		'question' => '¿Ofrecen precios especiales para grupos?',
		'answer'   => 'Tenemos tarifas preferenciales para grupos, agencias y bodas destino. Envía tus fechas y número de personas para cotizar.',
	],
	[
		'question' => '¿En qué moneda puedo pagar?',
		'answer'   => 'Aceptamos MXN y USD. Nuestro equipo te ayuda a cerrar la operación en la moneda que te sea más conveniente.',
	],
];

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'fusion-home-page box-border flex flex-col items-center gap-0 w-full bg-white p-0 m-0' ); ?>>
	<section class="box-border relative w-full h-[700px] overflow-hidden m-0 p-0 flex flex-col justify-end max-md:h-[600px] max-sm:h-[500px]">
		<img class="box-border w-full h-full object-cover absolute m-0 p-0 left-0 top-0 z-0" src="<?php echo esc_url( $hero_image_url ); ?>" alt="<?php esc_attr_e( 'Playa tropical con palmeras y mar turquesa', 'blankslate' ); ?>" />

			<div class="box-border relative z-10 flex flex-col gap-6 m-0 px-20 pb-8 max-md:px-10 max-md:pb-6 max-sm:px-5 max-sm:pb-4 max-sm:gap-4">
			<div class="max-sm:hidden">
				<div class="box-border flex flex-row justify-between items-center gap-8 m-0 p-0 max-md:flex-col max-md:gap-4 max-md:items-start">
					<p class="box-border text-white text-base font-normal leading-relaxed m-0 p-0">
						<?php esc_html_e( 'Vive la experiencia del Caribe Mexicano', 'blankslate' ); ?>
					</p>
					<div class="box-border flex items-center gap-3 m-0 p-0">
						<a href="<?php echo esc_url( home_url( '/tours' ) ); ?>">
							<span class="box-border flex justify-center items-center gap-3 m-0 px-6 py-2.5 rounded-full bg-[#FF8B4C] hover:bg-[#ff7a3a] transition-colors text-white text-center text-sm font-bold uppercase"><?php esc_html_e( 'Tours', 'blankslate' ); ?></span>
						</a>
						<a href="#actividades" class="fusion-scroll-activities box-border flex justify-center items-center gap-3 m-0 px-6 py-2.5 rounded-full bg-[#0070C0] hover:bg-[#005a9f] transition-colors">
							<span class="box-border text-white text-center text-sm font-bold uppercase m-0 p-0"><?php esc_html_e( 'Actividades', 'blankslate' ); ?></span>
							<span class="box-border text-white text-lg m-0 p-0">→</span>
						</a>
					</div>
				</div>
			</div>

			<div class="sm:hidden flex flex-col gap-3 items-center">
				<p class="box-border text-white text-sm font-normal leading-relaxed m-0 p-0 text-center">
					<?php esc_html_e( 'Vive la experiencia del Caribe Mexicano', 'blankslate' ); ?>
				</p>
				<div class="box-border flex items-center gap-2 m-0 p-0 justify-center w-full">
					<a class="flex-1 max-w-[160px]" href="<?php echo esc_url( home_url( '/tours' ) ); ?>">
						<span class="box-border w-full flex justify-center items-center gap-2 m-0 px-4 py-2.5 rounded-full bg-[#FF8B4C] hover:bg-[#ff7a3a] transition-colors text-white text-center text-xs font-bold uppercase"><?php esc_html_e( 'Tours', 'blankslate' ); ?></span>
					</a>
					<a href="#actividades" class="fusion-scroll-activities flex-1 max-w-[160px] box-border flex justify-center items-center gap-2 m-0 px-4 py-2.5 rounded-full bg-[#0070C0] hover:bg-[#005a9f] transition-colors">
						<span class="box-border text-white text-center text-xs font-bold uppercase m-0 p-0"><?php esc_html_e( 'Actividades', 'blankslate' ); ?></span>
						<span class="box-border text-white text-sm m-0 p-0">→</span>
					</a>
				</div>
			</div>

			<h1 class="fusion-hero-title box-border text-white font-black leading-[0.9] tracking-tighter uppercase m-0 p-0 text-center text-5xl sm:text-7xl md:text-8xl lg:text-9xl max-sm:text-4xl max-sm:leading-[1]">
				<?php esc_html_e( 'Fusion Tours', 'blankslate' ); ?>
			</h1>
		</div>
	</section>

	<section class="box-border flex w-full flex-col gap-12 m-0 px-20 py-16 max-md:px-10 max-sm:px-5 max-sm:gap-8">
		<div class="box-border flex justify-between items-start gap-8 m-0 p-0 max-md:flex-col max-md:gap-5">
			<h2 class="box-border text-black text-[32px] font-bold leading-tight m-0 p-0 max-md:text-[28px] max-sm:text-[24px]">
				<?php echo wp_kses_post( __( 'TOURS EN<br>QUINTANA ROO', 'blankslate' ) ); ?>
			</h2>
			<p class="box-border flex-1 text-[#6F6F6F] text-sm font-normal leading-relaxed m-0 p-0 max-w-[600px] max-md:text-sm">
				<?php esc_html_e( 'Prepárate para descubrir lo mejor del Caribe Mexicano con Fusion Tours Riviera Maya. Diseñamos experiencias que combinan aventura, cultura y relajación para que disfrutes cada minuto.', 'blankslate' ); ?>
			</p>
		</div>
		<div class="box-border relative w-full m-0 p-0">
			<button type="button" class="hidden md:flex absolute left-5 top-1/2 -translate-y-1/2 z-20 w-[30px] h-[30px] rounded-full bg-white/70 hover:bg-white/90 items-center justify-center transition-all hover:scale-110 fusion-tour-prev" aria-label="<?php esc_attr_e( 'Tour anterior', 'blankslate' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="#1F2937" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6 6-6"/></svg>
			</button>
			<div class="box-border flex gap-6 overflow-x-auto m-0 p-0 pb-4 scroll-smooth w-full max-sm:gap-4 scrollbar-hide snap-x snap-mandatory" data-fusion-tour-track>
				<?php foreach ( $tours as $tour ) : ?>
					<article class="group relative flex-shrink-0 w-[320px] h-[450px] snap-center max-lg:w-[280px] max-lg:h-[400px] max-md:w-[260px] max-md:h-[370px] max-sm:w-[85vw] max-sm:h-[340px] rounded-[20px] overflow-hidden cursor-pointer transition-all duration-300 shadow-lg hover:shadow-xl bg-white" data-fusion-tour-card>
						<div class="absolute inset-0 transition-all duration-500 md:group-hover:top-0 md:group-hover:left-4 md:group-hover:right-4 md:group-hover:bottom-auto md:group-hover:h-[50%] md:group-hover:rounded-[16px] md:group-hover:shadow-lg max-md:top-0 max-md:left-4 max-md:right-4 max-md:h-[50%] max-md:rounded-[16px] max-md:shadow-lg overflow-hidden">
							<img class="w-full h-full object-cover" src="<?php echo esc_url( $tour['image'] ); ?>" alt="<?php echo esc_attr( $tour['name'] ); ?>" loading="lazy" />
							<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent transition-opacity duration-500 md:group-hover:opacity-0 max-md:opacity-0"></div>
						</div>
						<div class="absolute bottom-0 left-0 right-0 px-5 pb-5 transition-all duration-500 md:group-hover:top-[52%] md:group-hover:bottom-auto md:group-hover:px-5 md:group-hover:pt-4 md:group-hover:pb-5 max-md:top-[52%] max-md:px-5 max-md:pt-4">
							<h3 class="text-white md:text-white md:group-hover:text-black max-md:text-black text-xl font-bold mb-2 transition-colors duration-300 max-sm:text-lg">
								<?php echo esc_html( $tour['name'] ); ?>
							</h3>
							<p class="text-white/95 md:text-white/95 md:group-hover:text-gray-700 max-md:text-gray-700 text-sm leading-relaxed transition-colors duration-300 max-sm:text-xs line-clamp-2">
								<?php echo esc_html( $tour['description'] ); ?>
							</p>
							<a class="opacity-0 md:opacity-0 md:group-hover:opacity-100 max-md:opacity-100 transition-all duration-300 mt-3 flex items-center gap-2 bg-[#0070C0] px-5 py-2.5 rounded-full hover:bg-[#005a9f]" href="<?php echo esc_url( $tour['link'] ); ?>">
								<span class="text-white text-sm font-bold uppercase"><?php esc_html_e( 'Ver tour', 'blankslate' ); ?></span>
								<svg width="12" height="8" viewBox="0 0 16 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 3.18201C0.223858 3.18201 2.41411e-08 3.40586 0 3.68201C-2.41411e-08 3.95815 0.223858 4.18201 0.5 4.18201L0.5 3.68201L0.5 3.18201ZM15.8536 4.03556C16.0488 3.8403 16.0488 3.52372 15.8536 3.32845L12.6716 0.146474C12.4763 -0.0487882 12.1597 -0.0487882 11.9645 0.146474C11.7692 0.341736 11.7692 0.658319 11.9645 0.853581L14.7929 3.68201L11.9645 6.51043C11.7692 6.7057 11.7692 7.02228 11.9645 7.21754C12.1597 7.4128 12.4763 7.4128 12.6716 7.21754L15.8536 4.03556ZM0.5 3.68201L0.5 4.18201L15.5 4.18201L15.5 3.68201L15.5 3.18201L0.5 3.18201L0.5 3.68201Z" fill="white"/></svg>
							</a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
			<button type="button" class="hidden md:flex absolute right-5 top-1/2 -translate-y-1/2 z-20 w-[30px] h-[30px] rounded-full bg-white/70 hover:bg-white/90 items-center justify-center transition-all hover:scale-110 fusion-tour-next" aria-label="<?php esc_attr_e( 'Tour siguiente', 'blankslate' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="#1F2937" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 6l6 6-6 6"/></svg>
			</button>
		</div>
	</section>

	<section id="actividades" class="box-border relative flex w-full flex-col items-center gap-12 bg-[#FFFDE5] m-0 px-20 py-16 max-md:gap-8 max-md:px-10 max-md:py-12 max-sm:px-5 max-sm:py-8 z-0">
		<div class="box-border flex flex-col justify-center items-center gap-6 m-0 p-0 max-w-[800px]">
			<div class="box-border flex justify-center items-center gap-2.5 bg-[#FF8B4C] m-0 px-3 py-1 rounded-full">
				<span class="box-border text-white text-center text-xs font-bold uppercase m-0 p-0">
					<?php esc_html_e( 'Cancún', 'blankslate' ); ?>
				</span>
			</div>
			<h2 class="box-border text-[#0070C0] text-center text-[28px] font-bold leading-tight m-0 p-0 max-md:text-[24px] max-sm:text-[20px]">
				<?php esc_html_e( 'Actividades imperdibles en el Caribe Mexicano', 'blankslate' ); ?>
			</h2>
			<p class="box-border text-[#6F6F6F] text-center text-sm font-normal leading-relaxed m-0 p-0 max-sm:text-sm">
				<?php esc_html_e( 'Disfruta de experiencias inolvidables en lugares como Xcaret, Xel-Há y Holbox. Sumérgete en ríos subterráneos, deslízate por tirolesas, nada entre peces tropicales o vive espectáculos inigualables.', 'blankslate' ); ?>
			</p>
		</div>
		<div class="box-border w-full relative m-0 p-0 flex items-center justify-center">
			<div class="relative w-full max-w-[1200px] h-[540px] flex items-center justify-center max-md:max-w-[900px] max-md:h-[480px] max-sm:h-[400px]" data-fusion-activities>
				<?php foreach ( $activities as $index => $activity ) : ?>
					<div class="fusion-activity-card absolute transition-all duration-500 ease-out" data-activity-index="<?php echo esc_attr( $index ); ?>">
						<div class="relative overflow-hidden rounded-[20px] shadow-2xl w-[300px] h-[360px] max-lg:w-[260px] max-lg:h-[320px] max-md:w-[220px] max-md:h-[280px]" data-activity-card-body>
							<img class="absolute inset-0 w-full h-full object-cover" src="<?php echo esc_url( $activity['image'] ); ?>" alt="<?php echo esc_attr( $activity['name'] ); ?>" loading="lazy" />
							<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
							<div class="absolute bottom-0 left-0 right-0 px-6 pb-6 max-sm:px-4 max-sm:pb-4">
								<p class="text-white/70 text-xs font-bold uppercase tracking-wider mb-1 max-sm:text-[10px]" data-activity-category><?php echo esc_html( $activity['category'] ); ?></p>
								<h3 class="text-white font-bold mb-2 text-lg max-lg:text-base max-sm:text-sm" data-activity-title><?php echo esc_html( $activity['name'] ); ?></h3>
								<p class="text-white/90 text-sm leading-relaxed max-lg:text-xs line-clamp-3 hidden" data-activity-description><?php echo esc_html( $activity['description'] ); ?></p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<button type="button" class="fusion-activity-prev hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white/90 w-[42px] h-[42px] rounded-full items-center justify-center shadow-md transition-all">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="#1F2937" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6 6-6"/></svg>
			</button>
			<button type="button" class="fusion-activity-next hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white/90 w-[42px] h-[42px] rounded-full items-center justify-center shadow-md transition-all">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="#1F2937" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 6l6 6-6 6"/></svg>
			</button>
		</div>
	</section>

	<section class="box-border flex w-full flex-col items-start gap-10 m-0 px-20 py-16 max-md:px-10 max-sm:px-5 max-sm:gap-8">
		<div class="box-border flex justify-between items-start gap-8 w-full m-0 p-0 max-md:flex-col max-md:gap-5">
			<h2 class="box-border text-black text-[32px] font-bold leading-tight m-0 p-0 max-md:text-[28px] max-sm:text-[24px]">
				<?php echo wp_kses_post( __( 'CONOCE SOBRE<br>FUSION TOURS', 'blankslate' ) ); ?>
			</h2>
			<p class="box-border flex-1 text-[#6F6F6F] text-sm font-normal leading-relaxed m-0 p-0 max-w-[600px]">
				<?php esc_html_e( 'Fusion Tours Riviera Maya nació con la misión de ofrecer experiencias auténticas, seguras y llenas de emoción. Tu satisfacción es nuestra mayor recompensa.', 'blankslate' ); ?>
			</p>
		</div>
		<div class="box-border h-[400px] w-full relative m-0 p-0 max-md:h-[300px]">
			<div class="box-border w-full h-full bg-cover bg-center m-0 p-0 rounded-lg" style="background-image:url('https://api.builder.io/api/v1/image/assets/TEMP/745b74eac0718e6fe5533f67aae81a3c03297a50?width=2866');"></div>
			<a class="box-border inline-flex justify-center items-center gap-3 absolute bg-[#0070C0] m-0 px-6 py-2.5 rounded-full right-6 bottom-6 hover:bg-[#005a9f] transition-colors text-white text-center text-sm font-bold uppercase" href="<?php echo esc_url( home_url( '/about' ) ); ?>">
				<?php esc_html_e( 'Conócenos', 'blankslate' ); ?>
			</a>
		</div>
		<div class="box-border flex justify-around items-start w-full m-0 p-0 max-md:flex-col max-md:gap-8">
			<?php foreach ( $stats as $stat ) : ?>
				<div class="box-border flex items-end gap-4 m-0 p-0 max-sm:flex-col max-sm:items-start max-sm:gap-2">
					<div class="box-border text-black text-[72px] font-bold leading-none m-0 p-0 max-md:text-[60px] max-sm:text-[48px]">
						<?php echo esc_html( $stat['number'] ); ?>
					</div>
					<div class="box-border w-[140px] text-[#686868] text-sm font-bold leading-tight mb-2 m-0 p-0 max-sm:w-full max-sm:text-xs">
						<?php echo esc_html( $stat['description'] ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="box-border w-full relative m-0 px-20 py-12 max-md:px-10 max-sm:px-5 overflow-hidden z-0">
		<div class="max-w-7xl mx-auto">
			<div class="hidden md:grid grid-cols-[1fr_auto_1fr] gap-8 items-center">
				<div class="flex flex-col relative">
					<?php foreach ( $clients_left as $index => $client ) : ?>
						<img class="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200" src="<?php echo esc_url( $client['src'] ); ?>" alt="<?php echo esc_attr( $client['alt'] ); ?>" loading="lazy" style="<?php
						$styles = [
							'0' => 'width:240px;height:180px;margin-left:60px;margin-bottom:10px;',
							'1' => 'width:280px;height:200px;margin-top:-30px;margin-left:15px;',
							'2' => 'width:260px;height:190px;margin-top:-20px;margin-left:80px;',
							'3' => 'width:300px;height:220px;margin-top:-25px;margin-left:5px;',
							'4' => 'width:240px;height:180px;margin-top:-15px;margin-left:70px;',
						];
						echo esc_attr( $styles[ (string) $index ] );
						?>" />
					<?php endforeach; ?>
				</div>
				<div class="flex flex-col items-center gap-6 px-8 py-8">
					<div class="bg-white/95 backdrop-blur-sm rounded-xl p-6 text-center max-w-sm shadow-lg">
						<h2 class="box-border text-black text-center text-[32px] font-bold leading-tight m-0 p-0 mb-4">
							<?php esc_html_e( 'Nuestros Clientes', 'blankslate' ); ?>
						</h2>
						<p class="box-border text-[#6F6F6F] text-center text-sm font-normal leading-relaxed m-0 p-0 mb-6">
							<?php esc_html_e( 'Cada experiencia compartida nos motiva a seguir ofreciendo tours inolvidables, atención personalizada y momentos únicos en la Riviera Maya.', 'blankslate' ); ?>
						</p>
						<a class="box-border flex justify-center items-center gap-3 bg-[#0070C0] m-0 mx-auto px-6 py-2.5 rounded-full hover:bg-[#005a9f] transition-colors text-white text-center text-sm font-bold uppercase" href="<?php echo esc_url( home_url( '/galeria' ) ); ?>">
							<?php esc_html_e( 'Ver más', 'blankslate' ); ?>
						</a>
					</div>
				</div>
				<div class="flex flex-col relative">
					<?php foreach ( $clients_right as $index => $client ) : ?>
						<img class="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200" src="<?php echo esc_url( $client['src'] ); ?>" alt="<?php echo esc_attr( $client['alt'] ); ?>" loading="lazy" style="<?php
						$styles = [
							'0' => 'width:280px;height:200px;margin-top:30px;margin-left:10px;',
							'1' => 'width:240px;height:180px;margin-top:-20px;margin-left:75px;',
							'2' => 'width:300px;height:220px;margin-top:-25px;margin-left:0;',
							'3' => 'width:260px;height:190px;margin-top:-30px;margin-left:65px;',
							'4' => 'width:280px;height:200px;margin-top:-15px;margin-left:20px;',
						];
						echo esc_attr( $styles[ (string) $index ] );
						?>" />
					<?php endforeach; ?>
				</div>
			</div>
			<div class="md:hidden flex flex-col items-center gap-6">
				<h2 class="text-black text-center text-[28px] font-bold leading-tight max-sm:text-[24px]">
					<?php esc_html_e( 'Qué dicen nuestros clientes', 'blankslate' ); ?>
				</h2>
				<div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full">
					<div class="relative mb-6 overflow-hidden rounded-lg" data-fusion-clients>
						<div class="flex transition-transform duration-500 ease-out" data-fusion-clients-track>
							<?php foreach ( array_merge( $clients_left, $clients_right ) as $client ) : ?>
								<img class="w-full h-[280px] object-cover flex-shrink-0" src="<?php echo esc_url( $client['src'] ); ?>" alt="<?php echo esc_attr( $client['alt'] ); ?>" loading="lazy" />
							<?php endforeach; ?>
						</div>
						<div class="absolute bottom-3 right-3 flex gap-1 animate-pulse">
							<div class="w-1.5 h-1.5 rounded-full bg-white/80 animate-bounce" style="animation-delay:0ms"></div>
							<div class="w-1.5 h-1.5 rounded-full bg-white/80 animate-bounce" style="animation-delay:150ms"></div>
							<div class="w-1.5 h-1.5 rounded-full bg-white/80 animate-bounce" style="animation-delay:300ms"></div>
						</div>
						<div class="flex justify-center gap-2 mt-4">
							<?php foreach ( array_merge( $clients_left, $clients_right ) as $index => $client ) : ?>
								<button type="button" class="w-2 h-2 rounded-full transition-all duration-300 bg-gray-300" data-client-dot="<?php echo esc_attr( $index ); ?>" aria-label="<?php printf( esc_attr__( 'Ver imagen %d', 'blankslate' ), $index + 1 ); ?>"></button>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="text-center">
						<p class="text-[#6F6F6F] text-sm leading-relaxed mb-4">
							<?php esc_html_e( 'Historias reales de viajeros que eligieron Fusion Tours para vivir el Caribe Mexicano como nunca antes.', 'blankslate' ); ?>
						</p>
						<a class="box-border inline-flex justify-center items-center gap-3 bg-[#0070C0] m-0 px-6 py-2.5 rounded-full hover:bg-[#005a9f] transition-colors text-white text-center text-sm font-bold uppercase" href="<?php echo esc_url( home_url( '/reseñas' ) ); ?>">
							<?php esc_html_e( 'Ver testimonios', 'blankslate' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="box-border flex w-full flex-col justify-center items-center gap-8 m-0 px-20 py-16 max-md:px-10 max-sm:px-5 max-sm:py-8">
		<div class="box-border flex flex-col items-center gap-8 m-0 p-0 w-full max-w-4xl">
			<h2 class="box-border text-black text-center text-[32px] font-bold leading-tight m-0 p-0 max-sm:text-[24px]">
				<?php esc_html_e( 'Preguntas frecuentes', 'blankslate' ); ?>
			</h2>
			<div class="box-border flex flex-col items-start m-0 p-0 w-full">
				<div class="fusion-faqs w-full">
					<?php foreach ( $faqs as $index => $faq ) : ?>
						<div class="border-t-black/20 border-t border-solid">
							<button type="button" class="box-border flex w-full justify-between items-center gap-4 relative m-0 px-0 py-4 text-left hover:no-underline fusion-faq-trigger" aria-expanded="false">
								<span class="box-border text-[#212121] text-base font-medium leading-relaxed flex-1 m-0 p-0 max-sm:text-sm"><?php echo esc_html( $faq['question'] ); ?></span>
								<span class="fusion-faq-icon text-[#0070C0] text-xl font-bold">+</span>
							</button>
							<div class="fusion-faq-content box-border flex flex-col items-start gap-2 m-0 px-0 overflow-hidden" data-faq-index="<?php echo esc_attr( $index ); ?>">
								<p class="box-border text-[#666] text-sm font-normal leading-relaxed m-0 p-0 pb-4">
									<?php echo esc_html( $faq['answer'] ); ?>
								</p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<a class="box-border flex justify-center items-center gap-3 bg-[#0070C0] m-0 px-6 py-2.5 rounded-full hover:bg-[#005a9f] transition-colors text-white text-center text-sm font-bold uppercase" href="<?php echo esc_url( home_url( '/faqs' ) ); ?>">
			<?php esc_html_e( 'Ver todas', 'blankslate' ); ?>
		</a>
	</section>

</article>

<?php
get_footer();
