<?php
$label = yiari_field('vm_label', __('Arah Kami Bergerak', 'yiari'));
$section_title = yiari_field('vm_section_title', __('Visi &amp; Misi YIARI', 'yiari'));
$image = yiari_field('vm_image');
$visi_label = yiari_field('visi_label', __('Visi', 'yiari'));
$visi_text = yiari_field('visi_text', __('Dunia tempat manusia dan satwa hidup berdampingan dalam ekosistem yang sehat.', 'yiari'));
$misi_label = yiari_field('misi_label', __('Misi', 'yiari'));
$misi_text = yiari_field('misi_text', __('Membangun kesadaran dan kepedulian serta mengimplementasikan sistem yang efektif yang melindungi satwa dan habitatnya.', 'yiari'));
?>
<section class="about-direction-section" id="visi-misi">
  <div class="container">
    <div class="about-direction-header"><?php yiari_section_label($label, true); ?></div>
    <div class="about-direction-grid">
      <div class="about-direction-intro">
        <h2 class="section-heading-lg about-direction-title"><?php echo wp_kses_post($section_title); ?></h2>
        <div class="about-direction-image"><?php yiari_img($image, 'portrait', '', __('Orangutan dalam perawatan tim YIARI', 'yiari')); ?></div>
      </div>
      <div class="about-direction-content">
        <div class="about-direction-block"><h3 class="about-direction-label"><?php echo esc_html($visi_label); ?></h3><p class="about-direction-text"><?php echo esc_html($visi_text); ?></p></div>
        <div class="about-direction-divider"></div>
        <div class="about-direction-block"><h3 class="about-direction-label"><?php echo esc_html($misi_label); ?></h3><p class="about-direction-text"><?php echo esc_html($misi_text); ?></p></div>
      </div>
    </div>
  </div>
</section>
