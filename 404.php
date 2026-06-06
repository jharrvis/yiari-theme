<?php
defined('ABSPATH') || exit;

get_template_part('template-parts/global/header');

$is_english = yiari_post_is_english();

$title = $is_english
    ? __('We Could Not Find This Page', 'yiari')
    : __('Kami Tidak Menemukan Halaman Ini', 'yiari');
$text = $is_english
    ? __('This page may have been moved, deleted, or is not available yet. But the journey to protect Indonesia’s wildlife continues.', 'yiari')
    : __('Halaman ini mungkin telah dipindahkan, dihapus, atau belum tersedia. Namun perjalanan untuk melindungi satwa liar Indonesia terus berlanjut.', 'yiari');
$button_text = $is_english ? __('Back to Home', 'yiari') : __('Ke Beranda', 'yiari');
?>

<main class="error404-page">
  <section class="error404-content-section">
    <div class="container">
      <div class="error404-content">
        <h1 class="error404-title"><?php echo esc_html($title); ?></h1>
        <p class="error404-text"><?php echo esc_html($text); ?></p>
        <div class="error404-actions">
          <?php yiari_btn($button_text, yiari_home_url(), 'btn-action btn-action-primary error404-btn'); ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php get_template_part('template-parts/global/footer'); ?>
