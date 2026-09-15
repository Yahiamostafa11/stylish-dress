(function () {
    'use strict';

    var pageRoot = document.querySelector('.woocommerce-account .woocommerce');
    if (!pageRoot) {
        return;
    }

    var navSelector = '.woocommerce-MyAccount-navigation';
    var contentSelector = '.woocommerce-MyAccount-content';
    var activeRequest = null;

    var isArabicPath = function (url) {
        var path = normalizePath(url || window.location.href);
        return path.indexOf('/ar') === 0 || path.indexOf('/حسابي') !== -1;
    };

    var markNoTranslate = function (element) {
        if (!element || element.nodeType !== 1) {
            return;
        }
        element.setAttribute('data-no-translation', '');
        element.setAttribute('translate', 'no');
        element.classList.add('notranslate', 'trp-no-translate');
    };

    var applyArabicNoTranslateGuards = function (root) {
        if (!isArabicPath(window.location.href)) {
            return;
        }

        var scope = root && root.nodeType === 1 ? root : document;
        var guards = [];
        var nav = scope.querySelector(navSelector);
        var content = scope.querySelector(contentSelector);

        if (nav) {
            guards.push(nav);
        }

        if (content) {
            guards.push(content);
        }

        guards.forEach(function (block) {
            markNoTranslate(block);
            block.querySelectorAll('a, span, p, h1, h2, h3, h4, h5, h6, label, th, td, legend, button').forEach(markNoTranslate);
        });
    };

    var isSameOrigin = function (url) {
        try {
            return new URL(url, window.location.origin).origin === window.location.origin;
        } catch (e) {
            return false;
        }
    };

    var normalizePath = function (url) {
        try {
            return new URL(url, window.location.origin).pathname.replace(/\/$/, '');
        } catch (e) {
            return '';
        }
    };

    var normalizeUrlSignature = function (url) {
        try {
            var parsed = new URL(url, window.location.origin);
            var path = parsed.pathname.replace(/\/$/, '');
            if (!path) {
                path = '/';
            }
            return path + parsed.search;
        } catch (e) {
            return '';
        }
    };

    var lastLoadedSignature = normalizeUrlSignature(window.location.href);

    var isSamePageUrl = function (url) {
        return normalizeUrlSignature(url) === normalizeUrlSignature(window.location.href);
    };

    var isAccountUrl = function (url) {
        var path = normalizePath(url);
        if (!path) {
            return false;
        }
        return path.indexOf('/my-account') !== -1 || path.indexOf('/ar/حسابي') !== -1;
    };

    var shouldSkipAjaxLink = function (url) {
        if (!url || !isSameOrigin(url) || !isAccountUrl(url)) {
            return true;
        }
        return /customer-logout|\/logout\/?$/i.test(url);
    };

    var setLoading = function (state) {
        pageRoot.classList.toggle('ekart-account-loading', !!state);
    };

    var parseHTML = function (html) {
        return new DOMParser().parseFromString(html, 'text/html');
    };

    var replaceAccountSections = function (doc) {
        var currentNav = document.querySelector(navSelector);
        var currentContent = document.querySelector(contentSelector);
        var incomingNav = doc.querySelector(navSelector);
        var incomingContent = doc.querySelector(contentSelector);

        if (!currentContent || !incomingContent) {
            return false;
        }

        if (currentNav && incomingNav) {
            currentNav.replaceWith(incomingNav);
        }

        currentContent.replaceWith(incomingContent);
        return true;
    };

    var fetchPage = function (url, options) {
        return fetch(url, {
            method: (options && options.method) || 'GET',
            body: options && options.body ? options.body : undefined,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function (response) {
            return response.text();
        });
    };

    var loadAccountUrl = function (url, pushState) {
        var targetSignature = normalizeUrlSignature(url);
        if (!targetSignature) {
            window.location.href = url;
            return Promise.resolve();
        }

        if (activeRequest) {
            return activeRequest;
        }

        if (!pushState && targetSignature === lastLoadedSignature) {
            return Promise.resolve();
        }

        setLoading(true);
        activeRequest = fetchPage(url).then(function (html) {
            var doc = parseHTML(html);
            var applied = replaceAccountSections(doc);
            if (!applied) {
                window.location.href = url;
                return;
            }

            if (pushState) {
                window.history.pushState({ ekartAccountAjax: true }, '', url);
            }

            lastLoadedSignature = targetSignature;

            applyArabicNoTranslateGuards(document);

            window.requestAnimationFrame(function () {
                var content = document.querySelector(contentSelector);
                if (content) {
                    content.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }).catch(function () {
            window.location.href = url;
        }).finally(function () {
            activeRequest = null;
            setLoading(false);
        });

        return activeRequest;
    };

    document.addEventListener('click', function (event) {
        var link = event.target.closest('a');
        if (!link) {
            return;
        }

        if (event.defaultPrevented || event.button !== 0 || link.target === '_blank' || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        var href = link.getAttribute('href') || '';
        if (!href || shouldSkipAjaxLink(href)) {
            return;
        }

        if (isSamePageUrl(link.href)) {
            return;
        }

        var insideAccount = link.closest(navSelector) || link.closest(contentSelector);
        if (!insideAccount) {
            return;
        }

        event.preventDefault();
        loadAccountUrl(link.href, true);
    });

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!form || !(form instanceof HTMLFormElement)) {
            return;
        }

        if (!form.closest(contentSelector)) {
            return;
        }

        var action = form.getAttribute('action') || window.location.href;
        if (shouldSkipAjaxLink(action)) {
            return;
        }

        var method = (form.getAttribute('method') || 'POST').toUpperCase();
        if (method !== 'POST') {
            return;
        }

        event.preventDefault();
        setLoading(true);

        var formData = new FormData(form);
        fetchPage(action, { method: 'POST', body: formData }).then(function (html) {
            var doc = parseHTML(html);
            var applied = replaceAccountSections(doc);
            if (!applied) {
                window.location.reload();
                return;
            }
            window.history.replaceState({ ekartAccountAjax: true }, '', action);
            applyArabicNoTranslateGuards(document);
        }).catch(function () {
            window.location.reload();
        }).finally(function () {
            setLoading(false);
        });
    });

    window.addEventListener('popstate', function () {
        if (!isAccountUrl(window.location.href)) {
            return;
        }
        if (normalizeUrlSignature(window.location.href) === lastLoadedSignature) {
            return;
        }
        loadAccountUrl(window.location.href, false);
    });

    applyArabicNoTranslateGuards(document);
})();
