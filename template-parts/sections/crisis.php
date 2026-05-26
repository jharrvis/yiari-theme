<?php
$label = yiari_field('crisis_label', __('ANCAMAN MENDESAK', 'yiari'));
$title = yiari_field('crisis_title', __('3 Krisis yang Kita Hadapi Bersama', 'yiari'));
$desc = yiari_field('crisis_desc', __('Data lengkap dari lapangan yang menunjukkan capaian nyata dari setiap krisis dan data yang dikumpulkan kami.', 'yiari'));
$crisis_cards = yiari_field('crisis_cards', []);
if (empty($crisis_cards)) {
    $crisis_cards = [
        ['image' => null, 'title' => __('Perdagangan Satwa Liar', 'yiari'), 'text' => __('Ribuan satwa liar Indonesia diperdagangkan secara ilegal, merusak populasi dan keseimbangan ekosistem.', 'yiari')],
        ['image' => null, 'title' => __('Kehilangan Habitat', 'yiari'), 'text' => __('Deforestasi besar-besaran menghilangkan jutaan hektar hutan tropis. Satwa kehilangan tempat tinggal yang alami.', 'yiari')],
        ['image' => null, 'title' => __('Konflik Manusia-Satwa', 'yiari'), 'text' => __('Perluasan wilayah manusia menimbulkan konflik menegangkan. Satwa liar terdesak akibat deforestasi.', 'yiari')],
    ];
}
?>
<section class="section-crisis" id="crisis">
  <div class="container">
    <div class="stats-layout">
      <div class="stats-intro fade-in">
        <?php yiari_section_label($label, true); ?>
        <h2 class="section-heading section-heading-dark"><?php echo wp_kses_post(nl2br(esc_html($title))); ?></h2>
      </div>
      <div class="stats-desc fade-in">
        <p class="text-muted section-description"><?php echo esc_html($desc); ?></p>
      </div>
    </div>
    <div class="crisis-cards fade-in">
      <?php foreach ($crisis_cards as $card): ?>
        <div class="crisis-card">
          <div class="crisis-card-img"><?php yiari_img($card['image'] ?? null, 'card-thumb', '', $card['title'] ?? ''); ?></div>
          <div class="crisis-card-body">
            <h3 class="crisis-card-title"><?php echo esc_html($card['title'] ?? ''); ?></h3>
            <p class="crisis-card-text"><?php echo esc_html($card['text'] ?? ''); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
