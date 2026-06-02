<?php
/**
 * Template Name: Buku
 * Template Post Type: page
 * Halaman koleksi buku dan panduan konservasi YIARI.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();

$hero_image = yiari_field('books_hero_image', ['url' => $theme_uri . '/assets/img/hero-section.jpg']);
$hero_title = yiari_field('books_hero_title', __('Buku Konservasi', 'yiari'));
$hero_text = yiari_field('books_hero_text', __('Buku-buku ini berisi pengetahuan, pengalaman lapangan, dan panduan yang membantu kita memahami satwa liar serta cara melindungi habitatnya.', 'yiari'));
$hero_btn_text = yiari_field('books_hero_btn_text', __('Lihat Koleksi Buku', 'yiari'));
$hero_btn_url = yiari_localize_url((string) yiari_field('books_hero_btn_url', '#books-library'));

$library_label = yiari_field('books_library_label', __('SUMBER PENGETAHUAN', 'yiari'));
$library_title = yiari_field('books_library_title', __('Koleksi Buku', 'yiari'));
$library_desc = yiari_field('books_library_desc', __('Publikasi buku, panduan, dan materi bacaan yang memperluas wawasan konservasi dan pengalaman lapangan YIARI.', 'yiari'));
$library_terms_raw = yiari_field('books_library_terms', []);
$library_terms = is_array($library_terms_raw) ? $library_terms_raw : [$library_terms_raw];
$library_terms = array_values(array_filter(array_map('intval', $library_terms)));
$library_count = max(3, min(18, (int) yiari_field('books_library_count', 9)));
$load_more_text = yiari_field('books_load_more_text', __('Muat Lebih Banyak', 'yiari'));

$book_query_args = [
    'post_type' => 'publikasi',
    'post_status' => 'publish',
    'posts_per_page' => $library_count,
    'ignore_sticky_posts' => true,
    'orderby' => 'date',
    'order' => 'DESC',
    'suppress_filters' => false,
];

if (!empty($library_terms)) {
    $book_query_args['tax_query'] = [[
        'taxonomy' => 'kategori-publikasi',
        'field' => 'term_id',
        'terms' => $library_terms,
    ]];
}

$book_query = new WP_Query($book_query_args);
$book_posts = $book_query->posts;
wp_reset_postdata();

$more_label = yiari_field('books_more_label', __('EKSPLOR LEBIH BANYAK', 'yiari'));
$more_title = yiari_field('books_more_title', __('Publikasi Lainnya', 'yiari'));
$more_desc = yiari_field('books_more_desc', __('Beragam konten tambahan yang melengkapi informasi dan wawasan seputar konservasi.', 'yiari'));
$more_items = yiari_field('books_more_items', []);

if (empty($more_items) || !is_array($more_items)) {
    $more_items = [
        [
            'title' => __('Blog', 'yiari'),
            'text' => __('Cerita, insight, dan perspektif dari lapangan.', 'yiari'),
            'url' => yiari_get_page_url_by_paths(['blog'], home_url('/blog/')),
            'icon' => 'file-text',
        ],
        [
            'title' => __('Siaran Pers', 'yiari'),
            'text' => __('Informasi resmi mengenai kegiatan dan perkembangan terbaru.', 'yiari'),
            'url' => yiari_get_page_url_by_paths(['siaran-pers', 'press-releases'], home_url('/siaran-pers/')),
            'icon' => 'newspaper',
        ],
        [
            'title' => __('Buletin', 'yiari'),
            'text' => __('Ringkasan kabar dan update terbaru.', 'yiari'),
            'url' => yiari_get_page_url_by_paths(['buletin', 'bulletin'], home_url('/buletin/')),
            'icon' => 'book-open-text',
        ],
    ];
}

$cta_label = yiari_field('books_cta_label', __('DUKUNGAN ANDA', 'yiari'));
$cta_title = yiari_field('books_cta_title', __("Bantu Sebarkan\nPengetahuan untuk\nKonservasi", 'yiari'));
$cta_text = yiari_field('books_cta_text', __('Dukungan Anda membantu YIARI menghadirkan lebih banyak buku, materi edukasi, dan pengetahuan konservasi yang mudah diakses oleh masyarakat luas.', 'yiari'));
$cta_btn1_text = yiari_field('books_cta_btn1_text', __('Donasi Sekarang', 'yiari'));
$cta_btn1_url = yiari_localize_url((string) yiari_field('books_cta_btn1_url', yiari_get_page_url_by_template('templates/donasi.php', yiari_home_url())));
$cta_btn2_text = yiari_field('books_cta_btn2_text', __('Gabung Bersama YIARI', 'yiari'));
$cta_btn2_url = yiari_localize_url((string) yiari_field('books_cta_btn2_url', yiari_get_join_url()));
$cta_image = yiari_field('books_cta_image', ['url' => $theme_uri . '/assets/img/gambar-cta.png', 'alt' => __('Dukung penyebaran pengetahuan YIARI', 'yiari')]);
?>

<main class="books-page">
  <section class="books-hero-wrap">
    <?php
    get_template_part('template-parts/sections/page-hero', null, [
        'id' => 'books-top',
        'classes' => 'books-hero-section',
        'image' => $hero_image,
        'image_class' => 'books-hero-img',
        'image_alt' => __('Buku Konservasi YIARI', 'yiari'),
        'overlay_class' => 'books-hero-overlay',
        'content_class' => 'books-hero-content',
        'copy_class' => 'books-hero-copy',
        'title_class' => 'books-hero-title',
        'text_class' => 'books-hero-text',
        'actions_class' => 'books-hero-actions',
        'title' => $hero_title,
        'text' => $hero_text,
        'buttons' => [
            ['text' => $hero_btn_text, 'url' => $hero_btn_url, 'class' => 'btn-action btn-action-primary'],
        ],
    ]);
    ?>
  </section>

  <section class="books-library-section" id="books-library">
    <div
      class="container"
      x-data="{
        mobile: window.innerWidth <= 640,
        expanded: false,
        init() {
          const syncState = () => {
            this.mobile = window.innerWidth <= 640;
            if (!this.mobile) this.expanded = true;
          };
          syncState();
          window.addEventListener('resize', syncState, { passive: true });
        },
        isVisible(index) {
          return !this.mobile || this.expanded || index < 4;
        }
      }"
    >
      <div class="stats-layout books-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($library_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($library_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($library_desc); ?></p>
        </div>
      </div>

      <?php if (!empty($book_posts)): ?>
        <div class="books-grid">
          <?php foreach ($book_posts as $book_index => $book_post): ?>
            <?php
            $book_post_id = (int) $book_post->ID;
            $book_permalink = get_permalink($book_post_id) ?: '#';
            $book_image_url = get_the_post_thumbnail_url($book_post_id, 'card-thumb') ?: ($theme_uri . '/assets/img/hero-section.jpg');
            $book_title = get_the_title($book_post_id);
            $book_excerpt = has_excerpt($book_post_id)
                ? get_the_excerpt($book_post_id)
                : wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $book_post_id)), 18, '…');
            $book_date = get_the_date('j F Y', $book_post_id);
            $book_author = get_the_author_meta('display_name', (int) get_post_field('post_author', $book_post_id));
            $book_meta = trim($book_date . ($book_author !== '' ? ' • ' . $book_author : ''));
            ?>
            <article class="book-card" x-show="isVisible(<?php echo esc_attr((string) $book_index); ?>)" x-cloak>
              <a href="<?php echo esc_url($book_permalink); ?>" class="book-card-media" aria-label="<?php echo esc_attr($book_title); ?>">
                <img src="<?php echo esc_url($book_image_url); ?>" alt="<?php echo esc_attr($book_title); ?>" class="book-card-image" />
              </a>
              <div class="book-card-body">
                <h3 class="book-card-title">
                  <a href="<?php echo esc_url($book_permalink); ?>"><?php echo esc_html($book_title); ?></a>
                </h3>
                <?php if ($book_meta !== ''): ?>
                  <div class="book-card-meta"><?php echo esc_html($book_meta); ?></div>
                <?php endif; ?>
                <p class="book-card-text"><?php echo esc_html($book_excerpt); ?></p>
                <a href="<?php echo esc_url($book_permalink); ?>" class="link-more book-card-link"><?php esc_html_e('Baca Selengkapnya', 'yiari'); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <?php if (count($book_posts) > 4): ?>
          <div class="books-load-more-wrap" x-show="mobile && !expanded" x-cloak>
            <button type="button" class="btn-volunteer-main books-load-more-btn" @click="expanded = true"><?php echo esc_html($load_more_text); ?></button>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="books-empty-state">
          <p><?php esc_html_e('Belum ada buku yang tersedia untuk kategori ini.', 'yiari'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="journal-more-section books-more-section">
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

  <section class="journal-cta-section books-cta-section">
    <div class="container">
      <div class="journal-cta-card books-cta-card">
        <div class="journal-cta-copy books-cta-copy">
          <div class="donate-pill">
            <i data-lucide="heart-handshake" class="donate-pill-icon"></i>
            <span><?php echo esc_html($cta_label); ?></span>
          </div>

          <h2 class="journal-cta-title books-cta-title"><?php echo wp_kses_post(nl2br(esc_html($cta_title))); ?></h2>
          <p class="journal-cta-text books-cta-text"><?php echo esc_html($cta_text); ?></p>

          <div class="journal-cta-actions books-cta-actions">
            <?php yiari_btn($cta_btn1_text, $cta_btn1_url, 'btn-donate-main'); ?>
            <?php yiari_btn($cta_btn2_text, $cta_btn2_url, 'btn-volunteer-main'); ?>
          </div>
        </div>

        <div class="journal-cta-media books-cta-media">
          <?php yiari_img($cta_image, 'section-wide', 'journal-cta-image books-cta-image', $cta_title); ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
