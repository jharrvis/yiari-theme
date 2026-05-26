<?php
$hero_image    = yiari_field('hero_image');
$hero_title    = yiari_field('hero_title', __('Bersama<br>Selamatkan Alam', 'yiari'));
$hero_subtitle = yiari_field('hero_subtitle', __('Bersama kita bisa menjaga satwa liar tetap hidup di alamnya', 'yiari'));
$cta_text      = yiari_field('hero_cta_text', __('Donasi Sekarang', 'yiari'));
$cta_url       = yiari_field('hero_cta_url', yiari_fragment_url('donate'));
?>
<section class="hero-section" id="hero">
  <?php if (!empty($hero_image['url'])): ?>
    <img src="<?php echo esc_url($hero_image['url']); ?>" alt="<?php echo esc_attr($hero_image['alt'] ?: get_bloginfo('name')); ?>" class="hero-img" />
  <?php else: ?>
    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/hero-section.jpg'); ?>" alt="<?php bloginfo('name'); ?>" class="hero-img" />
  <?php endif; ?>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="hero-text-wrapper">
      <h1 class="hero-title"><?php echo wp_kses_post(nl2br($hero_title)); ?></h1>
      <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
      <?php yiari_btn($cta_text, $cta_url, 'btn-hero'); ?>
    </div>
  </div>
</section>
