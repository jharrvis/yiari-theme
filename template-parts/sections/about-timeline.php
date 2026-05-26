<?php
$label = yiari_field('timeline_label', __('Dampak Nyata', 'yiari'));
$title = yiari_field('timeline_title', __('Jejak Dampak YIARI', 'yiari'));
$desc = yiari_field('timeline_desc', __('Setiap aksi yang kami lakukan menghasilkan perubahan nyata: satwa diselamatkan, dipulihkan, dan kembali ke alamnya.', 'yiari'));
$timeline_items = yiari_field('timeline_items', []);
if (empty($timeline_items)) {
    $timeline_items = [
        ['year' => '2007', 'title' => __('Awal Fasilitas', 'yiari'), 'text' => __('Pusat rehabilitasi primata di Ciapus, Bogor didirikan khusus untuk kukang dan makaka, sekaligus menjadi pusat rehabilitasi kukang pertama di Indonesia.', 'yiari')],
        ['year' => '2008', 'title' => __('Status Legal', 'yiari'), 'text' => __('YIARI resmi mendapat status legal dan mulai menolong lebih banyak satwa liar Indonesia.', 'yiari')],
        ['year' => '2012', 'title' => __('Ekspansi Rehabilitasi', 'yiari'), 'text' => __('Pusat rehabilitasi orangutan di Ketapang berdiri lengkap dengan fasilitas medis dan kawasan hutan seluas ±200 hektar.', 'yiari')],
        ['year' => '2019', 'title' => __('Pusat Pembelajaran', 'yiari'), 'text' => __('Pusat pembelajaran di Kalimantan diresmikan dengan fasilitas lengkap untuk pelatihan, lokakarya, dan edukasi.', 'yiari')],
    ];
}
?>
<section class="about-timeline-section" id="timeline">
  <div class="container">
    <div class="stats-layout about-timeline-header">
      <div class="stats-intro"><?php yiari_section_label($label, true); ?><h2 class="section-heading-lg"><?php echo esc_html($title); ?></h2></div>
      <div class="stats-desc"><p class="section-description"><?php echo esc_html($desc); ?></p></div>
    </div>
    <div class="about-timeline-grid">
      <?php foreach ($timeline_items as $item): ?>
        <article class="about-timeline-item">
          <div class="about-timeline-year"><?php echo esc_html($item['year'] ?? ''); ?></div>
          <h3 class="about-timeline-title"><?php echo esc_html($item['title'] ?? ''); ?></h3>
          <p class="about-timeline-text"><?php echo esc_html($item['text'] ?? ''); ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
