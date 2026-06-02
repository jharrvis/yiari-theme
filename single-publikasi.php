<?php
defined('ABSPATH') || exit;

get_template_part('template-parts/global/header');
?>

<?php if (have_posts()): ?>
  <?php while (have_posts()): the_post(); ?>
    <?php
    $post_id = get_the_ID();
    $title = get_the_title();
    $summary = yiari_field('publication_summary', '');
    if ($summary === '') {
        $summary = has_excerpt()
            ? get_the_excerpt()
            : wp_trim_words(wp_strip_all_tags((string) get_the_content('')), 30, '…');
    }

    $file_url = yiari_get_publication_file_url($post_id);
    $preview_url = yiari_get_publication_preview_url($file_url);
    $download_url = yiari_get_publication_download_url($file_url);
    $is_google_drive = yiari_get_google_drive_file_id($file_url) !== '';
    $supports_pdfjs = yiari_publication_supports_pdfjs($file_url);
    $document_meta = yiari_get_publication_document_meta($post_id);

    $year = yiari_field('publication_year', get_the_date('Y'));
    $language = yiari_field('publication_language', __('Indonesia', 'yiari'));
    $manual_pages = yiari_field('publication_pages', '');
    $manual_file_size = yiari_field('publication_file_size', '');
    $pages = $document_meta['pages'] > 0 ? (string) $document_meta['pages'] : (string) $manual_pages;
    $file_size = $document_meta['file_size_label'] !== '' ? $document_meta['file_size_label'] : (string) $manual_file_size;
    $published_label = yiari_field('publication_publish_label', get_the_date('F Y'));
    $publication_archive_url = yiari_get_publication_archive_url();
    $publication_year_url = yiari_get_publication_year_url($post_id, (string) $year);

    $meta_items = [
        [
            'icon' => 'calendar-days',
            'label' => __('Tahun', 'yiari'),
            'value' => $year,
        ],
        [
            'icon' => 'languages',
            'label' => __('Bahasa', 'yiari'),
            'value' => $language,
        ],
        [
            'icon' => 'file-text',
            'label' => __('Halaman', 'yiari'),
            'value' => $pages !== '' ? sprintf(__('%s Halaman', 'yiari'), $pages) : '',
        ],
        [
            'icon' => 'file-stack',
            'label' => __('Ukuran File', 'yiari'),
            'value' => $file_size,
        ],
        [
            'icon' => 'upload',
            'label' => __('Diterbitkan', 'yiari'),
            'value' => $published_label,
        ],
    ];
    $meta_items = array_values(array_filter($meta_items, static fn($item) => trim((string) ($item['value'] ?? '')) !== ''));
    ?>

    <article class="publication-detail-page">
      <section class="publication-detail-section">
        <div class="container publication-detail-container">
          <nav class="single-post-breadcrumbs publication-detail-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'yiari'); ?>">
            <a href="<?php echo esc_url($publication_archive_url); ?>"><?php esc_html_e('Jurnal', 'yiari'); ?></a>
            <span class="single-post-breadcrumb-sep">/</span>
            <a href="<?php echo esc_url($publication_year_url); ?>"><?php echo esc_html($year); ?></a>
            <span class="single-post-breadcrumb-sep">/</span>
            <span aria-current="page"><?php echo esc_html($title); ?></span>
          </nav>
          <h1 class="publication-detail-title"><?php echo esc_html($title); ?></h1>

          <div class="publication-detail-card">
            <aside class="publication-detail-sidebar">
              <h2 class="publication-detail-sidebar-heading"><?php esc_html_e('Tentang Penelitian Ini:', 'yiari'); ?></h2>
              <?php if ($summary !== ''): ?>
                <p class="publication-detail-summary"><?php echo esc_html($summary); ?></p>
              <?php endif; ?>

              <?php if ($meta_items): ?>
                <div class="publication-detail-meta-list">
                  <?php foreach ($meta_items as $item): ?>
                    <div class="publication-detail-meta-item">
                      <div class="publication-detail-meta-label">
                        <i data-lucide="<?php echo esc_attr($item['icon']); ?>" class="icon-md"></i>
                        <span><?php echo esc_html($item['label']); ?></span>
                      </div>
                      <div class="publication-detail-meta-value"><?php echo esc_html((string) $item['value']); ?></div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <div class="publication-detail-actions">
                <?php if ($download_url !== ''): ?>
                  <a href="<?php echo esc_url($download_url); ?>" class="btn-donate-main publication-detail-btn" target="_blank" rel="noopener"><?php esc_html_e('Download Laporan', 'yiari'); ?></a>
                <?php endif; ?>
                <?php if ($preview_url !== ''): ?>
                  <a href="<?php echo esc_url($preview_url); ?>" class="btn-volunteer-main publication-detail-btn" target="_blank" rel="noopener"><?php esc_html_e('Buka Layar Penuh', 'yiari'); ?></a>
                <?php endif; ?>
              </div>
            </aside>

            <div class="publication-detail-viewer">
              <?php if ($preview_url !== ''): ?>
                <?php if ($supports_pdfjs): ?>
                  <div
                    class="publication-detail-viewer-shell publication-pdfjs-viewer"
                    data-pdf-viewer
                    data-pdf-url="<?php echo esc_url($file_url); ?>"
                    data-pdf-title="<?php echo esc_attr($title); ?>"
                  >
                    <div class="publication-pdfjs-toolbar" role="toolbar" aria-label="<?php esc_attr_e('Kontrol PDF', 'yiari'); ?>">
                      <div class="publication-pdfjs-toolbar-group">
                        <button type="button" class="publication-pdfjs-btn" data-pdf-action="zoom-out"><?php esc_html_e('Perkecil', 'yiari'); ?></button>
                        <button type="button" class="publication-pdfjs-btn" data-pdf-action="zoom-in"><?php esc_html_e('Perbesar', 'yiari'); ?></button>
                        <button type="button" class="publication-pdfjs-btn is-secondary" data-pdf-action="reset"><?php esc_html_e('Reset', 'yiari'); ?></button>
                      </div>
                      <div class="publication-pdfjs-toolbar-group">
                        <button type="button" class="publication-pdfjs-btn is-secondary" data-pdf-action="hand-tool" aria-pressed="false"><?php esc_html_e('Hand Tool', 'yiari'); ?></button>
                      </div>
                    </div>
                    <div class="publication-pdfjs-status" data-pdf-status><?php esc_html_e('Memuat PDF...', 'yiari'); ?></div>
                    <div class="publication-pdfjs-stage" data-pdf-stage>
                      <div class="publication-pdfjs-canvas-list" data-pdf-canvas-list></div>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="publication-detail-viewer-shell">
                    <iframe
                      src="<?php echo esc_url($preview_url); ?>"
                      class="publication-detail-iframe"
                      title="<?php echo esc_attr($title); ?>"
                      loading="lazy"
                      allow="<?php echo $is_google_drive ? esc_attr('autoplay') : ''; ?>"
                    ></iframe>
                  </div>
                <?php endif; ?>
              <?php else: ?>
                <div class="publication-detail-preview-unavailable">
                  <p><?php esc_html_e('Preview file belum tersedia. Tambahkan link PDF atau Google Drive pada field link_jurnal.', 'yiari'); ?></p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>
    </article>
  <?php endwhile; ?>
<?php endif; ?>

<?php get_template_part('template-parts/global/footer'); ?>
