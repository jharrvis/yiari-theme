<?php
$image = yiari_field('about_hero_image');
$title = yiari_field('about_hero_title', __("Untuk Mereka yang\nTak Bisa Membela Diri", 'yiari'));
$text = yiari_field('about_hero_text', __('YIARI berdiri untuk memberikan perlindungan, rehabilitasi, dan kesempatan kedua bagi satwa liar Indonesia yang terancam. Dari penyelamatan darurat hingga pelepasliaran kembali ke habitat alami.', 'yiari'));
$btn1_text = yiari_field('about_btn1_text', __('Lihat Program Kami', 'yiari'));
$btn1_url = yiari_field('about_btn1_url', yiari_get_program_url());
$btn2_text = yiari_field('about_btn2_text', __('Pelajari Lebih Lanjut', 'yiari'));
$btn2_url = yiari_field('about_btn2_url', '#visi-misi');
?>
<section class="about-hero-section" id="about-hero">
  <?php if (!empty($image['url'])): ?>
    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?: 'YIARI'); ?>" class="about-hero-img" />
  <?php else: ?>
    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/hero-about-us.jpg'); ?>" alt="<?php echo esc_attr__('About YIARI', 'yiari'); ?>" class="about-hero-img" />
  <?php endif; ?>
  <div class="about-hero-overlay"></div>
  <div class="about-hero-content">
    <div class="about-hero-copy">
      <h1 class="about-hero-title"><?php echo wp_kses_post(nl2br(esc_html($title))); ?></h1>
      <p class="about-hero-text"><?php echo esc_html($text); ?></p>
      <div class="about-hero-actions">
        <?php yiari_btn($btn1_text, $btn1_url, 'about-hero-btn about-hero-btn-primary'); ?>
        <?php yiari_btn($btn2_text, $btn2_url, 'about-hero-btn about-hero-btn-outline'); ?>
      </div>
    </div>
  </div>
</section>
