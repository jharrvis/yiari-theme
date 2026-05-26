<?php
$label = yiari_field('story_label', __('DARI LAPANGAN', 'yiari'));
$title = yiari_field('story_title', __('Barda: Dari Nyaris Mati ke Duta Konservasi', 'yiari'));
$para1 = yiari_field('story_para1', __('Ditemukan dengan luka tembak di kepala, kehilangan penglihatan, dan trauma mental mendalam. Tim YIARI memberikan perawatan medis intensif dan rehabilitasi jangka panjang.', 'yiari'));
$para2 = yiari_field('story_para2', __('Setelah berbulan-bulan perawatan, Barda kini hidup aman di pusat rehabilitasi kami. Ia menjadi simbol harapan yang menginspirasi ribuan orang untuk melindungi satwa Indonesia.', 'yiari'));
$btn1_text = yiari_field('story_btn1_text', __('Baca Selengkapnya', 'yiari'));
$btn1_url = yiari_field('story_btn1_url', yiari_get_posts_page_url(home_url('/cerita/')));
$btn2_text = yiari_field('story_btn2_text', __('Donasi Sekarang', 'yiari'));
$btn2_url = yiari_field('story_btn2_url', yiari_fragment_url('donate'));
$image = yiari_field('story_image');
?>
<section class="section-story" id="story">
  <div class="container">
    <div class="story-layout">
      <div class="story-content fade-in">
        <?php yiari_section_label($label, true); ?>
        <h2 class="section-heading-lg story-heading"><?php echo wp_kses_post(nl2br(esc_html($title))); ?></h2>
        <?php if ($para1): ?><p class="text-muted story-paragraph-spaced"><?php echo esc_html($para1); ?></p><?php endif; ?>
        <?php if ($para2): ?><p class="text-muted story-paragraph-final"><?php echo esc_html($para2); ?></p><?php endif; ?>
        <div class="story-actions">
          <?php yiari_btn($btn1_text, $btn1_url, 'btn-primary'); ?>
          <?php yiari_btn($btn2_text, $btn2_url, 'btn-outline-warning'); ?>
        </div>
      </div>
      <div class="story-image fade-in"><?php yiari_img($image, 'portrait', 'story-img-barda', get_the_title()); ?></div>
    </div>
  </div>
</section>
