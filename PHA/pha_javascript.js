/**
 * PHA Website - Main JavaScript
 * assets/js/navigation.js & main.js combined
 */

(function() {
    'use strict';

    // ============================================
    // MOBILE MENU TOGGLE
    // ============================================
    
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNavigation = document.querySelector('.main-navigation');
    
    if (mobileMenuToggle && mainNavigation) {
        mobileMenuToggle.addEventListener('click', function() {
            mainNavigation.classList.toggle('active');
            const expanded = mainNavigation.classList.contains('active');
            this.setAttribute('aria-expanded', expanded);
            
            // Toggle icon
            const icon = this.querySelector('i') || this;
            if (expanded) {
                icon.textContent = '✕';
            } else {
                icon.textContent = '☰';
            }
        });
    }
    
    // ============================================
    // ACCESSIBLE DROPDOWN MENUS
    // ============================================
    
    const menuItems = document.querySelectorAll('.main-navigation li');
    
    menuItems.forEach(item => {
        const link = item.querySelector('a');
        const submenu = item.querySelector('.sub-menu');
        
        if (submenu) {
            // Add aria attributes
            link.setAttribute('aria-haspopup', 'true');
            link.setAttribute('aria-expanded', 'false');
            
            // Keyboard navigation
            link.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !expanded);
                    submenu.style.display = expanded ? 'none' : 'flex';
                }
            });
            
            // Mouse events
            item.addEventListener('mouseenter', function() {
                link.setAttribute('aria-expanded', 'true');
            });
            
            item.addEventListener('mouseleave', function() {
                link.setAttribute('aria-expanded', 'false');
            });
        }
    });
    
    // ============================================
    // SEARCH FUNCTIONALITY
    // ============================================
    
    const searchForm = document.querySelector('.hero-search form, .search-form');
    const searchInput = document.querySelector('.hero-search input[type="search"], .search-form input[type="search"]');
    const searchResults = document.createElement('div');
    searchResults.className = 'search-results';
    
    if (searchForm && searchInput) {
        searchForm.insertAdjacentElement('afterend', searchResults);
        
        // AJAX search
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 3) {
                searchResults.innerHTML = '';
                searchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
        
        function performSearch(query) {
            searchResults.innerHTML = '<div class="spinner"></div>';
            searchResults.style.display = 'block';
            
            fetch(phaData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'pha_search',
                    nonce: phaData.searchNonce,
                    search: query
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    let html = '<ul class="search-results-list">';
                    data.data.forEach(item => {
                        html += `
                            <li>
                                <a href="${item.link}">
                                    <strong>${item.title}</strong>
                                    <span class="type">${item.type}</span>
                                    <p>${item.excerpt}</p>
                                </a>
                            </li>
                        `;
                    });
                    html += '</ul>';
                    searchResults.innerHTML = html;
                } else {
                    searchResults.innerHTML = '<p class="no-results">No results found</p>';
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResults.innerHTML = '<p class="error">Search failed. Please try again.</p>';
            });
        }
        
        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchForm.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }
    
    // ============================================
    // CONTACT FORM SUBMISSION
    // ============================================
    
    const contactForm = document.querySelector('#contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'pha_contact_form');
            formData.append('nonce', phaData.contactNonce);
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            submitButton.textContent = 'Sending...';
            submitButton.disabled = true;
            
            // Clear previous messages
            const existingMessage = this.querySelector('.form-message');
            if (existingMessage) {
                existingMessage.remove();
            }
            
            fetch(phaData.ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `form-message alert ${data.success ? 'alert-success' : 'alert-danger'}`;
                messageDiv.textContent = data.data.message;
                this.insertBefore(messageDiv, this.firstChild);
                
                if (data.success) {
                    this.reset();
                }
                
                submitButton.textContent = originalButtonText;
                submitButton.disabled = false;
            })
            .catch(error => {
                console.error('Form submission error:', error);
                const messageDiv = document.createElement('div');
                messageDiv.className = 'form-message alert alert-danger';
                messageDiv.textContent = 'An error occurred. Please try again.';
                this.insertBefore(messageDiv, this.firstChild);
                
                submitButton.textContent = originalButtonText;
                submitButton.disabled = false;
            });
        });
    }
    
    // ============================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ============================================
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Set focus for accessibility
                target.setAttribute('tabindex', '-1');
                target.focus();
            }
        });
    });
    
    // ============================================
    // STICKY HEADER
    // ============================================
    
    const siteHeader = document.querySelector('.site-header');
    let lastScrollTop = 0;
    
    if (siteHeader) {
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 100) {
                siteHeader.classList.add('scrolled');
            } else {
                siteHeader.classList.remove('scrolled');
            }
            
            // Hide header on scroll down, show on scroll up
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                siteHeader.style.transform = 'translateY(-100%)';
            } else {
                siteHeader.style.transform = 'translateY(0)';
            }
            
            lastScrollTop = scrollTop;
        });
    }
    
    // ============================================
    // IMAGE LAZY LOADING (for older browsers)
    // ============================================
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.getAttribute('data-src');
                    if (src) {
                        img.src = src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // ============================================
    // ACCORDION FUNCTIONALITY
    // ============================================
    
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const accordionItem = this.parentElement;
            const accordionContent = this.nextElementSibling;
            const isActive = accordionItem.classList.contains('active');
            
            // Close all accordion items
            document.querySelectorAll('.accordion-item').forEach(item => {
                item.classList.remove('active');
                item.querySelector('.accordion-content').style.maxHeight = null;
            });
            
            // Open clicked item if it wasn't active
            if (!isActive) {
                accordionItem.classList.add('active');
                accordionContent.style.maxHeight = accordionContent.scrollHeight + 'px';
            }
            
            // Update ARIA attributes
            const expanded = !isActive;
            this.setAttribute('aria-expanded', expanded);
        });
    });
    
    // ============================================
    // TABS FUNCTIONALITY
    // ============================================
    
    const tabButtons = document.querySelectorAll('.tab-button');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            const tabContainer = this.closest('.tabs-container');
            
            // Remove active class from all buttons and panels
            tabContainer.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            });
            
            tabContainer.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
                panel.setAttribute('hidden', '');
            });
            
            // Add active class to clicked button and corresponding panel
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            
            const targetPanel = tabContainer.querySelector(`#${tabId}`);
            if (targetPanel) {
                targetPanel.classList.add('active');
                targetPanel.removeAttribute('hidden');
            }
        });
        
        // Keyboard navigation for tabs
        button.addEventListener('keydown', function(e) {
            const tabList = this.parentElement;
            const tabs = Array.from(tabList.querySelectorAll('.tab-button'));
            const currentIndex = tabs.indexOf(this);
            
            let nextIndex;
            
            if (e.key === 'ArrowRight') {
                nextIndex = currentIndex + 1 >= tabs.length ? 0 : currentIndex + 1;
            } else if (e.key === 'ArrowLeft') {
                nextIndex = currentIndex - 1 < 0 ? tabs.length - 1 : currentIndex - 1;
            } else if (e.key === 'Home') {
                nextIndex = 0;
            } else if (e.key === 'End') {
                nextIndex = tabs.length - 1;
            }
            
            if (nextIndex !== undefined) {
                e.preventDefault();
                tabs[nextIndex].focus();
                tabs[nextIndex].click();
            }
        });
    });
    
    // ============================================
    // MODAL FUNCTIONALITY
    // ============================================
    
    const modalTriggers = document.querySelectorAll('[data-modal]');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            const modal = document.querySelector(`#${modalId}`);
            
            if (modal) {
                openModal(modal);
            }
        });
    });
    
    function openModal(modal) {
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        
        // Set focus to modal
        const firstFocusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (firstFocusable) {
            firstFocusable.focus();
        }
        
        // Trap focus within modal
        trapFocus(modal);
    }
    
    function closeModal(modal) {
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }
    
    // Close modal on close button click
    document.querySelectorAll('.modal-close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            closeModal(modal);
        });
    });
    
    // Close modal on backdrop click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this);
            }
        });
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal.active');
            if (activeModal) {
                closeModal(activeModal);
            }
        }
    });
    
    // ============================================
    // FOCUS TRAP FOR MODALS
    // ============================================
    
    function trapFocus(element) {
        const focusableElements = element.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];
        
        element.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstFocusable) {
                        e.preventDefault();
                        lastFocusable.focus();
                    }
                } else {
                    if (document.activeElement === lastFocusable) {
                        e.preventDefault();
                        firstFocusable.focus();
                    }
                }
            }
        });
    }
    
    // ============================================
    // FORM VALIDATION
    // ============================================
    
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            this.querySelectorAll('.form-error').forEach(error => error.remove());
            this.querySelectorAll('.is-invalid').forEach(field => field.classList.remove('is-invalid'));
            
            // Validate required fields
            this.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    showFieldError(field, 'This field is required');
                }
            });
            
            // Validate email fields
            this.querySelectorAll('input[type="email"]').forEach(field => {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (field.value && !emailRegex.test(field.value)) {
                    isValid = false;
                    showFieldError(field, 'Please enter a valid email address');
                }
            });
            
            // Validate phone fields
            this.querySelectorAll('input[type="tel"]').forEach(field => {
                const phoneRegex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
                if (field.value && !phoneRegex.test(field.value)) {
                    isValid = false;
                    showFieldError(field, 'Please enter a valid phone number');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Focus on first error
                const firstError = this.querySelector('.is-invalid');
                if (firstError) {
                    firstError.focus();
                }
            }
        });
    });
    
    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        const errorElement = document.createElement('div');
        errorElement.className = 'form-error';
        errorElement.textContent = message;
        errorElement.setAttribute('role', 'alert');
        field.parentElement.appendChild(errorElement);
    }
    
    // ============================================
    // ACCESSIBILITY ENHANCEMENTS
    // ============================================
    
    // Skip to main content link
    const skipLink = document.querySelector('.skip-link');
    if (skipLink) {
        skipLink.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.setAttribute('tabindex', '-1');
                target.focus();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
    
    // Announce page changes to screen readers
    function announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('role', 'status');
        announcement.setAttribute('aria-live', 'polite');
        announcement.className = 'screen-reader-text';
        announcement.textContent = message;
        document.body.appendChild(announcement);
        
        setTimeout(() => {
            announcement.remove();
        }, 1000);
    }
    
    // ============================================
    // TEXT RESIZE FUNCTIONALITY
    // ============================================
    
    const textSizeButtons = document.querySelectorAll('[data-text-size]');
    let currentSize = 100;
    
    textSizeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-text-size');
            
            if (action === 'increase' && currentSize < 150) {
                currentSize += 10;
            } else if (action === 'decrease' && currentSize > 80) {
                currentSize -= 10;
            } else if (action === 'reset') {
                currentSize = 100;
            }
            
            document.documentElement.style.fontSize = currentSize + '%';
            localStorage.setItem('textSize', currentSize);
            announceToScreenReader(`Text size set to ${currentSize}%`);
        });
    });
    
    // Load saved text size
    const savedTextSize = localStorage.getItem('textSize');
    if (savedTextSize) {
        currentSize = parseInt(savedTextSize);
        document.documentElement.style.fontSize = currentSize + '%';
    }
    
    // ============================================
    // HIGH CONTRAST MODE TOGGLE
    // ============================================
    
    const contrastToggle = document.querySelector('[data-contrast-toggle]');
    
    if (contrastToggle) {
        contrastToggle.addEventListener('click', function() {
            document.body.classList.toggle('high-contrast');
            const isHighContrast = document.body.classList.contains('high-contrast');
            localStorage.setItem('highContrast', isHighContrast);
            announceToScreenReader(isHighContrast ? 'High contrast mode enabled' : 'High contrast mode disabled');
        });
        
        // Load saved preference
        if (localStorage.getItem('highContrast') === 'true') {
            document.body.classList.add('high-contrast');
        }
    }
    
    // ============================================
    // ANIMATED SCROLL REVEAL
    // ============================================
    
    if ('IntersectionObserver' in window) {
        const revealElements = document.querySelectorAll('[data-reveal]');
        
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15
        });
        
        revealElements.forEach(element => {
            revealObserver.observe(element);
        });
    }
    
    // ============================================
    // BACK TO TOP BUTTON
    // ============================================
    
    const backToTop = document.querySelector('.back-to-top');
    
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        
        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // ============================================
    // EVENT CALENDAR FILTERING
    // ============================================
    
    const eventFilters = document.querySelectorAll('.event-filter');
    
    eventFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            const category = this.value;
            const events = document.querySelectorAll('.event-item');
            
            events.forEach(event => {
                if (category === 'all' || event.dataset.category === category) {
                    event.style.display = '';
                } else {
                    event.style.display = 'none';
                }
            });
            
            announceToScreenReader(`Showing ${category} events`);
        });
    });
    
    // ============================================
    // PRINT PAGE FUNCTIONALITY
    // ============================================
    
    const printButtons = document.querySelectorAll('[data-print]');
    
    printButtons.forEach(button => {
        button.addEventListener('click', function() {
            window.print();
        });
    });
    
    // ============================================
    // SHARE FUNCTIONALITY
    // ============================================
    
    const shareButtons = document.querySelectorAll('[data-share]');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const platform = this.getAttribute('data-share');
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            
            let shareUrl;
            
            switch(platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                    break;
                case 'linkedin':
                    shareUrl = `https://www.linkedin.com/shareArticle?mini=true&url=${url}&title=${title}`;
                    break;
                case 'email':
                    shareUrl = `mailto:?subject=${title}&body=${url}`;
                    break;
            }
            
            if (shareUrl) {
                if (platform === 'email') {
                    window.location.href = shareUrl;
                } else {
                    window.open(shareUrl, '_blank', 'width=600,height=400');
                }
            }
        });
    });
    
    // ============================================
    // COOKIE CONSENT
    // ============================================
    
    const cookieBanner = document.querySelector('.cookie-banner');
    const cookieAccept = document.querySelector('.cookie-accept');
    
    if (cookieBanner && !localStorage.getItem('cookieConsent')) {
        setTimeout(() => {
            cookieBanner.classList.add('visible');
        }, 2000);
    }
    
    if (cookieAccept) {
        cookieAccept.addEventListener('click', function() {
            localStorage.setItem('cookieConsent', 'true');
            cookieBanner.classList.remove('visible');
        });
    }
    
    // ============================================
    // INITIALIZE ON DOM READY
    // ============================================
    
    function init() {
        console.log('PHA Website initialized');
        
        // Add loaded class to body
        document.body.classList.add('loaded');
        
        // Initialize any third-party plugins here
        
        // Announce to screen readers that page is loaded
        announceToScreenReader('Page loaded successfully');
    }
    
    // Run initialization
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // ============================================
    // UTILITY FUNCTIONS
    // ============================================
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Throttle function
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
    
    // Check if element is in viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
    
    // Format date
    function formatDate(date) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString('en-US', options);
    }
    
    // Expose utilities globally if needed
    window.PHAUtils = {
        debounce,
        throttle,
        isInViewport,
        formatDate,
        announceToScreenReader
    };

})();