<?php
/**
 * Template Part: Partners Section (Home)
 * Disesuaikan mengikuti design/index.html.
 */

$org_logos = [
    ['file' => 'Oak_option1.svg', 'alt' => 'OAK Foundation'],
    ['file' => 'IAR-LOGO.webp', 'alt' => 'IAR'],
    ['file' => 'INL-logo.webp', 'alt' => 'INL'],
    ['file' => 'the-orangutan-project-logo.svg', 'alt' => 'The Orangutan Project'],
    ['file' => 'orangutan-outreach.svg', 'alt' => 'Orangutan Outreach'],
    ['file' => 'Arcuslogo-1.svg', 'alt' => 'Arcus Foundation'],
    ['file' => 'pbnf.svg', 'alt' => 'PBNF'],
    ['file' => 'bcf-logo.svg', 'alt' => 'BCF'],
    ['file' => 'holtzman-wildlife.webp', 'alt' => 'Holtzman Wildlife'],
    ['file' => 'pro-wildlife.svg', 'alt' => 'Pro Wildlife'],
    ['file' => 'foundacion-reina-sofia.svg', 'alt' => 'Fundacion Reina Sofia'],
    ['file' => 'The-Moondance-Foundation-1-1.webp', 'alt' => 'Moondance Foundation'],
    ['file' => 'bkcf-logo.svg', 'alt' => 'BKCF'],
    ['file' => 'darwininitiative.svg', 'alt' => 'Darwin Initiative'],
    ['file' => 'Wildlife-asia-logo.svg', 'alt' => 'Wildlife Asia'],
    ['file' => 'wcn-logo.svg', 'alt' => 'WCN'],
    ['file' => 'Logo-rettet-den-regenwald-ev-2019.svg', 'alt' => 'Rettet den Regenwald'],
    ['file' => 'iwt-logo.svg', 'alt' => 'IWT'],
    ['file' => 'climate-land-use-alliance-color-2.svg', 'alt' => 'Climate Land Use Alliance'],
    ['file' => 'global-forest-watch-logo.svg', 'alt' => 'Global Forest Watch'],
    ['file' => 'Seal_of_the_United_States_Fish_and_Wildlife_Service.svg', 'alt' => 'US Fish and Wildlife Service'],
];
$assets = get_template_directory_uri() . '/assets/img/logos';
?>
<section class="section-partners" id="partners">
  <div class="container">
    <div class="stats-layout">
      <div class="stats-intro fade-in">
        <span class="section-label">Mitra dan Pendukung Lainya</span>
        <h2 class="section-heading section-heading-dark">Jaringan Mitra<br>Konservasi</h2>
      </div>
      <div class="stats-desc fade-in">
        <p class="text-muted section-description">
          Kami bergandengan tangan dengan lembaga nasional dan internasional untuk memperluas jangkauan konservasi kami, demi kelestarian satwa dan ekosistem Indonesia.
        </p>
      </div>
    </div>

    <div class="partners-layout fade-in">
      <div class="partners-logos">
        <div class="partners-stack">
          <div class="key-partners-label">4 Mitra Instansi Pemerintah</div>
          <div class="government-grid">
            <div class="key-partner-logo">
              <img src="<?php echo esc_url($assets . '/logo-kemenhut.webp'); ?>" alt="Kementerian Kehutanan" class="government-logo government-logo-kemenhut" />
            </div>
            <div class="key-partner-logo">
              <img src="<?php echo esc_url($assets . '/mitra-kami.webp'); ?>" alt="3 Mitra Instansi Pemerintah lainnya" class="government-logo government-logo-strip" />
            </div>
          </div>
        </div>

        <div class="partners-stack">
          <div class="partners-logos-label">21 Organisasi Pendukung</div>
          <div class="partners-grid">
            <?php foreach ($org_logos as $logo): ?>
              <div class="partner-logo-card">
                <img src="<?php echo esc_url($assets . '/' . $logo['file']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>" class="partner-logo" />
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
