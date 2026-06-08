document.addEventListener('alpine:init', () => {
  const FADE_IN_THRESHOLD = 0.1;
  const COUNTER_THRESHOLD = 0.5;
  const COUNTER_DURATION = 1800;
  const APPROACH_IMAGE_FADE_DURATION = 150;

  Alpine.data('siteUI', () => ({
    mobileMenuOpen: false,
    mobileLangOpen: false,
    mobileDropdowns: {},
    searchOpen: false,
    searchQuery: '',
    searchResults: [],
    searchLoading: false,
    searchTouched: false,
    searchDebounceTimer: null,
    searchAbortController: null,
    minSearchChars: Number(window.yiariSearch?.minChars || 3),
    searchStrings: window.yiariSearch?.strings || {},
    scrolled: false,
    statsActivePage: 0,
    statsPageCount: 1,
    statsItemsPerPage: 1,
    statsCanScroll: false,
    statsIsMobile: false,
    timelineActivePage: 0,
    timelinePageCount: 1,
    timelineItemsPerPage: 1,
    timelineCanScroll: false,
    timelineIsMobile: false,

    init() {
      this.handleScroll();
      window.addEventListener('scroll', () => this.handleScroll(), { passive: true });
      window.addEventListener('resize', () => {
        this.updateStatsMetrics();
        this.updateTimelineMetrics();
      }, { passive: true });
      document.addEventListener('click', (event) => this.handleOutsideClick(event));
      window.addEventListener('load', () => this.initLucide(), { once: true });

      this.initLucide();
      this.initFadeIn();
      this.initCounters();
      this.$nextTick(() => {
        this.initStatsCarousel();
        this.initTimelineCarousel();
      });
    },

    get navbarStyle() {
      return this.scrolled
        ? 'box-shadow: 0 2px 12px rgba(0,0,0,0.12);'
        : 'box-shadow: 0 1px 4px rgba(0,0,0,0.08);';
    },

    get minCharsMessage() {
      const template = this.searchStrings.minChars || 'Type at least %d characters.';
      return template.replace('%d', this.minSearchChars);
    },

    handleScroll() {
      this.scrolled = window.scrollY > 10;
    },

    scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    toggleMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen;
      if (this.mobileMenuOpen) {
        this.searchOpen = false;
      }
    },

    toggleSearch() {
      this.searchOpen = !this.searchOpen;
      if (this.searchOpen) {
        this.mobileMenuOpen = false;
        if (this.$refs.searchInput) {
          this.$nextTick(() => this.$refs.searchInput.focus());
        }
      } else {
        this.resetSearchState();
      }
    },

    resetSearchState() {
      this.searchQuery = '';
      this.searchResults = [];
      this.searchLoading = false;
      this.searchTouched = false;
      if (this.searchDebounceTimer) {
        window.clearTimeout(this.searchDebounceTimer);
        this.searchDebounceTimer = null;
      }
      if (this.searchAbortController) {
        this.searchAbortController.abort();
        this.searchAbortController = null;
      }
    },

    handleSearchInput() {
      const query = this.searchQuery.trim();
      this.searchTouched = query.length > 0;

      if (this.searchDebounceTimer) {
        window.clearTimeout(this.searchDebounceTimer);
      }

      if (this.searchAbortController) {
        this.searchAbortController.abort();
        this.searchAbortController = null;
      }

      if (query.length < this.minSearchChars) {
        this.searchLoading = false;
        this.searchResults = [];
        this.refreshSearchIcons();
        return;
      }

      this.searchDebounceTimer = window.setTimeout(() => {
        this.fetchSearchResults(query);
      }, 220);
    },

    async fetchSearchResults(query) {
      this.searchLoading = true;
      this.searchAbortController = new AbortController();

      try {
        const payload = new URLSearchParams({
          action: 'yiari_live_search',
          nonce: window.yiariSearch?.nonce || '',
          query,
        });

        const response = await fetch(window.yiariSearch?.ajaxUrl || '', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          },
          body: payload.toString(),
          signal: this.searchAbortController.signal,
        });

        const data = await response.json();
        this.searchResults = data?.success && Array.isArray(data.data?.items) ? data.data.items : [];
      } catch (error) {
        if (error.name !== 'AbortError') {
          this.searchResults = [];
        }
      } finally {
        this.searchLoading = false;
        this.searchAbortController = null;
        this.$nextTick(() => this.refreshSearchIcons());
      }
    },

    toggleMobileDropdown(key) {
      this.mobileDropdowns[key] = !this.isMobileDropdownOpen(key);
    },

    isMobileDropdownOpen(key) {
      return Boolean(this.mobileDropdowns[key]);
    },

    handleOutsideClick(event) {
      const insideSearch = this.$refs.searchPanel && this.$refs.searchPanel.contains(event.target);
      const insideMenu = this.$refs.mobileMenu && this.$refs.mobileMenu.contains(event.target);
      const insideHamburger = this.$refs.hamburger && this.$refs.hamburger.contains(event.target);
      const searchToggle = event.target.closest('[data-search-toggle]');

      if (this.mobileMenuOpen && !insideMenu && !insideHamburger && !insideSearch) {
        this.mobileMenuOpen = false;
      }

      if (this.searchOpen && !insideSearch && !searchToggle) {
        this.searchOpen = false;
        this.resetSearchState();
      }
    },

    activateApproach(element) {
      if (element.classList.contains('active')) {
        return;
      }

      document.querySelectorAll('.approach-item').forEach((item) => item.classList.remove('active'));
      element.classList.add('active');

      if (this.$refs.approachImg && element.dataset.image) {
        this.$refs.approachImg.style.opacity = '0';
        setTimeout(() => {
          this.$refs.approachImg.src = element.dataset.image;
          this.$refs.approachImg.style.opacity = '1';
        }, APPROACH_IMAGE_FADE_DURATION);
      }
    },

    scrollStats(direction) {
      if (!this.$refs.statsGrid) {
        return;
      }

      const targetPage = direction === 'next'
        ? Math.min(this.statsActivePage + 1, this.statsPageCount - 1)
        : Math.max(this.statsActivePage - 1, 0);

      this.scrollStatsToPage(targetPage);
    },

    scrollStatsToPage(page) {
      if (!this.$refs.statsGrid) {
        return;
      }

      const grid = this.$refs.statsGrid;
      const nextPage = Math.max(0, Math.min(page, this.statsPageCount - 1));
      const card = grid.querySelector('.stat-card');

      if (!card) {
        return;
      }

      const gap = parseFloat(window.getComputedStyle(grid).columnGap || window.getComputedStyle(grid).gap || '0');
      const cardWidth = card.getBoundingClientRect().width;
      const step = this.statsIsMobile ? ((cardWidth + gap) * this.statsItemsPerPage) : grid.clientWidth;

      grid.scrollTo({
        left: nextPage * step,
        behavior: 'smooth'
      });
    },

    handleStatsScroll() {
      if (!this.$refs.statsGrid || !this.statsCanScroll) {
        return;
      }

      const grid = this.$refs.statsGrid;
      const card = grid.querySelector('.stat-card');

      if (!card) {
        return;
      }

      const gap = parseFloat(window.getComputedStyle(grid).columnGap || window.getComputedStyle(grid).gap || '0');
      const cardWidth = card.getBoundingClientRect().width;
      const step = this.statsIsMobile ? ((cardWidth + gap) * this.statsItemsPerPage) : grid.clientWidth;

      this.statsActivePage = Math.max(0, Math.min(this.statsPageCount - 1, Math.round(grid.scrollLeft / Math.max(step, 1))));
    },

    initStatsCarousel() {
      this.updateStatsMetrics();
    },

    updateStatsMetrics() {
      if (!this.$refs.statsGrid) {
        return;
      }

      const grid = this.$refs.statsGrid;
      const cards = Array.from(grid.querySelectorAll('.stat-card'));
      const viewport = window.innerWidth;
      const totalItems = cards.length;

      this.statsIsMobile = viewport <= 768;
      this.statsItemsPerPage = this.statsIsMobile ? 2 : (viewport <= 1024 ? 3 : totalItems);
      this.statsPageCount = Math.max(1, Math.ceil(totalItems / this.statsItemsPerPage));
      this.statsCanScroll = viewport <= 1024 && totalItems > this.statsItemsPerPage;

      if (!this.statsCanScroll) {
        this.statsActivePage = 0;
        grid.scrollTo({ left: 0, behavior: 'auto' });
        return;
      }

      this.handleStatsScroll();
    },

    scrollTimeline(direction) {
      if (!this.$refs.timelineGrid) {
        return;
      }

      const currentPage = this.getNearestTimelinePage();
      const targetPage = direction === 'next'
        ? (currentPage + 1) % this.timelinePageCount
        : (currentPage - 1 + this.timelinePageCount) % this.timelinePageCount;

      this.timelineActivePage = targetPage;
      this.scrollTimelineToPage(targetPage);
    },

    scrollTimelineToPage(page) {
      if (!this.$refs.timelineGrid) {
        return;
      }

      const grid = this.$refs.timelineGrid;
      const nextPage = Math.max(0, Math.min(page, this.timelinePageCount - 1));
      const pageOffsets = this.getTimelinePageOffsets();

      if (!pageOffsets.length) {
        return;
      }

      grid.scrollTo({
        left: pageOffsets[nextPage] ?? 0,
        behavior: 'smooth'
      });
    },

    handleTimelineScroll() {
      if (!this.$refs.timelineGrid || !this.timelineCanScroll) {
        return;
      }

      this.timelineActivePage = this.getNearestTimelinePage();
    },

    initTimelineCarousel() {
      this.updateTimelineMetrics();
    },

    updateTimelineMetrics() {
      if (!this.$refs.timelineGrid) {
        return;
      }

      const grid = this.$refs.timelineGrid;
      const cards = Array.from(grid.querySelectorAll('.about-timeline-item'));
      const viewport = window.innerWidth;
      const totalItems = cards.length;

      this.timelineIsMobile = viewport <= 768;
      this.timelineItemsPerPage = this.timelineIsMobile ? 1 : (viewport <= 1024 ? 2 : 3);
      this.timelinePageCount = Math.max(1, Math.ceil(totalItems / this.timelineItemsPerPage));
      this.timelineCanScroll = totalItems > this.timelineItemsPerPage;

      if (!this.timelineCanScroll) {
        this.timelineActivePage = 0;
        grid.scrollTo({ left: 0, behavior: 'auto' });
        return;
      }

      this.handleTimelineScroll();
    },

    getNearestTimelinePage() {
      if (!this.$refs.timelineGrid) {
        return 0;
      }

      const grid = this.$refs.timelineGrid;
      const pageOffsets = this.getTimelinePageOffsets();

      if (!pageOffsets.length) {
        return 0;
      }

      let nearestPage = 0;
      let nearestDistance = Number.POSITIVE_INFINITY;

      pageOffsets.forEach((offset, index) => {
        const distance = Math.abs(grid.scrollLeft - offset);

        if (distance < nearestDistance) {
          nearestDistance = distance;
          nearestPage = index;
        }
      });

      return Math.max(0, Math.min(this.timelinePageCount - 1, nearestPage));
    },

    getTimelinePageOffsets() {
      if (!this.$refs.timelineGrid) {
        return [];
      }

      const grid = this.$refs.timelineGrid;
      const cards = Array.from(grid.querySelectorAll('.about-timeline-item'));

      if (!cards.length) {
        return [];
      }

      const maxScroll = Math.max(0, grid.scrollWidth - grid.clientWidth);

      return cards
        .filter((_, index) => index % this.timelineItemsPerPage === 0)
        .map((card) => Math.min(card.offsetLeft, maxScroll));
    },

    initLucide() {
      if (window.lucide && typeof window.lucide.createIcons === 'function') {
        window.lucide.createIcons();
      }
    },

    refreshSearchIcons() {
      this.initLucide();
    },

    initFadeIn() {
      const fadeElements = document.querySelectorAll('.fade-in');

      if (!('IntersectionObserver' in window)) {
        fadeElements.forEach((element) => element.classList.add('visible'));
        return;
      }

      const fadeObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            fadeObserver.unobserve(entry.target);
          }
        });
      }, { threshold: FADE_IN_THRESHOLD });

      fadeElements.forEach((element) => fadeObserver.observe(element));
    },

    initCounters() {
      const statNumbers = document.querySelectorAll('.stat-number[data-target]');

      const animateStat = (element) => {
        if (element.dataset.animated === 'true') {
          return;
        }

        element.dataset.animated = 'true';
        const target = parseFloat(element.dataset.target);
        const suffix = element.dataset.suffix || '';
        const prefix = element.dataset.prefix || '';
        const startTime = performance.now();

        const update = (now) => {
          const progress = Math.min((now - startTime) / COUNTER_DURATION, 1);
          const eased = 1 - Math.pow(1 - progress, 3);
          const current = Math.round(eased * target);
          element.innerHTML = `${prefix}${current.toLocaleString()}${suffix ? `<span class="stat-plus">${suffix}</span>` : ''}`;

          if (progress < 1) {
            requestAnimationFrame(update);
          }
        };

        requestAnimationFrame(update);
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            animateStat(entry.target);
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: COUNTER_THRESHOLD });

      statNumbers.forEach((stat) => observer.observe(stat));
    }
  }));

  Alpine.data('searchPage', (initialState = {}, filterGroups = []) => ({
    query: initialState.query || '',
    page: Number(initialState.page || 1),
    total: Number(initialState.total || 0),
    totalPages: Number(initialState.totalPages || 1),
    filters: {
      contentTypes: Array.isArray(initialState.filters?.contentTypes) ? [...initialState.filters.contentTypes] : [],
      locations: Array.isArray(initialState.filters?.locations) ? initialState.filters.locations.map(String) : [],
      programs: Array.isArray(initialState.filters?.programs) ? initialState.filters.programs.map(String) : [],
    },
    draftFilters: {
      contentTypes: Array.isArray(initialState.filters?.contentTypes) ? [...initialState.filters.contentTypes] : [],
      locations: Array.isArray(initialState.filters?.locations) ? initialState.filters.locations.map(String) : [],
      programs: Array.isArray(initialState.filters?.programs) ? initialState.filters.programs.map(String) : [],
    },
    filterGroups,
    filtersOpen: false,
    loading: false,
    abortController: null,
    accordionOpen: {
      contentTypes: true,
      locations: false,
      programs: false,
    },

    init() {
      this.syncResultsHtml(initialState.html || '');
      this.refreshIcons();
    },

    get resultLabel() {
      const template = window.yiariSearchPage?.strings?.resultsLabel || 'Showing %d Results';
      return template.replace('%d', String(this.total));
    },

    get pageItems() {
      if (this.totalPages <= 1) {
        return [];
      }

      const items = [];
      const pages = new Set([1, this.totalPages, this.page - 1, this.page, this.page + 1]);
      const visiblePages = Array.from(pages)
        .filter((page) => page >= 1 && page <= this.totalPages)
        .sort((left, right) => left - right);

      visiblePages.forEach((value, index) => {
        const previous = visiblePages[index - 1];
        if (previous && value - previous > 1) {
          items.push({ type: 'ellipsis', key: `ellipsis-${previous}-${value}` });
        }

        items.push({ type: 'page', value, key: `page-${value}` });
      });

      return items;
    },

    handleQueryInput() {
      this.fetchResults(1);
    },

    handleQuerySubmit() {
      this.fetchResults(1);
    },

    clearQuery() {
      this.query = '';
      this.fetchResults(1);
    },

    openFilters() {
      this.filtersOpen = true;
      document.documentElement.classList.add('search-modal-open');
      document.body.classList.add('search-modal-open');
    },

    closeFilters() {
      this.filtersOpen = false;
      document.documentElement.classList.remove('search-modal-open');
      document.body.classList.remove('search-modal-open');
    },

    toggleAccordion(key) {
      this.accordionOpen[key] = !this.accordionOpen[key];
    },

    isAccordionOpen(key) {
      return Boolean(this.accordionOpen[key]);
    },

    applyFilters() {
      this.filters = {
        contentTypes: [...this.draftFilters.contentTypes],
        locations: [...this.draftFilters.locations],
        programs: [...this.draftFilters.programs],
      };

      this.closeFilters();
      this.fetchResults(1);
    },

    resetFilters() {
      this.filters = { contentTypes: [], locations: [], programs: [] };
      this.draftFilters = { contentTypes: [], locations: [], programs: [] };
      this.closeFilters();
      this.fetchResults(1);
    },

    goToPage(page) {
      const nextPage = Number(page || 1);
      if (nextPage < 1 || nextPage > this.totalPages || nextPage === this.page) {
        return;
      }

      this.fetchResults(nextPage);
    },

    syncResultsHtml(html) {
      if (this.$refs.resultsGrid) {
        this.$refs.resultsGrid.innerHTML = html || '';
      }

      this.$nextTick(() => this.refreshIcons());
    },

    refreshIcons() {
      if (window.lucide?.createIcons) {
        window.lucide.createIcons();
      }
    },

    updateUrl() {
      const url = new URL(window.location.href);

      if (this.query.trim() !== '') {
        url.searchParams.set('s', this.query.trim());
      } else {
        url.searchParams.delete('s');
      }

      [
        ['types', this.filters.contentTypes],
        ['locations', this.filters.locations],
        ['programs', this.filters.programs],
      ].forEach(([key, values]) => {
        if (values.length) {
          url.searchParams.set(key, values.join(','));
        } else {
          url.searchParams.delete(key);
        }
      });

      if (this.page > 1) {
        url.searchParams.set('search_page', String(this.page));
      } else {
        url.searchParams.delete('search_page');
      }

      window.history.replaceState({}, '', url.toString());
    },

    async fetchResults(nextPage = 1) {
      if (this.abortController) {
        this.abortController.abort();
      }

      this.loading = true;
      this.abortController = new AbortController();

      const payload = new URLSearchParams({
        action: 'yiari_search_results',
        nonce: window.yiariSearchPage?.nonce || '',
        s: this.query.trim(),
        search_page: String(nextPage),
      });

      this.filters.contentTypes.forEach((value) => payload.append('types[]', value));
      this.filters.locations.forEach((value) => payload.append('locations[]', value));
      this.filters.programs.forEach((value) => payload.append('programs[]', value));

      try {
        const response = await fetch(window.yiariSearchPage?.ajaxUrl || '', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          },
          body: payload.toString(),
          signal: this.abortController.signal,
        });

        const data = await response.json();
        if (!data?.success) {
          throw new Error('Invalid response');
        }

        this.page = Number(data.data?.page || nextPage);
        this.total = Number(data.data?.total || 0);
        this.totalPages = Number(data.data?.totalPages || 1);
        this.syncResultsHtml(data.data?.html || '');
        this.updateUrl();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } catch (error) {
        if (error.name !== 'AbortError') {
          this.page = 1;
          this.total = 0;
          this.totalPages = 1;
          this.syncResultsHtml('');
        }
      } finally {
        this.loading = false;
        this.abortController = null;
      }
    },
  }));
});

document.addEventListener('DOMContentLoaded', () => {
  const journalPage = document.querySelector('.journal-page');
  if (journalPage) {
    window.setTimeout(() => {
      const firstJournalTrigger = journalPage.querySelector('.jurnal-accordion-trigger[aria-expanded="false"]');
      if (firstJournalTrigger) {
        firstJournalTrigger.click();
      }
    }, 180);
  }

  const loadMoreButton = document.querySelector('[data-load-more-updates]');
  const updatesGrid = document.querySelector('[data-updates-grid]');

  if (!loadMoreButton || !updatesGrid) {
    return;
  }

  const defaultLabel = loadMoreButton.textContent.trim();

  loadMoreButton.addEventListener('click', async () => {
    if (loadMoreButton.disabled) {
      return;
    }

    const categoryId = loadMoreButton.dataset.categoryId || '0';
    const page = loadMoreButton.dataset.page || '2';
    const count = loadMoreButton.dataset.count || '9';

    loadMoreButton.disabled = true;
    loadMoreButton.textContent = window.yiariUpdates?.strings?.loading || defaultLabel;

    try {
      const payload = new URLSearchParams({
        action: 'yiari_load_more_updates',
        nonce: window.yiariUpdates?.nonce || '',
        category_id: categoryId,
        page,
        count,
      });

      const response = await fetch(window.yiariUpdates?.ajaxUrl || '', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        },
        body: payload.toString(),
      });

      const data = await response.json();

      if (!data?.success || !data?.data?.html) {
        throw new Error('Invalid response');
      }

      const fragment = document.createRange().createContextualFragment(data.data.html);
      const newCards = Array.from(fragment.querySelectorAll('.detail-landscape-update-card'));

      newCards.forEach((card, index) => {
        card.classList.add('is-entering');
        card.style.setProperty('--enter-delay', `${index * 70}ms`);
      });

      updatesGrid.appendChild(fragment);

      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          newCards.forEach((card) => card.classList.add('is-visible'));
        });
      });

      loadMoreButton.dataset.page = String(data.data.nextPage || Number(page) + 1);

      if (window.lucide && typeof window.lucide.createIcons === 'function') {
        window.lucide.createIcons();
      }

      if (!data.data.hasMore) {
        loadMoreButton.closest('.detail-landscape-updates-footer')?.remove();
        return;
      }
    } catch (error) {
      loadMoreButton.textContent = window.yiariUpdates?.strings?.error || defaultLabel;
      window.setTimeout(() => {
        loadMoreButton.textContent = defaultLabel;
      }, 1800);
      loadMoreButton.disabled = false;
      return;
    }

    loadMoreButton.disabled = false;
    loadMoreButton.textContent = defaultLabel;
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const donationForm = document.querySelector('#wp-midtrans-donation-form');
  if (!donationForm) {
    return;
  }

  const currencyInputs = donationForm.querySelectorAll('input[name="currency"]');
  const idrPrefix = donationForm.querySelector('.donation-input-prefix-idr');
  const usdPrefix = donationForm.querySelector('.donation-input-prefix-usd');

  const syncDonationCurrencyState = () => {
    const activeCurrency = donationForm.querySelector('input[name="currency"]:checked')?.value || 'IDR';

    if (idrPrefix && usdPrefix) {
      if (activeCurrency === 'USD') {
        idrPrefix.style.display = 'none';
        usdPrefix.style.display = 'inline-flex';
      } else {
        usdPrefix.style.display = 'none';
        idrPrefix.style.display = 'inline-flex';
      }
    }
  };

  currencyInputs.forEach((input) => {
    input.addEventListener('change', syncDonationCurrencyState);
  });

  syncDonationCurrencyState();
});
