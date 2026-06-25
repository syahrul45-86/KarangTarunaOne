{{--
    Shared Search JavaScript Partial
    Usage: @include('shared.search-js', ['prefix' => 'admin', 'searchRoute' => 'admin.search'])
--}}
<script>
(function() {
    'use strict';

    var PREFIX = '{{ $prefix }}';
    var SEARCH_URL = '{{ route($searchRoute) }}';

    // ============================
    // SEARCH FUNCTIONALITY
    // ============================
    function buildResultsHtml(data, emptyMsg) {
        if (!data.results || data.results.length === 0) {
            return '<div style="padding: 20px; text-align: center; color: #94a3b8;">' +
                       '<i class="fas fa-search" style="font-size: 24px; margin-bottom: 8px; display: block; opacity: 0.4;"></i>' +
                       '<div style="font-size: 13px;">' + emptyMsg + '</div>' +
                   '</div>';
        }
        var html = '<div style="padding: 10px 0;">';
        data.results.forEach(function(item) {
            html +=
            '<a href="' + item.url + '" style="' +
                'display: flex; align-items: center; gap: 12px; padding: 10px 16px;' +
                'text-decoration: none; color: #1e293b; border-left: 3px solid transparent;' +
                'transition: all 0.2s ease;"' +
                ' onmouseover="this.style.background=\'#f8fafc\';this.style.borderLeftColor=\'' + item.color + '\';"' +
                ' onmouseout="this.style.background=\'\';this.style.borderLeftColor=\'transparent\';">' +
                '<div style="width:36px;height:36px;border-radius:10px;background:' + item.color + '20;color:' + item.color + ';display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;">' +
                    '<i class="' + item.icon + '"></i>' +
                '</div>' +
                '<div style="min-width:0;">' +
                    '<div style="font-weight:600;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + item.title + '</div>' +
                    '<div style="font-size:11px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + item.subtitle + '</div>' +
                '</div>' +
                '<i class="fas fa-chevron-right" style="margin-left:auto;color:#cbd5e1;font-size:10px;flex-shrink:0;"></i>' +
            '</a>';
        });
        html += '<div style="padding:8px 16px;font-size:11px;color:#94a3b8;border-top:1px solid #f1f5f9;text-align:right;">' + data.total + ' hasil ditemukan</div>';
        html += '</div>';
        return html;
    }

    function initSearch(inputEl, resultsEl) {
        if (!inputEl || !resultsEl) return;

        var debounceTimer = null;
        var currentController = null;

        inputEl.addEventListener('input', function() {
            var q = this.value.trim();
            clearTimeout(debounceTimer);

            if (q.length < 2) {
                resultsEl.style.display = 'none';
                resultsEl.innerHTML = '';
                return;
            }

            resultsEl.style.display = 'block';
            resultsEl.innerHTML = '<div style="padding:16px;text-align:center;color:#94a3b8;"><i class="fas fa-spinner fa-spin"></i> Mencari...</div>';

            debounceTimer = setTimeout(function() {
                if (currentController) currentController.abort();
                currentController = new AbortController();

                fetch(SEARCH_URL + '?q=' + encodeURIComponent(q), {
                    signal: currentController.signal,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(r) {
                    if (!r.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return r.json();
                })
                .then(function(data) {
                    resultsEl.style.display = 'block';
                    resultsEl.innerHTML = buildResultsHtml(data, 'Tidak ada hasil untuk "' + q + '"');
                })
                .catch(function(err) {
                    if (err.name !== 'AbortError') {
                        resultsEl.style.display = 'block';
                        resultsEl.innerHTML = '<div style="padding:16px;text-align:center;color:#ef4444;font-size:13px;"><i class="fas fa-exclamation-circle"></i> Gagal memuat hasil</div>';
                    }
                });
            }, 350);
        });

        inputEl.addEventListener('focus', function() {
            if (this.value.trim().length >= 2 && resultsEl.innerHTML !== '') {
                resultsEl.style.display = 'block';
            }
        });
    }

    // Init desktop search
    initSearch(
        document.getElementById(PREFIX + 'SearchInput'),
        document.getElementById(PREFIX + 'SearchResults')
    );

    // Mobile search toggle
    var mobileBtn    = document.getElementById(PREFIX + 'MobileSearchBtn');
    var mobilePanel  = document.getElementById(PREFIX + 'MobileSearchPanel');
    if (mobileBtn && mobilePanel) {
        mobileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            var isVisible = mobilePanel.style.display === 'block';
            mobilePanel.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                var mInput = document.getElementById(PREFIX + 'MobileSearchInput');
                if (mInput) setTimeout(function() { mInput.focus(); }, 100);
            }
        });
    }

    // Init mobile search
    initSearch(
        document.getElementById(PREFIX + 'MobileSearchInput'),
        document.getElementById(PREFIX + 'MobileSearchResults')
    );

    // Close on outside click
    document.addEventListener('click', function(e) {
        var desktopBox = document.querySelector('.' + PREFIX + '-search-box');
        if (desktopBox && !desktopBox.contains(e.target)) {
            var res = document.getElementById(PREFIX + 'SearchResults');
            if (res) res.style.display = 'none';
        }
        if (mobilePanel && !mobilePanel.contains(e.target) && mobileBtn && !mobileBtn.contains(e.target)) {
            mobilePanel.style.display = 'none';
        }
    });

    // Close on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var res = document.getElementById(PREFIX + 'SearchResults');
            if (res) res.style.display = 'none';
            if (mobilePanel) mobilePanel.style.display = 'none';
        }
    });

})();
</script>
