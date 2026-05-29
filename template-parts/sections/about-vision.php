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
    <div class="about-direction-card">
      <div class="about-direction-head">
        <div class="about-direction-intro">
          <?php yiari_section_label($label, true); ?>
          <h2 class="section-heading about-direction-title"><?php echo wp_kses_post($section_title); ?></h2>
        </div>
        <div class="about-direction-content">
          <div class="about-direction-block">
            <div class="about-direction-block-head">
              <span class="about-direction-icon-wrap">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/icons/icon-visi.svg'); ?>" alt="<?php echo esc_attr($visi_label); ?>" class="about-direction-icon" />
              </span>
              <h3 class="about-direction-label"><?php echo esc_html($visi_label); ?></h3>
            </div>
            <p class="about-direction-text"><?php echo esc_html($visi_text); ?></p>
          </div>
          <div class="about-direction-block">
            <div class="about-direction-block-head">
              <span class="about-direction-icon-wrap">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/icons/icon-misi.svg'); ?>" alt="<?php echo esc_attr($misi_label); ?>" class="about-direction-icon" />
              </span>
              <h3 class="about-direction-label"><?php echo esc_html($misi_label); ?></h3>
            </div>
            <p class="about-direction-text"><?php echo esc_html($misi_text); ?></p>
          </div>
        </div>
      </div>

      <div class="about-direction-image">
        <?php yiari_img($image, 'section-wide', '', __('Orangutan dalam perawatan tim YIARI', 'yiari')); ?>
      </div>
    </div>
  </div>
</section>
