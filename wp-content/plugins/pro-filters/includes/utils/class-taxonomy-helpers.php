<?php
namespace Brandoon\WooFilterPro;

/**
 * Shared helpers for taxonomy operations used across the plugin.
 */
class Taxonomy_Helpers {
    /**
     * Group slugs by their top-level parent ID within the provided taxonomy.
     *
     * @param array  $slugs    Term slugs to group.
     * @param string $taxonomy Taxonomy name.
     * @return array<int,array<string>> Grouped by parent term ID.
     */
    public static function group_terms_by_top_parent(array $slugs, $taxonomy) {
        $groups = [];

        foreach ($slugs as $slug) {
            $term = get_term_by('slug', $slug, $taxonomy);
            if (! $term || is_wp_error($term)) {
                continue;
            }

            $top_id = self::get_top_level_parent_id($term);
            if (! isset($groups[$top_id])) {
                $groups[$top_id] = [];
            }

            $groups[$top_id][] = $slug;
        }

        return $groups;
    }

    /**
     * Resolve the top-level parent ID of a term.
     *
     * @param \WP_Term $term Term instance.
     * @return int Parent term ID, or the term ID itself if already top-level.
     */
    public static function get_top_level_parent_id(\WP_Term $term) {
        if ($term->parent === 0) {
            return (int) $term->term_id;
        }

        $ancestors = get_ancestors($term->term_id, $term->taxonomy);
        if (empty($ancestors) || is_wp_error($ancestors)) {
            return (int) $term->parent;
        }

        $top = end($ancestors);
        if (! $top) {
            return (int) $term->parent;
        }

        return (int) $top;
    }
}
