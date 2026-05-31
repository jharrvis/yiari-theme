<?php
/**
 * Template Name: Donasi
 * Template Post Type: page
 * Halaman donasi YIARI.
 */

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();

$donation_label = yiari_field('donation_label', __('AKSI NYATA', 'yiari'));
$donation_title = yiari_field('donation_title', __("Karena Kontribusi\nAnda", 'yiari'));
$donation_desc = yiari_field('donation_desc', __('Membantu menyelamatkan, merawat, dan mengembalikan satwa ke alamnya, serta menjaga keseimbangan ekosistem.', 'yiari'));
$donation_image = yiari_field('donation_image', ['url' => $theme_uri . '/assets/img/gambar-cta.png', 'alt' => __('Orangutan yang sedang diselamatkan', 'yiari')]);
$donation_supporters_text = yiari_field('donation_supporters_text', __('Lebih dari 1 Juta orang sudah berdonasi', 'yiari'));
$donation_quote = yiari_field('donation_quote', __('“Semangat terus tim YIARI. Semoga semakin banyak hewan-hewan kita yang bisa diselamatkan”', 'yiari'));
$donation_donor_name = yiari_field('donation_donor_name', __('Ariani Namira', 'yiari'));
$donation_donor_meta = yiari_field('donation_donor_meta', __('Sudah berdonasi Rp 50.000', 'yiari'));
$donation_avatars = yiari_field('donation_supporter_avatars', []);
$donation_form_title = yiari_field('donation_form_title', __('Lengkapi Kontribusi Anda', 'yiari'));
$donation_form_desc = yiari_field('donation_form_desc', __('Pilih jumlah donasi dan lengkapi data diri secara singkat. Semua transaksi aman dan terenkripsi.', 'yiari'));
$donation_form_button = yiari_field('donation_form_button_text', __('Kirim Donasi', 'yiari'));
$successful_donations = yiari_get_successful_donations(5);
$successful_donation_count = yiari_count_successful_donations();

if (empty($donation_avatars) || !is_array($donation_avatars)) {
    $donation_avatars = [
        ['url' => $theme_uri . '/assets/img/avatars/avatar-1.jpg', 'alt' => __('Donatur 1', 'yiari')],
        ['url' => $theme_uri . '/assets/img/avatars/avatar-2.jpg', 'alt' => __('Donatur 2', 'yiari')],
        ['url' => $theme_uri . '/assets/img/avatars/avatar-3.jpg', 'alt' => __('Donatur 3', 'yiari')],
        ['url' => $theme_uri . '/assets/img/avatars/avatar-4.jpg', 'alt' => __('Donatur 4', 'yiari')],
        ['url' => $theme_uri . '/assets/img/avatars/avatar-5.jpg', 'alt' => __('Donatur 5', 'yiari')],
    ];
}

if (empty($successful_donations)) {
    $successful_donations = [
        (object) [
            'donor_name' => $donation_donor_name,
            'amount' => 50000,
            'message' => $donation_quote,
        ],
    ];
}

if ($successful_donation_count > 0) {
    $donation_supporters_text = sprintf(
        /* translators: %s is the number of successful donations. */
        __('Lebih dari %s orang sudah berdonasi', 'yiari'),
        number_format($successful_donation_count, 0, ',', '.')
    );
}

$shortcode = sprintf(
    '[midtrans_donation_form title="%s" description="%s" button_text="%s"]',
    esc_attr($donation_form_title),
    esc_attr($donation_form_desc),
    esc_attr($donation_form_button)
);
?>

<main class="donation-page">
  <section class="donation-section">
    <div class="container donation-layout">
      <div class="donation-content">
        <?php yiari_section_label($donation_label, true); ?>
        <h1 class="donation-title"><?php echo wp_kses_post(nl2br(esc_html($donation_title))); ?></h1>
        <p class="donation-description"><?php echo esc_html($donation_desc); ?></p>

        <div
          class="donation-proof-card"
          x-data="{
            activeDonationSlide: 0,
            donationSlideCount: <?php echo (int) count($successful_donations); ?>,
            donationSlideTimer: null,
            donationSlideDirection: 1,
            nextDonationSlide() {
              if (this.donationSlideCount <= 1) return;
              this.donationSlideDirection = 1;
              this.activeDonationSlide = (this.activeDonationSlide + 1) % this.donationSlideCount;
            },
            startDonationSlide() {
              if (this.donationSlideCount <= 1) return;
              this.stopDonationSlide();
              this.donationSlideTimer = setInterval(() => this.nextDonationSlide(), 4200);
            },
            stopDonationSlide() {
              if (this.donationSlideTimer) {
                clearInterval(this.donationSlideTimer);
                this.donationSlideTimer = null;
              }
            }
          }"
          x-init="startDonationSlide()"
        >
          <div class="donation-proof-media">
            <?php yiari_img($donation_image, 'section-wide', 'donation-proof-image', $donation_title); ?>
          </div>

          <div class="donation-proof-body">
            <div class="donation-proof-supporters">
              <div class="donation-proof-avatars" aria-hidden="true">
                <?php foreach (array_slice($donation_avatars, 0, 5) as $avatar): ?>
                  <?php yiari_img($avatar, 'square', 'donation-proof-avatar', $avatar['alt'] ?? __('Avatar donatur', 'yiari')); ?>
                <?php endforeach; ?>
              </div>
              <p class="donation-proof-supporters-text"><?php echo esc_html($donation_supporters_text); ?></p>
            </div>

            <div class="donation-proof-slides">
              <?php foreach ($successful_donations as $index => $donation): ?>
                <?php
                $slide_name = trim((string) ($donation->donor_name ?? ''));
                $slide_message = trim((string) ($donation->message ?? ''));
                $slide_message = $slide_message !== '' ? $slide_message : $donation_quote;
                $slide_initials = yiari_get_donation_initials($slide_name);
                $slide_meta = yiari_format_donation_amount($donation);
                ?>
                <div
                  class="donation-proof-slide"
                  x-show="activeDonationSlide === <?php echo (int) $index; ?>"
                  x-transition:enter="donation-slide-enter"
                  x-transition:enter-start="donation-slide-enter-start"
                  x-transition:enter-end="donation-slide-enter-end"
                  x-transition:leave="donation-slide-leave"
                  x-transition:leave-start="donation-slide-leave-start"
                  x-transition:leave-end="donation-slide-leave-end"
                  x-cloak
                >
                  <blockquote class="donation-proof-quote"><?php echo esc_html($slide_message); ?></blockquote>

                  <div class="donation-proof-donor">
                    <span class="donation-proof-donor-initials"><?php echo esc_html($slide_initials); ?></span>
                    <div class="donation-proof-donor-meta">
                      <div class="donation-proof-donor-name"><?php echo esc_html($slide_name); ?></div>
                      <div class="donation-proof-donor-note"><?php echo esc_html($slide_meta); ?></div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="donation-form-column">
        <?php if (shortcode_exists('midtrans_donation_form')): ?>
          <?php echo do_shortcode($shortcode); ?>
        <?php else: ?>
          <div class="donation-form-unavailable">
            <p><?php echo esc_html__('Plugin formulir donasi belum aktif.', 'yiari'); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
