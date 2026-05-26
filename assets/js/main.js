document.addEventListener('alpine:init', () => {
  const FADE_IN_THRESHOLD = 0.1;
  const COUNTER_THRESHOLD = 0.5;
  const COUNTER_DURATION = 1800;
  const APPROACH_IMAGE_FADE_DURATION = 150;
  const STATS_SCROLL_DISTANCE = 276;

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

    init() {
      this.handleScroll();
      window.addEventListener('scroll', () => this.handleScroll(), { passive: true });
      document.addEventListener('click', (event) => this.handleOutsideClick(event));
      window.addEventListener('load', () => this.initLucide(), { once: true });

      this.initLucide();
      this.initFadeIn();
      this.initCounters();
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

      this.$refs.statsGrid.scrollBy({
        left: direction === 'next' ? STATS_SCROLL_DISTANCE : -STATS_SCROLL_DISTANCE,
        behavior: 'smooth'
      });
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
});
