<?php
$donation_form_language = 'id';

if (function_exists('pll_current_language')) {
    $donation_form_language = pll_current_language('slug') ?: 'id';
} elseif (function_exists('determine_locale') && strpos(determine_locale(), 'en') === 0) {
    $donation_form_language = 'en';
}

$is_english = $donation_form_language === 'en';
$donation_form_i18n = $is_english ? [
    'confirmation_title' => 'Donation Confirmation',
    'donor_info' => 'Donor Information',
    'donor_name' => 'Name',
    'donor_email' => 'Email',
    'donation_details' => 'Donation Details',
    'final_amount' => 'Amount to be processed',
    'cancel' => 'Cancel',
    'confirm_donation' => 'Confirm Donation',
] : [
    'confirmation_title' => 'Konfirmasi Donasi',
    'donor_info' => 'Informasi Donatur',
    'donor_name' => 'Nama',
    'donor_email' => 'Email',
    'donation_details' => 'Detail Donasi',
    'final_amount' => 'Jumlah yang akan diproses',
    'cancel' => 'Batal',
    'confirm_donation' => 'Konfirmasi Donasi',
];

$amount_copy = [
    'IDR' => [
        ['title' => 'Rp 50.000', 'desc' => $is_english ? 'Covers basic treatment for one rescued animal.' : 'Membeli obat-obatan dasar untuk satu satwa rehabilitasi.'],
        ['title' => 'Rp 100.000', 'desc' => $is_english ? 'Supports routine medical checks for rescue animals.' : 'Mendukung tim medis dalam pemeriksaan rutin satwa rescue.'],
        ['title' => 'Rp 250.000', 'desc' => $is_english ? 'Provides nutritious feed supply for one month.' : 'Menyediakan pakan bernutrisi lengkap selama 1 bulan.'],
        ['title' => 'Rp 500.000', 'desc' => $is_english ? 'Funds an emergency rescue mission in conflict areas.' : 'Mendanai misi penyelamatan darurat di lokasi konflik.'],
    ],
    'USD' => [
        ['title' => '$ 10', 'desc' => $is_english ? 'Helps provide basic care for one rescued animal.' : 'Membantu menyediakan perawatan dasar untuk satu satwa rehabilitasi.'],
        ['title' => '$ 25', 'desc' => $is_english ? 'Supports field response and rescue logistics.' : 'Mendukung respons lapangan dan logistik penyelamatan.'],
        ['title' => '$ 50', 'desc' => $is_english ? 'Contributes to habitat and nutrition support.' : 'Berkontribusi pada dukungan habitat dan nutrisi satwa.'],
        ['title' => '$ 100', 'desc' => $is_english ? 'Strengthens urgent rescue and conservation action.' : 'Memperkuat aksi penyelamatan dan konservasi yang mendesak.'],
    ],
];

 $idr_amounts = array_values(array_filter(array_map('trim', explode(',', WP_Midtrans_Currency_Converter::get_suggested_amounts('IDR')))));
$usd_amounts = array_values(array_filter(array_map('trim', explode(',', WP_Midtrans_Currency_Converter::get_suggested_amounts('USD')))));
?>
<div class="wp-midtrans-donation-form-container donation-form-shell">
    <div class="donation-form-shell-head">
        <h2><?php echo esc_html($atts['title']); ?></h2>
        <p class="donation-description"><?php echo esc_html($atts['description']); ?></p>
    </div>

    <form id="wp-midtrans-donation-form" class="wp-midtrans-donation-form donation-form-theme">
        <section class="donation-form-section">
            <h3 class="donation-form-section-title"><?php echo esc_html($is_english ? '1. Donation Amount' : '1. Jumlah Donasi'); ?></h3>

            <?php if (get_option('wp_midtrans_donation_enable_usd', 1)): ?>
            <div class="form-row donation-form-currency-row">
                <div class="currency-selector donation-currency-selector">
                    <label class="currency-option donation-currency-option">
                        <input type="radio" name="currency" value="IDR" checked>
                        <span class="currency-label"><?php echo esc_html__('IDR', 'yiari'); ?></span>
                    </label>
                    <label class="currency-option donation-currency-option">
                        <input type="radio" name="currency" value="USD">
                        <span class="currency-label"><?php echo esc_html__('USD', 'yiari'); ?></span>
                    </label>
                </div>

                <div class="currency-info usd-info" style="display: none;">
                    <div class="conversion-notice">
                        <span><?php esc_html_e('Nominal USD akan otomatis dikonversi ke Rupiah dengan kurs terkini.', 'yiari'); ?></span>
                    </div>
                    <div class="current-rate">
                        <?php esc_html_e('Kurs saat ini:', 'yiari'); ?> <strong>1 USD = <span id="current-exchange-rate"><?php echo esc_html(WP_Midtrans_Currency_Converter::format_currency(WP_Midtrans_Currency_Converter::get_exchange_rate('USD', 'IDR'), 'IDR')); ?></span></strong>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-row donation-amount-grid-wrap">
                <div class="suggested-amounts donation-amount-grid" id="suggested-amounts-idr">
                    <?php foreach ($idr_amounts as $index => $amount): ?>
                        <?php $copy = $amount_copy['IDR'][$index] ?? ['title' => 'Rp ' . number_format((int) $amount, 0, ',', '.'), 'desc' => '']; ?>
                        <button type="button" class="amount-option donation-amount-option" data-amount="<?php echo esc_attr($amount); ?>" data-currency="IDR">
                            <span class="donation-amount-option-title"><?php echo esc_html($copy['title']); ?></span>
                            <?php if (!empty($copy['desc'])): ?>
                                <span class="donation-amount-option-text"><?php echo esc_html($copy['desc']); ?></span>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <?php if (get_option('wp_midtrans_donation_enable_usd', 1)): ?>
                <div class="suggested-amounts donation-amount-grid" id="suggested-amounts-usd" style="display: none;">
                    <?php foreach ($usd_amounts as $index => $amount): ?>
                        <?php $copy = $amount_copy['USD'][$index] ?? ['title' => '$ ' . number_format((float) $amount, 0, '.', ','), 'desc' => '']; ?>
                        <button type="button" class="amount-option donation-amount-option" data-amount="<?php echo esc_attr($amount); ?>" data-currency="USD">
                            <span class="donation-amount-option-title"><?php echo esc_html($copy['title']); ?></span>
                            <?php if (!empty($copy['desc'])): ?>
                                <span class="donation-amount-option-text"><?php echo esc_html($copy['desc']); ?></span>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <button type="button" class="amount-option custom-amount donation-amount-option-hidden" aria-hidden="true" tabindex="-1"><?php esc_html_e('Jumlah Lain', 'yiari'); ?></button>
            </div>

            <div class="custom-amount-container donation-custom-amount-container">
                <label for="amount" class="donation-input-label"><?php echo esc_html($is_english ? 'Custom Amount' : 'Nominal Lainnya'); ?></label>
                <div class="donation-input-shell">
                    <span class="donation-input-prefix donation-input-prefix-idr">Rp</span>
                    <span class="donation-input-prefix donation-input-prefix-usd" style="display:none;">$</span>
                    <input type="number" id="amount" name="amount" step="0.01" placeholder="<?php echo esc_attr($is_english ? 'Enter custom amount' : 'Masukkan jumlah kustom'); ?>">
                </div>
                <div class="amount-info">
                    <p class="min-amount-note idr-min"><?php echo esc_html(sprintf($is_english ? 'Minimum: Rp %s' : 'Minimum: Rp %s', number_format(WP_Midtrans_Currency_Converter::get_minimum_amount('IDR'), 0, ',', '.'))); ?></p>
                    <p class="min-amount-note usd-min" style="display: none;"><?php echo esc_html(sprintf($is_english ? 'Minimum: $%s' : 'Minimum: $%s', WP_Midtrans_Currency_Converter::get_minimum_amount('USD'))); ?></p>
                </div>
                <div class="conversion-preview" style="display: none;">
                    <div class="conversion-details">
                        <span class="original-amount"></span>
                        <span class="conversion-arrow">→</span>
                        <span class="converted-amount"></span>
                    </div>
                </div>
            </div>
        </section>

        <section class="donation-form-section">
            <h3 class="donation-form-section-title"><?php echo esc_html($is_english ? '2. Personal Information' : '2. Informasi Pribadi'); ?></h3>

            <div class="form-row">
                <label for="donor_name" class="donation-input-label"><?php echo esc_html($is_english ? 'Full Name*' : 'Nama Lengkap*'); ?></label>
                <input type="text" id="donor_name" name="donor_name" placeholder="<?php echo esc_attr($is_english ? 'Example: Budi Santoso' : 'Contoh: Budi Santoso'); ?>" required>
            </div>

            <div class="form-row">
                <label for="donor_email" class="donation-input-label"><?php echo esc_html($is_english ? 'Active Email*' : 'Email Aktif*'); ?></label>
                <input type="email" id="donor_email" name="donor_email" placeholder="<?php echo esc_attr($is_english ? 'Example: budisantoso@gmail.com' : 'Contoh: budisantoso@gmail.com'); ?>" required>
            </div>

            <div class="form-row">
                <label for="donor_phone" class="donation-input-label"><?php echo esc_html($is_english ? 'Phone Number' : 'Nomor Telepon'); ?></label>
                <input type="tel" id="donor_phone" name="donor_phone" placeholder="<?php echo esc_attr($is_english ? 'Example: 08123456789' : 'Contoh: 08123456789'); ?>">
            </div>
        </section>

        <section class="donation-form-section">
            <h3 class="donation-form-section-title"><?php echo esc_html($is_english ? '3. Support Message' : '3. Pesan Dukungan'); ?></h3>
            <div class="form-row">
                <textarea id="message" name="message" rows="4" placeholder="<?php echo esc_attr($is_english ? 'Write a support message for our frontline team...' : 'Tuliskan pesan penyemangat untuk tim kami di garis depan...'); ?>"></textarea>
            </div>
        </section>

        <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('wp_midtrans_donation_nonce')); ?>">
        <input type="hidden" name="action" value="submit_donation">
        <input type="hidden" name="selected_currency" id="selected_currency" value="IDR">

        <div class="form-row donation-form-submit-row">
            <button type="submit" class="donation-submit-button"><?php echo esc_html($atts['button_text']); ?></button>
        </div>

        <p class="donation-form-disclaimer"><?php echo esc_html($is_english ? 'By clicking the button above, you agree to contribute to YIARI conservation efforts. You will be redirected to a secure payment gateway.' : 'Dengan mengklik tombol di atas, Anda setuju untuk berkontribusi dalam upaya konservasi YIARI. Anda akan dialihkan ke gerbang pembayaran aman.'); ?></p>
        <div class="donation-message" style="display: none;"></div>
    </form>

    <div id="donation-confirmation-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><?php echo esc_html($donation_form_i18n['confirmation_title']); ?></h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="confirmation-details">
                    <div class="donor-info">
                        <h4><?php echo esc_html($donation_form_i18n['donor_info']); ?></h4>
                        <p><strong><?php echo esc_html($donation_form_i18n['donor_name']); ?>:</strong> <span id="confirm-donor-name"></span></p>
                        <p><strong><?php echo esc_html($donation_form_i18n['donor_email']); ?>:</strong> <span id="confirm-donor-email"></span></p>
                    </div>
                    <div class="donation-info">
                        <h4><?php echo esc_html($donation_form_i18n['donation_details']); ?></h4>
                        <div class="currency-conversion" id="currency-conversion-details"></div>
                        <div class="final-amount">
                            <p><strong><?php echo esc_html($donation_form_i18n['final_amount']); ?>:</strong> <span id="final-idr-amount"></span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancel-donation"><?php echo esc_html($donation_form_i18n['cancel']); ?></button>
                <button type="button" class="btn btn-primary" id="confirm-donation"><?php echo esc_html($donation_form_i18n['confirm_donation']); ?></button>
            </div>
        </div>
    </div>
</div>
