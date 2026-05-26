<?php
$title = yiari_field('donate_title', __('Setiap Donasi Menyelamatkan Nyawa', 'yiari'));
$text = yiari_field('donate_text', __('Dari perawatan darurat hingga pelepasliaran. Dukungan Anda membuat perbedaan nyata bagi masa depan satwa Indonesia.', 'yiari'));
$btn1_text = yiari_field('donate_btn1_text', __('Donasi Sekarang', 'yiari'));
$btn1_url = yiari_field('donate_btn1_url', '#');
$btn2_text = yiari_field('donate_btn2_text', __('Jadi Relawan', 'yiari'));
$btn2_url = yiari_field('donate_btn2_url', yiari_get_join_url());
$image = yiari_field('donate_image');
?>
<section class="section-donate" id="donate">
  <div class="donate-layout">
    <div class="donate-content fade-in">
      <h2 class="donate-heading"><?php echo wp_kses_post(nl2br(esc_html($title))); ?></h2>
      <p class="donate-text"><?php echo esc_html($text); ?></p>
      <div class="donate-actions">
        <?php yiari_btn($btn1_text, $btn1_url, 'btn-donate-main'); ?>
        <?php yiari_btn($btn2_text, $btn2_url, 'btn-volunteer-main'); ?>
      </div>
    </div>
    <div class="donate-image fade-in">
      <?php if (!empty($image['url'])): ?>
        <?php yiari_img($image, 'section-wide', '', __('Donasi untuk satwa', 'yiari')); ?>
      <?php else: ?>
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/gambar-cta.png'); ?>" alt="<?php echo esc_attr__('Orangutan', 'yiari'); ?>" />
      <?php endif; ?>
    </div>
  </div>
</section>
