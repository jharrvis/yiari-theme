<?php
$label = yiari_field('donate_label', __('DUKUNGAN ANDA', 'yiari'));
$title = yiari_field('donate_title', __('Setiap Donasi Menyelamatkan Nyawa', 'yiari'));
$text = yiari_field('donate_text', __('Dari perawatan darurat hingga pelepasliaran. Dukungan Anda membuat perbedaan nyata bagi masa depan satwa Indonesia.', 'yiari'));
$btn1_text = yiari_field('donate_btn1_text', __('Donasi Sekarang', 'yiari'));
$btn1_url = yiari_field('donate_btn1_url', '#');
$btn2_text = yiari_field('donate_btn2_text', __('Jadi Relawan', 'yiari'));
$btn2_url = yiari_field('donate_btn2_url', yiari_get_join_url());
$image = yiari_field('donate_image');
?>
<section class="section-donate" id="donate">
  <div class="container">
    <div class="donate-card fade-in">
      <div class="donate-media">
        <?php if (!empty($image['url'])): ?>
          <?php yiari_img($image, 'section-wide', 'donate-media-img', __('Donasi untuk satwa', 'yiari')); ?>
        <?php else: ?>
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/gambar-cta.png'); ?>" alt="<?php echo esc_attr__('Orangutan', 'yiari'); ?>" class="donate-media-img" />
        <?php endif; ?>
      </div>

      <div class="donate-content">
        <div class="donate-pill">
          <i data-lucide="heart-handshake" class="donate-pill-icon"></i>
          <span><?php echo esc_html($label); ?></span>
        </div>

        <h2 class="donate-heading"><?php echo wp_kses_post(nl2br(esc_html($title))); ?></h2>
        <p class="donate-text"><?php echo esc_html($text); ?></p>

        <div class="donate-actions">
          <?php yiari_btn($btn1_text, $btn1_url, 'btn-donate-main'); ?>
          <?php yiari_btn($btn2_text, $btn2_url, 'btn-volunteer-main'); ?>
        </div>
      </div>
    </div>
  </div>
</section>
