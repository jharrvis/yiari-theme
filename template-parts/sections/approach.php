<?php
$label = yiari_field('approach_label', __('Program', 'yiari'));
$title = yiari_field('approach_title', __("Pendekatan\nKonservasi yang\nTerintegrasi", 'yiari'));
$desc = yiari_field('approach_desc', __('Dari penyelamatan satwa terlantar hingga pemberdayaan masyarakat, setiap program dirancang untuk menciptakan dampak jangka panjang.', 'yiari'));
$approach_items = yiari_field('approach_items', []);
if (empty($approach_items)) {
    $approach_items = [
        ['title' => __('Konservasi Satwa', 'yiari'), 'subtitle' => __('Penyelamatan dan rehabilitasi', 'yiari'), 'details' => __('Menyelamatkan, merawat, dan merehabilitasi satwa liar yang terluka, diperdagangkan, atau kehilangan habitat agar dapat kembali hidup dengan aman.', 'yiari'), 'button_text' => __('Lihat Program', 'yiari'), 'button_url' => yiari_get_program_url(), 'image' => null],
        ['title' => __('Konservasi Habitat', 'yiari'), 'subtitle' => __('Restorasi ekosistem', 'yiari'), 'details' => __('Melindungi dan memulihkan bentang alam penting agar satwa liar memiliki habitat yang aman dan berkelanjutan.', 'yiari'), 'button_text' => __('Lihat Program', 'yiari'), 'button_url' => yiari_get_program_url(), 'image' => null],
        ['title' => __('Edukasi', 'yiari'), 'subtitle' => __('Pendidikan masyarakat', 'yiari'), 'details' => __('Menghadirkan edukasi konservasi yang relevan bagi anak muda, sekolah, dan masyarakat sekitar kawasan penting satwa liar.', 'yiari'), 'button_text' => __('Lihat Program', 'yiari'), 'button_url' => yiari_get_program_url(), 'image' => null],
        ['title' => __('Pemberdayaan', 'yiari'), 'subtitle' => __('Ekonomi berkelanjutan', 'yiari'), 'details' => __('Mendorong mata pencaharian yang selaras dengan konservasi agar masyarakat mendapat manfaat tanpa merusak alam.', 'yiari'), 'button_text' => __('Lihat Program', 'yiari'), 'button_url' => yiari_get_program_url(), 'image' => null],
        ['title' => __('One Health', 'yiari'), 'subtitle' => __('Kesehatan holistik', 'yiari'), 'details' => __('Menghubungkan kesehatan manusia, satwa, dan lingkungan sebagai satu ekosistem yang saling memengaruhi.', 'yiari'), 'button_text' => __('Lihat Program', 'yiari'), 'button_url' => yiari_get_program_url(), 'image' => null],
        ['title' => __('Perlindungan', 'yiari'), 'subtitle' => __('Penjagaan Satwa', 'yiari'), 'details' => __('Memperkuat upaya perlindungan satwa dari perdagangan ilegal, konflik, dan ancaman langsung di lapangan.', 'yiari'), 'button_text' => __('Lihat Program', 'yiari'), 'button_url' => yiari_get_program_url(), 'image' => null],
    ];
}

$approach_icons = ['paw-print', 'leaf', 'book-open', 'hand-coins', 'heart-pulse', 'shield-check'];
?>
<section
  class="section-approach"
  id="approach"
  x-data="{
    activeApproach: 0,
    setApproach(index) {
      this.activeApproach = index;
    }
  }"
>
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
          <?php foreach ($approach_items as $i => $item): ?>
            <?php
            $img_url = !empty($item['image']['url']) ? $item['image']['url'] : get_template_directory_uri() . '/assets/img/konservasi.png';
            $item_title = $item['title'] ?? '';
            $item_subtitle = $item['subtitle'] ?? '';
            $item_details = $item['details'] ?? $item_subtitle;
            $item_button_text = $item['button_text'] ?? __('Lihat Program', 'yiari');
            $item_button_url = $item['button_url'] ?? yiari_get_program_url();
            $icon_name = $approach_icons[$i] ?? 'sparkles';
            ?>
            <div
              class="approach-item"
              :class="{ 'is-active': activeApproach === <?php echo (int) $i; ?> }"
              @mouseenter="if (window.innerWidth > 1024) setApproach(<?php echo (int) $i; ?>)"
            >
              <button
                type="button"
                class="approach-trigger"
                @click="setApproach(<?php echo (int) $i; ?>)"
                @focus="setApproach(<?php echo (int) $i; ?>)"
                :aria-expanded="activeApproach === <?php echo (int) $i; ?> ? 'true' : 'false'"
              >
                <span class="approach-icon-wrap"><i data-lucide="<?php echo esc_attr($icon_name); ?>" class="approach-icon"></i></span>
                <div class="approach-text">
                  <div class="approach-title"><?php echo esc_html($item_title); ?></div>
                  <div class="approach-subtitle"><?php echo esc_html($item_subtitle); ?></div>
                </div>
                <span class="approach-trigger-meta"><?php echo esc_html($item_subtitle); ?></span>
              </button>

              <div class="approach-accordion-panel" x-show="activeApproach === <?php echo (int) $i; ?>" x-cloak>
                <div class="approach-mobile-card">
                  <div class="approach-mobile-image-wrap">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($item_title); ?>" class="approach-main-img" loading="lazy" />
                  </div>
                  <div class="approach-mobile-footer">
                    <p class="approach-detail-text"><?php echo esc_html($item_details); ?></p>
                    <a class="btn-primary approach-detail-btn" href="<?php echo esc_url($item_button_url); ?>"><?php echo esc_html($item_button_text); ?></a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="approach-images fade-in approach-images-frame">
        <?php foreach ($approach_items as $i => $item): ?>
          <?php
          $img_url = !empty($item['image']['url']) ? $item['image']['url'] : get_template_directory_uri() . '/assets/img/konservasi.png';
          $item_title = $item['title'] ?? '';
          $item_details = $item['details'] ?? ($item['subtitle'] ?? '');
          $item_button_text = $item['button_text'] ?? __('Lihat Program', 'yiari');
          $item_button_url = $item['button_url'] ?? yiari_get_program_url();
          ?>
          <div
            class="approach-detail-card"
            x-show="activeApproach === <?php echo (int) $i; ?>"
            x-transition:enter="story-fade-enter"
            x-transition:enter-start="story-fade-enter-start"
            x-transition:enter-end="story-fade-enter-end"
            x-transition:leave="story-fade-leave"
            x-transition:leave-start="story-fade-leave-start"
            x-transition:leave-end="story-fade-leave-end"
            x-cloak
          >
            <div class="approach-detail-image-wrap">
              <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($item_title); ?>" class="approach-main-img" loading="lazy" />
            </div>
            <div class="approach-detail-footer">
              <p class="approach-detail-text"><?php echo esc_html($item_details); ?></p>
              <a class="btn-primary approach-detail-btn" href="<?php echo esc_url($item_button_url); ?>"><?php echo esc_html($item_button_text); ?></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
