<?php
/**
 * Template Name: Jurnal
 * Template Post Type: page
 * Halaman perpustakaan penelitian dan publikasi jurnal YIARI.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();

$hero_image = yiari_field('journal_hero_image', ['url' => $theme_uri . '/assets/img/hero-section.jpg']);
$hero_title = yiari_field('journal_hero_title', __("Penelitian untuk Memahami dan\nMelindungi Satwa Liar", 'yiari'));
$hero_text = yiari_field('journal_hero_text', __('Penelitian membantu kami memahami satwa, habitatnya, dan ancaman yang mereka hadapi. Pengetahuan ini menjadi dasar bagi berbagai upaya konservasi yang dilakukan YIARI.', 'yiari'));
$hero_btn_text = yiari_field('journal_hero_btn_text', __('Lihat Perpustakaan Penelitian', 'yiari'));
$hero_btn_url = yiari_localize_url((string) yiari_field('journal_hero_btn_url', '#journal-library'));

$library_label = yiari_field('journal_library_label', __('SUMBER PENGETAHUAN', 'yiari'));
$library_title = yiari_field('journal_library_title', __('Perpustakaan Penelitian', 'yiari'));
$library_desc = yiari_field('journal_library_desc', __('Kumpulan publikasi ilmiah dan laporan teknis hasil kerja sama YIARI dengan berbagai mitra akademis.', 'yiari'));
$library_shortcode = trim((string) yiari_field('journal_library_shortcode', '[jurnal_accordion parent_slug="jurnal" taxonomy="kategori-publikasi" orderby="name" order="DESC" class="yiari-journal-accordion" show_search="yes" show_filter="yes"]'));
$has_journal_shortcode = $library_shortcode !== '' && preg_match('/\[jurnal_accordion\b/i', $library_shortcode) && shortcode_exists('jurnal_accordion');

$more_label = yiari_field('journal_more_label', __('EKSPLOR LEBIH BANYAK', 'yiari'));
$more_title = yiari_field('journal_more_title', __('Publikasi Lainnya', 'yiari'));
$more_desc = yiari_field('journal_more_desc', __('Beragam konten tambahan yang melengkapi informasi dan wawasan seputar konservasi.', 'yiari'));
$more_items = yiari_merge_publication_more_items(yiari_field('journal_more_items', []));
$current_page_url = trailingslashit((string) get_permalink());
$more_items = array_values(array_filter($more_items, static function ($item) use ($current_page_url): bool {
    $url = yiari_localize_url(trim((string) ($item['url'] ?? '')));
    if ($url === '') {
        return false;
    }

    return trailingslashit($url) !== $current_page_url;
}));

$cta_label = yiari_field('journal_cta_label', __('DUKUNGAN ANDA', 'yiari'));
$cta_title = yiari_field('journal_cta_title', __("Dukung Penelitian untuk\nMelindungi Satwa Liar", 'yiari'));
$cta_text = yiari_field('journal_cta_text', __('Dukungan Anda membantu kami melanjutkan penelitian di lapangan, membiayai peralatan teknologi mutakhir, dan melatih generasi peneliti muda Indonesia untuk masa depan alam kita.', 'yiari'));
$cta_btn1_text = yiari_field('journal_cta_btn1_text', __('Donasi Sekarang', 'yiari'));
$cta_btn1_url = yiari_localize_url((string) yiari_field('journal_cta_btn1_url', yiari_get_page_url_by_template('templates/donasi.php', yiari_home_url())));
$cta_btn2_text = yiari_field('journal_cta_btn2_text', __('Gabung Bersama YIARI', 'yiari'));
$cta_btn2_url = yiari_localize_url((string) yiari_field('journal_cta_btn2_url', yiari_get_join_url()));
$cta_image = yiari_field('journal_cta_image', ['url' => $theme_uri . '/assets/img/gambar-cta.png', 'alt' => __('Dukung penelitian YIARI', 'yiari')]);
?>

<main class="journal-page">
  <section class="journal-hero-wrap">
    <?php
    get_template_part('template-parts/sections/page-hero', null, [
        'id' => 'journal-top',
        'classes' => 'journal-hero-section',
        'image' => $hero_image,
        'image_class' => 'journal-hero-img',
        'image_alt' => __('Penelitian YIARI', 'yiari'),
        'overlay_class' => 'journal-hero-overlay',
        'content_class' => 'journal-hero-content',
        'copy_class' => 'journal-hero-copy',
        'title_class' => 'journal-hero-title',
        'text_class' => 'journal-hero-text',
        'actions_class' => 'journal-hero-actions',
        'title' => $hero_title,
        'text' => $hero_text,
        'allow_title_breaks' => true,
        'buttons' => [
            ['text' => $hero_btn_text, 'url' => $hero_btn_url, 'class' => 'btn-action btn-action-primary'],
        ],
    ]);
    ?>
  </section>

  <section class="journal-library-section" id="journal-library">
    <div class="container">
      <div class="stats-layout journal-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($library_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($library_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($library_desc); ?></p>
        </div>
      </div>

      <div class="journal-library-shell">
        <?php if ($has_journal_shortcode): ?>
          <?php echo do_shortcode($library_shortcode); ?>
        <?php else: ?>
          <div class="journal-library-unavailable">
            <p><?php esc_html_e('Shortcode jurnal belum diisi atau plugin Jurnal Accordion AJAX belum aktif.', 'yiari'); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="journal-more-section">
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

  <section class="journal-cta-section">
    <div class="container">
      <div class="journal-cta-card">
        <div class="journal-cta-copy">
          <div class="donate-pill">
            <i data-lucide="heart-handshake" class="donate-pill-icon"></i>
            <span><?php echo esc_html($cta_label); ?></span>
          </div>

          <h2 class="journal-cta-title"><?php echo wp_kses_post(nl2br(esc_html($cta_title))); ?></h2>
          <p class="journal-cta-text"><?php echo esc_html($cta_text); ?></p>

          <div class="journal-cta-actions">
            <?php yiari_btn($cta_btn1_text, $cta_btn1_url, 'btn-donate-main'); ?>
            <?php yiari_btn($cta_btn2_text, $cta_btn2_url, 'btn-volunteer-main'); ?>
          </div>
        </div>

        <div class="journal-cta-media">
          <?php yiari_img($cta_image, 'section-wide', 'journal-cta-image', $cta_title); ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
