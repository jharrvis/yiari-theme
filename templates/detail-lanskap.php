<?php
/**
 * Template Name: Detail Lanskap
 * Template Post Type: page
 * Halaman detail lokasi/lanskap program konservasi YIARI.
 */

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();

$hero_image = yiari_field('landscape_detail_hero_image', ['url' => $theme_uri . '/assets/img/hero-section.jpg']);
$hero_title = yiari_field('landscape_detail_hero_title', __('Di Garis Depan Perlindungan Satwa dan Hutan Indonesia', 'yiari'));
$hero_title = preg_replace('/\s+/', ' ', trim((string) $hero_title));
$hero_text = yiari_field('landscape_detail_hero_text', __('Dari hutan hujan Kalimantan hingga kawasan lindung Sumatra, kami bekerja di garis depan untuk melindungi ekosistem kritis dan keanekaragaman hayati Indonesia yang terancam.', 'yiari'));
$hero_btn1_text = yiari_field('landscape_detail_hero_btn1_text', __('Lihat Lokasi Kegiatan', 'yiari'));
$hero_btn1_url = yiari_field('landscape_detail_hero_btn1_url', '#');
$hero_btn2_text = yiari_field('landscape_detail_hero_btn2_text', __('Pelajari Lebih Lanjut', 'yiari'));
$hero_btn2_url = yiari_field('landscape_detail_hero_btn2_url', '#');

$intro_label = yiari_field('landscape_detail_intro_label', __('WILAYAH KUNCI KONSERVASI', 'yiari'));
$intro_title = yiari_field('landscape_detail_intro_title', __("Mengapa Ketapang\nSangat Krusial?", 'yiari'));
$intro_desc = yiari_field('landscape_detail_intro_desc', __('Kami beroperasi di wilayah-wilayah prioritas konservasi dengan kehadiran spesies kunci dan tingkat ancaman tinggi.', 'yiari'));
$intro_image = yiari_field('landscape_detail_intro_image', ['url' => $theme_uri . '/assets/img/ketapang.webp']);
$intro_points = yiari_field('landscape_detail_intro_points', []);

if (empty($intro_points)) {
    $intro_points = [
        [
            'image' => $intro_image,
            'title' => __('Rumah bagi Satwa Langka', 'yiari'),
            'text' => __('Wilayah ini menjadi habitat penting bagi berbagai satwa kunci yang membutuhkan perlindungan dan ruang hidup yang aman.', 'yiari'),
        ],
        [
            'image' => $intro_image,
            'title' => __('Ancaman Terus Meningkat', 'yiari'),
            'text' => __('Perburuan, perambahan, serta konversi hutan menjadi tekanan besar yang mengancam keseimbangan alam dan kehidupan satwa liar.', 'yiari'),
        ],
        [
            'image' => $intro_image,
            'title' => __('Wilayah Prioritas Konservasi', 'yiari'),
            'text' => __('Letaknya yang strategis dan kondisi ekologisnya menjadikan kawasan ini prioritas utama dalam upaya perlindungan jangka panjang.', 'yiari'),
        ],
    ];
}

$program_label = yiari_field('landscape_detail_program_label', __('PROGRAM', 'yiari'));
$program_title = yiari_field('landscape_detail_program_title', __("Apa yang Kami\nLakukan di Ketapang", 'yiari'));
$program_desc = yiari_field('landscape_detail_program_desc', __('Lima program konservasi terintegrasi yang berjalan secara bersamaan untuk memastikan perlindungan jangka panjang.', 'yiari'));
$program_items = yiari_field('landscape_detail_program_items', []);

if (empty($program_items)) {
    $program_items = [
        [
            'icon' => ['url' => $theme_uri . '/assets/img/icons/icon-satwa.svg'],
            'title' => __('Konservasi Satwa', 'yiari'),
            'subtitle' => __('Penyelamatan dan rehabilitasi', 'yiari'),
            'details' => __('Menyelamatkan, merawat, dan merehabilitasi satwa liar yang terluka, diperdagangkan, atau kehilangan habitat agar dapat kembali hidup dengan aman.', 'yiari'),
            'button_text' => __('Lihat Program', 'yiari'),
            'button_url' => '#',
            'image' => ['url' => $theme_uri . '/assets/img/konservasi.png'],
        ],
        [
            'icon' => ['url' => $theme_uri . '/assets/img/icons/icon-restorasi.svg'],
            'title' => __('Konservasi Habitat', 'yiari'),
            'subtitle' => __('Restorasi ekosistem', 'yiari'),
            'details' => __('Melindungi dan memulihkan bentang alam penting agar satwa liar memiliki habitat yang aman dan berkelanjutan.', 'yiari'),
            'button_text' => __('Lihat Program', 'yiari'),
            'button_url' => '#',
            'image' => ['url' => $theme_uri . '/assets/img/Hutan-Mangrove1.jpg'],
        ],
        [
            'icon' => ['url' => $theme_uri . '/assets/img/icons/Icon-penyuluhan.svg'],
            'title' => __('Edukasi', 'yiari'),
            'subtitle' => __('Pendidikan masyarakat', 'yiari'),
            'details' => __('Menghadirkan edukasi konservasi yang relevan bagi anak muda, sekolah, dan masyarakat sekitar kawasan penting satwa liar.', 'yiari'),
            'button_text' => __('Lihat Program', 'yiari'),
            'button_url' => '#',
            'image' => ['url' => $theme_uri . '/assets/img/gambar4.jpg'],
        ],
        [
            'icon' => ['url' => $theme_uri . '/assets/img/icons/icon-kelompok.svg'],
            'title' => __('Pemberdayaan', 'yiari'),
            'subtitle' => __('Ekonomi berkelanjutan', 'yiari'),
            'details' => __('Mendorong mata pencaharian yang selaras dengan konservasi agar masyarakat mendapat manfaat tanpa merusak alam.', 'yiari'),
            'button_text' => __('Lihat Program', 'yiari'),
            'button_url' => '#',
            'image' => ['url' => $theme_uri . '/assets/img/bogor.webp'],
        ],
        [
            'icon' => ['url' => $theme_uri . '/assets/img/icons/icon-pelatihan.svg'],
            'title' => __('One Health', 'yiari'),
            'subtitle' => __('Kesehatan holistik', 'yiari'),
            'details' => __('Menghubungkan kesehatan manusia, satwa, dan lingkungan sebagai satu ekosistem yang saling memengaruhi.', 'yiari'),
            'button_text' => __('Lihat Program', 'yiari'),
            'button_url' => '#',
            'image' => ['url' => $theme_uri . '/assets/img/pulau-cempedak.webp'],
        ],
        [
            'icon' => ['url' => $theme_uri . '/assets/img/icons/icon-misi.svg'],
            'title' => __('Perlindungan', 'yiari'),
            'subtitle' => __('Penjagaan satwa', 'yiari'),
            'details' => __('Memperkuat upaya perlindungan satwa dari perdagangan ilegal, konflik, dan ancaman langsung di lapangan.', 'yiari'),
            'button_text' => __('Lihat Program', 'yiari'),
            'button_url' => '#',
            'image' => ['url' => $theme_uri . '/assets/img/ketapang.webp'],
        ],
    ];
}

$field_story_label = yiari_field('landscape_detail_story_label', __('DARI LAPANGAN', 'yiari'));
$field_story_title = yiari_field('landscape_detail_story_title', __("Pulih Pasca Kena\nJerat, Orangutan Siap\nKembali ke Habitatnya", 'yiari'));
$field_story_desc = yiari_field('landscape_detail_story_desc', __('Satwa ditemukan terluka akibat jerat, orangutan ini menjalani perawatan intensif dan kini tengah bersiap kembali ke alam liar.', 'yiari'));
$field_story_btn1_text = yiari_field('landscape_detail_story_btn1_text', __('Baca Selengkapnya', 'yiari'));
$field_story_btn1_url = yiari_field('landscape_detail_story_btn1_url', yiari_get_posts_page_url('#'));
$field_story_btn2_text = yiari_field('landscape_detail_story_btn2_text', __('Donasi Sekarang', 'yiari'));
$field_story_btn2_url = yiari_field('landscape_detail_story_btn2_url', yiari_fragment_url('donate'));
$field_story_image = yiari_field('landscape_detail_story_image');
$field_story_gallery = yiari_field('landscape_detail_story_gallery', []);

$detail_story_slides = [];

if (is_array($field_story_gallery) && $field_story_gallery) {
    foreach ($field_story_gallery as $gallery_image) {
        if (!empty($gallery_image['url'])) {
            $detail_story_slides[] = $gallery_image;
        }
    }
}

if (!empty($field_story_image['url'])) {
    array_unshift($detail_story_slides, $field_story_image);
}

if (empty($detail_story_slides)) {
    $detail_story_slides = [
        ['url' => $theme_uri . '/assets/img/gambar4.jpg', 'alt' => $field_story_title],
        ['url' => $theme_uri . '/assets/img/gambar5.jpg', 'alt' => $field_story_title],
        ['url' => $theme_uri . '/assets/img/gambar6.jpg', 'alt' => $field_story_title],
    ];
}

$detail_story_slides = array_values(array_filter($detail_story_slides, static fn($slide) => !empty($slide['url'])));
$detail_story_slide_count = (int) count($detail_story_slides);

$updates_label = yiari_field('landscape_detail_updates_label', __('SOROTAN LAPANGAN', 'yiari'));
$updates_title = yiari_field('landscape_detail_updates_title', __("Update dari\nKetapang", 'yiari'));
$updates_desc = yiari_field('landscape_detail_updates_desc', __('Beragam cerita, berita, dan informasi terbaru seputar kegiatan dan perkembangan konservasi di wilayah ini.', 'yiari'));
$updates_category_id = (int) yiari_field('landscape_detail_updates_category', 0);
$updates_count = max(3, min(12, (int) yiari_field('landscape_detail_updates_count', 9)));
$updates_button_text = yiari_field('landscape_detail_updates_button_text', __('Muat Lebih Banyak', 'yiari'));

$updates_args = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $updates_count,
    'ignore_sticky_posts' => true,
    'orderby' => 'date',
    'order' => 'DESC',
];

if ($updates_category_id > 0) {
    $updates_args['cat'] = $updates_category_id;
}

$updates_query = new WP_Query($updates_args);
$updates_has_more = $updates_query->max_num_pages > 1;
?>

<main class="detail-landscape-page">
  <section class="location-hero-wrap">
    <?php
    get_template_part('template-parts/sections/page-hero', null, [
        'classes' => 'location-hero-section',
        'image' => $hero_image,
        'image_class' => 'location-hero-img',
        'image_alt' => __('Lanskap konservasi YIARI', 'yiari'),
        'overlay_class' => 'location-hero-overlay',
        'content_class' => 'location-hero-content',
        'copy_class' => 'location-hero-copy',
        'title_class' => 'location-hero-title',
        'text_class' => 'location-hero-text',
        'actions_class' => 'location-hero-actions',
        'title' => $hero_title,
        'text' => $hero_text,
        'buttons' => [
            ['text' => $hero_btn1_text, 'url' => $hero_btn1_url, 'class' => 'btn-action btn-action-primary'],
            ['text' => $hero_btn2_text, 'url' => $hero_btn2_url, 'class' => 'btn-action btn-action-outline'],
        ],
    ]);
    ?>
  </section>

  <section class="detail-landscape-intro-section" x-data="{ activeIntro: 0 }">
    <div class="container">
      <div class="stats-layout detail-landscape-intro-header">
        <div class="stats-intro">
          <?php yiari_section_label($intro_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($intro_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($intro_desc); ?></p>
        </div>
      </div>

      <div class="crisis-accordion detail-landscape-intro-accordion">
        <div class="crisis-visual detail-landscape-intro-visual">
          <?php foreach ($intro_points as $index => $point): ?>
            <?php
            $point_image = $point['image'] ?? $intro_image;
            $point_title = $point['title'] ?? '';
            ?>
            <div
              class="crisis-visual-item"
              x-show="activeIntro === <?php echo (int) $index; ?>"
              x-transition:enter="crisis-fade-enter"
              x-transition:enter-start="crisis-fade-enter-start"
              x-transition:enter-end="crisis-fade-enter-end"
              x-transition:leave="crisis-fade-leave"
              x-transition:leave-start="crisis-fade-leave-start"
              x-transition:leave-end="crisis-fade-leave-end"
              x-cloak
            >
              <?php yiari_img($point_image, 'section-wide', 'crisis-visual-img detail-landscape-intro-image', $point_title); ?>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="crisis-panels detail-landscape-intro-points">
          <?php foreach ($intro_points as $index => $point): ?>
            <?php
            $point_image = $point['image'] ?? $intro_image;
            $point_title = $point['title'] ?? '';
            $point_text = $point['text'] ?? '';
            ?>
            <article class="crisis-panel detail-landscape-intro-point" :class="{ 'is-active': activeIntro === <?php echo (int) $index; ?> }">
              <button
                class="crisis-panel-trigger detail-landscape-intro-point-trigger"
                type="button"
                @click="activeIntro = <?php echo (int) $index; ?>"
                :aria-expanded="activeIntro === <?php echo (int) $index; ?> ? 'true' : 'false'"
              >
                <span class="detail-landscape-intro-point-icon crisis-panel-index"><?php echo (int) ($index + 1); ?></span>
                <span class="crisis-panel-line"></span>
              </button>

              <div class="crisis-panel-body detail-landscape-intro-point-body">
                <h3 class="crisis-panel-title detail-landscape-intro-point-title" @click="activeIntro = <?php echo (int) $index; ?>"><?php echo esc_html($point_title); ?></h3>

                <div
                  x-show="activeIntro === <?php echo (int) $index; ?>"
                  x-transition:enter="crisis-content-enter"
                  x-transition:enter-start="crisis-content-enter-start"
                  x-transition:enter-end="crisis-content-enter-end"
                  x-transition:leave="crisis-content-leave"
                  x-transition:leave-start="crisis-content-leave-start"
                  x-transition:leave-end="crisis-content-leave-end"
                  x-cloak
                >
                  <p class="crisis-panel-text detail-landscape-intro-point-text"><?php echo esc_html($point_text); ?></p>
                  <div class="crisis-panel-mobile-image detail-landscape-intro-point-mobile-image">
                    <?php yiari_img($point_image, 'section-wide', 'crisis-visual-img detail-landscape-intro-image', $point_title); ?>
                  </div>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section
    class="section-approach detail-landscape-program-section"
    x-data="{ activeApproach: 0, setApproach(index) { this.activeApproach = index; } }"
  >
    <div class="container">
      <div class="stats-layout">
        <div class="stats-intro fade-in">
          <?php yiari_section_label($program_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($program_title))); ?></h2>
        </div>
        <div class="stats-desc fade-in">
          <p class="section-description"><?php echo esc_html($program_desc); ?></p>
        </div>
      </div>

      <div class="approach-layout">
        <div class="approach-list fade-in">
          <div class="approach-items">
            <?php foreach ($program_items as $i => $item): ?>
              <?php
              $img_url = !empty($item['image']['url']) ? $item['image']['url'] : $theme_uri . '/assets/img/konservasi.png';
              $item_title = $item['title'] ?? '';
              $item_subtitle = $item['subtitle'] ?? '';
              $item_details = $item['details'] ?? $item_subtitle;
              $item_button_text = $item['button_text'] ?? __('Lihat Program', 'yiari');
              $item_button_url = $item['button_url'] ?? '#';
              $item_icon = $item['icon'] ?? null;
              ?>
              <div class="approach-item" :class="{ 'is-active': activeApproach === <?php echo (int) $i; ?> }">
                <button
                  type="button"
                  class="approach-trigger"
                  @click="setApproach(<?php echo (int) $i; ?>)"
                  @focus="setApproach(<?php echo (int) $i; ?>)"
                  :aria-expanded="activeApproach === <?php echo (int) $i; ?> ? 'true' : 'false'"
                >
                  <span class="approach-icon-wrap">
                    <?php yiari_img($item_icon, 'full', 'approach-icon', $item_title); ?>
                  </span>
                  <div class="approach-text">
                    <div class="approach-title"><?php echo esc_html($item_title); ?></div>
                    <div class="approach-subtitle"><?php echo esc_html($item_subtitle); ?></div>
                  </div>
                </button>

                <div class="approach-accordion-panel" x-show="activeApproach === <?php echo (int) $i; ?>" x-cloak>
                  <div class="approach-mobile-card">
                    <div class="approach-mobile-image-wrap">
                      <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($item_title); ?>" class="approach-main-img" loading="lazy" />
                    </div>
                    <div class="approach-mobile-footer">
                      <p class="approach-detail-text"><?php echo esc_html($item_details); ?></p>
                      <a class="btn-primary approach-detail-btn" href="<?php echo esc_url($item_button_url); ?>"><?php echo esc_html($item_button_text); ?></a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="approach-images fade-in approach-images-frame">
          <?php foreach ($program_items as $i => $item): ?>
            <?php
              $img_url = !empty($item['image']['url']) ? $item['image']['url'] : $theme_uri . '/assets/img/konservasi.png';
            $item_title = $item['title'] ?? '';
            $item_subtitle = $item['subtitle'] ?? '';
            $item_details = $item['details'] ?? ($item['subtitle'] ?? '');
            $item_button_text = $item['button_text'] ?? __('Lihat Program', 'yiari');
            $item_button_url = $item['button_url'] ?? '#';
            ?>
            <div
              class="approach-detail-card"
              x-show="activeApproach === <?php echo (int) $i; ?>"
              x-transition:enter="story-fade-enter"
              x-transition:enter-start="story-fade-enter-start"
              x-transition:enter-end="story-fade-enter-end"
              x-transition:leave="story-fade-leave"
              x-transition:leave-start="story-fade-leave-start"
              x-transition:leave-end="story-fade-leave-end"
              x-cloak
            >
              <div class="approach-detail-image-wrap">
                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($item_title); ?>" class="approach-main-img" loading="lazy" />
              </div>
              <div class="approach-detail-footer">
                <p class="approach-detail-text"><?php echo esc_html($item_details); ?></p>
                <a class="btn-primary approach-detail-btn" href="<?php echo esc_url($item_button_url); ?>"><?php echo esc_html($item_button_text); ?></a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section
    class="section-story detail-landscape-story-section"
    x-data="{
      activeStorySlide: 0,
      storySlideCount: <?php echo $detail_story_slide_count; ?>,
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
      <div class="story-card fade-in">
        <div class="story-card-head">
          <div class="story-card-title-block">
            <?php yiari_section_label($field_story_label, true); ?>
            <h2 class="section-heading story-heading"><?php echo wp_kses_post(nl2br(esc_html($field_story_title))); ?></h2>
          </div>
          <div class="story-card-copy">
            <?php if ($field_story_desc): ?><p class="section-description story-summary"><?php echo esc_html($field_story_desc); ?></p><?php endif; ?>
            <div class="story-actions">
              <?php yiari_btn($field_story_btn1_text, $field_story_btn1_url, 'btn-primary'); ?>
              <?php yiari_btn($field_story_btn2_text, $field_story_btn2_url, 'btn-outline-warning'); ?>
            </div>
          </div>
        </div>

        <div class="story-carousel">
          <div class="story-carousel-frame">
            <?php foreach ($detail_story_slides as $index => $slide): ?>
              <?php
              $slide_alt = $slide['alt'] ?? $field_story_title;
              $slide_url = $slide['url'] ?? '';
              ?>
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
                <img src="<?php echo esc_url($slide_url); ?>" alt="<?php echo esc_attr($slide_alt); ?>" class="story-slide-img" loading="lazy" />
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

  <section class="detail-landscape-updates-section">
    <div class="container">
      <div class="stats-layout detail-landscape-updates-header">
        <div class="stats-intro">
          <?php yiari_section_label($updates_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($updates_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($updates_desc); ?></p>
        </div>
      </div>

      <?php if ($updates_query->have_posts()): ?>
        <div
          class="detail-landscape-updates-grid"
          data-updates-grid
        >
          <?php while ($updates_query->have_posts()): $updates_query->the_post(); ?>
            <?php echo yiari_render_detail_landscape_update_card(get_the_ID()); ?>
          <?php endwhile; ?>
        </div>

        <?php if ($updates_button_text && $updates_has_more): ?>
          <div class="detail-landscape-updates-footer">
            <button
              type="button"
              class="btn-action btn-action-outline detail-landscape-updates-more"
              data-load-more-updates
              data-category-id="<?php echo esc_attr((string) $updates_category_id); ?>"
              data-page="2"
              data-count="<?php echo esc_attr((string) $updates_count); ?>"
            >
              <?php echo esc_html($updates_button_text); ?>
            </button>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="detail-landscape-updates-empty">
          <p class="detail-landscape-updates-empty-text"><?php echo esc_html__('Belum ada artikel pada kategori ini.', 'yiari'); ?></p>
        </div>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
