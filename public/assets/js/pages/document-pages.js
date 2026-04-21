(function () {
    'use strict';

    const pmVersions = window.pmReleasesData || [];
    const API_BASE = window.pmApiBase || '';
    const CSRF = window.pmCsrfToken
        || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || '';

    let pmPages = [];
    let sidebarData = [];
    let pmCurrentView = 'all';
    let pmCurrentFilter = 'live';
    let pmVerFilter = '';
    let pmEditingId = null;
    let pmScopeMode = 'all';
    let pmSelectedVers = new Set();
    let pmLoading = false;

    function pmEl(id) { return document.getElementById(id); }

    function pmNotify(msg, type = 'success') {
        if (type == 'success') {
            Toastify.success(msg);
        } else if (type == 'error') {
            Toastify.error(msg);
        }

    }

    function pmSetLoading(state) {
        pmLoading = state;
        const saveBtn = pmEl('pm-save-btn');
        if (saveBtn) {
            saveBtn.disabled = state;
            saveBtn.textContent = state ? 'Saving…' : 'Save Page';
        }
    }

    async function apiFetch(url, options = {}) {
        const defaults = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
        };
        const res = await fetch(url, { ...defaults, ...options, headers: { ...defaults.headers, ...(options.headers || {}) } });
        const json = await res.json();
        if (!res.ok) {
            throw new Error(json.message || `HTTP ${res.status}`);
        }
        return json;
    }


    window.pmOpenModal = pmOpenModal;
    window.pmCloseModal = pmCloseModal;
    window.pmSavePage = pmSavePage;
    window.pmSelectScope = pmSelectScope;
    window.pmSetFilter = pmSetFilter;
    window.pmSetVersionFilter = pmSetVersionFilter;
    window.pmRenderPages = pmRenderPages;
    window.pmTogglePage = pmTogglePage;
    window.pmDeletePage = pmDeletePage;
    window.pmEditPage = pmEditPage;
    window.pmToggleVerCheck = pmToggleVerCheck;

    document.querySelectorAll('.pm-nav-item').forEach(btn => {
        btn.addEventListener('click', function () {
            const view = this.dataset.view;
            if (!view) return;
            document.querySelectorAll('.pm-nav-item').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            pmCurrentView = view;
            pmEl('pm-view-pages').classList.remove('pm-hidden');
            pmUpdateHeader(view);
            pmFetchAndRender();
        });
    });

    const pmViewMeta = {
        all: ['All Pages', 'Manage all policy and legal pages across releases'],
        privacy: ['Privacy Policy', 'Privacy and data protection pages'],
        terms: ['Terms of Service', 'Terms and legal agreement pages'],
        conduct: ['Code of Conduct', 'Community conduct pages'],
        cookie: ['Cookie Policy', 'Cookie and tracking policy pages'],
        sponsors: ['Sponsors', 'Sponsors management on version'],
        partners: ['Partners', 'Partner management on version'],
        guide: ['Community Guide', 'Community Guidelines articals'],
        faq: ["FAQ's", 'Frequently asked questions'],
        custom: ['Custom Pages', 'Custom policy pages'],
    };

    const viewToType = {
        privacy: 'privacy',
        terms: 'terms',
        conduct: 'code_of_conduct',
        cookie: 'cookie',
        sponsors: 'sponsors',
        partners: 'partners',
        guide: 'guide',
        faq: 'faq',
        custom: 'custom',
    };

    function pmUpdateHeader(view) {
        const [title, sub] = pmViewMeta[view] || pmViewMeta.all;
        pmEl('pm-view-title').textContent = title;
        pmEl('pm-view-subtitle').textContent = sub;
    }


    async function pmFetchAndRender() {
        const list = pmEl('pm-pages-list');
        list.innerHTML = `
        <div class="text-center mt-3">
            <div class="spinner-border text-dark" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        `;

        const params = new URLSearchParams();
        if (pmCurrentView !== 'all' && viewToType[pmCurrentView]) {
            params.set('type', viewToType[pmCurrentView]);
        }
        if (pmCurrentFilter && pmCurrentFilter !== 'all') {
            params.set('status', pmCurrentFilter);
        }
        if (pmVerFilter) {
            params.set('release_id', pmVerFilter);
        }
        const searchVal = pmEl('pm-search')?.value?.trim();
        if (searchVal) {
            params.set('search', searchVal);
        }

        try {
            const res = await apiFetch(`${API_BASE}/list?${params.toString()}`);
            pmPages = res.data || [];
            sidebarData = res.sidebar_data || [];
            pmRenderPages();
        } catch (err) {
            list.innerHTML = `<div class="pm-empty"><div class="pm-empty-icon text-danger"><i class="bx bx-error"></i></div><div class="pm-empty-title">Failed to load pages</div><div class="pm-empty-desc">${err.message}</div></div>`;
        }
    }


    function pmRenderPages() {
        pmUpdateStats();
        pmUpdateCounts();

        const list = pmEl('pm-pages-list');

        if (!pmPages.length) {
            list.innerHTML = `
                <div class="pm-empty">
                    <div class="pm-empty-icon"><i class="bx bx-file"></i></div>
                    <div class="pm-empty-title">No pages found</div>
                    <div class="pm-empty-desc">Create a new page or adjust your filters</div>
                </div>`;
            return;
        }


        const typeLabel = {
            privacy: 'privacy',
            terms: 'terms',
            code_of_conduct: 'conduct',
            guide: 'guide',
            custom: 'custom',
        };

        list.innerHTML = pmPages.map(p => {

            let vTag;
            if (p.scope === 'all') {
                vTag = `<span class="pm-ver-tag pm-ver-all">All Releases</span>`;
            } else if (p.release) {
                vTag = `<span class="pm-ver-tag">${p.release.version}</span>`;
            } else {
                vTag = '';
            }

            const barColor = p.status === 'live' ? 'var(--pm-success)' :
                p.status === 'draft' ? 'var(--pm-warning)' :
                    'var(--pm-dim)';

            const badgeClass = typeLabel[p.type] || p.type;

            return `
                <div class="pm-page-card">
                    <div class="pm-status-bar" style="background:${barColor}"></div>
                    <div class="pm-card-body">
                        <div class="pm-card-top">
                            <a class="pm-page-name" href="${p.link}">
                            ${pmEscape(p.title)}
                            </a>
                            <span class="pm-badge pm-badge-${badgeClass}">${badgeClass}</span>
                            ${vTag}
                            <span class="pm-status-pill">
                                <span class="pm-status-dot ${p.status}"></span>
                                ${p.status.charAt(0).toUpperCase() + p.status.slice(1)}
                            </span>
                        </div>
                    </div>
                    <div class="pm-card-actions">
                        <div class="form-check form-switch" onclick="event.stopPropagation()">
    <input class="form-check-input"
           type="checkbox"
           role="switch"
           id="pmSwitch_${p.id}"
           ${p.status === 'live' ? 'checked' : ''}
           onchange="pmTogglePage(${p.id}, event)"
           title="${p.status === 'live' ? 'Disable' : 'Enable'}">
</div>
                        <button class="pm-icon-btn" onclick="pmEditPage(${p.id})" title="Edit">
                           <i class="bx bx-edit"></i>
                        </button>
                        <button class="pm-icon-btn pm-danger" onclick="pmDeletePage(${p.id})" title="Delete">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </div>`;
        }).join('');
    }

    function pmEscape(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function pmUpdateStats() {
        pmEl('pm-stat-total').textContent = pmPages.length;
        pmEl('pm-stat-live').textContent = pmPages.filter(p => p.status === 'live').length;
        pmEl('pm-stat-draft').textContent = pmPages.filter(p => p.status === 'draft').length;
        pmEl('pm-stat-off').textContent = pmPages.filter(p => p.status === 'off').length;
    }

    function pmUpdateCounts() {

        pmEl('pm-count-all').textContent = sidebarData.total;

        pmEl('pm-count-privacy').textContent = sidebarData.privacy;
        pmEl('pm-count-terms').textContent = sidebarData.terms;
        pmEl('pm-count-conduct').textContent = sidebarData.code_of_conduct;
        pmEl('pm-count-cookie').textContent = sidebarData.cookie;
        pmEl('pm-count-sponsors').textContent = sidebarData.sponsors;
        pmEl('pm-count-partners').textContent = sidebarData.partners;
        pmEl('pm-count-faq').textContent = sidebarData.faq;
        pmEl('pm-count-guide').textContent = sidebarData.guide;
        pmEl('pm-count-custom').textContent = sidebarData.custom;
    }

    function pmSetFilter(f) {
        pmCurrentFilter = pmCurrentFilter === f ? '' : f;
        document.querySelectorAll('.pm-filter-btn').forEach(b => b.classList.remove('active'));
        if (pmCurrentFilter) pmEl('pmf-' + pmCurrentFilter)?.classList.add('active');
        pmFetchAndRender();
    }

    function pmSetVersionFilter(v) {
        pmVerFilter = v;
        pmFetchAndRender();
    }


    let pmSearchTimer;
    pmEl('pm-search')?.addEventListener('input', () => {
        clearTimeout(pmSearchTimer);
        pmSearchTimer = setTimeout(pmFetchAndRender, 350);
    });


    async function pmTogglePage(id, e) {
        e.stopPropagation();

        const checkbox = e.target;
        const p = pmPages.find(x => x.id === id);
        if (!p) return;

        const previousState = p.status;

        try {
            const res = await apiFetch(`${API_BASE}/${id}/toggle`, { method: 'PATCH' });

            p.status = res.data.status;

            checkbox.checked = p.status === 'live';

            pmRenderPages();
            pmNotify(res.message);

        } catch (err) {
            checkbox.checked = previousState === 'live';
            pmNotify(err.message, 'error');
        }
    }


    async function pmDeletePage(id) {
        const p = pmPages.find(x => x.id === id);
        if (!p || !confirm(`Delete "${p.title}"?`)) return;

        try {
            const res = await apiFetch(`${API_BASE}/${id}`, { method: 'DELETE' });
            pmPages = pmPages.filter(x => x.id !== id);
            pmRenderPages();
            pmNotify(res.message);
        } catch (err) {
            pmNotify(err.message, 'error');
        }
    }

    function pmEditPage(id) {
        const p = pmPages.find(x => x.id === id);
        if (!p) return;

        pmEditingId = id;
        pmEl('pm-page-modal-title').textContent = 'Edit Page';
        pmEl('pm-page-name').value = p.title;
        pmEl('pm-page-type').value = p.type;
        pmEl('pm-page-status').value = p.status;
        pmEl('pm-page-description').value = p.description || '';

        const scope = p.scope || (p.release_id ? 'specific' : 'all');
        pmSelectedVers = new Set(p.release_id ? [p.release_id] : []);
        pmSelectScope(scope);
        pmRefreshVerChecks();
        pmOpenModal();
    }

    function pmOpenModal() {
        if (pmEditingId === null) {
            pmResetPageForm();
            pmEl('pm-page-modal-title').textContent = 'Create New Page';
        }
        pmRefreshVerChecks();
        bootstrap.Modal.getOrCreateInstance(pmEl('pmPageModal')).show();
    }

    function pmCloseModal() {
        bootstrap.Modal.getOrCreateInstance(pmEl('pmPageModal')).hide();
        pmEditingId = null;
    }

    pmEl('pmPageModal')?.addEventListener('hidden.bs.modal', () => {
        pmEditingId = null;
    });

    function pmResetPageForm() {
        ['pm-page-name', 'pm-page-description'].forEach(id => {
            const el = pmEl(id);
            if (el) el.value = '';
        });
        pmEl('pm-page-type').value = 'privacy';
        pmEl('pm-page-status').value = 'draft';
        pmSelectedVers = new Set();
        pmSelectScope('all');
    }

    function pmSelectScope(mode) {
        pmScopeMode = mode;
        pmEl('pm-scope-all').classList.toggle('selected', mode === 'all');
        pmEl('pm-scope-specific').classList.toggle('selected', mode === 'specific');
        pmEl('pm-version-picker').classList.toggle('pm-hidden', mode === 'all');
    }

    function pmRefreshVerChecks() {
        const container = pmEl('pm-ver-checkboxes');
        if (!container) return;
        container.innerHTML = pmVersions.map(v => `
            <div class="pm-ver-check ${pmSelectedVers.has(v.id) ? 'checked' : ''}"
                onclick="pmToggleVerCheck(${v.id}, this)">
                <div class="pm-check-box">${pmSelectedVers.has(v.id) ? '✓' : ''}</div>
                ${pmEscape(v.name)}${v.current ? ' ★' : ''}
            </div>
        `).join('');
    }

    function pmToggleVerCheck(id, el) {
        pmSelectedVers.clear();
        document.querySelectorAll('#pm-ver-checkboxes .pm-ver-check').forEach(c => {
            c.classList.remove('checked');
            c.querySelector('.pm-check-box').textContent = '';
        });
        pmSelectedVers.add(id);
        el.classList.add('checked');
        el.querySelector('.pm-check-box').textContent = '✓';
    }


    async function pmSavePage() {
        const title = pmEl('pm-page-name').value.trim();
        if (!title) {
            pmNotify('Please enter a page name', 'error');
            return;
        }

        const type = pmEl('pm-page-type').value;
        const status = pmEl('pm-page-status').value;
        const description = pmEl('pm-page-description').value.trim();


        const releaseId = pmScopeMode === 'specific' && pmSelectedVers.size > 0
            ? [...pmSelectedVers][0]
            : null;

        const payload = {
            title,
            type,
            status,
            description,
            scope: pmScopeMode,
            release_id: releaseId,
        };

        pmSetLoading(true);

        try {
            let res;
            if (pmEditingId !== null) {
                res = await apiFetch(`${API_BASE}/${pmEditingId}`, {
                    method: 'PUT',
                    body: JSON.stringify(payload),
                });
            } else {
                res = await apiFetch(`${API_BASE}`, {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });
            }

            pmNotify(res.message);
            pmCloseModal();
            pmFetchAndRender();
        } catch (err) {
            pmNotify(err.message, 'error');
        } finally {
            pmSetLoading(false);
        }
    }

    pmFetchAndRender();

})();
