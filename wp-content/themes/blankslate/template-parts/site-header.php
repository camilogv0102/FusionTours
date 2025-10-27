<?php
/**
 * Fusion Tours global site header.
 *
 * @package BlankSlate
 */

$mode_class = 'fusion-site-header--light';

$nav_links = [
	[
		'label' => __( 'Tours', 'blankslate' ),
		'url'   => get_term_link( 'tours', 'product_cat' ),
		'class' => 'fusion-site-header__link',
	],
	[
		'label' => __( 'Actividades', 'blankslate' ),
		'url'   => get_term_link( 'actividades', 'product_cat' ),
		'class' => 'fusion-site-header__link fusion-scroll-activities',
	],
	[
		'label' => __( 'Sobre nosotros', 'blankslate' ),
		'url'   => home_url( '/about' ),
		'class' => 'fusion-site-header__link',
	],
	[
		'label' => __( 'Contacto', 'blankslate' ),
		'url'   => home_url( '/contacto' ),
		'class' => 'fusion-site-header__link',
	],
];

$mobile_links = [
	[
		'label' => __( 'Inicio', 'blankslate' ),
		'url'   => home_url( '/' ),
	],
	[
		'label' => __( 'Tours', 'blankslate' ),
		'url'   => home_url( '/tours' ),
	],
	[
		'label' => __( 'Actividades', 'blankslate' ),
		'url'   => home_url( '/#actividades' ),
		'class' => 'fusion-scroll-activities',
	],
	[
		'label' => __( 'Sobre nosotros', 'blankslate' ),
		'url'   => home_url( '/about' ),
	],
	[
		'label' => __( 'Contacto', 'blankslate' ),
		'url'   => home_url( '/contacto' ),
	],
];

$inner_classes = [
	'fusion-site-header__inner',
	'box-border',
	'flex',
	'w-[calc(100%_-_40px)]',
	'max-w-[1472px]',
	'h-[81px]',
	'justify-between',
	'items-center',
	'backdrop-blur-[54.29999923706055px]',
	'm-0',
	'px-10',
	'py-[25px]',
	'rounded-[10px]',
	'top-[30px]',
	'left-2/4',
	'max-lg:px-8',
	'max-lg:py-5',
	'max-md:px-6',
	'max-md:py-4',
	'max-md:h-[70px]',
	'max-sm:px-4',
	'max-sm:py-3',
	'max-sm:h-[60px]',
	'max-sm:w-[calc(100%_-_20px)]',
	'max-sm:top-[15px]',
	'fixed',
	'-translate-x-2/4',
	'z-50',
];

$inner_classes[] = 'bg-[rgba(45,45,45,0.40)]';

$mobile_menu_classes = [
	'fusion-mobile-menu',
	'hidden',
	'md:hidden',
	'flex',
	'w-[calc(100%_-_40px)]',
	'max-sm:w-[calc(100%_-_20px)]',
	'mt-3',
	'rounded-[10px]',
	'px-6',
	'py-5',
	'flex-col',
	'gap-4',
	'shadow-lg',
	'backdrop-blur-md',
];

$mobile_menu_classes[] = 'bg-[rgba(45,45,45,0.95)]';
$mobile_menu_classes[] = 'text-[#FFFDE5]';
?>

<header class="fusion-site-header <?php echo esc_attr( $mode_class ); ?>" role="banner">
	<div class="fusion-site-header__wrap absolute top-0 left-0 right-0 z-40 flex justify-center pt-[30px] max-sm:pt-[15px]">
		<div class="<?php echo esc_attr( implode( ' ', $inner_classes ) ); ?>">
			<a class="fusion-site-header__logo flex items-center cursor-pointer hover:opacity-80 transition-opacity" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<svg width="150" height="33" viewBox="0 0 150 33" fill="none" xmlns="http://www.w3.org/2000/svg" class="logo max-md:w-[120px] max-sm:w-[100px] max-sm:h-[20px]" role="img" aria-hidden="true">
					<text fill="currentColor" xml:space="preserve" style="white-space:pre" font-family="Neue Montreal" font-size="43.8721" font-weight="bold" letter-spacing="0em">
						<tspan x="0" y="32.4391">FS</tspan>
					</text>
				</svg>
			</a>

			<nav class="fusion-site-header__nav box-border flex items-center gap-[30px] m-0 p-0 max-lg:gap-5 max-md:hidden" aria-label="<?php esc_attr_e( 'Menú principal', 'blankslate' ); ?>">
				<?php foreach ( $nav_links as $link ) : ?>
					<a class="<?php echo esc_attr( $link['class'] ); ?> box-border flex justify-center items-center m-0 p-0 cursor-pointer hover:opacity-80 transition-opacity" href="<?php echo esc_url( $link['url'] ); ?>">
						<span class="box-border text-center text-base font-bold leading-[26px] uppercase m-0 p-0"><?php echo esc_html( $link['label'] ); ?></span>
					</a>
				<?php endforeach; ?>
			</nav>

			<div class="fusion-site-header__actions box-border flex items-center gap-10 m-0 p-0 max-lg:gap-6 max-md:gap-4">
				<div class="box-border flex items-center gap-[30px] m-0 p-0 max-lg:gap-5 max-md:hidden">
					<button type="button" class="fusion-language-toggle box-border flex justify-center items-center gap-[15px] m-0 p-0 cursor-pointer hover:opacity-80 transition-opacity" aria-haspopup="true" aria-expanded="false">
						<span class="box-border text-center text-base font-bold leading-[26px] uppercase m-0 p-0" data-language-label>ES</span>
						<svg width="7" height="5" viewBox="0 0 7 5" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M3.95007 3.94964C3.86649 4.03325 3.76726 4.09956 3.65805 4.14481C3.54884 4.19006 3.43178 4.21335 3.31357 4.21335C3.19535 4.21335 3.0783 4.19006 2.96909 4.14481C2.85987 4.09956 2.76064 4.03325 2.67707 3.94964L0.277068 1.54964C0.190585 1.46672 0.121529 1.36737 0.0739491 1.25741C0.0263693 1.14745 0.00122424 1.0291 -1.22098e-05 0.90929C-0.00124866 0.789484 0.0214488 0.670637 0.0667488 0.559719C0.112049 0.4488 0.179041 0.348043 0.263794 0.263356C0.348548 0.178669 0.449357 0.111757 0.56031 0.0665441C0.671264 0.0213309 0.790129 -0.00127264 0.909934 5.77203e-05C1.02974 0.00138809 1.14807 0.0266266 1.25721 0.0739795C1.36636 0.121332 1.46558 0.190091 1.54907 0.276644L3.31357 2.04114L5.07807 0.276644C5.24908 0.105639 5.48757 0.0095184 5.73532 0.0095184C5.98307 0.0095184 6.22156 0.105639 6.39257 0.276644C6.56357 0.44765 6.65969 0.686139 6.65969 0.933889C6.65969 1.18164 6.56357 1.42013 6.39257 1.59114L3.94944 3.94964H3.95007Z" fill="currentColor"/>
						</svg>
					</button>
					<div class="relative">
						<button type="button" class="fusion-currency-toggle box-border flex justify-center items-center gap-[15px] m-0 p-0 cursor-pointer hover:opacity-80 transition-opacity" aria-haspopup="true" aria-expanded="false">
							<span class="box-border text-center text-base font-bold leading-[26px] uppercase m-0 p-0" data-currency-label>MXN</span>
							<svg width="7" height="5" viewBox="0 0 7 5" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M3.95007 3.94964C3.86649 4.03325 3.76726 4.09956 3.65805 4.14481C3.54884 4.19006 3.43178 4.21335 3.31357 4.21335C3.19535 4.21335 3.0783 4.19006 2.96909 4.14481C2.85987 4.09956 2.76064 4.03325 2.67707 3.94964L0.277068 1.54964C0.190585 1.46672 0.121529 1.36737 0.0739491 1.25741C0.0263693 1.14745 0.00122424 1.0291 -1.22098e-05 0.90929C-0.00124866 0.789484 0.0214488 0.670637 0.0667488 0.559719C0.112049 0.4488 0.179041 0.348043 0.263794 0.263356C0.348548 0.178669 0.449357 0.111757 0.56031 0.0665441C0.671264 0.0213309 0.790129 -0.00127264 0.909934 5.77203e-05C1.02974 0.00138809 1.14807 0.0266266 1.25721 0.0739795C1.36636 0.121332 1.46558 0.190091 1.54907 0.276644L3.31357 2.04114L5.07807 0.276644C5.24908 0.105639 5.48757 0.0095184 5.73532 0.0095184C5.98307 0.0095184 6.22156 0.105639 6.39257 0.276644C6.56357 0.44765 6.65969 0.686139 6.65969 0.933889C6.65969 1.18164 6.56357 1.42013 6.39257 1.59114L3.94944 3.94964H3.95007Z" fill="currentColor"/>
							</svg>
						</button>
					</div>
				</div>

				<div class="fusion-site-header__divider box-border w-[1.5px] h-[11px] m-0 p-0 max-md:hidden"></div>

				<div class="fusion-site-header__icons box-border flex items-center gap-5 m-0 p-0 max-md:gap-3">
					<a class="fusion-site-header__icon-btn" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="<?php esc_attr_e( 'Cuenta de usuario', 'blankslate' ); ?>">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M12 13C14.7614 13 17 10.7614 17 8C17 5.23858 14.7614 3 12 3C9.23858 3 7 5.23858 7 8C7 10.7614 9.23858 13 12 13Z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M20 21C20 18.8783 19.1571 16.8434 17.6569 15.3431C16.1566 13.8429 14.1217 13 12 13C9.87827 13 7.84344 13.8429 6.34315 15.3431C4.84285 16.8434 4 18.8783 4 21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</a>
					<a class="fusion-site-header__icon-btn" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Carrito de compras', 'blankslate' ); ?>">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M17 18C17.5304 18 18.0391 18.2107 18.4142 18.5858C18.7893 18.9609 19 19.4696 19 20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22C16.4696 22 15.9609 21.7893 15.5858 21.4142C15.2107 21.0391 15 20.5304 15 20C15 18.89 15.89 18 17 18ZM1 2H4.27L5.21 4H20C20.2652 4 20.5196 4.10536 20.7071 4.29289C20.8946 4.48043 21 4.73478 21 5C21 5.17 20.95 5.34 20.88 5.5L17.3 11.97C16.96 12.58 16.3 13 15.55 13H8.1L7.2 14.63L7.17 14.75C7.17 14.8163 7.19634 14.8799 7.24322 14.9268C7.29011 14.9737 7.3537 15 7.42 15H19V17H7C6.46957 17 5.96086 16.7893 5.58579 16.4142C5.21071 16.0391 5 15.5304 5 15C5 14.65 5.09 14.32 5.24 14.04L6.6 11.59L3 4H1V2ZM7 18C7.53043 18 8.03914 18.2107 8.41421 18.5858C8.78929 18.9609 9 19.4696 9 20C9 20.5304 8.78929 21.0391 8.41421 21.4142C8.03914 21.7893 7.53043 22 7 22C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20C5 18.89 5.89 18 7 18Z" fill="currentColor"/>
						</svg>
					</a>
				</div>

				<button type="button" class="fusion-mobile-menu-toggle md:hidden" aria-label="<?php esc_attr_e( 'Alternar menú móvil', 'blankslate' ); ?>" aria-expanded="false">
					<svg class="fusion-mobile-menu-toggle__icon fusion-mobile-menu-toggle__icon--menu" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M3 6H21M3 12H21M3 18H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
					</svg>
					<svg class="fusion-mobile-menu-toggle__icon fusion-mobile-menu-toggle__icon--close" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			</div>
		</div>

		<div class="<?php echo esc_attr( implode( ' ', $mobile_menu_classes ) ); ?>">
			<?php foreach ( $mobile_links as $mobile_link ) : ?>
				<a class="font-bold uppercase py-2 border-b border-gray-200 <?php echo isset( $mobile_link['class'] ) ? esc_attr( $mobile_link['class'] ) : ''; ?>" href="<?php echo esc_url( $mobile_link['url'] ); ?>">
					<?php echo esc_html( $mobile_link['label'] ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</header>
