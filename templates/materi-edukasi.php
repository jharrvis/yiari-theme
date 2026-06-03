<?php
/**
 * Template Name: Materi Edukasi
 * Template Post Type: page
 * Halaman materi edukasi dan sumber pembelajaran YIARI.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();

$hero_image = yiari_field('education_hero_image', ['url' => $theme_uri . '/assets/img/hero-section.jpg']);
$hero_title = yiari_field('education_hero_title', __('Materi Edukasi', 'yiari'));
$hero_text = yiari_field('education_hero_text', __('Materi edukasi ini membantu memperluas pemahaman tentang satwa liar, habitat, dan praktik konservasi melalui media yang mudah diakses.', 'yiari'));
$hero_btn_text = yiari_field('education_hero_btn_text', __('Lihat Materi Edukasi', 'yiari'));
$hero_btn_url = yiari_localize_url((string) yiari_field('education_hero_btn_url', '#education-library'));

$library_label = yiari_field('education_library_label', __('SUMBER PEMBELAJARAN', 'yiari'));
$library_title = yiari_field('education_library_title', __('Koleksi Materi Edukasi', 'yiari'));
$library_desc = yiari_field('education_library_desc', __('Poster, panduan, lembar belajar, dan materi edukasi yang mendukung penyadartahuan konservasi.', 'yiari'));
$library_terms_raw = yiari_field('education_library_terms', []);
$library_terms = is_array($library_terms_raw) ? $library_terms_raw : [$library_terms_raw];
$library_terms = array_values(array_filter(array_map('intval', $library_terms)));
$library_count = max(3, min(18, (int) yiari_field('education_library_count', 9)));
$load_more_text = yiari_field('education_load_more_text', __('Muat Lebih Banyak', 'yiari'));

$education_query_args = [
    'post_type' => 'publikasi',
    'post_status' => 'publish',
    'posts_per_page' => $library_count,
    'ignore_sticky_posts' => true,
    'orderby' => 'date',
    'order' => 'DESC',
    'suppress_filters' => false,
];

if (!empty($library_terms)) {
    $education_query_args['tax_query'] = [[
        'taxonomy' => 'kategori-publikasi',
        'field' => 'term_id',
        'terms' => $library_terms,
    ]];
}

$education_query = new WP_Query($education_query_args);
$education_posts = $education_query->posts;
$education_has_more = $education_query->max_num_pages > 1;
wp_reset_postdata();

$more_label = yiari_field('education_more_label', __('EKSPLOR LEBIH BANYAK', 'yiari'));
$more_title = yiari_field('education_more_title', __('Publikasi Lainnya', 'yiari'));
$more_desc = yiari_field('education_more_desc', __('Beragam konten tambahan yang melengkapi informasi dan wawasan seputar konservasi.', 'yiari'));
$more_items = yiari_merge_publication_more_items(yiari_field('education_more_items', []));
$current_page_url = trailingslashit((string) get_permalink());
$more_items = array_values(array_filter($more_items, static function ($item) use ($current_page_url): bool {
    $title = trim((string) ($item['title'] ?? ''));
    $normalized_title = function_exists('mb_strtolower') ? mb_strtolower($title) : strtolower($title);

    if (in_array($normalized_title, ['materi edukasi', 'education materials'], true)) {
        return false;
    }

    $url = yiari_localize_url(trim((string) ($item['url'] ?? '')));
    if ($url === '') {
        return true;
    }

    return trailingslashit($url) !== $current_page_url;
}));

$cta_label = yiari_field('education_cta_label', __('DUKUNGAN ANDA', 'yiari'));
$cta_title = yiari_field('education_cta_title', __("Bantu Sebarkan\nPengetahuan untuk\nKonservasi", 'yiari'));
$cta_text = yiari_field('education_cta_text', __('Dukungan Anda membantu YIARI menghadirkan lebih banyak materi edukasi dan pengetahuan konservasi yang mudah diakses oleh masyarakat luas.', 'yiari'));
$cta_btn1_text = yiari_field('education_cta_btn1_text', __('Donasi Sekarang', 'yiari'));
$cta_btn1_url = yiari_localize_url((string) yiari_field('education_cta_btn1_url', yiari_get_page_url_by_template('templates/donasi.php', yiari_home_url())));
$cta_btn2_text = yiari_field('education_cta_btn2_text', __('Gabung Bersama YIARI', 'yiari'));
$cta_btn2_url = yiari_localize_url((string) yiari_field('education_cta_btn2_url', yiari_get_join_url()));
$cta_image = yiari_field('education_cta_image', ['url' => $theme_uri . '/assets/img/gambar-cta.png', 'alt' => __('Dukung penyebaran materi edukasi YIARI', 'yiari')]);
?>

<main class="education-page books-page">
  <section class="books-hero-wrap education-hero-wrap">
    <?php
    get_template_part('template-parts/sections/page-hero', null, [
        'id' => 'education-top',
        'classes' => 'books-hero-section education-hero-section',
        'image' => $hero_image,
        'image_class' => 'books-hero-img education-hero-img',
        'image_alt' => __('Materi Edukasi YIARI', 'yiari'),
        'overlay_class' => 'books-hero-overlay education-hero-overlay',
        'content_class' => 'books-hero-content education-hero-content',
        'copy_class' => 'books-hero-copy education-hero-copy',
        'title_class' => 'books-hero-title education-hero-title',
        'text_class' => 'books-hero-text education-hero-text',
        'actions_class' => 'books-hero-actions education-hero-actions',
        'title' => $hero_title,
        'text' => $hero_text,
        'buttons' => [
            ['text' => $hero_btn_text, 'url' => $hero_btn_url, 'class' => 'btn-action btn-action-primary'],
        ],
    ]);
    ?>
  </section>

  <section class="books-library-section education-library-section" id="education-library">
    <div
      class="container"
      x-data="{
        itemsHtml: '',
        page: 2,
        hasMore: <?php echo $education_has_more ? 'true' : 'false'; ?>,
        loading: false,
        error: '',
        terms: <?php echo wp_json_encode($library_terms); ?>,
        count: <?php echo (int) $library_count; ?>,
        init() {
          this.itemsHtml = this.$refs.grid ? this.$refs.grid.innerHTML : '';
        },
        async loadMore() {
          if (this.loading || !this.hasMore) return;

          this.loading = true;
          this.error = '';

          try {
            const payload = new URLSearchParams({
              action: 'yiari_load_more_publications',
              nonce: window.yiariPublications?.nonce || '',
              page: String(this.page),
              count: String(this.count),
              variant: 'education'
            });

            this.terms.forEach((termId) => payload.append('terms[]', String(termId)));

            const response = await fetch(window.yiariPublications?.ajaxUrl || '', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
              },
              body: payload.toString(),
            });

            const data = await response.json();
            if (!data?.success) {
              throw new Error('Request failed');
            }

            this.itemsHtml += data.data?.html || '';
            this.page = Number(data.data?.nextPage || (this.page + 1));
            this.hasMore = Boolean(data.data?.hasMore);

            if (this.$refs.grid) {
              this.$refs.grid.innerHTML = this.itemsHtml;
            }

            this.$nextTick(() => {
              if (window.lucide?.createIcons) {
                window.lucide.createIcons();
              }
            });
          } catch (error) {
            this.error = window.yiariPublications?.strings?.error || 'Gagal memuat publikasi berikutnya.';
          } finally {
            this.loading = false;
          }
        }
      }"
    >
      <div class="stats-layout books-section-header education-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($library_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($library_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($library_desc); ?></p>
        </div>
      </div>

      <?php if (!empty($education_posts)): ?>
        <div class="books-grid education-grid" x-ref="grid">
          <?php foreach ($education_posts as $education_post): ?>
            <?php echo yiari_render_publication_card((int) $education_post->ID, 'education'); ?>
          <?php endforeach; ?>
        </div>

        <?php if ($education_has_more): ?>
          <div class="books-load-more-wrap books-load-more-wrap-visible education-load-more-wrap" x-show="hasMore" x-cloak>
            <button type="button" class="btn-volunteer-main books-load-more-btn education-load-more-btn" @click="loadMore()" :disabled="loading">
              <span x-show="!loading"><?php echo esc_html($load_more_text); ?></span>
              <span x-show="loading" x-cloak><?php esc_html_e('Memuat...', 'yiari'); ?></span>
            </button>
          </div>
          <p class="books-load-more-error" x-show="error" x-text="error" x-cloak></p>
        <?php endif; ?>
      <?php else: ?>
        <div class="books-empty-state education-empty-state">
          <p><?php esc_html_e('Belum ada materi edukasi yang tersedia untuk kategori ini.', 'yiari'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="journal-more-section books-more-section education-more-section">
    <div class="container" x-data="{
      prev() {
        if (!this.$refs.track) return;
        this.$refs.track.scrollBy({ left: -320, behavior: 'smooth' });
      },
      next() {
        if (!this.$refs.track) return;
        this.$refs.track.scrollBy({ left: 320, behavior: 'smooth' });
      }
    }">
      <div class="stats-layout journal-section-header journal-more-header">
        <div class="stats-intro">
          <?php yiari_section_label($more_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($more_title))); ?></h2>
        </div>
        <div class="stats-desc journal-more-desc">
          <p class="section-description"><?php echo esc_html($more_desc); ?></p>
          <div class="journal-more-nav" aria-label="<?php esc_attr_e('Navigasi publikasi lainnya', 'yiari'); ?>">
            <button type="button" class="journal-more-arrow" @click="prev()">
              <i data-lucide="chevron-left" class="icon-sm"></i>
            </button>
            <button type="button" class="journal-more-arrow" @click="next()">
              <i data-lucide="chevron-right" class="icon-sm"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="journal-more-track" x-ref="track">
        <?php foreach ($more_items as $more_item): ?>
          <?php
          $more_url = yiari_localize_url(trim((string) ($more_item['url'] ?? '')));
          $more_title_text = trim((string) ($more_item['title'] ?? ''));
          $more_text = trim((string) ($more_item['text'] ?? ''));
          $more_icon = trim((string) ($more_item['icon'] ?? 'file-text'));
          ?>
          <article class="journal-more-card">
            <a href="<?php echo esc_url($more_url !== '' ? $more_url : '#'); ?>" class="journal-more-card-link" aria-label="<?php echo esc_attr($more_title_text); ?>">
              <div class="journal-more-card-head">
                <span class="journal-more-card-icon">
                  <i data-lucide="<?php echo esc_attr($more_icon); ?>" class="icon-md"></i>
                </span>
                <span class="journal-more-card-arrow">
                  <i data-lucide="chevron-right" class="icon-sm"></i>
                </span>
              </div>
              <h3 class="journal-more-card-title"><?php echo esc_html($more_title_text); ?></h3>
              <p class="journal-more-card-text"><?php echo esc_html($more_text); ?></p>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="journal-cta-section books-cta-section education-cta-section">
    <div class="container">
      <div class="journal-cta-card books-cta-card education-cta-card">
        <div class="journal-cta-copy books-cta-copy education-cta-copy">
          <div class="donate-pill">
            <i data-lucide="heart-handshake" class="donate-pill-icon"></i>
            <span><?php echo esc_html($cta_label); ?></span>
          </div>

          <h2 class="journal-cta-title books-cta-title education-cta-title"><?php echo wp_kses_post(nl2br(esc_html($cta_title))); ?></h2>
          <p class="journal-cta-text books-cta-text education-cta-text"><?php echo esc_html($cta_text); ?></p>

          <div class="journal-cta-actions books-cta-actions education-cta-actions">
            <?php yiari_btn($cta_btn1_text, $cta_btn1_url, 'btn-donate-main'); ?>
            <?php yiari_btn($cta_btn2_text, $cta_btn2_url, 'btn-volunteer-main'); ?>
          </div>
        </div>

        <div class="journal-cta-media books-cta-media education-cta-media">
          <?php yiari_img($cta_image, 'section-wide', 'journal-cta-image books-cta-image education-cta-image', $cta_title); ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
