document.addEventListener('alpine:init', () => {
  const FADE_IN_THRESHOLD = 0.1;
  const COUNTER_THRESHOLD = 0.5;
  const COUNTER_DURATION = 1800;
  const APPROACH_IMAGE_FADE_DURATION = 150;
  const STATS_SCROLL_DISTANCE = 276;

  Alpine.data('siteUI', () => ({
    mobileMenuOpen: false,
    mobileTentangOpen: false,
    mobileProgramOpen: false,
    mobileLangOpen: false,
    searchOpen: false,
    scrolled: false,

    init() {
      this.handleScroll();
      window.addEventListener('scroll', () => this.handleScroll(), { passive: true });
      document.addEventListener('click', (event) => this.handleOutsideClick(event));

      this.initLucide();
      this.initFadeIn();
      this.initCounters();
    },

    get navbarStyle() {
      return this.scrolled
        ? 'box-shadow: 0 2px 12px rgba(0,0,0,0.12);'
        : 'box-shadow: 0 1px 4px rgba(0,0,0,0.08);';
    },

    handleScroll() {
      this.scrolled = window.scrollY > 10;
    },

    scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    toggleMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen;
    },

    toggleSearch() {
      this.searchOpen = !this.searchOpen;

      if (this.searchOpen && this.$refs.searchInput) {
        this.$nextTick(() => this.$refs.searchInput.focus());
      }
    },

    handleOutsideClick(event) {
      if (
        this.mobileMenuOpen &&
        this.$refs.mobileMenu &&
        this.$refs.hamburger &&
        !this.$refs.mobileMenu.contains(event.target) &&
        !this.$refs.hamburger.contains(event.target) &&
        !(this.$refs.searchPanel && this.$refs.searchPanel.contains(event.target))
      ) {
        this.mobileMenuOpen = false;
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
      if (window.lucide) {
        window.lucide.createIcons();
      }
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
