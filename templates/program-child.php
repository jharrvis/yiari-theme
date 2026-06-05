<?php
/**
 * Template Name: Program Child
 * Template Post Type: page
 * Halaman turunan program untuk update dan cerita lapangan.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/global/header');

$theme_uri = get_template_directory_uri();
$page_id = get_queried_object_id();
$is_english = yiari_post_is_english($page_id);

$legacy_title = trim((string) get_post_meta($page_id, 'program_title', true));
$legacy_description = trim((string) get_post_meta($page_id, 'diskripsi', true));
$legacy_description = trim((string) preg_replace('/\s+/', ' ', wp_strip_all_tags($legacy_description)));
$legacy_categories = maybe_unserialize(get_post_meta($page_id, 'program_category', true));
$legacy_category_ids = is_array($legacy_categories)
    ? array_values(array_filter(array_map('intval', $legacy_categories)))
    : [];
$selected_category_ids = yiari_field('program_child_updates_categories', []);
if (!is_array($selected_category_ids)) {
    $selected_category_ids = $selected_category_ids !== '' ? [(int) $selected_category_ids] : [];
}
$selected_category_ids = array_values(array_filter(array_map('intval', $selected_category_ids)));
$updates_category_ids = !empty($selected_category_ids) ? $selected_category_ids : $legacy_category_ids;

$parent_id = yiari_translate_post_id((int) wp_get_post_parent_id($page_id));
$parent_url = $parent_id > 0 ? (get_permalink($parent_id) ?: yiari_get_program_url()) : yiari_get_program_url();
$default_focus_url = $parent_url !== '' ? $parent_url . '#program-focus' : yiari_get_program_url();

$hero_image = has_post_thumbnail($page_id)
    ? [
        'url' => (string) get_the_post_thumbnail_url($page_id, 'hero-full'),
        'alt' => get_the_title($page_id),
    ]
    : yiari_field('program_child_hero_image', ['url' => $theme_uri . '/assets/img/hero-section.jpg']);
$hero_title = yiari_field('program_child_hero_title', $legacy_title !== '' ? $legacy_title : get_the_title($page_id));
$hero_text = yiari_field('program_child_hero_text', $legacy_description !== '' ? $legacy_description : ($is_english
    ? __('Follow stories, activities, and the latest field updates from this program.', 'yiari')
    : __('Ikuti berbagai cerita, kegiatan, dan perkembangan terbaru dari program yang sedang berjalan di lapangan.', 'yiari')));
$hero_btn1_text = yiari_field('program_child_hero_btn1_text', $is_english ? __('View Program Focus', 'yiari') : __('Lihat Fokus Program', 'yiari'));
$hero_btn1_raw = trim((string) yiari_field('program_child_hero_btn1_url', $default_focus_url));
$hero_btn1_url = $hero_btn1_raw !== '' && str_starts_with($hero_btn1_raw, '#')
    ? $hero_btn1_raw
    : yiari_localize_url($hero_btn1_raw !== '' ? $hero_btn1_raw : $default_focus_url);
$hero_btn2_text = yiari_field('program_child_hero_btn2_text', $is_english ? __('Learn More', 'yiari') : __('Pelajari Lebih Lanjut', 'yiari'));
$hero_btn2_raw = trim((string) yiari_field('program_child_hero_btn2_url', '#program-updates'));
$hero_btn2_url = $hero_btn2_raw !== '' && str_starts_with($hero_btn2_raw, '#')
    ? $hero_btn2_raw
    : yiari_localize_url($hero_btn2_raw !== '' ? $hero_btn2_raw : '#program-updates');

$updates_label = yiari_field('program_child_updates_label', $is_english ? __('FIELD HIGHLIGHTS', 'yiari') : __('SOROTAN LAPANGAN', 'yiari'));
$updates_title = yiari_field('program_child_updates_title', $is_english ? __("Stories and\nLatest\nUpdates", 'yiari') : __("Cerita dan\nPerkembangan\nTerbaru", 'yiari'));
$updates_desc = yiari_field('program_child_updates_desc', $is_english
    ? __('Follow stories, activities, and the latest updates from the program in the field.', 'yiari')
    : __('Ikuti berbagai cerita, kegiatan, dan perkembangan terbaru dari program yang sedang berjalan di lapangan.', 'yiari'));
$updates_count = max(3, min(18, (int) yiari_field('program_child_updates_count', 9)));
$load_more_text = yiari_field('program_child_load_more_text', $is_english ? __('Load More', 'yiari') : __('Muat Lebih Banyak', 'yiari'));

$updates_query_args = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $updates_count,
    'ignore_sticky_posts' => true,
    'orderby' => 'date',
    'order' => 'DESC',
];

if (!empty($updates_category_ids)) {
    $updates_query_args['category__in'] = $updates_category_ids;
}

$updates_query = new WP_Query($updates_query_args);
$updates_posts = $updates_query->posts;
$updates_has_more = $updates_query->max_num_pages > 1;
wp_reset_postdata();
?>

<main class="program-child-page">
  <section class="program-child-hero-wrap">
    <?php
    get_template_part('template-parts/sections/page-hero', null, [
        'id' => 'program-child-top',
        'classes' => 'program-child-hero-section',
        'image' => $hero_image,
        'image_class' => 'program-child-hero-img',
        'image_alt' => $hero_title,
        'overlay_class' => 'program-child-hero-overlay',
        'content_class' => 'program-child-hero-content',
        'copy_class' => 'program-child-hero-copy',
        'title_class' => 'program-child-hero-title',
        'text_class' => 'program-child-hero-text',
        'actions_class' => 'program-child-hero-actions',
        'title' => $hero_title,
        'text' => $hero_text,
        'allow_title_breaks' => true,
        'buttons' => [
            ['text' => $hero_btn1_text, 'url' => $hero_btn1_url, 'class' => 'btn-action btn-action-primary'],
            ['text' => $hero_btn2_text, 'url' => $hero_btn2_url, 'class' => 'btn-action btn-action-outline'],
        ],
    ]);
    ?>
  </section>

  <section class="program-child-updates-section" id="program-updates">
    <div
      class="container"
      x-data="{
        page: 2,
        hasMore: <?php echo $updates_has_more ? 'true' : 'false'; ?>,
        loading: false,
        error: '',
        categoryIds: <?php echo wp_json_encode($updates_category_ids); ?>,
        count: <?php echo (int) $updates_count; ?>,
        async loadMore() {
          if (this.loading || !this.hasMore) return;

          this.loading = true;
          this.error = '';

          try {
            const payload = new URLSearchParams({
              action: 'yiari_load_more_updates',
              nonce: window.yiariUpdates?.nonce || '',
              page: String(this.page),
              count: String(this.count)
            });

            this.categoryIds.forEach((categoryId) => payload.append('category_ids[]', String(categoryId)));

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
      <div class="stats-layout program-child-section-header">
        <div class="stats-intro">
          <?php yiari_section_label($updates_label, true); ?>
          <h2 class="section-heading"><?php echo wp_kses_post(nl2br(esc_html($updates_title))); ?></h2>
        </div>
        <div class="stats-desc">
          <p class="section-description"><?php echo esc_html($updates_desc); ?></p>
        </div>
      </div>

      <?php if (!empty($updates_posts)): ?>
        <div class="program-child-updates-grid" x-ref="grid">
          <?php foreach ($updates_posts as $update_post): ?>
            <?php echo yiari_render_program_child_update_card((int) $update_post->ID); ?>
          <?php endforeach; ?>
        </div>

        <div class="program-child-updates-footer" x-show="hasMore || loading" x-cloak>
          <button type="button" class="btn-action btn-action-outline program-child-updates-more" @click="loadMore()" :disabled="loading">
            <span x-show="!loading"><?php echo esc_html($load_more_text); ?></span>
            <span x-show="loading"><?php esc_html_e('Memuat...', 'yiari'); ?></span>
          </button>
        </div>

        <p class="program-child-updates-error" x-show="error" x-text="error" x-cloak></p>
      <?php else: ?>
        <div class="program-child-updates-empty">
          <p class="program-child-updates-empty-text"><?php echo esc_html($is_english ? __('No stories are available for this program yet.', 'yiari') : __('Belum ada cerita yang tersedia untuk program ini.', 'yiari')); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
