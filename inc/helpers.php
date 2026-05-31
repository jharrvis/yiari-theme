<?php
/**
 * YIARI Theme – helpers.php
 * Fungsi bantu yang dipakai di seluruh template.
 */

defined('ABSPATH') || exit;

function yiari_img($image, string $size = 'full', string $class = '', string $alt_fallback = ''): void {
    if (!empty($image['url'])) {
        $url = !empty($image['sizes'][$size]) ? $image['sizes'][$size] : $image['url'];
        $alt = esc_attr($image['alt'] ?: $alt_fallback);
        printf('<img src="%s" alt="%s" class="%s" />', esc_url($url), $alt, esc_attr($class));
        return;
    }

    printf(
        '<img src="%s/assets/img/placeholder.png" alt="%s" class="%s" />',
        esc_url(get_template_directory_uri()),
        esc_attr($alt_fallback),
        esc_attr($class)
    );
}

function yiari_btn(string $text, string $url, string $class = 'btn-primary'): void {
    if (empty($url) || empty($text)) {
        return;
    }

    printf(
        '<a href="%s" class="%s">%s</a>',
        esc_url($url),
        esc_attr($class),
        esc_html($text)
    );
}

function yiari_render_detail_landscape_update_card(int $post_id): string {
    $title = get_the_title($post_id);
    $permalink = get_permalink($post_id);
    $image = get_the_post_thumbnail_url($post_id, 'card-thumb') ?: (get_template_directory_uri() . '/assets/img/hero-section.jpg');
    $excerpt = has_excerpt($post_id)
        ? get_the_excerpt($post_id)
        : wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $post_id)), 18, '…');

    ob_start();
    ?>
    <article class="detail-landscape-update-card">
      <a href="<?php echo esc_url($permalink); ?>" class="detail-landscape-update-image-wrap" aria-label="<?php echo esc_attr($title); ?>">
        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="detail-landscape-update-image" />
      </a>
      <div class="detail-landscape-update-body">
        <h3 class="detail-landscape-update-title">
          <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
        </h3>
        <p class="detail-landscape-update-excerpt"><?php echo esc_html($excerpt); ?></p>
        <a href="<?php echo esc_url($permalink); ?>" class="link-more"><?php echo esc_html__('Baca Selengkapnya', 'yiari'); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
      </div>
    </article>
    <?php

    return trim((string) ob_get_clean());
}

function yiari_section_label(string $text, bool $circle = false): void {
    $class = $circle ? 'section-label section-label-circle' : 'section-label';
    printf('<span class="%s">%s</span>', esc_attr($class), esc_html($text));
}

function yiari_field(string $key, $default = '') {
    if (!function_exists('get_field')) {
        return $default;
    }

    $value = get_field($key);
    return ($value !== null && $value !== false && $value !== '') ? $value : $default;
}

function yiari_get_donation_initials(string $name): string {
    $initials = '';

    foreach (preg_split('/\s+/', trim($name)) as $part) {
        if ($part === '') {
            continue;
        }

        $initials .= function_exists('mb_substr') ? mb_substr($part, 0, 1) : substr($part, 0, 1);
        if (strlen($initials) >= 2) {
            break;
        }
    }

    return strtoupper($initials ?: 'DN');
}

function yiari_get_donation_avatar_url(string $email, string $name = '', int $size = 80): string {
    if (is_email($email)) {
        $avatar_url = get_avatar_url($email, [
            'size' => $size,
            'default' => 'mp',
            'scheme' => is_ssl() ? 'https' : 'http',
        ]) ?: '';

        if ($avatar_url !== '' && strpos($avatar_url, 'gravatar.com') !== false && strpos($avatar_url, 'd=404') === false) {
            return $avatar_url;
        }
    }

    $name = trim($name);
    if ($name !== '') {
        return sprintf(
            'https://ui-avatars.com/api/?name=%s&size=%d&background=EEF2FB&color=395AA8&bold=true&format=png',
            rawurlencode($name),
            max(32, min(256, $size))
        );
    }

    return '';
}

function yiari_format_donation_amount(object $donation): string {
    $amount = isset($donation->amount) ? (float) $donation->amount : 0.0;

    return sprintf(
        /* translators: %s is the formatted donation amount in IDR. */
        __('Sudah berdonasi Rp %s', 'yiari'),
        number_format($amount, 0, ',', '.')
    );
}

function yiari_get_successful_donations(int $limit = 5): array {
    global $wpdb;

    $table_name = $wpdb->prefix . 'midtrans_donations';

    if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table_name)) !== $table_name) {
        return [];
    }

    $limit = max(1, min(12, $limit));

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT donor_name, donor_email, amount, currency, original_amount, message, created_at
             FROM {$table_name}
             WHERE status IN ('success', 'PAID')
             AND donor_name <> ''
             ORDER BY created_at DESC
             LIMIT %d",
            $limit
        )
    );

    return is_array($results) ? $results : [];
}

function yiari_count_successful_donations(): int {
    global $wpdb;

    $table_name = $wpdb->prefix . 'midtrans_donations';

    if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table_name)) !== $table_name) {
        return 0;
    }

    return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table_name} WHERE status IN ('success', 'PAID')");
}

function yiari_home_url(): string {
    if (function_exists('pll_home_url')) {
        $current_lang = function_exists('pll_current_language') ? pll_current_language('slug') : '';
        $url = pll_home_url($current_lang ?: null);
        if (!empty($url)) {
            return $url;
        }
    }

    return home_url('/');
}

function yiari_fragment_url(string $fragment): string {
    return trailingslashit(yiari_home_url()) . '#' . ltrim($fragment, '#');
}

function yiari_translate_post_id(int $post_id): int {
    if ($post_id > 0 && function_exists('pll_get_post')) {
        $current_lang = function_exists('pll_current_language') ? pll_current_language('slug') : '';
        $translated_id = pll_get_post($post_id, $current_lang ?: null);
        if (!empty($translated_id)) {
            return (int) $translated_id;
        }
    }

    return $post_id;
}

function yiari_get_page_url_by_template(string $template, string $fallback = ''): string {
    $pages = get_posts([
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'suppress_filters' => false,
    ]);

    if ($pages) {
        $page_id = yiari_translate_post_id((int) $pages[0]->ID);
        return get_permalink($page_id) ?: $fallback;
    }

    return $fallback;
}

function yiari_get_posts_page_url(string $fallback = ''): string {
    $page_id = (int) get_option('page_for_posts');
    if ($page_id > 0) {
        $page_id = yiari_translate_post_id($page_id);
        return get_permalink($page_id) ?: $fallback;
    }

    return $fallback;
}

function yiari_get_privacy_url(): string {
    $page_id = (int) get_option('wp_page_for_privacy_policy');
    if ($page_id > 0) {
        $page_id = yiari_translate_post_id($page_id);
        return get_permalink($page_id) ?: get_privacy_policy_url();
    }

    return get_privacy_policy_url();
}

function yiari_get_page_url_by_paths(array $paths, string $fallback = ''): string {
    foreach ($paths as $path) {
        $path = trim((string) $path);
        if ($path === '') {
            continue;
        }

        $page = get_page_by_path(trim($path, '/'));
        if ($page instanceof WP_Post) {
            $page_id = yiari_translate_post_id((int) $page->ID);
            $url = get_permalink($page_id);
            if (!empty($url)) {
                return $url;
            }
        }
    }

    return $fallback;
}

function yiari_get_terms_url(): string {
    return yiari_get_page_url_by_paths(
        ['terms', 'terms-and-conditions', 'syarat-ketentuan'],
        yiari_home_url()
    );
}

function yiari_get_program_url(): string {
    return yiari_get_page_url_by_paths(
        ['program', 'programs'],
        yiari_home_url()
    );
}

function yiari_get_join_url(): string {
    return yiari_get_page_url_by_paths(
        ['bergabung', 'join', 'volunteer'],
        yiari_home_url()
    );
}

function yiari_fallback_primary_menu(): array {
    return [
        [
            'ID' => 101,
            'title' => __('Tentang', 'yiari'),
            'url' => yiari_get_page_url_by_template('templates/about.php', home_url('/tentang/')),
            'children' => [
                ['ID' => 102, 'title' => __('Tentang YIARI', 'yiari'), 'url' => yiari_get_page_url_by_template('templates/about.php', home_url('/tentang/')), 'children' => []],
                ['ID' => 103, 'title' => __('Lokasi Konservasi', 'yiari'), 'url' => yiari_get_page_url_by_paths(['lokasi-konservasi','conservation-locations'], home_url('/lokasi-konservasi/')), 'children' => []],
                ['ID' => 104, 'title' => __('Mitra dan Pendukung', 'yiari'), 'url' => yiari_get_page_url_by_paths(['mitra-dan-pendukung','partners-and-supporters'], home_url('/mitra-dan-pendukung/')), 'children' => []],
                ['ID' => 105, 'title' => __('Laporan', 'yiari'), 'url' => yiari_get_page_url_by_paths(['laporan','reports'], home_url('/laporan/')), 'children' => []],
            ],
        ],
        [
            'ID' => 201,
            'title' => __('Program', 'yiari'),
            'url' => yiari_get_program_url(),
            'children' => [
                ['ID' => 202, 'title' => __('Konservasi Satwa', 'yiari'), 'url' => yiari_get_page_url_by_paths(['program/konservasi-satwa','programs/wildlife-conservation'], home_url('/program/konservasi-satwa/')), 'children' => []],
                ['ID' => 203, 'title' => __('Konservasi Habitat', 'yiari'), 'url' => yiari_get_page_url_by_paths(['program/konservasi-habitat','programs/habitat-conservation'], home_url('/program/konservasi-habitat/')), 'children' => []],
                ['ID' => 204, 'title' => __('Edukasi dan Penyadartahuan', 'yiari'), 'url' => yiari_get_page_url_by_paths(['program/edukasi','programs/education'], home_url('/program/edukasi/')), 'children' => []],
                ['ID' => 205, 'title' => __('Pemberdayaan Masyarakat', 'yiari'), 'url' => yiari_get_page_url_by_paths(['program/pemberdayaan','programs/community-empowerment'], home_url('/program/pemberdayaan/')), 'children' => []],
                ['ID' => 206, 'title' => __('One Health', 'yiari'), 'url' => yiari_get_page_url_by_paths(['program/one-health','programs/one-health'], home_url('/program/one-health/')), 'children' => []],
            ],
        ],
        ['ID' => 301, 'title' => __('Cerita', 'yiari'), 'url' => yiari_get_posts_page_url(home_url('/cerita/')), 'children' => []],
        ['ID' => 302, 'title' => __('Publikasi', 'yiari'), 'url' => yiari_get_page_url_by_paths(['publikasi','publications'], home_url('/publikasi/')), 'children' => []],
        ['ID' => 303, 'title' => __('Bergabung', 'yiari'), 'url' => yiari_get_join_url(), 'children' => []],
    ];
}

function yiari_fallback_footer_menu(string $location): array {
    $fallbacks = [
        'footer-col1' => [
            ['title' => __('Tentang Kami', 'yiari'), 'url' => yiari_get_page_url_by_template('templates/about.php', home_url('/tentang/'))],
            ['title' => __('Lokasi Program', 'yiari'), 'url' => yiari_get_page_url_by_paths(['lokasi-program','program-locations'], home_url('/lokasi-program/'))],
            ['title' => __('Mitra dan Pendukung', 'yiari'), 'url' => yiari_get_page_url_by_paths(['mitra-dan-pendukung','partners-and-supporters'], home_url('/mitra-dan-pendukung/'))],
            ['title' => __('Laporan', 'yiari'), 'url' => yiari_get_page_url_by_paths(['laporan','reports'], home_url('/laporan/'))],
        ],
        'footer-col2' => [
            ['title' => __('Konservasi Satwa', 'yiari'), 'url' => yiari_get_page_url_by_paths(['program/konservasi-satwa','programs/wildlife-conservation'], home_url('/program/konservasi-satwa/'))],
            ['title' => __('Konservasi Habitat', 'yiari'), 'url' => yiari_get_page_url_by_paths(['program/konservasi-habitat','programs/habitat-conservation'], home_url('/program/konservasi-habitat/'))],
            ['title' => __('Edukasi', 'yiari'), 'url' => yiari_get_page_url_by_paths(['program/edukasi','programs/education'], home_url('/program/edukasi/'))],
            ['title' => __('Pemberdayaan', 'yiari'), 'url' => yiari_get_page_url_by_paths(['program/pemberdayaan','programs/community-empowerment'], home_url('/program/pemberdayaan/'))],
            ['title' => __('One Health', 'yiari'), 'url' => yiari_get_page_url_by_paths(['program/one-health','programs/one-health'], home_url('/program/one-health/'))],
        ],
        'footer-col3' => [
            ['title' => __('Cerita', 'yiari'), 'url' => yiari_get_posts_page_url(home_url('/cerita/'))],
            ['title' => __('Penelitian', 'yiari'), 'url' => yiari_get_page_url_by_paths(['penelitian','research'], home_url('/penelitian/'))],
            ['title' => __('Buku', 'yiari'), 'url' => yiari_get_page_url_by_paths(['buku','books'], home_url('/buku/'))],
            ['title' => __('Materi Edukasi', 'yiari'), 'url' => yiari_get_page_url_by_paths(['materi-edukasi','education-materials'], home_url('/materi-edukasi/'))],
            ['title' => __('Buletin', 'yiari'), 'url' => yiari_get_page_url_by_paths(['buletin','bulletin'], home_url('/buletin/'))],
            ['title' => __('Blog', 'yiari'), 'url' => yiari_get_page_url_by_paths(['blog'], home_url('/blog/'))],
            ['title' => __('Siaran Pers', 'yiari'), 'url' => yiari_get_page_url_by_paths(['siaran-pers','press-releases'], home_url('/siaran-pers/'))],
            ['title' => __('Galeri', 'yiari'), 'url' => yiari_get_page_url_by_paths(['galeri','gallery'], home_url('/galeri/'))],
        ],
    ];

    return $fallbacks[$location] ?? [];
}

function yiari_get_menu_tree(string $location, ?array $fallback = null): array {
    $locations = get_nav_menu_locations();
    $menu_id = $locations[$location] ?? 0;

    if (!$menu_id) {
        return $fallback ?? [];
    }

    $menu_items = wp_get_nav_menu_items($menu_id, ['update_post_term_cache' => false]);
    if (empty($menu_items)) {
        return $fallback ?? [];
    }

    usort($menu_items, static fn($a, $b) => (int) $a->menu_order <=> (int) $b->menu_order);

    $indexed = [];
    foreach ($menu_items as $item) {
        $indexed[$item->ID] = [
            'ID' => (int) $item->ID,
            'title' => $item->title,
            'url' => $item->url,
            'target' => $item->target,
            'children' => [],
        ];
    }

    $tree = [];
    foreach ($menu_items as $item) {
        $parent_id = (int) $item->menu_item_parent;
        if ($parent_id && isset($indexed[$parent_id])) {
            $indexed[$parent_id]['children'][] = &$indexed[$item->ID];
            continue;
        }

        $tree[] = &$indexed[$item->ID];
    }

    return $tree;
}

function yiari_get_flat_footer_menu(string $location): array {
    $items = yiari_get_menu_tree($location, []);
    if (!$items) {
        return yiari_fallback_footer_menu($location);
    }

    $flat = [];
    $stack = $items;
    while ($stack) {
        $item = array_shift($stack);
        $flat[] = [
            'title' => $item['title'],
            'url' => $item['url'],
            'target' => $item['target'] ?? '',
        ];

        foreach ($item['children'] as $child) {
            $stack[] = $child;
        }
    }

    return $flat;
}

function yiari_render_primary_desktop_menu(): void {
    $items = yiari_get_menu_tree('primary', yiari_fallback_primary_menu());

    foreach ($items as $item) {
        $has_children = !empty($item['children']);
        $url = !empty($item['url']) ? $item['url'] : '#';
        $target = !empty($item['target']) ? ' target="' . esc_attr($item['target']) . '" rel="noopener"' : '';

        if ($has_children) {
            echo '<div class="nav-item-dropdown">';
            printf(
                '<a href="%s" class="nav-link"%s>%s <i data-lucide="chevron-down" class="chevron submenu-chevron"></i></a>',
                esc_url($url),
                $target,
                esc_html($item['title'])
            );
            echo '<div class="dropdown-menu">';
            foreach ($item['children'] as $child) {
                $child_target = !empty($child['target']) ? ' target="' . esc_attr($child['target']) . '" rel="noopener"' : '';
                printf(
                    '<a href="%s"%s>%s</a>',
                    esc_url($child['url'] ?: '#'),
                    $child_target,
                    esc_html($child['title'])
                );
            }
            echo '</div></div>';
            continue;
        }

        printf(
            '<a href="%s" class="nav-link"%s>%s</a>',
            esc_url($url),
            $target,
            esc_html($item['title'])
        );
    }
}

function yiari_render_primary_mobile_menu(): void {
    $items = yiari_get_menu_tree('primary', yiari_fallback_primary_menu());

    foreach ($items as $index => $item) {
        $has_children = !empty($item['children']);
        $url = !empty($item['url']) ? $item['url'] : '#';
        $target = !empty($item['target']) ? ' target="' . esc_attr($item['target']) . '" rel="noopener"' : '';
        $state_key = 'menu-' . ($item['ID'] ?: $index);

        if ($has_children) {
            echo '<div class="mobile-dropdown">';
            printf(
                '<a href="%s" class="nav-link mobile-dropdown-toggle" @click.prevent="toggleMobileDropdown(\'%s\')" :class="{ active: isMobileDropdownOpen(\'%s\') }">%s <i data-lucide="chevron-down" class="chevron submenu-chevron"></i></a>',
                esc_url($url),
                esc_attr($state_key),
                esc_attr($state_key),
                esc_html($item['title'])
            );
            printf('<div class="mobile-dropdown-menu" :class="{ open: isMobileDropdownOpen(\'%s\') }">', esc_attr($state_key));
            foreach ($item['children'] as $child) {
                $child_target = !empty($child['target']) ? ' target="' . esc_attr($child['target']) . '" rel="noopener"' : '';
                printf(
                    '<a href="%s"%s>%s</a>',
                    esc_url($child['url'] ?: '#'),
                    $child_target,
                    esc_html($child['title'])
                );
            }
            echo '</div></div>';
            continue;
        }

        printf(
            '<a href="%s" class="nav-link"%s>%s</a>',
            esc_url($url),
            $target,
            esc_html($item['title'])
        );
    }
}

function yiari_render_footer_menu_column(string $location, string $heading, bool $wide = false): void {
    $items = yiari_get_flat_footer_menu($location);
    $column_class = $wide ? 'footer-links-col footer-links-col-wide' : 'footer-links-col';

    echo '<div class="' . esc_attr($column_class) . '">';
    echo '<div class="footer-heading">' . esc_html($heading) . '</div>';

    if ($wide) {
        $groups = array_chunk($items, (int) ceil(max(count($items), 1) / 2));
        echo '<div class="footer-links-split">';
        foreach ($groups as $group) {
            echo '<div class="footer-links-group">';
            foreach ($group as $item) {
                $target = !empty($item['target']) ? ' target="' . esc_attr($item['target']) . '" rel="noopener"' : '';
                printf('<a href="%s"%s>%s</a>', esc_url($item['url'] ?: '#'), $target, esc_html($item['title']));
            }
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
        return;
    }

    foreach ($items as $item) {
        $target = !empty($item['target']) ? ' target="' . esc_attr($item['target']) . '" rel="noopener"' : '';
        printf('<a href="%s"%s>%s</a>', esc_url($item['url'] ?: '#'), $target, esc_html($item['title']));
    }

    echo '</div>';
}

function yiari_get_language_switcher_items(): array {
    if (function_exists('pll_the_languages')) {
        $languages = pll_the_languages([
            'raw' => 1,
            'hide_if_no_translation' => 0,
            'hide_current' => 0,
        ]);

        if (is_array($languages) && $languages) {
            $items = [];
            foreach ($languages as $language) {
                $slug = strtoupper((string) ($language['slug'] ?? ''));
                $name = (string) ($language['name'] ?? $slug);
                $items[] = [
                    'slug' => $slug,
                    'label' => trim($name . ($slug ? ' (' . $slug . ')' : '')),
                    'url' => (string) ($language['url'] ?? '#'),
                    'current' => !empty($language['current_lang']),
                ];
            }

            return $items;
        }
    }

    $current_locale = strtolower(substr(get_locale(), 0, 2));
    return [
        ['slug' => 'ID', 'label' => __('Indonesia (ID)', 'yiari'), 'url' => '#', 'current' => $current_locale === 'id'],
        ['slug' => 'EN', 'label' => __('English (EN)', 'yiari'), 'url' => '#', 'current' => $current_locale === 'en'],
        ['slug' => 'ES', 'label' => __('Español (ES)', 'yiari'), 'url' => '#', 'current' => $current_locale === 'es'],
    ];
}

function yiari_get_current_language_item(): array {
    $items = yiari_get_language_switcher_items();
    foreach ($items as $item) {
        if (!empty($item['current'])) {
            return $item;
        }
    }

    return $items[0] ?? ['slug' => 'ID', 'label' => __('Indonesia (ID)', 'yiari'), 'url' => '#', 'current' => true];
}

function yiari_render_language_switcher(bool $mobile = false): void {
    $items = yiari_get_language_switcher_items();
    $menu_class = $mobile ? 'mobile-dropdown-menu' : 'dropdown-menu dropdown-menu-right dropdown-menu-lang';

    if ($mobile) {
        echo '<div class="' . esc_attr($menu_class) . '" id="mobileLangDropdown" :class="{ open: mobileLangOpen }">';
    } else {
        echo '<div class="' . esc_attr($menu_class) . '">';
    }

    foreach ($items as $item) {
        $class = !empty($item['current']) ? ' class="dropdown-link-active"' : '';
        printf('<a href="%s"%s>%s</a>', esc_url($item['url'] ?: '#'), $class, esc_html($item['label']));
    }

    echo '</div>';
}
