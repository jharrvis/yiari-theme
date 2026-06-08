<?php
/**
 * Search results template.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/global/header');

$search_state = yiari_get_search_request_state();
$search_results = yiari_get_search_results($search_state);
$filter_groups = yiari_get_search_filter_groups();

$is_english = yiari_post_is_english();
$page_title = $is_english ? __('Search Results', 'yiari') : __('Hasil Pencarian', 'yiari');
$results_template = $is_english ? __('Showing %d Results', 'yiari') : __('Menampilkan %d Hasil', 'yiari');
$filter_title = $is_english ? __('Filter By', 'yiari') : __('Filter berdasarkan', 'yiari');
$search_placeholder = $is_english ? __('Search anything...', 'yiari') : __('Cari apa saja...', 'yiari');
$clear_label = $is_english ? __('Clear search', 'yiari') : __('Hapus pencarian', 'yiari');
$filter_button = $is_english ? __('Filter By', 'yiari') : __('Filter berdasarkan', 'yiari');
$apply_label = $is_english ? __('Apply Filter', 'yiari') : __('Terapkan Filter', 'yiari');
$reset_label = $is_english ? __('Reset Filter', 'yiari') : __('Hapus Filter', 'yiari');
$empty_text = $is_english ? __('No results match your keyword or selected filters.', 'yiari') : __('Tidak ada hasil yang cocok dengan kata kunci atau filter yang dipilih.', 'yiari');
$loading_text = $is_english ? __('Loading results...', 'yiari') : __('Memuat hasil...', 'yiari');
$pagination_label = $is_english ? __('Pagination', 'yiari') : __('Pagination', 'yiari');
$previous_label = $is_english ? __('Previous', 'yiari') : __('Sebelumnya', 'yiari');
$next_label = $is_english ? __('Next', 'yiari') : __('Selanjutnya', 'yiari');
$close_filter_label = $is_english ? __('Close filter', 'yiari') : __('Tutup filter', 'yiari');

$initial_payload = [
    'query' => $search_state['query'],
    'page' => $search_results['page'],
    'total' => $search_results['total'],
    'totalPages' => $search_results['totalPages'],
    'html' => $search_results['html'],
    'filters' => [
        'contentTypes' => $search_state['contentTypes'],
        'locations' => $search_state['locations'],
        'programs' => $search_state['programs'],
    ],
];

$render_filter_options = static function (array $group, string $model, string $prefix): void {
    foreach ($group['options'] as $index => $option) {
        $input_id = $prefix . '-' . $group['key'] . '-' . $index;
        ?>
        <label class="search-filter-option" for="<?php echo esc_attr($input_id); ?>">
          <input
            id="<?php echo esc_attr($input_id); ?>"
            type="checkbox"
            class="search-filter-checkbox"
            value="<?php echo esc_attr((string) $option['value']); ?>"
            x-model="<?php echo esc_attr($model); ?>"
          />
          <span><?php echo esc_html($option['label']); ?></span>
        </label>
        <?php
    }
};
?>

<main class="search-page">
  <section
    class="search-page-section"
    x-data="searchPage(<?php echo esc_attr(wp_json_encode($initial_payload)); ?>, <?php echo esc_attr(wp_json_encode($filter_groups)); ?>)"
    x-init="init()"
  >
    <div class="container search-page-container">
      <header class="search-page-header">
        <h1 class="search-page-title"><?php echo esc_html($page_title); ?></h1>

        <form class="search-page-form" role="search" @submit.prevent="handleQuerySubmit()">
          <div class="search-page-input-wrap">
            <i data-lucide="search" class="search-page-input-icon"></i>
            <input
              type="search"
              class="search-page-input"
              name="s"
              x-model="query"
              @input.debounce.300ms="handleQueryInput()"
              placeholder="<?php echo esc_attr($search_placeholder); ?>"
              autocomplete="off"
            />
            <button type="button" class="search-page-clear" :class="{ 'is-visible': query.length > 0 }" @click="clearQuery()" aria-label="<?php echo esc_attr($clear_label); ?>">
              <i data-lucide="x-circle"></i>
            </button>
          </div>
        </form>

        <div class="search-page-toolbar">
          <p class="search-page-count" x-text="resultLabel"><?php echo esc_html(sprintf($results_template, (int) $search_results['total'])); ?></p>

          <button type="button" class="search-page-filter-trigger" @click="openFilters()">
            <i data-lucide="sliders-horizontal"></i>
            <span><?php echo esc_html($filter_button); ?></span>
          </button>
        </div>
      </header>

      <div class="search-page-layout">
        <aside class="search-page-sidebar" aria-label="<?php echo esc_attr($filter_title); ?>">
          <div class="search-page-sidebar-inner">
            <h2 class="search-page-filter-title"><?php echo esc_html($filter_title); ?></h2>

            <?php foreach ($filter_groups as $group): ?>
              <section class="search-filter-group">
                <h3 class="search-filter-group-title"><?php echo esc_html($group['label']); ?></h3>
                <div class="search-filter-options">
                  <?php $render_filter_options($group, 'draftFilters.' . $group['key'], 'desktop'); ?>
                </div>
              </section>
            <?php endforeach; ?>

            <div class="search-page-sidebar-actions">
              <button type="button" class="btn-action btn-action-primary search-page-action-btn" @click="applyFilters()"><?php echo esc_html($apply_label); ?></button>
              <button type="button" class="btn-action btn-action-outline search-page-action-btn search-page-action-btn-outline" @click="resetFilters()"><?php echo esc_html($reset_label); ?></button>
            </div>
          </div>
        </aside>

        <div class="search-page-results-shell">
          <div class="search-page-loading" x-show="loading" x-cloak>
            <span class="search-page-loading-dot"></span>
            <span><?php echo esc_html($loading_text); ?></span>
          </div>

          <div class="search-page-empty" x-show="!loading && total === 0" x-cloak>
            <p class="search-page-empty-text"><?php echo esc_html($empty_text); ?></p>
          </div>

          <div class="search-page-grid" x-ref="resultsGrid"><?php echo $search_results['html']; ?></div>

          <nav class="search-page-pagination" x-show="totalPages > 1" x-cloak aria-label="<?php echo esc_attr($pagination_label); ?>">
            <button type="button" class="search-page-page-link search-page-page-link-arrow" @click="goToPage(page - 1)" :disabled="page <= 1">
              <i data-lucide="chevron-left"></i>
              <span><?php echo esc_html($previous_label); ?></span>
            </button>

            <div class="search-page-page-list">
              <template x-for="item in pageItems" :key="item.key">
                <template x-if="item.type === 'page'">
                  <button type="button" class="search-page-page-pill" :class="{ 'is-active': item.value === page }" @click="goToPage(item.value)" x-text="item.value"></button>
                </template>
                <template x-if="item.type === 'ellipsis'">
                  <span class="search-page-page-ellipsis">…</span>
                </template>
              </template>
            </div>

            <button type="button" class="search-page-page-link search-page-page-link-arrow search-page-page-link-next" @click="goToPage(page + 1)" :disabled="page >= totalPages">
              <span><?php echo esc_html($next_label); ?></span>
              <i data-lucide="chevron-right"></i>
            </button>
          </nav>
        </div>
      </div>
    </div>

    <div class="search-filter-modal-backdrop" x-show="filtersOpen" x-cloak @click="closeFilters()"></div>

    <div class="search-filter-modal-wrap" x-show="filtersOpen" x-cloak>
      <div class="search-filter-modal" @click.stop>
        <div class="search-filter-modal-head">
          <h2 class="search-filter-modal-title"><?php echo esc_html($filter_title); ?></h2>
          <button type="button" class="search-filter-modal-close" @click="closeFilters()" aria-label="<?php echo esc_attr($close_filter_label); ?>">
            <i data-lucide="x"></i>
          </button>
        </div>

        <div class="search-filter-modal-body">
          <?php foreach ($filter_groups as $index => $group): ?>
            <section class="search-filter-accordion" :class="{ 'is-open': isAccordionOpen('<?php echo esc_attr($group['key']); ?>') }">
              <button type="button" class="search-filter-accordion-toggle" @click="toggleAccordion('<?php echo esc_attr($group['key']); ?>')">
                <span><?php echo esc_html($group['label']); ?></span>
                <i data-lucide="chevron-down"></i>
              </button>
              <div class="search-filter-accordion-panel" x-show="isAccordionOpen('<?php echo esc_attr($group['key']); ?>')">
                <div class="search-filter-options search-filter-options-modal">
                  <?php $render_filter_options($group, 'draftFilters.' . $group['key'], 'modal'); ?>
                </div>
              </div>
            </section>
          <?php endforeach; ?>
        </div>

        <div class="search-filter-modal-actions">
          <button type="button" class="btn-action btn-action-primary search-page-action-btn" @click="applyFilters()"><?php echo esc_html($apply_label); ?></button>
          <button type="button" class="btn-action btn-action-outline search-page-action-btn search-page-action-btn-outline" @click="resetFilters()"><?php echo esc_html($reset_label); ?></button>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
get_template_part('template-parts/global/footer');
