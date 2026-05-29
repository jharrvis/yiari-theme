<?php
$label = yiari_field('crisis_label', __('ANCAMAN MENDESAK', 'yiari'));
$title = yiari_field('crisis_title', __('3 Krisis yang Kita Hadapi Bersama', 'yiari'));
$desc = yiari_field('crisis_desc', __('Di balik hutan yang terus hilang dan satwa yang terancam, ada krisis nyata yang sedang terjadi di sekitar kita setiap hari.', 'yiari'));
$crisis_cards = yiari_field('crisis_cards', []);

if (empty($crisis_cards)) {
    $crisis_cards = [
        ['image' => null, 'title' => __('Satwa Dijual, Alam Terancam', 'yiari'), 'text' => __('Ribuan satwa liar ditangkap dan diperjualbelikan secara ilegal setiap tahun. Banyak yang mati sebelum kembali ke habitatnya.', 'yiari')],
        ['image' => null, 'title' => __('Hutan Hilang, Satwa Kehilangan Rumah', 'yiari'), 'text' => __('Pembukaan hutan dan ekspansi lahan membuat satwa kehilangan rumah dan sumber hidupnya sedikit demi sedikit.', 'yiari')],
        ['image' => null, 'title' => __('Saat Satwa dan Manusia Bertemu Konflik', 'yiari'), 'text' => __('Ketika habitat mengecil, perjumpaan satwa dengan manusia makin sering terjadi dan memicu konflik di lapangan.', 'yiari')],
    ];
}

$fallback_images = [
    get_template_directory_uri() . '/assets/img/gambar4.jpg',
    get_template_directory_uri() . '/assets/img/Hutan-Mangrove1.jpg',
    get_template_directory_uri() . '/assets/img/barda.png',
];
?>
<section class="section-crisis" id="crisis" x-data="{ activeCrisis: 0 }">
  <div class="container">
    <div class="stats-layout">
      <div class="stats-intro fade-in">
        <?php yiari_section_label($label, true); ?>
        <h2 class="section-heading section-heading-dark"><?php echo wp_kses_post(nl2br(esc_html($title))); ?></h2>
      </div>
      <div class="stats-desc fade-in">
        <p class="section-description"><?php echo esc_html($desc); ?></p>
      </div>
    </div>

    <div class="crisis-accordion fade-in">
      <div class="crisis-visual">
        <?php foreach ($crisis_cards as $index => $card): ?>
          <?php
          $image = $card['image'] ?? null;
          $image_url = !empty($image['url']) ? $image['url'] : ($fallback_images[$index] ?? $fallback_images[0]);
          $image_alt = !empty($image['alt']) ? $image['alt'] : ($card['title'] ?? '');
          ?>
          <div
            class="crisis-visual-item"
            x-show="activeCrisis === <?php echo (int) $index; ?>"
            x-transition:enter="crisis-fade-enter"
            x-transition:enter-start="crisis-fade-enter-start"
            x-transition:enter-end="crisis-fade-enter-end"
            x-transition:leave="crisis-fade-leave"
            x-transition:leave-start="crisis-fade-leave-start"
            x-transition:leave-end="crisis-fade-leave-end"
            x-cloak
          >
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="crisis-visual-img" loading="lazy" />
          </div>
        <?php endforeach; ?>
      </div>

      <div class="crisis-panels">
        <?php foreach ($crisis_cards as $index => $card): ?>
          <?php
          $image = $card['image'] ?? null;
          $image_url = !empty($image['url']) ? $image['url'] : ($fallback_images[$index] ?? $fallback_images[0]);
          $image_alt = !empty($image['alt']) ? $image['alt'] : ($card['title'] ?? '');
          ?>
          <article class="crisis-panel" :class="{ 'is-active': activeCrisis === <?php echo (int) $index; ?> }">
            <button
              class="crisis-panel-trigger"
              type="button"
              @click="activeCrisis = <?php echo (int) $index; ?>"
              :aria-expanded="activeCrisis === <?php echo (int) $index; ?> ? 'true' : 'false'"
            >
              <span class="crisis-panel-index"><?php echo (int) ($index + 1); ?></span>
              <span class="crisis-panel-line"></span>
            </button>

            <div class="crisis-panel-body">
              <h3 class="crisis-panel-title" @click="activeCrisis = <?php echo (int) $index; ?>"><?php echo esc_html($card['title'] ?? ''); ?></h3>

              <div
                x-show="activeCrisis === <?php echo (int) $index; ?>"
                x-transition:enter="crisis-content-enter"
                x-transition:enter-start="crisis-content-enter-start"
                x-transition:enter-end="crisis-content-enter-end"
                x-transition:leave="crisis-content-leave"
                x-transition:leave-start="crisis-content-leave-start"
                x-transition:leave-end="crisis-content-leave-end"
                x-cloak
              >
                <p class="crisis-panel-text"><?php echo esc_html($card['text'] ?? ''); ?></p>
                <div class="crisis-panel-mobile-image">
                  <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="crisis-visual-img" loading="lazy" />
                </div>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
