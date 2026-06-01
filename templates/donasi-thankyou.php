<?php
/**
 * Template Name: Donasi Thank You
 * Template Post Type: page
 * Halaman redirect setelah donasi berhasil.
 */

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();

$hero_label = yiari_field('donation_thankyou_label', __('DUKUNGAN ANDA', 'yiari'));
$hero_title = yiari_field('donation_thankyou_title', __("Terima Kasih atas\nDukungan Anda", 'yiari'));
$hero_desc = yiari_field('donation_thankyou_desc', __('Donasi Anda berhasil kami terima. Kontribusi Anda sangat berarti bagi keberlangsungan satwa liar di Indonesia, membantu kami terus melindungi keanekaragaman hayati nusantara.', 'yiari'));
$hero_note = yiari_field('donation_thankyou_note', __('Bukti konfirmasi telah dikirimkan ke email Anda. Silakan periksa folder spam jika tidak menemukannya di kotak masuk utama. Butuh bantuan? Hubungi kami di informasi@yiari.or.id', 'yiari'));
$hero_image = yiari_field('donation_thankyou_image', ['url' => $theme_uri . '/assets/img/gambar-cta.png', 'alt' => __('Orangutan yang sedang diselamatkan', 'yiari')]);

$explore_label = yiari_field('donation_thankyou_explore_label', __('EKSPLORASI', 'yiari'));
$explore_title = yiari_field('donation_thankyou_explore_title', __("Terus Berjalan\nBersama Kami", 'yiari'));
$explore_desc = yiari_field('donation_thankyou_explore_desc', __('Eksplorasi lebih dalam bagaimana kontribusi Anda membentuk masa depan yang lebih baik bagi satwa liar Indonesia.', 'yiari'));
$explore_cards = yiari_field('donation_thankyou_cards', []);

if (empty($explore_cards)) {
    $explore_cards = [
        [
            'image' => ['url' => $theme_uri . '/assets/img/gambar4.jpg', 'alt' => __('Jelajahi Program Konservasi', 'yiari')],
            'title' => __('Jelajahi Program Konservasi', 'yiari'),
            'text' => __('Pelajari inisiatif strategis kami dalam melindungi ekosistem dan memberdayakan komunitas lokal.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => yiari_get_program_url(),
        ],
        [
            'image' => ['url' => $theme_uri . '/assets/img/ketapang.webp', 'alt' => __('Lihat Lokasi Konservasi', 'yiari')],
            'title' => __('Lihat Lokasi Konservasi', 'yiari'),
            'text' => __('Kami beroperasi di wilayah-wilayah prioritas konservasi dengan kehadiran spesies kunci dan tingkat ancaman tinggi.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => yiari_get_page_url_by_template('templates/lanskap.php', '#'),
        ],
        [
            'image' => ['url' => $theme_uri . '/assets/img/bogor.webp', 'alt' => __('Baca Cerita Magang', 'yiari')],
            'title' => __('Baca Cerita Magang', 'yiari'),
            'text' => __('Kisah mahasiswa, relawan, dan peserta magang yang terlibat langsung bersama YIARI.', 'yiari'),
            'link_text' => __('Lihat Selengkapnya', 'yiari'),
            'link_url' => yiari_get_posts_page_url('#'),
        ],
    ];
}
?>

<main class="donation-thankyou-page">
  <section class="donation-thankyou-hero-section">
    <div class="container">
      <div class="donation-thankyou-hero-card">
        <div class="donation-thankyou-hero-copy">
          <?php yiari_section_label($hero_label, true); ?>
          <h1 class="donation-thankyou-title"><?php echo wp_kses_post(nl2br(esc_html($hero_title))); ?></h1>
          <p class="donation-thankyou-description"><?php echo esc_html($hero_desc); ?></p>
          <div class="donation-thankyou-note">
            <p><?php echo make_clickable(esc_html($hero_note)); ?></p>
          </div>
        </div>
        <div class="donation-thankyou-hero-media">
          <?php yiari_img($hero_image, 'section-wide', 'donation-thankyou-hero-image', $hero_title); ?>
        </div>
      </div>
    </div>
  </section>

  <section class="donation-thankyou-explore-section">
    <div class="container">
      <div class="stats-layout donation-thankyou-explore-header">
        <div class="stats-intro">
          <?php yiari_section_label($explore_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($explore_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($explore_desc); ?></p>
        </div>
      </div>

      <div class="donation-thankyou-card-grid">
        <?php foreach ($explore_cards as $card): ?>
          <?php
          $card_image = $card['image'] ?? null;
          $card_title = $card['title'] ?? '';
          $card_text = $card['text'] ?? '';
          $card_link_text = $card['link_text'] ?? __('Lihat Selengkapnya', 'yiari');
          $card_link_url = $card['link_url'] ?? '#';
          ?>
          <article class="donation-thankyou-card">
            <div class="donation-thankyou-card-media">
              <?php yiari_img($card_image, 'card-thumb', 'donation-thankyou-card-image', $card_title); ?>
            </div>
            <div class="donation-thankyou-card-body">
              <h3 class="donation-thankyou-card-title"><?php echo esc_html($card_title); ?></h3>
              <p class="donation-thankyou-card-text"><?php echo esc_html($card_text); ?></p>
              <a href="<?php echo esc_url($card_link_url); ?>" class="link-more"><?php echo esc_html($card_link_text); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
