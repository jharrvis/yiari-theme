<?php
/**
 * Template Name: Lanskap
 * Halaman lanskap program konservasi YIARI.
 */

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();

$hero_image = yiari_field('landscape_hero_image', ['url' => $theme_uri . '/assets/img/hero-section.jpg']);
$hero_title = yiari_field('landscape_hero_title', __('Di Garis Depan Perlindungan Satwa dan Hutan Indonesia', 'yiari'));
$hero_title = preg_replace('/\s+/', ' ', trim((string) $hero_title));
$hero_text = yiari_field('landscape_hero_text', __('Dari hutan hujan Kalimantan hingga kawasan lindung Sumatra, kami bekerja di garis depan untuk melindungi ekosistem kritis dan keanekaragaman hayati Indonesia yang terancam.', 'yiari'));
$hero_btn1_text = yiari_field('landscape_hero_btn1_text', __('Lihat Lokasi Kegiatan', 'yiari'));
$hero_btn1_url = yiari_field('landscape_hero_btn1_url', '#lokasi-kegiatan');
$hero_btn2_text = yiari_field('landscape_hero_btn2_text', __('Pelajari Lebih Lanjut', 'yiari'));
$hero_btn2_url = yiari_field('landscape_hero_btn2_url', '#basis-operasi');

$locations_label = yiari_field('landscape_locations_label', __('WILAYAH KERJA KONSERVASI KAMI', 'yiari'));
$locations_title = yiari_field('landscape_locations_title', __("Lokasi Kegiatan\nProgram", 'yiari'));
$locations_desc = yiari_field('landscape_locations_desc', __('Kami beroperasi di wilayah-wilayah prioritas konservasi dengan kehadiran spesies kunci dan tingkat ancaman tinggi.', 'yiari'));
$locations = yiari_field('landscape_locations', []);

if (empty($locations)) {
    $locations = [
        [
            'image' => ['url' => $theme_uri . '/assets/img/batutegi.webp', 'alt' => __('Batutegi, Lampung', 'yiari')],
            'title' => __('Batutegi, Lampung', 'yiari'),
            'text' => __('Kawasan konservasi kukang dan primata lainnya, dengan fokus pada rehabilitasi satwa dan restorasi habitat alami.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => '#',
        ],
        [
            'image' => ['url' => $theme_uri . '/assets/img/pulau-cempedak.webp', 'alt' => __('Pulau Cempedak', 'yiari')],
            'title' => __('Pulau Cempedak', 'yiari'),
            'text' => __('Pulau pra-pelepasan orangutan yang menyediakan lingkungan semi-alami untuk persiapan kembali ke habitat liar.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => '#',
        ],
        [
            'image' => ['url' => $theme_uri . '/assets/img/arabella-schwanner.webp', 'alt' => __('Arabella–Schwaner', 'yiari')],
            'title' => __('Arabella–Schwaner', 'yiari'),
            'text' => __('Kawasan lindung yang mencakup koridor satwa penting bagi orangutan dan spesies endemik Kalimantan lainnya.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => '#',
        ],
        [
            'image' => ['url' => $theme_uri . '/assets/img/sentap-kancang.webp', 'alt' => __('Sentap Kancang', 'yiari')],
            'title' => __('Sentap Kancang', 'yiari'),
            'text' => __('Wilayah hutan primer dengan populasi orangutan liar yang menjadi fokus monitoring dan perlindungan habitat.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => '#',
        ],
        [
            'image' => ['url' => $theme_uri . '/assets/img/gunung-tarak.webp', 'alt' => __('Gunung Tarak', 'yiari')],
            'title' => __('Gunung Tarak', 'yiari'),
            'text' => __('Area konservasi dengan ekosistem pegunungan unik, rumah bagi berbagai spesies burung langka dan primata.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => '#',
        ],
        [
            'image' => ['url' => $theme_uri . '/assets/img/pematang-gadung.webp', 'alt' => __('Pematang Gadung', 'yiari')],
            'title' => __('Pematang Gadung', 'yiari'),
            'text' => __('Kawasan lindung yang mencakup koridor satwa penting bagi orangutan dan spesies endemik Kalimantan lainnya.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => '#',
        ],
    ];
}

$bases_label = yiari_field('landscape_bases_label', __('BASIS OPERASI', 'yiari'));
$bases_title = yiari_field('landscape_bases_title', __("Pusat Utama\nOperasional", 'yiari'));
$bases_desc = yiari_field('landscape_bases_desc', __('Dua lokasi strategis yang menjadi jantung operasional kami dalam menyelamatkan, merehabilitasi, dan mengedukasi.', 'yiari'));
$bases = yiari_field('landscape_bases', []);

if (empty($bases)) {
    $bases = [
        [
            'image' => ['url' => $theme_uri . '/assets/img/ketapang.webp', 'alt' => __('Ketapang, Kalimantan Barat', 'yiari')],
            'title' => __('Ketapang, Kalimantan Barat', 'yiari'),
            'text' => __('Fasilitas medis dan rehabilitasi terlengkap untuk penyelamatan orangutan dari konflik manusia-satwa dan perdagangan ilegal. Di sini, kami memberikan perawatan intensif, rehabilitasi perilaku, dan persiapan untuk kembali ke habitat liar.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => '#',
        ],
        [
            'image' => ['url' => $theme_uri . '/assets/img/bogor.webp', 'alt' => __('Bogor, Jawa Barat', 'yiari')],
            'title' => __('Bogor, Jawa Barat', 'yiari'),
            'text' => __('Basis koordinasi program nasional dan pusat edukasi konservasi. Di sini, kami mengembangkan materi pembelajaran, mengadakan pelatihan untuk masyarakat dan pemerintah daerah, serta menjalankan kampanye penyadartahuan publik.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => '#',
        ],
    ];
}
?>

<main class="location-page">
  <section class="location-hero-wrap">
    <?php
    get_template_part('template-parts/sections/page-hero', null, [
        'classes' => 'location-hero-section',
        'image' => $hero_image,
        'image_class' => 'location-hero-img',
        'image_alt' => __('Hutan konservasi YIARI', 'yiari'),
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

  <section class="location-dark-section" id="lokasi-kegiatan">
    <div class="container">
      <div class="stats-layout location-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($locations_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($locations_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($locations_desc); ?></p>
        </div>
      </div>

      <div class="location-card-grid">
        <?php foreach ($locations as $item): ?>
          <?php
          $item_image = $item['image'] ?? null;
          $item_title = $item['title'] ?? '';
          $item_text = $item['text'] ?? '';
          $item_link_text = $item['link_text'] ?? __('Lihat Selengkapnya', 'yiari');
          $item_link_url = $item['link_url'] ?? '#';
          ?>
          <article class="location-card">
            <div class="location-card-image">
              <?php yiari_img($item_image, 'large', '', $item_title); ?>
            </div>
            <div class="location-card-body">
              <h3 class="location-card-title"><?php echo esc_html($item_title); ?></h3>
              <p class="location-card-text"><?php echo esc_html($item_text); ?></p>
              <a href="<?php echo esc_url($item_link_url); ?>" class="location-card-link"><?php echo esc_html($item_link_text); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="stats-layout location-section-header location-section-header-secondary" id="basis-operasi">
        <div class="stats-intro">
          <?php yiari_section_label($bases_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($bases_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($bases_desc); ?></p>
        </div>
      </div>

      <div class="location-bases">
        <?php foreach ($bases as $index => $item): ?>
          <?php
          $item_image = $item['image'] ?? null;
          $item_title = $item['title'] ?? '';
          $item_text = $item['text'] ?? '';
          $item_link_text = $item['link_text'] ?? __('Lihat Selengkapnya', 'yiari');
          $item_link_url = $item['link_url'] ?? '#';
          $reverse_class = $index % 2 === 1 ? ' location-base-card-reverse' : '';
          ?>
          <article class="location-base-card<?php echo esc_attr($reverse_class); ?>">
            <div class="location-base-copy">
              <h3 class="location-base-title"><?php echo esc_html($item_title); ?></h3>
              <p class="location-base-text"><?php echo esc_html($item_text); ?></p>
              <a href="<?php echo esc_url($item_link_url); ?>" class="location-card-link"><?php echo esc_html($item_link_text); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
            </div>
            <div class="location-base-media">
              <div class="location-card-image location-card-image-wide">
                <?php yiari_img($item_image, 'section-wide', '', $item_title); ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
