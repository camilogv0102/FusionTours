<?php
/**
 * Template for the `productos_ajax` shortcode output.
 *
 * Expected variables provided by the shortcode context:
 * - WP_Query $loop  Query containing the filtered products.
 * - int      $paged Current page number.
 * - int      $count Total number of matching products.
 *
 * @package Woo_Filter_Pro
 */

if (! isset($loop) || ! ($loop instanceof WP_Query)) {
    return;
}

$count       = isset($count) ? (int) $count : 0;
$currentPage = isset($paged) ? max(1, (int) $paged) : 1;
$totalPages  = (int) $loop->max_num_pages;
$baseUrl     = remove_query_arg('pagina');
?>

<div id="productos-filtrados">
    <div class="productos-cantidad" data-total="<?php echo esc_attr($count); ?>">
        <?php
        printf(
            esc_html(_n('%d producto encontrado', '%d productos encontrados', $count, 'woo-filter-pro')),
            $count
        );
        ?>
    </div>

    <?php if ($loop->have_posts()) : ?>
        <ul class="products columns-4">
            <?php while ($loop->have_posts()) : $loop->the_post(); ?>
                <?php wc_get_template_part('content', 'product'); ?>
            <?php endwhile; ?>
        </ul>

        <?php if ($totalPages > 1) : ?>
            <nav class="ajax-pagination" role="navigation" aria-label="<?php esc_attr_e('Paginación de productos', 'woo-filter-pro'); ?>">
                <?php if ($currentPage > 1) :
                    $prevPage = $currentPage - 1;
                    $prevUrl  = $prevPage === 1 ? $baseUrl : add_query_arg('pagina', $prevPage, $baseUrl);
                    ?>
                    <a class="prev" href="<?php echo esc_url($prevUrl); ?>" data-page="<?php echo esc_attr($prevPage); ?>">
                        <?php esc_html_e('Anterior', 'woo-filter-pro'); ?>
                    </a>
                <?php endif; ?>

                <?php
                $pagesToShow = array_unique(array_merge(
                    [1, $totalPages],
                    range(max(1, $currentPage - 1), min($totalPages, $currentPage + 1))
                ));
                sort($pagesToShow);
                $lastPage = 0;
                foreach ($pagesToShow as $page) :
                    if ($lastPage && $page > $lastPage + 1) :
                        ?>
                        <span class="dots">&hellip;</span>
                        <?php
                    endif;

                    if ($page === $currentPage) :
                        ?>
                        <span class="current"><?php echo esc_html($page); ?></span>
                        <?php
                    else :
                        $pageUrl = $page === 1 ? $baseUrl : add_query_arg('pagina', $page, $baseUrl);
                        ?>
                        <a href="<?php echo esc_url($pageUrl); ?>" data-page="<?php echo esc_attr($page); ?>">
                            <?php echo esc_html($page); ?>
                        </a>
                        <?php
                    endif;

                    $lastPage = $page;
                endforeach;
                ?>

                <?php if ($currentPage < $totalPages) :
                    $nextPage = $currentPage + 1;
                    $nextUrl  = add_query_arg('pagina', $nextPage, $baseUrl);
                    ?>
                    <a class="next" href="<?php echo esc_url($nextUrl); ?>" data-page="<?php echo esc_attr($nextPage); ?>">
                        <?php esc_html_e('Siguiente', 'woo-filter-pro'); ?>
                    </a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php else : ?>
        <p class="no-products-found"><?php esc_html_e('No se encontraron productos que coincidan con tu selección.', 'woo-filter-pro'); ?></p>
    <?php endif; ?>
</div>
