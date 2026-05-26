<?php
$label = yiari_field('approach_label', __('Program', 'yiari'));
$title = yiari_field('approach_title', __("Pendekatan\nKonservasi yang\nTerintegrasi", 'yiari'));
$desc = yiari_field('approach_desc', __('Dari penyelamatan satwa terlantar hingga pemberdayaan masyarakat, setiap program dirancang untuk menciptakan dampak jangka panjang.', 'yiari'));
$approach_items = yiari_field('approach_items', []);
if (empty($approach_items)) {
    $approach_items = [
        ['title' => __('Konservasi Satwa', 'yiari'), 'subtitle' => __('Penyelamatan dan rehabilitasi', 'yiari'), 'image' => null],
        ['title' => __('Konservasi Habitat', 'yiari'), 'subtitle' => __('Restorasi ekosistem', 'yiari'), 'image' => null],
        ['title' => __('Edukasi', 'yiari'), 'subtitle' => __('Pendidikan masyarakat', 'yiari'), 'image' => null],
        ['title' => __('Pemberdayaan', 'yiari'), 'subtitle' => __('Ekonomi berkelanjutan', 'yiari'), 'image' => null],
        ['title' => __('One Health', 'yiari'), 'subtitle' => __('Kesehatan holistik', 'yiari'), 'image' => null],
        ['title' => __('Perlindungan', 'yiari'), 'subtitle' => __('Penjagaan Satwa', 'yiari'), 'image' => null],
    ];
}
$first_image = !empty($approach_items[0]['image']['url']) ? $approach_items[0]['image']['url'] : get_template_directory_uri() . '/assets/img/konservasi.png';
?>
<section class="section-approach" id="approach">
  <div class="container">
    <div class="stats-layout">
      <div class="stats-intro fade-in">
        <?php yiari_section_label($label); ?>
        <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($title))); ?></h2>
      </div>
      <div class="stats-desc fade-in"><p class="text-muted section-description"><?php echo esc_html($desc); ?></p></div>
    </div>
    <div class="approach-layout">
      <div class="approach-list fade-in">
        <div class="approach-items">
          <?php foreach ($approach_items as $i => $item): $img_url = !empty($item['image']['url']) ? $item['image']['url'] : get_template_directory_uri() . '/assets/img/konservasi.png'; $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?>
            <div class="approach-item <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo esc_attr($i); ?>" data-image="<?php echo esc_url($img_url); ?>" @mouseenter="activateApproach($el)" @focus="activateApproach($el)" tabindex="0">
              <div class="approach-text">
                <div class="approach-title"><?php echo esc_html($item['title'] ?? ''); ?></div>
                <div class="approach-subtitle"><?php echo esc_html($item['subtitle'] ?? ''); ?></div>
              </div>
              <div class="approach-num"><?php echo esc_html($num); ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="approach-images fade-in approach-images-frame">
        <img src="<?php echo esc_url($first_image); ?>" alt="<?php echo esc_attr($approach_items[0]['title'] ?? __('Konservasi', 'yiari')); ?>" id="approach-main-img" x-ref="approachImg" class="approach-main-img" />
      </div>
    </div>
  </div>
</section>
