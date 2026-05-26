<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri() . '/assets/img/favicon/favicon.svg'); ?>" />
  <link rel="icon" type="image/x-icon" href="<?php echo esc_url(get_template_directory_uri() . '/assets/img/favicon/favicon.ico'); ?>" />
  <link rel="icon" type="image/png" sizes="96x96" href="<?php echo esc_url(get_template_directory_uri() . '/assets/img/favicon/favicon-96x96.png'); ?>" />
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_template_directory_uri() . '/assets/img/favicon/apple-touch-icon.png'); ?>" />
  <link rel="manifest" href="<?php echo esc_url(get_template_directory_uri() . '/assets/img/favicon/site.webmanifest'); ?>" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> x-data="siteUI()" x-init="init()">
<?php wp_body_open(); ?>
<?php $current_language = yiari_get_current_language_item(); ?>

<nav class="navbar" id="navbar" :style="navbarStyle">
  <div class="nav-container">
    <div class="nav-left">
      <a href="<?php echo esc_url(yiari_home_url()); ?>" class="navbar-logo" id="navbar-logo">
        <?php if (has_custom_logo()): ?>
          <?php
          $logo_id = get_theme_mod('custom_logo');
          echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'logo-img', 'alt' => get_bloginfo('name')]);
          ?>
        <?php else: ?>
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo-yiari.svg'); ?>" alt="<?php bloginfo('name'); ?>" class="logo-img" />
        <?php endif; ?>
      </a>

      <div class="nav-divider"></div>

      <div class="desktop-nav" id="desktop-nav">
        <?php yiari_render_primary_desktop_menu(); ?>
      </div>
    </div>

    <div class="nav-actions desktop-nav" id="nav-actions">
      <button class="nav-icon-btn" aria-label="<?php echo esc_attr__('Search', 'yiari'); ?>" data-search-toggle id="search-btn" @click.prevent="toggleSearch()">
        <i data-lucide="search" class="icon-sm"></i>
        <span class="nav-action-text"><?php echo esc_html__('Cari', 'yiari'); ?></span>
      </button>

      <div class="nav-item-dropdown">
        <button class="nav-icon-btn lang-btn" id="lang-btn" type="button">
          <i data-lucide="globe" class="icon-sm"></i>
          <span class="nav-action-text"><?php echo esc_html($current_language['slug']); ?> <i data-lucide="chevron-down" class="chevron lang-chevron"></i></span>
        </button>
        <?php yiari_render_language_switcher(false); ?>
      </div>

      <a href="<?php echo esc_url(yiari_fragment_url('donate')); ?>" class="btn-donate" id="donate-nav-btn"><?php echo esc_html__('Donasi', 'yiari'); ?></a>
    </div>

    <button class="hamburger-btn" id="hamburger" x-ref="hamburger" aria-label="<?php echo esc_attr__('Menu', 'yiari'); ?>" type="button" @click="toggleMenu()">
      <span :class="{ 'hamburger-line-top': mobileMenuOpen }"></span>
      <span :class="{ 'hamburger-line-middle': mobileMenuOpen }"></span>
      <span :class="{ 'hamburger-line-bottom': mobileMenuOpen }"></span>
    </button>
  </div>

  <div class="search-panel" id="searchPanel" x-ref="searchPanel" :class="{ open: searchOpen }">
    <div class="container search-panel-shell">
      <div class="search-panel-content">
        <i data-lucide="search" class="icon-search-panel"></i>
        <input
          type="text"
          :placeholder="searchStrings.searchPlaceholder"
          class="search-input"
          id="searchInput"
          x-ref="searchInput"
          x-model="searchQuery"
          @input="handleSearchInput()"
        />
        <button class="close-search-btn" id="closeSearch" type="button" data-search-toggle @click="toggleSearch()">✕</button>
      </div>

      <div class="search-results" x-show="searchOpen" x-transition.opacity.duration.150ms>
        <div class="search-results-status" x-show="!searchQuery.trim()" x-text="searchStrings.idle"></div>
        <div class="search-results-status" x-show="searchQuery.trim().length > 0 && searchQuery.trim().length < minSearchChars" x-text="minCharsMessage"></div>
        <div class="search-results-status" x-show="searchLoading" x-text="searchStrings.loading"></div>
        <div class="search-results-status" x-show="!searchLoading && searchQuery.trim().length >= minSearchChars && !searchResults.length && searchTouched" x-text="searchStrings.empty"></div>

        <div class="search-results-list" x-show="searchResults.length">
          <template x-for="item in searchResults" :key="item.id">
            <a class="search-result-card" :href="item.url">
              <div class="search-result-thumb">
                <img :src="item.image" :alt="item.title" loading="lazy" />
              </div>
              <div class="search-result-body">
                <span class="search-result-type" x-text="item.type"></span>
                <div class="search-result-title" x-text="item.title"></div>
              </div>
              <i data-lucide="arrow-right" class="search-result-icon"></i>
            </a>
          </template>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="mobile-menu" id="mobileMenu" x-ref="mobileMenu" :class="{ open: mobileMenuOpen }">
  <div class="mobile-actions">
    <button class="nav-icon-btn mobile-action-btn" id="mobile-search-btn" type="button" data-search-toggle @click.prevent="toggleSearch()">
      <i data-lucide="search" class="icon-sm"></i>
      <span class="nav-action-text"><?php echo esc_html__('Cari', 'yiari'); ?></span>
    </button>

    <div class="mobile-dropdown mobile-dropdown-half">
      <button
        class="nav-icon-btn lang-btn mobile-dropdown-toggle mobile-action-btn"
        id="mobile-lang-btn"
        type="button"
        @click.prevent="mobileLangOpen = !mobileLangOpen"
        :class="{ active: mobileLangOpen }"
      >
        <i data-lucide="globe" class="icon-sm"></i>
        <span class="nav-action-text"><?php echo esc_html($current_language['slug']); ?> <i data-lucide="chevron-down" class="chevron lang-chevron"></i></span>
      </button>
      <?php yiari_render_language_switcher(true); ?>
    </div>
  </div>

  <?php yiari_render_primary_mobile_menu(); ?>

  <div class="mobile-menu-divider">
    <a href="<?php echo esc_url(yiari_fragment_url('donate')); ?>" class="btn-primary mobile-donate-btn"><?php echo esc_html__('Donasi', 'yiari'); ?></a>
  </div>
</div>

<main>
