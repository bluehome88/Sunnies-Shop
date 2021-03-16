/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2019 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

var TbcmsInfiniteScroll;
$(function() {
    var oldXHR = window.XMLHttpRequest;
    TbcmsInfiniteScroll = TbcmsInfiniteScroll || {
        ajaxUrl: false,
        next_ajaxUrl: false,
        products: false,
        current_page: 1,
        current_method: false,
        loader: false,
        loader_prev: false,
        button: false,
        back_top_button: false,
        busy: false,
        next_head: false,
        offset: false,
        offset_pages: [],
        hasLoadPreviousPages: false,
        timer: false,
        timerOffset: false,
        ajax_request: false,
        pushStateAvailable: true,
        isBlocklayeredActiv: false,
        layeredProducts: false,
        debug: false,
        initialize: function() {
            tb_log('initialize TbcmsInfiniteScroll');
            var self = this;
            this.current_method = this.method;
            this.loader_prev = $(this.loader_prev);
            this.loader = $(this.loader);
            this.button = $(this.button);
            this.back_top_button = $(this.back_top_button);
            if (typeof window.history.pushState === 'undefined') { this.pushStateAvailable = false; }
            $(document).ajaxSend(function(event, xhr, settings) { if (settings.url.indexOf('blocklayered') != '-1' && !self.isBlocklayeredActiv) { tb_log('ajaxSend -> stopActivity');
                    self.isBlocklayeredActiv = true;
                    self.stopActivity(); } }).ajaxSuccess(function(event, xhr, settings) {
                if (settings.url.indexOf('blocklayered') != '-1') {
                    if (self.active_with_layered == 1) { tb_log('in ajaxSuccess event');
                        self.init_blocklayered(); } else { $(self.pagination_wrapper).show();
                        self.loader.fadeOut('fast'); if (self.method == 'button') { self.button.fadeOut('fast'); } }
                }
            });
            window.XMLHttpRequest = this.newXHR;
            if (this.views_buttons !== '' && $(this.views_buttons).length !== 0) { $(document).on('click', this.views_buttons, function(e) { setTimeout(function() { self.reassign_offset(); }, 100); }); }
            this.current_page = this.getUrlParamPage();
            if (this.current_page > 1 && window.location.href.indexOf('#/') == -1) { $(this.product_wrapper).before(this.loader_prev.addClass('before-loader'));
                $('.before-loader').hide();
                this.loadPreviousPages(); }
            this.bindPopstate();
            this.init();
        },
        init: function() {
            tb_log('init TbcmsInfiniteScroll');
            var self = this;
            this.offset = false;
            if (this.ajax_request) this.ajax_request.abort();
            this.products = false;
            this.isBlocklayeredActiv = false;
            if (!this.hasLoadPreviousPages) { this.busy = false;
                this.offset_pages = []; }
            this.hasLoadPreviousPages = false;
            this.current_page = this.getUrlParamPage();
            this.loader.fadeOut('fast');
            this.button.fadeOut('fast');
            this.back_top_button.fadeOut('fast');
            $(this.pagination_wrapper).hide();
            $(this.loader).remove();
            $(this.product_wrapper).after(this.loader);
            this.loader.hide();
            this.getAjaxUrlFromPagination();
            var timer = setInterval(function() {
                if (self.busy) { return; } else { clearInterval(timer); }
                $(self.product_wrapper + ' ' + self.product_elem + ':last').addClass('tb-last-' + self.current_page);
                self.offset = $(self.product_wrapper + ' ' + self.product_elem + ':last').offset();
                self.offset_pages.push({ offset: self.offset.top + $(self.product_wrapper + ' ' + self.product_elem + ':last').height() - $(window).height(), url: window.location.href, head: $('<div>').append($('head').find('title').clone()).append($('head').find('meta').clone()) });
                self.redefineOffset();
                if (self.method == 'scroll') { self.methodScroll(); } else if (self.method == 'button') {
                    $(self.product_wrapper).after(self.button);
                    self.button.hide();
                    self.methodButton();
                    if (self.button_start_page > 1 || self.button_n_pages > 1) { self.methodScroll(); }
                    self.checkScrollMethod();
                }
                $(window).bind('scroll', function() { clearTimeout(self.timer); if (!self.isBlocklayeredActiv) { self.timer = setTimeout(function() { self.determineURL(); }, 100); } });
                if (self.ajaxUrl) {
                    if (!self.isBlocklayeredActiv && self.active_with_layered == 1 && self.ajaxUrl.indexOf('#/page-') != -1) {
                        if (window.location.href.indexOf('?p=') != -1) {
                            if (self.ajaxUrl.indexOf('?') != -1) { var url = self.ajaxUrl.substring(0, self.ajaxUrl.indexOf('?')); } else { var url = self.ajaxUrl.substring(0, self.ajaxUrl.indexOf('#/')); }
                            self.ajaxUrl = url + '?p=' + (self.current_page + 1);
                        } else { self.ajaxUrl = self.ajaxUrl.substring(0, self.ajaxUrl.indexOf('#/page-')) + '?p=2'; }
                        tb_log('ajaxUrl after update : ' + self.ajaxUrl);
                    }
                    self.preloadProducts();
                }
            }, 100);
        },
        methodScroll: function() { var self = this;
            $(window).unbind('scroll').bind('scroll', function() { if (self.current_method == 'scroll' && !self.busy && self.offset && self.ajaxUrl && ((self.offset.top + $(self.product_wrapper + ' ' + self.product_elem + ':last').height()) - $(window).height() <= $(window).scrollTop())) { self.displayProducts(); } }); },
        methodButton: function() { var self = this;
            this.button.click(function() { if (!self.busy) { $(this).fadeOut('fast');
                    self.displayProducts(); } }); },
        checkScrollMethod: function() {
            if (this.method == 'scroll') { this.current_method = 'scroll'; } else { this.current_method = 'scroll'; if ((this.button_start_page == this.current_page) || ((this.current_page - this.button_start_page) % this.button_n_pages === 0)) { this.current_method = 'button'; } }
        },
        shouldDisplayButton: function() {
            if (this.method == 'scroll') { return false; } else {
                if ((this.button_start_page == this.current_page) || ((this.current_page - this.button_start_page) % this.button_n_pages === 0)) { return true; } else { return false; }
            }
        },
        addEndButton: function() { tb_log('addEndButton');
            $(this.product_wrapper).after(this.back_top_button);
            this.back_top_button.hide().fadeIn();
            this.back_top_button.find('.tb-back-top-link').click(function() { $('html,body').stop().animate({ scrollTop: 0 }, 400); return false; }); },
        preloadProducts: function() {
            var self = this;
            if (!this.busy) {
                this.busy = true;
                this.loader.fadeIn();
                this.button.fadeOut('fast');
                url_to_preload = this.ajaxUrl;
                if (this.isBlocklayeredActiv) { url_to_preload = this.next_ajaxUrl; }
                tb_log('preloadProducts url : ' + url_to_preload);
                this.ajax_request = $.get(url_to_preload, function(data) {
                    tb_log('url preloaded');
                    if (!self.isBlocklayeredActiv) { self.products = $(self.product_wrapper, data).html();
                        self.next_ajaxUrl = $(self.pagination_wrapper + ' ' + self.next_button + ':not(.disabled)', data).attr('href'); if (self.next_ajaxUrl === url_to_preload) { self.next_ajaxUrl = false; } }
                    tb_log('next_ajaxUrl : ' + self.next_ajaxUrl);
                    self.next_head = self.getNextHead(data.substring(data.indexOf('<head>') + 6, data.indexOf('</head>')));
                    self.busy = false;
                    self.loader.fadeOut('fast');
                    if (self.method == 'button' && self.shouldDisplayButton()) { self.button.fadeIn(); }
                    if (self.offset && self.method == 'scroll' && ((self.offset.top + $(self.product_wrapper + ':visible ' + self.product_elem + ':last').height()) - $(window).height() <= $(window).scrollTop()) && !self.busy && self.ajaxUrl) { self.displayProducts(); }
                });
            }
        },
        displayProducts: function() {
            tb_log('displayProducts');
            var self = this;
            this.updateUrlHead(true);
            if (!this.isBlocklayeredActiv) {
                $(self.product_wrapper).append(self.products);
                $(self.product_wrapper + ' ' + self.product_elem + ':last').addClass('tb-last-' + self.offset_pages.length);
                if ($(self.selected_view).length !== 0) { $(self.selected_view).click(); }
                if (self.tbcmsinfinitescrollqv_enabled == '1') { tbcms_quick_view_add_btn(); }
                if (typeof ajaxCart != 'undefined' && typeof ajaxCart !== undefined) { ajaxCart.overrideButtonsInThePage(); }
                // tb_hook_after_display_products();
                self.current_page = self.current_page + 1;
                self.checkScrollMethod();
                self.offset = $(self.product_wrapper + ' ' + self.product_elem + ':last').offset();
                self.offset_pages.push({ offset: self.offset.top + $(self.product_wrapper + ' ' + self.product_elem + ':last').height() - $(window).height(), url: self.ajaxUrl, head: self.next_head });
                self.ajaxUrl = self.next_ajaxUrl;
                self.next_ajaxUrl = self.products = self.next_head = false;
                tb_log('ajaxUrl : ' + self.ajaxUrl);
                if (self.ajaxUrl) { self.preloadProducts(); } else { self.addEndButton(); }
            }
            this.redefineOffset();
        },
        loadPreviousPages: function() {
            tb_log('loadPreviousPages');
            this.busy = true;
            var $first_prd = $(this.product_wrapper + ' ' + this.product_elem + ':first');
            for (var i = (this.current_page - 1); i > 0; i--) { var the_url = this.makeUrlWithPage(i);
                this.loadPreviousPage(i, the_url, $first_prd); }
            this.hasLoadPreviousPages = true;
        },
        loadPreviousPage: function(i, url, $first_prd) {
            var self = this;
            $('.before-loader').fadeIn();
            tb_log('loadPreviousPage :' + url);
            $.get(url, function(data) {
                var $first_product = $(self.product_wrapper + ' ' + self.product_elem + ':first');
                $(self.product_wrapper).prepend($(self.product_wrapper, data).html());
                $first_product.prev(self.product_elem).addClass('tb-last-' + i);
                if ($(self.selected_view).length > 0) { $(self.selected_view).click(); }
                // tb_hook_after_display_products();
                if (i == 1 && self.tbcmsinfinitescrollqv_enabled == '1') { tbcms_quick_view_add_btn(); }
                var head = self.getNextHead(data.substring(data.indexOf('<head>') + 6, data.indexOf('</head>')));
                self.offset_pages[i - 1] = { offset: 0, url: url, head: head };
                $('.before-loader').fadeOut('fast');
                if (i == 1) {
                    for (var y = (self.current_page - 1); y > 0; y--) { if ($(self.product_wrapper + ' ' + '.tb-last-' + y).offset()) { self.offset_pages[y - 1].offset = $(self.product_wrapper + ' ' + '.tb-last-' + y).offset().top + $(self.product_wrapper + ' ' + self.product_elem + ':last').height() - $(window).height(); } }
                    $('html,body').stop().animate({ scrollTop: $first_prd.offset().top }, 400);
                    self.busy = false;
                }
            });
        },
        getNextHead: function(data) { var next_head = data.replace(new RegExp("\n", "gi"), '');
            next_head = next_head.replace(new RegExp("<script(.*)</script>", "gi"), '');
            next_head = next_head.replace(new RegExp("<link(.*)>", "gi"), '');
            next_head = next_head.replace(new RegExp("<style(.*)</style>", "gi"), ''); return next_head; },
        updateUrlHead: function(updateAll, i) {
            tb_log('updateUrlHead');
            if (!this.isBlocklayeredActiv) { $('head').find('title').remove();
                $('head').find('meta').remove(); }
            if (updateAll) {
                if (!this.isBlocklayeredActiv) {
                    if (this.pushStateAvailable) { window.history.pushState({ tbcmsinfinitescroll: 1 }, '', this.ajaxUrl); }
                    $('head').prepend(this.next_head);
                } else {
                    if (this.pushStateAvailable) { window.history.pushState({ tbcmsinfinitescroll: 1 }, '', this.ajaxUrl); }
                    this.busy = true;
                }
                if ('object' == typeof _gaq) _gaq.push(['_trackPageview', this.ajaxUrl]);
            } else {
                if (this.pushStateAvailable) { window.history.pushState({ tbcmsinfinitescroll: 1 }, '', this.offset_pages[i].url); }
                if (typeof this.offset_pages[i].head == 'object')
                    $('head').prepend(this.offset_pages[i].head.html());
                else
                    $('head').prepend(this.offset_pages[i].head);
            }
        },
        determineURL: function() {
            tb_log('determineURL');
            var location = window.location.href;
            var current = -1;
            for (var i = this.offset_pages.length - 1; i >= 0; i--) {
                if ($(window).scrollTop() <= this.offset_pages[i].offset)
                    current = i;
            }
            if (current > -1 && location != this.offset_pages[current].url && location != this.offset_pages[current].url + '#')
                this.updateUrlHead(false, current);
        },
        getAjaxUrlFromPagination: function() {
            var url = $(this.pagination_wrapper + ' ' + this.next_button + ':not(.disabled)').attr('href');
            if (url !== '' && url !== window.location.href) { this.ajaxUrl = url;
                this.offset = $(this.product_wrapper + ' ' + this.product_elem + ':last').offset(); } else { this.ajaxUrl = false;
                this.offset = false; }
            tb_log('getAjaxUrlFromPagination ajaxUrl: ' + this.ajaxUrl);
            tb_log(this.offset);
        },
        getUrlParamPage: function() {
            var currentUrl = window.location.href;
            var p = 1;
            if (this.has_facetedSearch) { var page = currentUrl.substring(currentUrl.indexOf('?') + 1, currentUrl.length); var page_split = page.split('&'); for (var i = 0; i < page_split.length; i++) { if (page_split[i].indexOf('page=') != -1) { p = page_split[i].substring(5, page_split[i].length); } } } else if (!this.isBlocklayeredActiv) { var page = currentUrl.substring(currentUrl.indexOf('?') + 1, currentUrl.length); var page_split = page.split('&'); for (var i = 0; i < page_split.length; i++) { if (page_split[i].indexOf('p=') != -1) { p = page_split[i].substring(2, page_split[i].length); } } } else { var page = currentUrl.substring(currentUrl.indexOf('#/') + 2, currentUrl.length); var page_split = page.split('/'); for (var i = 0; i < page_split.length; i++) { if (page_split[i].indexOf('page-') != -1) { p = page_split[i].substring(5, currentUrl.length); } } }
            tb_log('getUrlParamPage: ' + p);
            return parseInt(p);
        },
        makeUrlWithPage: function(p) {
            var the_url = '';
            var baseUrl = '';
            var paramsUrl = '';
            var currentUrl = window.location.href;
            if (this.has_facetedSearch) {
                baseUrl = currentUrl.substring(0, currentUrl.indexOf('?'));
                paramsUrl = currentUrl.substring(currentUrl.indexOf('?') + 1, currentUrl.length);
                var paramsSplit = paramsUrl.split('&');
                paramsUrl = '';
                for (var i = 0; i < paramsSplit.length; i++) {
                    if (paramsSplit[i].indexOf('page=') != -1) {
                        if (p > 1) {
                            if (paramsUrl !== '') { paramsUrl += '&'; }
                            paramsUrl += 'page=' + p;
                        }
                    } else {
                        if (paramsUrl !== '') { paramsUrl += '&'; }
                        paramsUrl += paramsSplit[i];
                    }
                }
                the_url = baseUrl;
                if (paramsUrl !== '') { the_url += '?' + paramsUrl; }
            } else if (!this.isBlocklayeredActiv) {
                baseUrl = window.location.href.substring(0, window.location.href.indexOf('?'));
                paramsUrl = currentUrl.substring(currentUrl.indexOf('?') + 1, currentUrl.length);
                var paramsSplit = paramsUrl.split('&');
                paramsUrl = '';
                for (var i = 0; i < paramsSplit.length; i++) {
                    if (paramsSplit[i].indexOf('p=') != -1) {
                        if (p > 1) {
                            if (paramsUrl !== '') { paramsUrl += '&'; }
                            paramsUrl += 'p=' + p;
                        }
                    } else {
                        if (paramsUrl !== '') { paramsUrl += '&'; }
                        paramsUrl += paramsSplit[i];
                    }
                }
                the_url = baseUrl;
                if (paramsUrl !== '') { the_url += '?' + paramsUrl; }
            } else {
                baseUrl = window.location.href.substring(0, window.location.href.indexOf('#/'));
                paramsUrl = currentUrl.substring(currentUrl.indexOf('#/') + 2, currentUrl.length);
                var paramsSplit = paramsUrl.split('/');
                paramsUrl = '';
                for (var i = 0; i < paramsSplit.length; i++) {
                    if (paramsSplit[i].indexOf('page-') != -1) {
                        if (paramsUrl !== '') {
                            if (i > 0) { paramsUrl += '/'; }
                            paramsUrl += 'page-' + p;
                        }
                    } else {
                        if (paramsUrl !== '') { paramsUrl += '/'; }
                        paramsUrl += paramsSplit[i];
                    }
                }
                the_url = baseUrl;
                if (paramsUrl !== '') { the_url += '#/' + paramsUrl; }
            }
            tb_log('makeUrlWithPage (i = ' + p + '): ' + the_url);
            return the_url;
        },
        reassign_offset: function() {
            this.offset = $(this.product_wrapper + ':visible ' + this.product_elem + ':last').offset();
            if (this.offset_pages.length === 0) { return; }
            for (var i = 0; i < this.offset_pages.length; i++) { if ($(this.product_wrapper + ' ' + '.tb-last-' + i).length > 0) { this.offset_pages[i].offset = $(this.product_wrapper + ' ' + '.tb-last-' + i).offset().top + $(this.product_wrapper + ' ' + this.product_elem + ':last').height() - $(window).height(); } }
            tb_log('reassign_offset done');
        },
        redefineOffset: function() { var self = this; if (this.offset) { this.timerOffset = setInterval(function() { var lastOffsetTop = self.offset.top;
                    tb_log('lastOffsetTop : ' + lastOffsetTop);
                    tb_log('redefineOffset');
                    self.reassign_offset(); var new_lastOffsetTop = self.offset.top;
                    tb_log('new_lastOffsetTop : ' + new_lastOffsetTop); if (new_lastOffsetTop == lastOffsetTop) { clearInterval(self.timerOffset); } }, 400); } },
        stopActivity: function() { if (this.ajax_request) this.ajax_request.abort();
            this.busy = false;
            this.products = false;
            this.offset_pages = [];
            this.current_page = 1;
            this.loader.fadeOut('fast');
            this.button.fadeOut('fast');
            this.back_top_button.fadeOut('fast');
            tb_log('stopActivity'); },
        bindPopstate: function() { var self = this;
            $(window).on('popstate', function(event) { tb_log('popstate'); var state = event.originalEvent.state; if (state !== null && state.tbcmsinfinitescroll && !self.isBlocklayeredActiv) { window.location = document.location.pathname; } }); },
        newXHR: function() { var self = TbcmsInfiniteScroll; var realXHR = new oldXHR();
            realXHR.addEventListener("load", function(event) { var req_xhr = event.target; var xhr_response_header = req_xhr.getResponseHeader("content-type") || ""; if (req_xhr.readyState == 4 && req_xhr.status == 200) { if (xhr_response_header && xhr_response_header == 'application/json') { var json = JSON.parse(req_xhr.responseText); if (json.rendered_facets !== undefined) { tb_log('bind xhr request. Stop activity');
                            setTimeout(function() { self.init(); }, 25); } } } }, false);
            realXHR.addEventListener("send", function(event) { tb_log('send'); }); return realXHR; },
        init_blocklayered: function() {
            var self = this;
            tb_log('init_blocklayered');
            if (!self.isBlocklayeredActiv) { self.stopActivity(); }
            self.busy = false;
            $(self.pagination_wrapper).hide();
            var $first = $(self.product_wrapper + ' ' + self.product_elem + ':first');
            var currentUrl = window.location.href;
            var currentBaseUrl = currentUrl.substring(0, currentUrl.indexOf('#/'));
            var currentLayeredUrl = currentUrl.substring(currentUrl.indexOf('#/'), currentUrl.length);
            if (currentLayeredUrl == '#/' || !currentLayeredUrl) {
                if (self.pushStateAvailable) { window.history.pushState({ tbcmsinfinitescroll: 1 }, '', currentUrl.replace('#/', '')); }
                self.init();
                return false;
            }
            if (self.current_page && self.layeredProducts && currentUrl.indexOf('/page-') != -1 && (self.current_page + 1) == self.getUrlParamPage()) { self.busy = true;
                $(self.product_wrapper).prepend(self.layeredProducts);
                $('html,body').stop().animate({ scrollTop: $first.offset().top }, 400);
                setTimeout(function() { self.busy = false; }, 450); }
            self.layeredProducts = $(self.product_wrapper + ' ' + self.product_elem);
            if (currentUrl.indexOf('/page-') != -1 || currentUrl.indexOf('?page=') != -1)
                self.current_page = self.current_page + 1;
            else
                self.current_page = 1;
            self.checkScrollMethod();
            self.offset = $(self.product_wrapper + ' ' + self.product_elem + ':last').offset();
            self.redefineOffset();
            self.ajaxUrl = $(self.pagination_wrapper + ' ' + self.next_button + ':not(.disabled)').attr('href');
            tb_log('ajaxUrl from the pagination next button : ' + self.ajaxUrl);
            if (self.ajaxUrl) {
                var next_url = '';
                if (currentLayeredUrl.indexOf('/page-') == -1)
                    next_url = currentLayeredUrl + '/page-2';
                else {
                    var urlSplit = currentLayeredUrl.split('/');
                    p = 1;
                    for (var i = 0; i < urlSplit.length; i++) {
                        if (urlSplit[i].indexOf('page-') != -1) { p = parseInt(urlSplit[i].substring(5, urlSplit[i].length));
                            p++;
                            urlSplit[i] = 'page-' + p; }
                        if (urlSplit[i] != '#')
                            next_url += '/';
                        next_url += urlSplit[i];
                    }
                }
                self.ajaxUrl = currentBaseUrl + next_url;
                tb_log('ajaxUrl after custom edit : ' + self.ajaxUrl);
                if (self.method == 'button' && self.shouldDisplayButton()) { self.button.fadeIn(); }
            } else { self.addEndButton(); }
        }
    };
    if (typeof tb_params != 'undefined' && $(tb_params.product_wrapper).length !== 0 && $(tb_params.pagination_wrapper).length !== 0) { TbcmsInfiniteScroll = $.extend(TbcmsInfiniteScroll, tb_params);
        TbcmsInfiniteScroll.initialize(); }

    function tb_log(text) { if (TbcmsInfiniteScroll.debug) { console.log(text); } }
});