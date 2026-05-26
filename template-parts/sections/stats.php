<?php
$label = yiari_field('stats_label', __('DAMPAK TERUKUR', 'yiari'));
$title = yiari_field('stats_title', __('Bukti Nyata Konservasi Kami', 'yiari'));
$desc = yiari_field('stats_desc', __('Data langsung dari lapangan yang menunjukkan dampak nyata dari setiap donasi dan dukungan Anda.', 'yiari'));
$stats_items = yiari_field('stats_items', []);
if (empty($stats_items)) {
    $stats_items = [
        ['number' => 2904, 'suffix' => '+', 'label' => __('Satwa telah diselamatkan', 'yiari')],
        ['number' => 220, 'suffix' => '+', 'label' => __('Hektar lahan telah direstorasi', 'yiari')],
        ['number' => 409, 'suffix' => '+', 'label' => __('Anak telah menerima pelatihan', 'yiari')],
        ['number' => 35, 'suffix' => '', 'label' => __('Kelompok binaan telah didampingi', 'yiari')],
        ['number' => 225, 'suffix' => '', 'label' => __('Penyuluhan lingkungan telah dilaksanakan', 'yiari')],
    ];
}
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
        <div class="stats-nav-mobile">
          <button class="stats-arrow" aria-label="<?php echo esc_attr__('Previous', 'yiari'); ?>" type="button" @click="scrollStats('prev')">
            <i data-lucide="chevron-left" class="icon-md"></i>
          </button>
          <button class="stats-arrow" aria-label="<?php echo esc_attr__('Next', 'yiari'); ?>" type="button" @click="scrollStats('next')">
            <i data-lucide="chevron-right" class="icon-md"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="stats-grid fade-in" x-ref="statsGrid">
      <?php foreach ($stats_items as $stat): ?>
        <div class="stat-card">
          <div class="stat-number" data-target="<?php echo esc_attr($stat['number']); ?>" <?php if (!empty($stat['suffix'])): ?>data-suffix="<?php echo esc_attr($stat['suffix']); ?>"<?php endif; ?>>
            <?php echo esc_html($stat['number']); ?>
            <?php if (!empty($stat['suffix'])): ?><span class="stat-plus"><?php echo esc_html($stat['suffix']); ?></span><?php endif; ?>
          </div>
          <div class="stat-label"><?php echo wp_kses_post(nl2br(esc_html($stat['label']))); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
