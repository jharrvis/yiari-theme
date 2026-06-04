<?php
/**
 * Template Name: Blog
 * Template Post Type: page
 * Halaman daftar artikel dan cerita YIARI.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();

$hero_image = yiari_field('blog_hero_image', ['url' => $theme_uri . '/assets/img/hero-section.jpg']);
$hero_title = yiari_field('blog_hero_title', __("Cerita dan Wawasan\nDari Lapangan", 'yiari'));
$hero_text = yiari_field('blog_hero_text', __('Kumpulan cerita, pembaruan lapangan, dan perspektif dari kerja konservasi YIARI di berbagai wilayah Indonesia.', 'yiari'));
$hero_btn_text = yiari_field('blog_hero_btn_text', __('Lihat Artikel', 'yiari'));
$hero_btn_raw = trim((string) yiari_field('blog_hero_btn_url', '#blog-archive'));
$hero_btn_url = $hero_btn_raw !== '' && str_starts_with($hero_btn_raw, '#')
    ? $hero_btn_raw
    : yiari_localize_url($hero_btn_raw !== '' ? $hero_btn_raw : '#blog-archive');

$category_id = (int) yiari_field('blog_category', 0);

$featured_label = yiari_field('blog_featured_label', __('ARTIKEL UNGGULAN', 'yiari'));
$featured_title = yiari_field('blog_featured_title', __('Sorotan Cerita Terbaru', 'yiari'));
$featured_desc = yiari_field('blog_featured_desc', __('Temukan cerita terbaru, pembelajaran lapangan, dan kabar dari kerja konservasi kami.', 'yiari'));

$featured_query_args = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'ignore_sticky_posts' => true,
    'orderby' => 'date',
    'order' => 'DESC',
];

if ($category_id > 0) {
    $featured_query_args['cat'] = $category_id;
}

$featured_query = new WP_Query($featured_query_args);
$featured_posts = $featured_query->posts;
$featured_ids = array_values(array_filter(array_map(static fn($post) => (int) ($post->ID ?? 0), $featured_posts)));
wp_reset_postdata();

$archive_label = yiari_field('blog_archive_label', __('SEMUA ARTIKEL', 'yiari'));
$archive_title = yiari_field('blog_archive_title', __('Jelajahi Semua Cerita', 'yiari'));
$archive_desc = yiari_field('blog_archive_desc', __('Artikel, pembaruan, dan cerita dari lapangan yang mendokumentasikan kerja konservasi YIARI.', 'yiari'));
$archive_count = max(3, min(18, (int) yiari_field('blog_archive_count', 9)));
$load_more_text = yiari_field('blog_load_more_text', __('Muat Lebih Banyak', 'yiari'));

$archive_query_args = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $archive_count,
    'ignore_sticky_posts' => true,
    'orderby' => 'date',
    'order' => 'DESC',
    'post__not_in' => $featured_ids,
];

if ($category_id > 0) {
    $archive_query_args['cat'] = $category_id;
}

$archive_query = new WP_Query($archive_query_args);
$archive_posts = $archive_query->posts;
$archive_has_more = $archive_query->max_num_pages > 1;
wp_reset_postdata();

$cta_label = yiari_field('blog_cta_label', __('DUKUNGAN ANDA', 'yiari'));
$cta_title = yiari_field('blog_cta_title', __("Bantu Cerita Konservasi\nMenjangkau Lebih Banyak\nOrang", 'yiari'));
$cta_text = yiari_field('blog_cta_text', __('Dukungan Anda membantu YIARI membagikan lebih banyak cerita, pembelajaran, dan pengetahuan dari lapangan kepada publik yang lebih luas.', 'yiari'));
$cta_btn1_text = yiari_field('blog_cta_btn1_text', __('Donasi Sekarang', 'yiari'));
$cta_btn1_url = yiari_localize_url((string) yiari_field('blog_cta_btn1_url', yiari_get_page_url_by_template('templates/donasi.php', yiari_home_url())));
$cta_btn2_text = yiari_field('blog_cta_btn2_text', __('Gabung Bersama YIARI', 'yiari'));
$cta_btn2_url = yiari_localize_url((string) yiari_field('blog_cta_btn2_url', yiari_get_join_url()));
$cta_image = yiari_field('blog_cta_image', ['url' => $theme_uri . '/assets/img/gambar-cta.png', 'alt' => __('Dukung penyebaran cerita YIARI', 'yiari')]);
?>

<main class="blog-page">
  <section class="blog-hero-wrap">
    <?php
    get_template_part('template-parts/sections/page-hero', null, [
        'id' => 'blog-top',
        'classes' => 'blog-hero-section',
        'image' => $hero_image,
        'image_class' => 'blog-hero-img',
        'image_alt' => __('Blog YIARI', 'yiari'),
        'overlay_class' => 'blog-hero-overlay',
        'content_class' => 'blog-hero-content',
        'copy_class' => 'blog-hero-copy',
        'title_class' => 'blog-hero-title',
        'text_class' => 'blog-hero-text',
        'actions_class' => 'blog-hero-actions',
        'title' => $hero_title,
        'text' => $hero_text,
        'allow_title_breaks' => true,
        'buttons' => [
            ['text' => $hero_btn_text, 'url' => $hero_btn_url, 'class' => 'btn-action btn-action-primary'],
        ],
    ]);
    ?>
  </section>

  <?php if (!empty($featured_posts)): ?>
    <section class="section-news blog-featured-section">
      <div class="container">
        <div class="stats-layout blog-section-header">
          <div class="stats-intro">
            <?php yiari_section_label($featured_label, true); ?>
            <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($featured_title))); ?></h2>
          </div>
          <div class="stats-desc">
            <p class="section-description"><?php echo esc_html($featured_desc); ?></p>
          </div>
        </div>

        <div class="news-grid">
          <?php
          $featured_primary = $featured_posts[0] ?? null;
          $featured_secondary = array_slice($featured_posts, 1, 2);
          ?>

          <?php if ($featured_primary instanceof WP_Post): ?>
            <?php
            $post_id = (int) $featured_primary->ID;
            $title = get_the_title($post_id);
            $permalink = get_permalink($post_id) ?: '#';
            $image = get_the_post_thumbnail_url($post_id, 'section-wide') ?: ($theme_uri . '/assets/img/hero-section.jpg');
            $excerpt = has_excerpt($post_id)
                ? get_the_excerpt($post_id)
                : wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $post_id)), 24, '…');
            $author = get_the_author_meta('display_name', (int) get_post_field('post_author', $post_id));
            ?>
            <article class="news-featured">
              <a href="<?php echo esc_url($permalink); ?>" class="news-featured-img" aria-label="<?php echo esc_attr($title); ?>">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" />
              </a>
              <div class="news-card-body">
                <div class="news-date">
                  <span><?php echo esc_html(get_the_date('j F Y', $post_id)); ?></span>
                  <?php if ($author !== ''): ?>
                    <span class="news-meta-sep">&bull;</span>
                    <span><?php echo esc_html($author); ?></span>
                  <?php endif; ?>
                </div>
                <h3 class="news-title"><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h3>
                <p class="news-excerpt"><?php echo esc_html($excerpt); ?></p>
                <a href="<?php echo esc_url($permalink); ?>" class="link-more"><?php esc_html_e('Baca Selengkapnya', 'yiari'); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
              </div>
            </article>
          <?php endif; ?>

          <div class="news-stacked">
            <?php foreach ($featured_secondary as $stacked_post): ?>
              <?php
              $post_id = (int) $stacked_post->ID;
              $title = get_the_title($post_id);
              $permalink = get_permalink($post_id) ?: '#';
              $image = get_the_post_thumbnail_url($post_id, 'card-thumb') ?: ($theme_uri . '/assets/img/hero-section.jpg');
              $excerpt = has_excerpt($post_id)
                  ? get_the_excerpt($post_id)
                  : wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $post_id)), 18, '…');
              $author = get_the_author_meta('display_name', (int) get_post_field('post_author', $post_id));
              ?>
              <article class="news-card-vertical">
                <a href="<?php echo esc_url($permalink); ?>" class="news-card-v-img" aria-label="<?php echo esc_attr($title); ?>">
                  <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" />
                </a>
                <div class="news-card-body">
                  <div class="news-date">
                    <span><?php echo esc_html(get_the_date('j F Y', $post_id)); ?></span>
                    <?php if ($author !== ''): ?>
                      <span class="news-meta-sep">&bull;</span>
                      <span><?php echo esc_html($author); ?></span>
                    <?php endif; ?>
                  </div>
                  <h3 class="news-title"><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h3>
                  <p class="news-excerpt"><?php echo esc_html($excerpt); ?></p>
                  <a href="<?php echo esc_url($permalink); ?>" class="link-more"><?php esc_html_e('Baca Selengkapnya', 'yiari'); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="detail-landscape-updates-section blog-archive-section" id="blog-archive">
    <div
      class="container"
      x-data="{
        page: 2,
        hasMore: <?php echo $archive_has_more ? 'true' : 'false'; ?>,
        loading: false,
        error: '',
        categoryId: <?php echo (int) $category_id; ?>,
        count: <?php echo (int) $archive_count; ?>,
        excludeIds: <?php echo wp_json_encode($featured_ids); ?>,
        async loadMore() {
          if (this.loading || !this.hasMore) return;

          this.loading = true;
          this.error = '';

          try {
            const payload = new URLSearchParams({
              action: 'yiari_load_more_updates',
              nonce: window.yiariUpdates?.nonce || '',
              page: String(this.page),
              count: String(this.count),
              category_id: String(this.categoryId)
            });

            this.excludeIds.forEach((postId) => payload.append('exclude_ids[]', String(postId)));

            const response = await fetch(window.yiariUpdates?.ajaxUrl || '', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
              },
              body: payload.toString(),
            });

            const data = await response.json();
            if (!data?.success) {
              throw new Error('Request failed');
            }

            if (this.$refs.grid) {
              this.$refs.grid.insertAdjacentHTML('beforeend', data.data?.html || '');
            }

            this.page = Number(data.data?.nextPage || (this.page + 1));
            this.hasMore = Boolean(data.data?.hasMore);

            this.$nextTick(() => {
              if (window.lucide?.createIcons) {
                window.lucide.createIcons();
              }
            });
          } catch (error) {
            this.error = window.yiariUpdates?.strings?.error || 'Gagal memuat artikel berikutnya.';
          } finally {
            this.loading = false;
          }
        }
      }"
    >
      <div class="stats-layout detail-landscape-updates-header blog-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($archive_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($archive_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($archive_desc); ?></p>
        </div>
      </div>

      <?php if (!empty($archive_posts)): ?>
        <div class="detail-landscape-updates-grid" x-ref="grid">
          <?php foreach ($archive_posts as $archive_post): ?>
            <?php echo yiari_render_detail_landscape_update_card((int) $archive_post->ID); ?>
          <?php endforeach; ?>
        </div>

        <?php if ($archive_has_more): ?>
          <div class="detail-landscape-updates-footer" x-show="hasMore" x-cloak>
            <button type="button" class="btn-action btn-action-outline detail-landscape-updates-more" @click="loadMore()" :disabled="loading">
              <span x-show="!loading"><?php echo esc_html($load_more_text); ?></span>
              <span x-show="loading" x-cloak><?php esc_html_e('Memuat...', 'yiari'); ?></span>
            </button>
          </div>
          <p class="books-load-more-error" x-show="error" x-text="error" x-cloak></p>
        <?php endif; ?>
      <?php else: ?>
        <div class="detail-landscape-updates-empty">
          <p class="detail-landscape-updates-empty-text"><?php esc_html_e('Belum ada artikel pada kategori ini.', 'yiari'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="journal-cta-section blog-cta-section">
    <div class="container">
      <div class="journal-cta-card blog-cta-card">
        <div class="journal-cta-copy blog-cta-copy">
          <div class="donate-pill">
            <i data-lucide="heart-handshake" class="donate-pill-icon"></i>
            <span><?php echo esc_html($cta_label); ?></span>
          </div>
          <h2 class="journal-cta-title blog-cta-title"><?php echo wp_kses_post(nl2br(esc_html($cta_title))); ?></h2>
          <p class="journal-cta-text blog-cta-text"><?php echo esc_html($cta_text); ?></p>
          <div class="journal-cta-actions blog-cta-actions">
            <?php yiari_btn($cta_btn1_text, $cta_btn1_url, 'btn-donate-main'); ?>
            <?php yiari_btn($cta_btn2_text, $cta_btn2_url, 'btn-volunteer-main'); ?>
          </div>
        </div>
        <div class="journal-cta-media blog-cta-media">
          <?php yiari_img($cta_image, 'section-wide', 'journal-cta-image blog-cta-image', $cta_title); ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
