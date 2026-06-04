<?php
/**
 * Template Name: Program
 * Template Post Type: page
 * Halaman detail program konservasi YIARI.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();
$page_id = get_queried_object_id();

$legacy_program_title = trim((string) get_post_meta($page_id, 'program_title', true));
$legacy_program_description = trim((string) get_post_meta($page_id, 'diskripsi', true));
$legacy_subtitle = trim((string) get_post_meta($page_id, 'sub_judul', true));
$legacy_description = trim((string) get_post_meta($page_id, 'deskripsi', true));
$legacy_stats_count = max(0, (int) get_post_meta($page_id, 'statistik', true));
$legacy_program_count = max(0, (int) get_post_meta($page_id, 'jenis_program', true));
$legacy_story_categories = maybe_unserialize(get_post_meta($page_id, 'program_category', true));
$legacy_story_category = is_array($legacy_story_categories) && !empty($legacy_story_categories[0])
    ? (int) $legacy_story_categories[0]
    : max(0, (int) maybe_unserialize(get_post_meta($page_id, 'jenis_program_0_kategori_artikel', true))[0] ?? 0);

$legacy_program_description = preg_replace('/\s+/', ' ', wp_strip_all_tags($legacy_program_description));
$legacy_description = preg_replace('/\s+/', ' ', wp_strip_all_tags($legacy_description));
$legacy_primary_title = $legacy_program_title !== '' ? $legacy_program_title : $legacy_subtitle;
$legacy_primary_description = trim((string) ($legacy_program_description !== '' ? $legacy_program_description : $legacy_description));

$hero_image = has_post_thumbnail($page_id)
    ? [
        'url' => (string) get_the_post_thumbnail_url($page_id, 'hero-full'),
        'alt' => get_the_title($page_id),
    ]
    : yiari_field('program_hero_image', ['url' => $theme_uri . '/assets/img/hero-section.jpg']);
$hero_title = yiari_field('program_hero_title', $legacy_primary_title !== '' ? $legacy_primary_title : (get_the_title($page_id) ?: __("Melindungi dan Mengembalikan Satwa\nLiar ke Habitatnya", 'yiari')));
$hero_text = yiari_field('program_hero_text', $legacy_primary_description !== '' ? $legacy_primary_description : __('Dari penyelamatan darurat hingga rehabilitasi intensif, kami memastikan setiap satwa mendapatkan perawatan terbaik dan kesempatan kedua untuk hidup bebas di alam liar.', 'yiari'));
$hero_btn1_raw = trim((string) yiari_field('program_hero_btn_url', '#program-focus'));
$hero_btn1_text = yiari_field('program_hero_btn_text', __('Lihat Fokus Program', 'yiari'));
$hero_btn1_url = $hero_btn1_raw !== '' && str_starts_with($hero_btn1_raw, '#')
    ? $hero_btn1_raw
    : yiari_localize_url($hero_btn1_raw !== '' ? $hero_btn1_raw : '#program-focus');
$hero_btn2_raw = trim((string) yiari_field('program_hero_btn2_url', '#program-action'));
$hero_btn2_text = yiari_field('program_hero_btn2_text', __('Pelajari Lebih Lanjut', 'yiari'));
$hero_btn2_url = $hero_btn2_raw !== '' && str_starts_with($hero_btn2_raw, '#')
    ? $hero_btn2_raw
    : yiari_localize_url($hero_btn2_raw !== '' ? $hero_btn2_raw : '#program-action');

$action_label = yiari_field('program_main_label', __('KRISIS EKOSISTEM', 'yiari'));
$action_title = yiari_field('program_main_title', $legacy_primary_title !== '' ? $legacy_primary_title : __("Kenapa Ini Penting dan\nBagaimana Kami\nBertindak", 'yiari'));
$action_desc = yiari_field('program_main_desc', $legacy_primary_description !== '' ? $legacy_primary_description : __('Perdagangan ilegal, konflik manusia-satwa, dan kerusakan habitat terus mengancam kehidupan satwa liar. Melalui penyelamatan, rehabilitasi, hingga pelepasliaran, kami membantu mereka mendapatkan kesempatan hidup yang lebih aman di alam.', 'yiari'));
$action_image = yiari_field('program_main_image_main', ['url' => $theme_uri . '/assets/img/konservasi.png', 'alt' => __('Kenapa ini penting', 'yiari')]);
$action_image = !empty($action_image['url']) ? $action_image : $hero_image;
$action_items = yiari_field('program_main_items', []);
if (empty($action_items) || !is_array($action_items)) {
    $action_items = [
        ['title' => __('Penyelamatan', 'yiari'), 'details' => __('Tim kami bergerak cepat menyelamatkan satwa liar dari perdagangan ilegal, jerat, dan situasi berbahaya lainnya.', 'yiari')],
        ['title' => __('Rehabilitasi', 'yiari'), 'details' => __('Satwa mendapatkan perawatan medis dan pelatihan agar siap kembali hidup mandiri di alam liar.', 'yiari')],
        ['title' => __('Pelepasliaran', 'yiari'), 'details' => __('Setelah pulih, satwa dilepaskan kembali ke habitat alaminya yang aman dan terlindungi.', 'yiari')],
        ['title' => __('Pemantauan', 'yiari'), 'details' => __('Tim terus memantau perkembangan satwa untuk memastikan mereka dapat beradaptasi dengan baik di alam liar.', 'yiari')],
    ];
}

$stats_label = yiari_field('program_stats_label', __('DATA & BUKTI', 'yiari'));
$stats_title = yiari_field('program_stats_title', __('Dampak yang Terukur', 'yiari'));
$stats_desc = yiari_field('program_stats_desc', __('Setiap angka di bawah ini mewakili satwa yang diselamatkan, kehidupan yang diselamatkan, dan ekosistem yang dilindungi.', 'yiari'));
$stats_items = yiari_field('program_stats_items', []);
if ((empty($stats_items) || !is_array($stats_items)) && $legacy_stats_count > 0) {
    $stats_items = [];
    for ($i = 0; $i < $legacy_stats_count; $i += 1) {
        $counter = trim((string) get_post_meta($page_id, "statistik_{$i}_counter", true));
        $description = wp_strip_all_tags((string) get_post_meta($page_id, "statistik_{$i}_deskripsi", true));
        if ($counter === '' && $description === '') {
            continue;
        }

        $number_only = preg_replace('/[^\d]/', '', $counter);
        preg_match('/[^\d\s,.]+/', $counter, $suffix_match);

        $stats_items[] = [
            'number' => $number_only !== '' ? (int) $number_only : 0,
            'suffix' => $suffix_match[0] ?? '',
            'label' => $description,
            'icon' => 'badge-check',
        ];
    }
}
if (empty($stats_items) || !is_array($stats_items)) {
    $stats_items = [
        ['number' => 264, 'suffix' => '', 'label' => __('Orang Utan Diselamatkan', 'yiari'), 'icon' => 'paw-print'],
        ['number' => 1302, 'suffix' => '', 'label' => __('Kukang Diselamatkan', 'yiari'), 'icon' => 'badge-check'],
        ['number' => 269, 'suffix' => '', 'label' => __('Monyet Diselamatkan', 'yiari'), 'icon' => 'shield-check'],
        ['number' => 1082, 'suffix' => '', 'label' => __('Satwa Lain Diselamatkan', 'yiari'), 'icon' => 'heart-pulse'],
        ['number' => 22, 'suffix' => '', 'label' => __('Lokasi Pemantauan', 'yiari'), 'icon' => 'map-pinned'],
    ];
}

$story_label = yiari_field('program_story_label', __('DARI LAPANGAN', 'yiari'));
$story_title = yiari_field('program_story_title', __("Pulih Pasca Kena\nJeratan, Orangutan Siap\nKembali ke Habitatnya", 'yiari'));
$story_desc = yiari_field('program_story_desc', __('Setelah ditemukan terluka akibat jerat, orangutan ini menjalani perawatan intensif bersama tim YIARI hingga akhirnya dapat kembali hidup di alam liar.', 'yiari'));
$story_btn1_text = yiari_field('program_story_btn1_text', __('Baca Selengkapnya', 'yiari'));
$story_btn1_url = yiari_localize_url((string) yiari_field('program_story_btn1_url', yiari_get_posts_page_url('#')));
$story_btn2_text = yiari_field('program_story_btn2_text', __('Donasi Sekarang', 'yiari'));
$story_btn2_url = yiari_localize_url((string) yiari_field('program_story_btn2_url', yiari_get_page_url_by_template('templates/donasi.php', yiari_home_url())));
$story_image = yiari_field('program_story_image');
$story_gallery = yiari_field('program_story_gallery', []);
$story_query = null;
if ((empty($story_image['url']) || empty($story_gallery)) && $legacy_story_category > 0) {
    $story_query = new WP_Query([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'ignore_sticky_posts' => true,
        'cat' => $legacy_story_category,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    if ($story_query->have_posts()) {
        $story_post = $story_query->posts[0];
        if ($story_post instanceof WP_Post) {
            $story_title = yiari_field('program_story_title', get_the_title($story_post->ID));
            $story_desc = yiari_field('program_story_desc', has_excerpt($story_post->ID)
                ? get_the_excerpt($story_post->ID)
                : wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $story_post->ID)), 24, '…'));
            $story_btn1_url = yiari_localize_url((string) yiari_field('program_story_btn1_url', get_permalink($story_post->ID) ?: '#'));
        }
    }
}
$story_slides = [];
if (is_array($story_gallery) && $story_gallery) {
    foreach ($story_gallery as $gallery_image) {
        if (!empty($gallery_image['url'])) {
            $story_slides[] = $gallery_image;
        }
    }
}
if (!empty($story_image['url'])) {
    array_unshift($story_slides, $story_image);
}
if (empty($story_slides)) {
    if ($story_query instanceof WP_Query && $story_query->have_posts()) {
        foreach ($story_query->posts as $story_post_item) {
            if (!$story_post_item instanceof WP_Post) {
                continue;
            }
            $slide_url = get_the_post_thumbnail_url($story_post_item->ID, 'section-wide');
            if (!$slide_url) {
                continue;
            }
            $story_slides[] = [
                'url' => $slide_url,
                'alt' => get_the_title($story_post_item->ID),
            ];
        }
    }
}
if (empty($story_slides)) {
    $story_slides = [
        ['url' => $theme_uri . '/assets/img/gambar4.jpg', 'alt' => $story_title],
        ['url' => $theme_uri . '/assets/img/gambar5.jpg', 'alt' => $story_title],
        ['url' => $theme_uri . '/assets/img/gambar6.jpg', 'alt' => $story_title],
    ];
}
$story_slides = array_values(array_filter($story_slides, static fn($slide) => !empty($slide['url'])));
$story_slide_count = count($story_slides);

$focus_label = yiari_field('program_focus_label', __('FOKUS PROGRAM', 'yiari'));
$focus_title = yiari_field('program_focus_title', __("Area Fokus Program\nKami", 'yiari'));
$focus_desc = yiari_field('program_focus_desc', __('Setiap program memiliki fokus dan tantangan yang berbeda, mulai dari perlindungan satwa hingga pemberdayaan masyarakat dan pelestarian habitat.', 'yiari'));
$focus_items = yiari_field('program_focus_items', []);
if ((empty($focus_items) || !is_array($focus_items)) && $legacy_program_count > 0) {
    $focus_items = [];
    for ($i = 0; $i < $legacy_program_count; $i += 1) {
        $title = trim((string) get_post_meta($page_id, "jenis_program_{$i}_judul_program", true));
        $text = (string) get_post_meta($page_id, "jenis_program_{$i}_diskripsi_program", true);
        $image_id = (int) get_post_meta($page_id, "jenis_program_{$i}_gambar_program", true);
        $link_id = (int) get_post_meta($page_id, "jenis_program_{$i}_page_link", true);

        $image_url = $image_id > 0 ? wp_get_attachment_image_url($image_id, 'card-thumb') : '';
        $image_alt = $image_id > 0 ? (get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $title) : $title;
        $link_url = $link_id > 0 ? get_permalink(yiari_translate_post_id($link_id)) : yiari_get_program_url();

        if ($title === '' && $text === '') {
            continue;
        }

        $focus_items[] = [
            'title' => $title,
            'text' => wp_strip_all_tags($text),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => $link_url ?: yiari_get_program_url(),
            'image' => [
                'url' => $image_url ?: ($theme_uri . '/assets/img/konservasi.png'),
                'alt' => $image_alt,
            ],
        ];
    }
}
if (empty($focus_items) || !is_array($focus_items)) {
    $focus_items = [
        ['title' => __('Orang Utan', 'yiari'), 'text' => __('Penyelamatan, rehabilitasi, dan pelepasliaran orangutan yang terancam akibat perburuan dan perdagangan ilegal.', 'yiari'), 'link_text' => __('Lihat Selengkapnya', 'yiari'), 'link_url' => yiari_get_program_url(), 'image' => ['url' => $theme_uri . '/assets/img/konservasi.png', 'alt' => __('Orang Utan', 'yiari')]],
        ['title' => __('Kukang', 'yiari'), 'text' => __('Perlindungan dan rehabilitasi kukang dari ancaman perburuan, perdagangan ilegal, dan kehilangan habitat.', 'yiari'), 'link_text' => __('Lihat Selengkapnya', 'yiari'), 'link_url' => yiari_get_program_url(), 'image' => ['url' => $theme_uri . '/assets/img/gambar4.jpg', 'alt' => __('Kukang', 'yiari')]],
        ['title' => __('Macaca', 'yiari'), 'text' => __('Penanganan konflik manusia dan monyet ekor panjang melalui pendekatan konservasi dan edukasi masyarakat.', 'yiari'), 'link_text' => __('Lihat Selengkapnya', 'yiari'), 'link_url' => yiari_get_program_url(), 'image' => ['url' => $theme_uri . '/assets/img/gambar1.png', 'alt' => __('Macaca', 'yiari')]],
        ['title' => __('Satwa Lainnya', 'yiari'), 'text' => __('Penyelamatan dan perawatan satwa liar korban perburuan, perdagangan ilegal, dan konflik dengan manusia.', 'yiari'), 'link_text' => __('Lihat Selengkapnya', 'yiari'), 'link_url' => yiari_get_program_url(), 'image' => ['url' => $theme_uri . '/assets/img/gambar3.png', 'alt' => __('Satwa Lainnya', 'yiari')]],
    ];
}

$cta_label = yiari_field('program_cta_label', __('DUKUNGAN ANDA', 'yiari'));
$cta_title = yiari_field('program_cta_title', __("Bersama Kita Bisa\nMenjaga Alam Tetap\nHidup", 'yiari'));
$cta_text = yiari_field('program_cta_text', __('Setiap dukungan membantu melindungi satwa liar, memulihkan habitat, dan menciptakan masa depan yang lebih baik bagi alam dan masyarakat.', 'yiari'));
$cta_btn1_text = yiari_field('program_cta_btn1_text', __('Donasi Sekarang', 'yiari'));
$cta_btn1_url = yiari_localize_url((string) yiari_field('program_cta_btn1_url', yiari_get_page_url_by_template('templates/donasi.php', yiari_home_url())));
$cta_btn2_text = yiari_field('program_cta_btn2_text', __('Gabung Bersama YIARI', 'yiari'));
$cta_btn2_url = yiari_localize_url((string) yiari_field('program_cta_btn2_url', yiari_get_join_url()));
$cta_image = yiari_field('program_cta_image', ['url' => $theme_uri . '/assets/img/gambar-cta.png', 'alt' => __('Dukung program konservasi YIARI', 'yiari')]);
if ($story_query instanceof WP_Query) {
    wp_reset_postdata();
}
?>

<main class="program-page">
  <section class="program-hero-wrap">
    <?php
    $hero_buttons = [];
    if ($hero_btn1_text && $hero_btn1_url) {
        $hero_buttons[] = ['text' => $hero_btn1_text, 'url' => $hero_btn1_url, 'class' => 'btn-action btn-action-primary'];
    }
    if ($hero_btn2_text && $hero_btn2_url) {
        $hero_buttons[] = ['text' => $hero_btn2_text, 'url' => $hero_btn2_url, 'class' => 'btn-action btn-action-outline'];
    }
    get_template_part('template-parts/sections/page-hero', null, [
        'id' => 'program-top',
        'classes' => 'program-hero-section',
        'image' => $hero_image,
        'image_class' => 'program-hero-img',
        'image_alt' => __('Program YIARI', 'yiari'),
        'overlay_class' => 'program-hero-overlay',
        'content_class' => 'program-hero-content',
        'copy_class' => 'program-hero-copy',
        'title_class' => 'program-hero-title',
        'text_class' => 'program-hero-text',
        'actions_class' => 'program-hero-actions',
        'title' => $hero_title,
        'text' => $hero_text,
        'allow_title_breaks' => true,
        'buttons' => $hero_buttons,
    ]);
    ?>
  </section>

  <section class="program-action-section" id="program-action">
    <div class="container">
      <div class="stats-layout program-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($action_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($action_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($action_desc); ?></p>
        </div>
      </div>

      <div class="program-action-grid">
        <div class="program-action-media">
          <?php yiari_img($action_image, 'section-wide', 'program-action-image', $action_title); ?>
        </div>
        <div class="program-action-list">
          <?php foreach ($action_items as $index => $item): ?>
            <?php
            $item_title = trim((string) ($item['title'] ?? ''));
            $item_text = trim((string) ($item['details'] ?? ''));
            ?>
            <article class="program-action-item">
              <div class="program-action-item-number" aria-hidden="true"><?php echo esc_html((string) ($index + 1)); ?></div>
              <div class="program-action-item-copy">
                <h3 class="program-action-item-title"><?php echo esc_html($item_title); ?></h3>
                <p class="program-action-item-text"><?php echo esc_html($item_text); ?></p>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="program-stats-section" id="program-stats">
    <div class="container" x-data="{ statsActivePage: 0, statsPageCount: 1, statsItemsPerPage: 1, statsCanScroll: false, statsIsMobile: false }">
      <div class="stats-layout program-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($stats_label, true); ?>
          <h2 class="section-heading section-heading-dark"><?php echo wp_kses_post(nl2br(esc_html($stats_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($stats_desc); ?></p>
        </div>
      </div>

      <div class="program-stats-grid">
        <?php foreach ($stats_items as $stat): ?>
          <?php
          $number = (int) ($stat['number'] ?? 0);
          $suffix = trim((string) ($stat['suffix'] ?? ''));
          $label = trim((string) ($stat['label'] ?? ''));
          $icon = trim((string) ($stat['icon'] ?? 'badge-check'));
          ?>
          <article class="program-stat-card">
            <div class="program-stat-card-top">
              <div class="program-stat-number">
                <?php echo esc_html((string) $number); ?><?php if ($suffix !== ''): ?><span class="program-stat-suffix"><?php echo esc_html($suffix); ?></span><?php endif; ?>
              </div>
              <div class="program-stat-icon">
                <i data-lucide="<?php echo esc_attr($icon !== '' ? $icon : 'badge-check'); ?>" class="icon-sm"></i>
              </div>
            </div>
            <div class="program-stat-label"><?php echo esc_html($label); ?></div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section
    class="program-story-section"
    id="program-story"
    x-data="{
      activeStorySlide: 0,
      storySlideCount: <?php echo (int) $story_slide_count; ?>,
      storyAutoSlide: null,
      nextStorySlide() {
        if (this.storySlideCount <= 1) return;
        this.activeStorySlide = (this.activeStorySlide + 1) % this.storySlideCount;
      },
      startStoryAutoSlide() {
        if (this.storySlideCount <= 1) return;
        this.stopStoryAutoSlide();
        this.storyAutoSlide = setInterval(() => this.nextStorySlide(), 4200);
      },
      stopStoryAutoSlide() {
        if (this.storyAutoSlide) {
          clearInterval(this.storyAutoSlide);
          this.storyAutoSlide = null;
        }
      }
    }"
    x-init="startStoryAutoSlide()"
    @mouseenter="stopStoryAutoSlide()"
    @mouseleave="startStoryAutoSlide()"
  >
    <div class="container">
      <div class="program-story-card">
        <div class="stats-layout program-section-header">
          <div class="stats-intro">
            <?php yiari_section_label($story_label, true); ?>
            <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($story_title))); ?></h2>
          </div>
          <div class="stats-desc">
            <p class="section-description"><?php echo esc_html($story_desc); ?></p>
            <div class="program-story-actions">
              <?php yiari_btn($story_btn1_text, $story_btn1_url, 'btn-donate-main'); ?>
              <?php yiari_btn($story_btn2_text, $story_btn2_url, 'btn-volunteer-main'); ?>
            </div>
          </div>
        </div>

        <div class="story-carousel">
          <div class="story-carousel-frame program-story-frame">
            <?php foreach ($story_slides as $index => $slide): ?>
              <?php $slide_alt = $slide['alt'] ?? $story_title; ?>
              <div
                class="story-slide"
                x-show="activeStorySlide === <?php echo (int) $index; ?>"
                x-transition:enter="story-fade-enter"
                x-transition:enter-start="story-fade-enter-start"
                x-transition:enter-end="story-fade-enter-end"
                x-transition:leave="story-fade-leave"
                x-transition:leave-start="story-fade-leave-start"
                x-transition:leave-end="story-fade-leave-end"
                x-cloak
              >
                <img src="<?php echo esc_url($slide['url']); ?>" alt="<?php echo esc_attr($slide_alt); ?>" class="story-slide-img program-story-image" loading="lazy" />
              </div>
            <?php endforeach; ?>
          </div>

          <div class="story-indicators" x-show="storySlideCount > 1" x-cloak>
            <template x-for="index in storySlideCount" :key="index">
              <button
                class="story-indicator-btn"
                type="button"
                :aria-label="`<?php echo esc_js(__('Go to story slide', 'yiari')); ?> ${index}`"
                @click="activeStorySlide = index - 1; startStoryAutoSlide()"
              >
                <span class="story-indicator" :class="{ 'is-active': activeStorySlide === (index - 1) }"></span>
              </button>
            </template>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="program-focus-section" id="program-focus">
    <div class="container">
      <div class="stats-layout program-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($focus_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($focus_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($focus_desc); ?></p>
        </div>
      </div>

      <div class="program-focus-grid">
        <?php foreach ($focus_items as $focus_item): ?>
          <?php
          $focus_item_title = trim((string) ($focus_item['title'] ?? ''));
          $focus_item_text = trim((string) ($focus_item['text'] ?? ''));
          $focus_item_link_text = trim((string) ($focus_item['link_text'] ?? __('Lihat Selengkapnya', 'yiari')));
          $focus_item_link_url = yiari_localize_url((string) ($focus_item['link_url'] ?? yiari_get_program_url()));
          $focus_item_image = $focus_item['image'] ?? ['url' => $theme_uri . '/assets/img/konservasi.png'];
          ?>
          <article class="program-focus-card">
            <a href="<?php echo esc_url($focus_item_link_url); ?>" class="program-focus-card-media" aria-label="<?php echo esc_attr($focus_item_title); ?>">
              <?php yiari_img($focus_item_image, 'card-thumb', 'program-focus-card-image', $focus_item_title); ?>
            </a>
            <div class="program-focus-card-body">
              <h3 class="program-focus-title"><?php echo esc_html($focus_item_title); ?></h3>
              <p class="program-focus-text"><?php echo esc_html($focus_item_text); ?></p>
              <a href="<?php echo esc_url($focus_item_link_url); ?>" class="link-more program-focus-link"><?php echo esc_html($focus_item_link_text); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="program-cta-section">
    <div class="container">
      <div class="donate-card">
        <div class="donate-media">
          <?php yiari_img($cta_image, 'section-wide', 'donate-media-img', $cta_title); ?>
        </div>
        <div class="donate-content">
          <div class="donate-pill">
            <i data-lucide="heart-handshake" class="donate-pill-icon"></i>
            <span><?php echo esc_html($cta_label); ?></span>
          </div>
          <h2 class="donate-heading"><?php echo wp_kses_post(nl2br(esc_html($cta_title))); ?></h2>
          <p class="donate-text"><?php echo esc_html($cta_text); ?></p>
          <div class="donate-actions">
            <?php yiari_btn($cta_btn1_text, $cta_btn1_url, 'btn-donate-main'); ?>
            <?php yiari_btn($cta_btn2_text, $cta_btn2_url, 'btn-volunteer-main'); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
