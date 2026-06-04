<?php
/**
 * YIARI Theme – functions.php
 * Setup utama tema: enqueue, image sizes, nav menus, ACF fields.
 */

defined('ABSPATH') || exit;

add_action('after_setup_theme', function () {
    load_theme_textdomain('yiari', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption', 'script', 'style']);
    add_theme_support('custom-logo', [
        'height'      => 64,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    add_image_size('hero-full', 1920, 900, true);
    add_image_size('section-wide', 1200, 600, true);
    add_image_size('card-thumb', 640, 480, true);
    add_image_size('portrait', 400, 500, true);
    add_image_size('square', 400, 400, true);

    register_nav_menus([
        'primary'     => __('Menu Utama', 'yiari'),
        'footer-col1' => __('Footer – Tentang', 'yiari'),
        'footer-col2' => __('Footer – Program', 'yiari'),
        'footer-col3' => __('Footer – Publikasi', 'yiari'),
    ]);
});

add_action('wp_enqueue_scripts', function () {
    $ver = wp_get_theme()->get('Version');
    $dir = get_template_directory_uri();

    wp_enqueue_style('yiari-style', $dir . '/assets/css/styles.css', [], $ver);

    wp_enqueue_script('yiari-main', $dir . '/assets/js/main.js', [], $ver, true);
    wp_localize_script('yiari-main', 'yiariSearch', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('yiari_live_search'),
        'minChars' => 3,
        'strings' => [
            'idle' => __('Mulai ketik untuk mencari artikel atau halaman.', 'yiari'),
            /* translators: %d is the minimum number of characters required before live search starts. */
            'minChars' => __('Ketik minimal %d karakter.', 'yiari'),
            'loading' => __('Mencari...', 'yiari'),
            'empty' => __('Tidak ada hasil yang cocok.', 'yiari'),
            'searchPlaceholder' => __('Ketik minimal 3 karakter...', 'yiari'),
            'article' => __('Artikel', 'yiari'),
            'page' => __('Halaman', 'yiari'),
        ],
    ]);
    wp_localize_script('yiari-main', 'yiariUpdates', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('yiari_load_more_updates'),
        'strings' => [
            'loading' => __('Memuat...', 'yiari'),
            'error' => __('Gagal memuat artikel berikutnya.', 'yiari'),
        ],
    ]);
    wp_localize_script('yiari-main', 'yiariPublications', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('yiari_load_more_publications'),
        'strings' => [
            'loading' => __('Memuat...', 'yiari'),
            'error' => __('Gagal memuat publikasi berikutnya.', 'yiari'),
        ],
    ]);

    wp_enqueue_script('alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', ['yiari-main'], '3.14.0', true);
    wp_script_add_data('alpinejs', 'defer', true);
    wp_enqueue_script('lucide', 'https://unpkg.com/lucide@latest', ['yiari-main'], null, true);

    if (is_singular('publikasi')) {
        $pdfjs_version = '3.11.174';
        wp_enqueue_script('pdfjs', $dir . '/assets/vendor/pdfjs/pdf.min.js', [], $pdfjs_version, true);
        wp_enqueue_script('yiari-pdf-viewer', $dir . '/assets/js/pdf-viewer.js', ['pdfjs'], $ver, true);
        wp_localize_script('yiari-pdf-viewer', 'yiariPdfViewer', [
            'workerSrc' => $dir . '/assets/vendor/pdfjs/pdf.worker.min.js',
            'strings' => [
                'loading' => __('Memuat PDF...', 'yiari'),
                'loadError' => __('PDF tidak dapat dimuat di viewer interaktif. Gunakan mode layar penuh atau unduh file.', 'yiari'),
                'zoomIn' => __('Perbesar', 'yiari'),
                'zoomOut' => __('Perkecil', 'yiari'),
                'reset' => __('Reset', 'yiari'),
                'handTool' => __('Hand Tool', 'yiari'),
                'page' => __('Halaman', 'yiari'),
            ],
        ]);
    }
});

add_filter('theme_page_templates', function (array $templates): array {
    $templates['templates/detail-lanskap.php'] = __('Detail Lanskap', 'yiari');
    $templates['templates/donasi.php'] = __('Donasi', 'yiari');
    $templates['templates/donasi-thankyou.php'] = __('Donasi Thank You', 'yiari');
    $templates['templates/bergabung.php'] = __('Bergabung', 'yiari');
    $templates['templates/jurnal.php'] = __('Jurnal', 'yiari');
    $templates['templates/buku.php'] = __('Buku', 'yiari');
    $templates['templates/materi-edukasi.php'] = __('Materi Edukasi', 'yiari');
    $templates['templates/blog.php'] = __('Blog', 'yiari');

    return $templates;
});

add_action('add_meta_boxes_page', function (): void {
    add_meta_box(
        'yiari-page-template',
        __('Template Halaman', 'yiari'),
        function (WP_Post $post): void {
            $templates = wp_get_theme()->get_page_templates($post, 'page');
            $current = get_page_template_slug($post->ID);

            wp_nonce_field('yiari_save_page_template', 'yiari_page_template_nonce');
            ?>
            <p>
                <label for="yiari_page_template_select" class="screen-reader-text"><?php esc_html_e('Template Halaman', 'yiari'); ?></label>
                <select name="yiari_page_template_select" id="yiari_page_template_select" style="width:100%;">
                    <option value="default"><?php esc_html_e('Template Default', 'yiari'); ?></option>
                    <?php foreach ($templates as $template_file => $template_name): ?>
                        <option value="<?php echo esc_attr($template_file); ?>" <?php selected($current, $template_file); ?>>
                            <?php echo esc_html($template_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            <?php
        },
        'page',
        'side',
        'high'
    );
});

add_action('save_post_page', function (int $post_id): void {
    if (!isset($_POST['yiari_page_template_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['yiari_page_template_nonce'])), 'yiari_save_page_template')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    $selected = isset($_POST['yiari_page_template_select']) ? sanitize_text_field(wp_unslash($_POST['yiari_page_template_select'])) : 'default';

    if ($selected === 'default' || $selected === '') {
        delete_post_meta($post_id, '_wp_page_template');
        return;
    }

    $templates = wp_get_theme()->get_page_templates();
    if (!isset($templates[$selected])) {
        return;
    }

    update_post_meta($post_id, '_wp_page_template', $selected);
});

add_action('wp_ajax_yiari_live_search', 'yiari_handle_live_search');
add_action('wp_ajax_nopriv_yiari_live_search', 'yiari_handle_live_search');
add_action('wp_ajax_yiari_load_more_updates', 'yiari_handle_load_more_updates');
add_action('wp_ajax_nopriv_yiari_load_more_updates', 'yiari_handle_load_more_updates');
add_action('wp_ajax_yiari_load_more_publications', 'yiari_handle_load_more_publications');
add_action('wp_ajax_nopriv_yiari_load_more_publications', 'yiari_handle_load_more_publications');

function yiari_handle_live_search(): void {
    check_ajax_referer('yiari_live_search', 'nonce');

    $raw_query = isset($_POST['query']) ? wp_unslash($_POST['query']) : '';
    $query = sanitize_text_field($raw_query);

    if (mb_strlen($query) < 3) {
        wp_send_json_success(['items' => []]);
    }

    $search_query = new WP_Query([
        'post_type' => ['post', 'page'],
        'post_status' => 'publish',
        'posts_per_page' => 6,
        's' => $query,
        'orderby' => 'date',
        'order' => 'DESC',
        'ignore_sticky_posts' => true,
    ]);

    $items = [];
    while ($search_query->have_posts()) {
        $search_query->the_post();
        $post_id = get_the_ID();
        $thumbnail = get_the_post_thumbnail_url($post_id, 'card-thumb');

        $items[] = [
            'id' => $post_id,
            'title' => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
            'url' => get_permalink($post_id),
            'type' => get_post_type($post_id) === 'page' ? __('Halaman', 'yiari') : __('Artikel', 'yiari'),
            'image' => $thumbnail ?: get_template_directory_uri() . '/assets/img/hero-section.jpg',
        ];
    }
    wp_reset_postdata();

    wp_send_json_success(['items' => $items]);
}

function yiari_handle_load_more_updates(): void {
    check_ajax_referer('yiari_load_more_updates', 'nonce');

    $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0;
    $page = isset($_POST['page']) ? max(1, (int) $_POST['page']) : 1;
    $count = isset($_POST['count']) ? max(1, min(12, (int) $_POST['count'])) : 9;
    $exclude_ids_raw = isset($_POST['exclude_ids']) ? wp_unslash($_POST['exclude_ids']) : [];
    if (!is_array($exclude_ids_raw)) {
        $exclude_ids_raw = [$exclude_ids_raw];
    }
    $exclude_ids = array_values(array_filter(array_map('intval', $exclude_ids_raw)));

    $query_args = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $count,
        'paged' => $page,
        'ignore_sticky_posts' => true,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    if (!empty($exclude_ids)) {
        $query_args['post__not_in'] = $exclude_ids;
    }

    if ($category_id > 0) {
        $query_args['cat'] = $category_id;
    }

    $query = new WP_Query($query_args);
    $items = [];

    while ($query->have_posts()) {
        $query->the_post();
        $items[] = yiari_render_detail_landscape_update_card(get_the_ID());
    }
    wp_reset_postdata();

    wp_send_json_success([
        'html' => implode('', $items),
        'nextPage' => $page + 1,
        'hasMore' => $page < (int) $query->max_num_pages,
    ]);
}

function yiari_handle_load_more_publications(): void {
    check_ajax_referer('yiari_load_more_publications', 'nonce');

    $page = isset($_POST['page']) ? max(1, (int) $_POST['page']) : 1;
    $count = isset($_POST['count']) ? max(3, min(12, (int) $_POST['count'])) : 9;
    $variant = isset($_POST['variant']) ? sanitize_key((string) wp_unslash($_POST['variant'])) : 'book';
    $variant = $variant === 'education' ? 'education' : 'book';

    $terms_raw = isset($_POST['terms']) ? wp_unslash($_POST['terms']) : [];
    if (!is_array($terms_raw)) {
        $terms_raw = [$terms_raw];
    }
    $terms = array_values(array_filter(array_map('intval', $terms_raw)));

    $query_args = [
        'post_type' => 'publikasi',
        'post_status' => 'publish',
        'posts_per_page' => $count,
        'paged' => $page,
        'ignore_sticky_posts' => true,
        'orderby' => 'date',
        'order' => 'DESC',
        'suppress_filters' => false,
    ];

    if (!empty($terms)) {
        $query_args['tax_query'] = [[
            'taxonomy' => 'kategori-publikasi',
            'field' => 'term_id',
            'terms' => $terms,
        ]];
    }

    $query = new WP_Query($query_args);
    $items = [];

    while ($query->have_posts()) {
        $query->the_post();
        $items[] = yiari_render_publication_card(get_the_ID(), $variant);
    }
    wp_reset_postdata();

    wp_send_json_success([
        'html' => implode('', $items),
        'nextPage' => $page + 1,
        'hasMore' => $page < (int) $query->max_num_pages,
    ]);
}

require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/helpers.php';

add_filter('excerpt_length', fn() => 24);
add_filter('excerpt_more', fn() => '…');

add_filter('wp_midtrans_donation_should_enqueue_assets', function (bool $should_enqueue): bool {
    if ($should_enqueue) {
        return true;
    }

    return is_page_template('templates/donasi.php');
});

add_filter('gettext', function (string $translation, string $text, string $domain): string {
    if (is_admin()) {
        return $translation;
    }

    global $post;

    if (!$post instanceof WP_Post || get_page_template_slug($post->ID) !== 'templates/bergabung.php') {
        return $translation;
    }

    $current_lang = function_exists('pll_current_language') ? (string) pll_current_language('slug') : '';
    $is_english = strpos($current_lang, 'en') === 0;

    $replacements = $is_english
        ? [
            'Next' => 'Next',
            'Previous' => 'Previous',
        ]
        : [
            'Next' => 'Selanjutnya',
            'Previous' => 'Sebelumnya',
        ];

    return $replacements[$text] ?? $translation;
}, 20, 3);

add_filter('gettext', function (string $translation, string $text, string $domain): string {
    if (is_admin()) {
        return $translation;
    }

    global $post;

    if (!$post instanceof WP_Post || get_page_template_slug($post->ID) !== 'templates/jurnal.php') {
        return $translation;
    }

    $current_lang = function_exists('pll_current_language') ? (string) pll_current_language('slug') : '';
    $is_english = strpos($current_lang, 'en') === 0;

    $replacements = $is_english
        ? [
            'Search Publications' => 'Search Publications',
            'Type to search...' => 'Search research...',
            'Search publications' => 'Search research',
            'Filter by Category' => 'Filter by Year',
            'Filter by category' => 'Filter by year',
            'All Categories' => 'All Years',
            'Reset' => 'Reset',
            'Loading...' => 'Loading...',
            'Searching...' => 'Searching...',
            'Error loading content. Please try again.' => 'Error loading content. Please try again.',
            'Retry' => 'Retry',
            'No research found' => 'No research found',
            'Try another keyword or change the search filter.' => 'Try another keyword or change the search filter.',
            'Search failed. Please try again.' => 'Search failed. Please try again.',
            'No categories match your search or filter criteria.' => 'No categories match your search or filter criteria.',
            'Parent category not found.' => 'Parent category not found.',
            'No categories found.' => 'No publication categories found.',
            'Invalid category ID.' => 'Invalid category ID.',
            'Category not found.' => 'Category not found.',
            'No publications found in this category.' => 'No publications found in this category.',
            'View Journal' => 'View Journal',
        ]
        : [
            'Search Publications' => 'Cari Penelitian',
            'Type to search...' => 'Cari penelitian...',
            'Search publications' => 'Cari penelitian',
            'Filter by Category' => 'Filter Berdasarkan Tahun',
            'Filter by category' => 'Filter berdasarkan tahun',
            'All Categories' => 'Semua Tahun',
            'Reset' => 'Reset',
            'Loading...' => 'Memuat...',
            'Searching...' => 'Mencari...',
            'Error loading content. Please try again.' => 'Gagal memuat konten. Silakan coba lagi.',
            'Retry' => 'Coba Lagi',
            'No research found' => 'Tidak ada penelitian ditemukan',
            'Try another keyword or change the search filter.' => 'Coba gunakan kata kunci lain atau ubah filter pencarian',
            'Search failed. Please try again.' => 'Pencarian gagal. Silakan coba lagi.',
            'No categories match your search or filter criteria.' => 'Tidak ada kategori yang cocok dengan pencarian atau filter Anda.',
            'Parent category not found.' => 'Kategori induk tidak ditemukan.',
            'No categories found.' => 'Belum ada kategori publikasi.',
            'Invalid category ID.' => 'ID kategori tidak valid.',
            'Category not found.' => 'Kategori tidak ditemukan.',
            'No publications found in this category.' => 'Belum ada publikasi pada kategori ini.',
            'View Journal' => 'Lihat Jurnal',
        ];

    return $replacements[$text] ?? $translation;
}, 20, 3);

add_action('wp_ajax_refresh_jurnal_nonce', 'yiari_refresh_jurnal_nonce');
add_action('wp_ajax_nopriv_refresh_jurnal_nonce', 'yiari_refresh_jurnal_nonce');

function yiari_refresh_jurnal_nonce(): void {
    wp_send_json_success([
        'nonce' => wp_create_nonce('jurnal_accordion_nonce'),
    ]);
}
