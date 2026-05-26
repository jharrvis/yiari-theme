</main>
<?php
$footer_tagline = get_theme_mod('footer_tagline', __('Menyelamatkan dan melestarikan satwa liar Indonesia untuk generasi mendatang.', 'yiari'));
$bogor_label = get_theme_mod('footer_office_bogor_label', __('KANTOR YIARI BOGOR', 'yiari'));
$bogor_address = get_theme_mod('footer_office_bogor_address', __('Jl. Curug Nangka, Kp. Sinarwangi, Kel. Sukajadi, Kec. Tamansari, Bogor, Jawa Barat 16610', 'yiari'));
$bogor_phone = get_theme_mod('footer_office_bogor_phone', __('+62-856 7536 660 (Bogor)', 'yiari'));
$ketapang_label = get_theme_mod('footer_office_ketapang_label', __('KANTOR YIARI KETAPANG', 'yiari'));
$ketapang_address = get_theme_mod('footer_office_ketapang_address', __('Jl. Ketapang-Tanjungpura RT.010, Dsn. Pematang Merbau, Kec. Muara Pawan, Kab. Ketapang, Kalimantan Barat 78813', 'yiari'));
$ketapang_phone = get_theme_mod('footer_office_ketapang_phone', __('+62-856 5237 3497 (Ketapang)', 'yiari'));
?>
<footer class="site-footer" id="footer">
  <div class="container footer-container">
    <div class="footer-main">
      <div class="footer-brand-panel">
        <div class="footer-logo">
          <?php if (has_custom_logo()): ?>
            <?php $logo_id = get_theme_mod('custom_logo'); echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'footer-logo-img', 'alt' => get_bloginfo('name')]); ?>
          <?php else: ?>
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo-yiari.svg'); ?>" alt="<?php bloginfo('name'); ?>" class="footer-logo-img" />
          <?php endif; ?>
        </div>
        <p class="footer-brand-text"><?php echo esc_html($footer_tagline); ?></p>
        <form class="footer-subscribe" action="<?php echo esc_url(yiari_home_url()); ?>" method="post">
          <label class="sr-only" for="footer-email-input"><?php echo esc_html__('Masukkan email', 'yiari'); ?></label>
          <input id="footer-email-input" type="email" name="footer_email" class="footer-input" placeholder="<?php echo esc_attr__('Masukkan email', 'yiari'); ?>" />
          <?php wp_nonce_field('footer_subscribe', 'footer_subscribe_nonce'); ?>
          <button type="submit" class="footer-subscribe-btn"><?php echo esc_html__('Langganan', 'yiari'); ?></button>
        </form>
      </div>
      <div class="footer-nav-grid">
        <?php yiari_render_footer_menu_column('footer-col1', __('Tentang', 'yiari')); ?>
        <?php yiari_render_footer_menu_column('footer-col2', __('Program', 'yiari')); ?>
        <?php yiari_render_footer_menu_column('footer-col3', __('Publikasi', 'yiari'), true); ?>
      </div>
    </div>

    <div class="footer-address-grid">
      <div class="footer-address-block">
        <div class="footer-address-title"><?php echo esc_html($bogor_label); ?></div>
        <p class="footer-address-text"><?php echo esc_html($bogor_address); ?></p>
        <p class="footer-address-text"><?php echo esc_html($bogor_phone); ?></p>
      </div>
      <div class="footer-address-block">
        <div class="footer-address-title"><?php echo esc_html($ketapang_label); ?></div>
        <p class="footer-address-text"><?php echo esc_html($ketapang_address); ?></p>
        <p class="footer-address-text"><?php echo esc_html($ketapang_phone); ?></p>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="footer-bottom-left">
        <p class="footer-copyright">&copy; <?php echo esc_html(date('Y')); ?> YIARI. <?php echo esc_html__('All rights reserved.', 'yiari'); ?></p>
        <div class="footer-bottom-links">
          <a href="<?php echo esc_url(yiari_get_privacy_url()); ?>"><?php echo esc_html__('Privacy', 'yiari'); ?></a>
          <a href="<?php echo esc_url(yiari_get_terms_url()); ?>"><?php echo esc_html__('Terms', 'yiari'); ?></a>
        </div>
      </div>
      <div class="footer-social footer-social-bottom">
        <?php
        $socials = [
            'youtube' => ['label' => __('YouTube', 'yiari'), 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31.6 31.6 0 0 0 0 12a31.6 31.6 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31.6 31.6 0 0 0 24 12a31.6 31.6 0 0 0-.5-5.8zM9.6 15.6V8.4l6.2 3.6-6.2 3.6z"></path></svg>'],
            'instagram' => ['label' => __('Instagram', 'yiari'), 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37a4 4 0 1 1-7.75 1.26 4 4 0 0 1 7.75-1.26z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>'],
            'twitter' => ['label' => __('X', 'yiari'), 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-6.8 7.8L23.2 22H17l-4.8-6.3L6.8 22H3.7l7.3-8.4L1.2 2h6.4l4.3 5.8L18.9 2zm-1.1 18h1.7L6.7 3.9H4.9L17.8 20z"></path></svg>'],
            'facebook' => ['label' => __('Facebook', 'yiari'), 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.9.3-1.6 1.7-1.6H16.7V4.8c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.4V11H7.3v3H10v8h3.5z"></path></svg>'],
        ];
        foreach ($socials as $key => $social) {
            $url = get_theme_mod('social_' . $key, '#');
            printf('<a href="%s" class="social-link social-link-square" aria-label="%s">%s</a>', esc_url($url), esc_attr($social['label']), $social['icon']);
        }
        ?>
      </div>
    </div>
  </div>
</footer>
<button class="scroll-top-btn" type="button" aria-label="<?php echo esc_attr__('Kembali ke atas', 'yiari'); ?>" x-show="scrolled" x-transition.opacity.duration.200ms @click="scrollToTop()">
  <i data-lucide="arrow-up" class="icon-sm"></i>
</button>
<?php wp_footer(); ?>
</body>
</html>
