<?php
/**
 * Template Name: Bergabung
 * Template Post Type: page
 * Halaman magang, relawan, dan pendaftaran program YIARI.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();

$hero_image = yiari_field('join_hero_image', ['url' => $theme_uri . '/assets/img/hero-section.jpg']);
$hero_title = yiari_field('join_hero_title', __("Bersama Kita Bisa\nMelindungi Satwa\ndan Hutan Indonesia", 'yiari'));
$hero_text = yiari_field('join_hero_text', __('Setiap langkah kecil yang Anda ambil hari ini menjadi fondasi bagi keberlangsungan ekosistem nusantara di masa depan. Bergabunglah dalam misi kolaboratif untuk menyelamatkan habitat yang terancam.', 'yiari'));
$hero_btn1_text = yiari_field('join_hero_btn1_text', __('Mulai Bergabung', 'yiari'));
$hero_btn1_url = yiari_field('join_hero_btn1_url', '#join-form');
$hero_btn2_text = yiari_field('join_hero_btn2_text', __('Pelajari Lebih Lanjut', 'yiari'));
$hero_btn2_url = yiari_field('join_hero_btn2_url', '#join-program');

$program_label = yiari_field('join_program_label', __('PROGRAM MAGANG & RELAWAN', 'yiari'));
$program_title = yiari_field('join_program_title', __("Rasakan Pengalaman\nNyata Bersama Tim\nKonservasi YIARI", 'yiari'));
$program_desc = yiari_field('join_program_desc', __('Program ini membuka kesempatan bagi pelajar dan mahasiswa untuk terlibat langsung dalam berbagai kegiatan konservasi, mulai dari magang, relawan, penelitian, hingga pengabdian lainnya di lingkungan YIARI.', 'yiari'));
$program_image = yiari_field('join_program_image', ['url' => $theme_uri . '/assets/img/gambar4.jpg', 'alt' => __('Program magang dan relawan YIARI', 'yiari')]);
$program_items = yiari_field('join_program_items', []);

if (empty($program_items) || !is_array($program_items)) {
    $program_items = [
        [
            'title' => __('Magang', 'yiari'),
            'text' => __('Belajar langsung melalui kegiatan operasional dan program konservasi YIARI.', 'yiari'),
        ],
        [
            'title' => __('Relawan', 'yiari'),
            'text' => __('Berpartisipasi dalam kegiatan sosial, edukasi, dan pelestarian lingkungan.', 'yiari'),
        ],
        [
            'title' => __('Penelitian', 'yiari'),
            'text' => __('Mendukung pengumpulan data dan kegiatan riset di lapangan.', 'yiari'),
        ],
    ];
}

$story_label = yiari_field('join_story_label', __('CERITA DARI LAPANGAN', 'yiari'));
$story_title = yiari_field('join_story_title', __("Pengalaman Nyata\nBersama YIARI", 'yiari'));
$story_desc = yiari_field('join_story_desc', __('Kisah mahasiswa, relawan, dan peserta magang yang terlibat langsung dalam kegiatan konservasi, penelitian, dan kehidupan di lapangan.', 'yiari'));
$story_link_text = yiari_field('join_story_link_text', __('Lihat Semua Cerita Magang', 'yiari'));
$story_link_url = yiari_field('join_story_link_url', yiari_get_posts_page_url('#'));
$story_category = (int) yiari_field('join_story_category', 0);
$story_count = max(3, min(6, (int) yiari_field('join_story_count', 3)));

$story_query_args = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $story_count,
    'ignore_sticky_posts' => true,
    'orderby' => 'date',
    'order' => 'DESC',
];

if ($story_category > 0) {
    $story_query_args['cat'] = $story_category;
}

$story_query = new WP_Query($story_query_args);
$story_posts = $story_query->posts;
wp_reset_postdata();

$steps_label = yiari_field('join_steps_label', __('LANGKAH AWAL ANDA', 'yiari'));
$steps_title = yiari_field('join_steps_title', __('Proses Pendaftaran', 'yiari'));
$steps_desc = yiari_field('join_steps_desc', __('Mulai langkah pertama Anda untuk terlibat langsung dalam upaya konservasi, ikuti prosesnya dan jadilah bagian dari perubahan.', 'yiari'));
$steps_items = yiari_field('join_steps_items', []);

if (empty($steps_items) || !is_array($steps_items)) {
    $steps_items = [
        [
            'title' => __('Formulir', 'yiari'),
            'text' => __('Lengkapi data diri dan minat Anda di awal.', 'yiari'),
        ],
        [
            'title' => __('Review', 'yiari'),
            'text' => __('Tim kami akan meninjau kelengkapan berkas Anda.', 'yiari'),
        ],
        [
            'title' => __('Seleksi', 'yiari'),
            'text' => __('Wawancara daring untuk menyelaraskan ekspektasi.', 'yiari'),
        ],
        [
            'title' => __('Mulai', 'yiari'),
            'text' => __('Anda siap bergabung di pusat rehabilitasi atau program lapangan.', 'yiari'),
        ],
    ];
}

$form_label = yiari_field('join_form_label', __('MULAI DARI SINI', 'yiari'));
$form_title = yiari_field('join_form_title', __('Formulir Pendaftaran', 'yiari'));
$form_desc = yiari_field('join_form_desc', __('Lengkapi formulir ini dan ambil langkah nyata untuk terlibat langsung dalam konservasi.', 'yiari'));
$form_shortcode = trim((string) yiari_field('join_form_shortcode', '[fluentform id="1"]'));
$has_fluent_shortcode = $form_shortcode !== '' && preg_match('/\[fluentform\b/i', $form_shortcode) && shortcode_exists('fluentform');

$fallback_story_cards = [
    [
        'title' => __('Begadang Bersama Kukang: Pengalaman Magang Sharfina dan Marcella di YIARI Cijapus', 'yiari'),
        'excerpt' => __('Kolaborasi mahasiswa di pusat rehabilitasi membuka pengalaman konservasi yang intensif dan dekat dengan kehidupan satwa.', 'yiari'),
        'image' => $theme_uri . '/assets/img/gambar5.jpg',
        'url' => $story_link_url,
    ],
    [
        'title' => __('Pengalaman Magang dan Penelitian di Pusat Rehabilitasi YIARI', 'yiari'),
        'excerpt' => __('Cerita tentang adaptasi, observasi satwa, dan kerja lintas tim selama program berjalan.', 'yiari'),
        'image' => $theme_uri . '/assets/img/gambar4.jpg',
        'url' => $story_link_url,
    ],
    [
        'title' => __('Catatan Lapangan Magang dan Penelitian Bersama YIARI', 'yiari'),
        'excerpt' => __('Peserta melihat langsung dinamika konservasi, edukasi, dan pengumpulan data di lapangan.', 'yiari'),
        'image' => $theme_uri . '/assets/img/gambar6.jpg',
        'url' => $story_link_url,
    ],
];
?>

<main class="join-page">
  <section class="join-hero-wrap">
    <?php
    get_template_part('template-parts/sections/page-hero', null, [
        'id' => 'join-top',
        'classes' => 'join-hero-section',
        'image' => $hero_image,
        'image_class' => 'join-hero-img',
        'image_alt' => __('Bergabung bersama YIARI', 'yiari'),
        'overlay_class' => 'join-hero-overlay',
        'content_class' => 'join-hero-content',
        'copy_class' => 'join-hero-copy',
        'title_class' => 'join-hero-title',
        'text_class' => 'join-hero-text',
        'actions_class' => 'join-hero-actions',
        'title' => $hero_title,
        'text' => $hero_text,
        'allow_title_breaks' => true,
        'buttons' => [
            ['text' => $hero_btn1_text, 'url' => $hero_btn1_url, 'class' => 'btn-action btn-action-primary'],
            ['text' => $hero_btn2_text, 'url' => $hero_btn2_url, 'class' => 'btn-action btn-action-outline'],
        ],
    ]);
    ?>
  </section>

  <section class="join-program-section" id="join-program">
    <div class="container">
      <div class="stats-layout join-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($program_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($program_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($program_desc); ?></p>
        </div>
      </div>

      <div class="join-program-card">
        <div class="join-program-media">
          <?php yiari_img($program_image, 'section-wide', 'join-program-image', $program_title); ?>
        </div>

        <div class="join-program-list">
          <?php foreach ($program_items as $program_index => $program_item): ?>
            <article class="join-program-item">
              <div class="join-program-item-icon" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $program_index + 1)); ?></div>
              <div class="join-program-item-copy">
                <h3 class="join-program-item-title"><?php echo esc_html($program_item['title'] ?? ''); ?></h3>
                <p class="join-program-item-text"><?php echo esc_html($program_item['text'] ?? ''); ?></p>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="join-story-section">
    <div class="container">
      <div class="stats-layout join-section-header join-story-header">
        <div class="stats-intro">
          <?php yiari_section_label($story_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($story_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description join-story-summary"><?php echo esc_html($story_desc); ?></p>
          <?php if (!empty($story_link_text) && !empty($story_link_url)): ?>
            <a href="<?php echo esc_url($story_link_url); ?>" class="link-more join-story-link"><?php echo esc_html($story_link_text); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
          <?php endif; ?>
        </div>
      </div>

      <div class="join-story-grid">
        <?php if (!empty($story_posts)): ?>
          <?php foreach ($story_posts as $story_index => $story_post): ?>
            <?php
            $story_post_id = (int) $story_post->ID;
            $story_permalink = get_permalink($story_post_id) ?: '#';
            $story_image_url = get_the_post_thumbnail_url($story_post_id, $story_index === 0 ? 'section-wide' : 'card-thumb') ?: ($theme_uri . '/assets/img/hero-section.jpg');
            $story_title_text = get_the_title($story_post_id);
            $story_excerpt = has_excerpt($story_post_id)
                ? get_the_excerpt($story_post_id)
                : wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $story_post_id)), $story_index === 0 ? 28 : 14, '…');
            ?>
            <article class="join-story-card <?php echo $story_index === 0 ? 'join-story-card-featured' : 'join-story-card-compact'; ?>">
              <a href="<?php echo esc_url($story_permalink); ?>" class="join-story-card-media" aria-label="<?php echo esc_attr($story_title_text); ?>">
                <img src="<?php echo esc_url($story_image_url); ?>" alt="<?php echo esc_attr($story_title_text); ?>" class="join-story-card-image" />
              </a>
              <div class="join-story-card-body">
                <div class="join-story-card-meta"><?php echo esc_html(get_the_date('j F Y', $story_post_id)); ?></div>
                <h3 class="join-story-card-title">
                  <a href="<?php echo esc_url($story_permalink); ?>"><?php echo esc_html($story_title_text); ?></a>
                </h3>
                <p class="join-story-card-text"><?php echo esc_html($story_excerpt); ?></p>
                <a href="<?php echo esc_url($story_permalink); ?>" class="link-more join-story-card-link"><?php esc_html_e('Baca Selengkapnya', 'yiari'); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <?php foreach ($fallback_story_cards as $story_index => $story_card): ?>
            <article class="join-story-card <?php echo $story_index === 0 ? 'join-story-card-featured' : 'join-story-card-compact'; ?>">
              <a href="<?php echo esc_url($story_card['url']); ?>" class="join-story-card-media" aria-label="<?php echo esc_attr($story_card['title']); ?>">
                <img src="<?php echo esc_url($story_card['image']); ?>" alt="<?php echo esc_attr($story_card['title']); ?>" class="join-story-card-image" />
              </a>
              <div class="join-story-card-body">
                <h3 class="join-story-card-title">
                  <a href="<?php echo esc_url($story_card['url']); ?>"><?php echo esc_html($story_card['title']); ?></a>
                </h3>
                <p class="join-story-card-text"><?php echo esc_html($story_card['excerpt']); ?></p>
                <a href="<?php echo esc_url($story_card['url']); ?>" class="link-more join-story-card-link"><?php esc_html_e('Baca Selengkapnya', 'yiari'); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="join-steps-section">
    <div class="container">
      <div class="stats-layout join-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($steps_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($steps_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($steps_desc); ?></p>
        </div>
      </div>

      <div class="join-steps-grid">
        <?php foreach ($steps_items as $step_index => $step_item): ?>
          <article class="join-step-card">
            <div class="join-step-number"><?php echo esc_html((string) ($step_index + 1)); ?></div>
            <div class="join-step-body">
              <h3 class="join-step-title"><?php echo esc_html($step_item['title'] ?? ''); ?></h3>
              <p class="join-step-text"><?php echo esc_html($step_item['text'] ?? ''); ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="join-form-section" id="join-form">
    <div class="container">
      <div class="stats-layout join-section-header join-form-header">
        <div class="stats-intro">
          <?php yiari_section_label($form_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($form_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($form_desc); ?></p>
        </div>
      </div>

      <div class="join-form-card">
        <?php if ($has_fluent_shortcode): ?>
          <?php echo do_shortcode($form_shortcode); ?>
        <?php else: ?>
          <div class="join-form-unavailable">
            <p><?php esc_html_e('Shortcode Fluent Forms belum diisi atau plugin belum aktif.', 'yiari'); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
