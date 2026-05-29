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
$timeline_icons = ['building-2', 'heart-handshake', 'leaf', 'graduation-cap'];
?>
<section class="about-timeline-section" id="timeline">
  <div class="container">
    <div class="stats-layout about-timeline-header">
      <div class="stats-intro"><?php yiari_section_label($label, true); ?><h2 class="section-heading"><?php echo esc_html($title); ?></h2></div>
      <div class="stats-desc"><p class="section-description"><?php echo esc_html($desc); ?></p></div>
      <div class="stats-nav-tablet about-timeline-nav" x-show="timelineCanScroll && !timelineIsMobile" x-cloak>
        <button
          class="stats-arrow"
          aria-label="<?php echo esc_attr__('Previous', 'yiari'); ?>"
          type="button"
          @click="scrollTimeline('prev')"
          :disabled="!timelineCanScroll"
        >
          <i data-lucide="chevron-left" class="icon-md"></i>
        </button>
        <button
          class="stats-arrow"
          aria-label="<?php echo esc_attr__('Next', 'yiari'); ?>"
          type="button"
          @click="scrollTimeline('next')"
          :disabled="!timelineCanScroll"
        >
          <i data-lucide="chevron-right" class="icon-md"></i>
        </button>
      </div>
    </div>
    <div class="about-timeline-carousel">
      <div class="about-timeline-grid" x-ref="timelineGrid" @scroll.passive="handleTimelineScroll()">
      <?php foreach ($timeline_items as $index => $item): ?>
        <?php $icon = $timeline_icons[$index % count($timeline_icons)]; ?>
        <article class="about-timeline-item">
          <div class="about-timeline-card-top">
            <div class="about-timeline-year"><?php echo esc_html($item['year'] ?? ''); ?></div>
            <span class="about-timeline-icon-wrap" aria-hidden="true">
              <i data-lucide="<?php echo esc_attr($icon); ?>" class="about-timeline-icon"></i>
            </span>
            <h3 class="about-timeline-title"><?php echo esc_html($item['title'] ?? ''); ?></h3>
          </div>
          <div class="about-timeline-card-bottom">
            <p class="about-timeline-text"><?php echo esc_html($item['text'] ?? ''); ?></p>
          </div>
        </article>
      <?php endforeach; ?>
      </div>
      <div class="stats-indicators about-timeline-indicators" x-show="timelineCanScroll && timelineIsMobile" x-cloak>
        <template x-for="index in timelinePageCount" :key="index">
          <button
            class="stats-indicator-btn"
            :aria-label="`<?php echo esc_js(__('Go to timeline slide', 'yiari')); ?> ${index}`"
            type="button"
            @click="scrollTimelineToPage(index - 1)"
          >
            <span
              class="stats-indicator"
              :class="{ 'is-active': timelineActivePage === (index - 1) }"
            ></span>
          </button>
        </template>
      </div>
    </div>
  </div>
</section>
