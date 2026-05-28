<?php
$label = yiari_field('stats_label', __('DAMPAK TERUKUR', 'yiari'));
$title = yiari_field('stats_title', __('Bukti Nyata Konservasi Kami', 'yiari'));
$desc = yiari_field('stats_desc', __('Data langsung dari lapangan yang menunjukkan dampak nyata dari setiap donasi dan dukungan Anda.', 'yiari'));
$stats_items = yiari_field('stats_items', []);
if (empty($stats_items)) {
    $stats_items = [
        ['number' => 2904, 'suffix' => '+', 'label' => __("Satwa\nDiselamatkan", 'yiari')],
        ['number' => 220, 'suffix' => '+', 'label' => __("Lahan berhasil\ndirestorasi", 'yiari')],
        ['number' => 409, 'suffix' => '+', 'label' => __("Anak menerima\npelatihan", 'yiari')],
        ['number' => 35, 'suffix' => '', 'label' => __("Kelompok telah\ndidampingi", 'yiari')],
        ['number' => 225, 'suffix' => '', 'label' => __("Penyuluhan telah\ndilakukan", 'yiari')],
    ];
}

$stats_icons = [
    'icon-satwa.svg',
    'icon-restorasi.svg',
    'icon-pelatihan.svg',
    'icon-kelompok.svg',
    'Icon-penyuluhan.svg',
];
?>
<section class="section-stats" id="stats">
  <div class="container">
    <div class="stats-layout">
      <div class="stats-intro fade-in">
        <?php yiari_section_label($label, true); ?>
        <h2 class="section-heading section-heading-dark"><?php echo wp_kses_post(nl2br(esc_html($title))); ?></h2>
      </div>
      <div class="stats-desc fade-in">
        <p class="text-muted section-description"><?php echo esc_html($desc); ?></p>
      </div>
      <div class="stats-nav-tablet" x-show="statsCanScroll && !statsIsMobile" x-cloak>
        <button
          class="stats-arrow"
          aria-label="<?php echo esc_attr__('Previous', 'yiari'); ?>"
          type="button"
          @click="scrollStats('prev')"
          :disabled="!statsCanScroll || statsActivePage === 0"
        >
          <i data-lucide="chevron-left" class="icon-md"></i>
        </button>
        <button
          class="stats-arrow"
          aria-label="<?php echo esc_attr__('Next', 'yiari'); ?>"
          type="button"
          @click="scrollStats('next')"
          :disabled="!statsCanScroll || statsActivePage >= statsPageCount - 1"
        >
          <i data-lucide="chevron-right" class="icon-md"></i>
        </button>
      </div>
    </div>
    <div class="stats-carousel fade-in">
      <div class="stats-grid" x-ref="statsGrid" @scroll.passive="handleStatsScroll()">
        <?php foreach ($stats_items as $index => $stat): ?>
          <?php $icon = $stats_icons[$index % count($stats_icons)]; ?>
          <article class="stat-card">
            <div class="stat-card-top">
              <div class="stat-number" data-target="<?php echo esc_attr($stat['number']); ?>" <?php if (!empty($stat['suffix'])): ?>data-suffix="<?php echo esc_attr($stat['suffix']); ?>"<?php endif; ?>>
                <?php echo esc_html($stat['number']); ?>
                <?php if (!empty($stat['suffix'])): ?><span class="stat-plus"><?php echo esc_html($stat['suffix']); ?></span><?php endif; ?>
              </div>
              <div class="stat-icon-wrap" aria-hidden="true">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/icons/' . $icon); ?>" alt="" class="stat-icon" loading="lazy" />
              </div>
            </div>
            <div class="stat-card-bottom">
              <div class="stat-label"><?php echo wp_kses_post(nl2br(esc_html($stat['label']))); ?></div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <div class="stats-indicators" x-show="statsCanScroll && statsIsMobile" x-cloak>
        <template x-for="index in statsPageCount" :key="index">
          <button
            class="stats-indicator-btn"
            :aria-label="`<?php echo esc_js(__('Go to slide', 'yiari')); ?> ${index}`"
            type="button"
            @click="scrollStatsToPage(index - 1)"
          >
            <span
              class="stats-indicator"
              :class="{ 'is-active': statsActivePage === (index - 1) }"
            ></span>
          </button>
        </template>
      </div>
    </div>
  </div>
</section>
