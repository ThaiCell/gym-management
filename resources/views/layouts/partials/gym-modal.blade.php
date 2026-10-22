{{-- GYMFit custom confirmation/alert modal --}}
<style>
    .gym-modal-backdrop {
        position: fixed; inset: 0; z-index: 99999;
        display: none; align-items: center; justify-content: center;
        padding: 20px; background: rgba(0,0,0,.72);
        backdrop-filter: blur(5px);
    }
    .gym-modal-backdrop.is-open { display: flex; }
    .gym-modal {
        width: min(100%, 430px); overflow: hidden;
        color: #f8fafc; background: #141414;
        border: 1px solid #343434; border-radius: 18px;
        box-shadow: 0 24px 80px rgba(0,0,0,.55);
        transform: translateY(8px) scale(.98);
        opacity: 0; transition: opacity .18s ease, transform .18s ease;
        font-family: inherit;
    }
    .gym-modal-backdrop.is-open .gym-modal { transform: translateY(0) scale(1); opacity: 1; }
    .gym-modal-top { padding: 24px 24px 10px; display: flex; gap: 14px; align-items: flex-start; }
    .gym-modal-icon {
        flex: 0 0 46px; width: 46px; height: 46px; border-radius: 14px;
        display: grid; place-items: center; color: #ff303a;
        background: rgba(229,9,20,.13); border: 1px solid rgba(229,9,20,.25);
        font-size: 22px; font-weight: 800;
    }
    .gym-modal-title { margin: 2px 0 7px; font-size: 19px; font-weight: 800; line-height: 1.3; }
    .gym-modal-message { margin: 0; color: #b8b8b8; font-size: 14px; line-height: 1.65; overflow-wrap: anywhere; }
    .gym-modal-actions { display: flex; justify-content: flex-end; gap: 10px; padding: 22px 24px 24px; }
    .gym-modal-btn {
        min-height: 42px; padding: 0 18px; border-radius: 10px; cursor: pointer;
        font: inherit; font-size: 13px; font-weight: 750; transition: .15s ease;
    }
    .gym-modal-cancel { color: #e5e5e5; background: #222; border: 1px solid #3a3a3a; }
    .gym-modal-cancel:hover { background: #303030; }
    .gym-modal-confirm { color: #fff; background: #e50914; border: 1px solid #e50914; }
    .gym-modal-confirm:hover { background: #c9000b; border-color: #c9000b; transform: translateY(-1px); }
    .gym-modal-backdrop [hidden] { display: none !important; }
    @media (max-width: 480px) {
        .gym-modal-top { padding: 20px 18px 8px; }
        .gym-modal-actions { padding: 18px; }
        .gym-modal-btn { flex: 1; }
    }
    @media (prefers-reduced-motion: reduce) {
        .gym-modal, .gym-modal-btn { transition: none; }
    }
</style>

<div class="gym-modal-backdrop" id="gymModalBackdrop" aria-hidden="true">
    <section class="gym-modal" role="dialog" aria-modal="true" aria-labelledby="gymModalTitle" aria-describedby="gymModalMessage">
        <div class="gym-modal-top">
            <div class="gym-modal-icon" id="gymModalIcon" aria-hidden="true">!</div>
            <div>
                <h2 class="gym-modal-title" id="gymModalTitle">Xác nhận thao tác</h2>
                <p class="gym-modal-message" id="gymModalMessage"></p>
            </div>
        </div>
        <div class="gym-modal-actions">
            <button type="button" class="gym-modal-btn gym-modal-cancel" id="gymModalCancel">Để sau</button>
            <button type="button" class="gym-modal-btn gym-modal-confirm" id="gymModalConfirm">Xác nhận</button>
        </div>
    </section>
</div>

<script>
(function () {
    const backdrop = document.getElementById('gymModalBackdrop');
    if (!backdrop || backdrop.dataset.initialized === '1') return;
    backdrop.dataset.initialized = '1';

    const title = document.getElementById('gymModalTitle');
    const message = document.getElementById('gymModalMessage');
    const icon = document.getElementById('gymModalIcon');
    const cancel = document.getElementById('gymModalCancel');
    const accept = document.getElementById('gymModalConfirm');
    let resolver = null;
    let previousFocus = null;
    let mode = 'confirm';

    function close(result) {
        backdrop.classList.remove('is-open');
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        const callback = resolver;
        resolver = null;
        if (callback) callback(result);
        if (previousFocus && typeof previousFocus.focus === 'function') previousFocus.focus();
    }

    function show(text, options) {
        options = options || {};
        mode = options.mode || 'confirm';
        previousFocus = document.activeElement;
        title.textContent = options.title || (mode === 'alert' ? 'Thông báo' : 'Xác nhận thao tác');
        message.textContent = String(text || '');
        icon.textContent = mode === 'alert' ? 'i' : (options.icon || '?');
        accept.textContent = options.confirmText || (mode === 'alert' ? 'Đã hiểu' : 'Xác nhận');
        cancel.hidden = mode === 'alert';
        backdrop.classList.add('is-open');
        backdrop.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        window.setTimeout(function () { accept.focus(); }, 0);
        return new Promise(function (resolve) { resolver = resolve; });
    }

    cancel.addEventListener('click', function () { close(false); });
    accept.addEventListener('click', function () { close(true); });
    backdrop.addEventListener('click', function (event) {
        if (event.target === backdrop) close(false);
    });
    document.addEventListener('keydown', function (event) {
        if (!backdrop.classList.contains('is-open')) return;
        if (event.key === 'Escape') { event.preventDefault(); close(false); }
        if (event.key === 'Enter' && mode === 'alert') { event.preventDefault(); close(true); }
    });

    // Custom confirmation for forms (delete, cancel, logout, payment, etc.).
    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm-submit')) return;
        if (form.dataset.gymConfirmed === '1') {
            delete form.dataset.gymConfirmed;
            return;
        }
        event.preventDefault();
        event.stopImmediatePropagation();
        show(form.getAttribute('data-confirm-submit'), {
            title: form.dataset.confirmTitle || 'Bạn có chắc không?',
            confirmText: form.dataset.confirmText || 'Tiếp tục'
        }).then(function (ok) {
            if (!ok) return;
            form.dataset.gymConfirmed = '1';
            if (typeof form.requestSubmit === 'function') form.requestSubmit();
            else form.submit();
        });
    }, true);

    // Custom confirmation for individual buttons.
    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-confirm]');
        if (!button) return;
        if (button.dataset.gymConfirmed === '1') {
            delete button.dataset.gymConfirmed;
            return;
        }
        event.preventDefault();
        event.stopImmediatePropagation();
        show(button.getAttribute('data-confirm'), {
            title: button.dataset.confirmTitle || 'Bạn có chắc không?',
            confirmText: button.dataset.confirmText || 'Tiếp tục'
        }).then(function (ok) {
            if (!ok) return;
            button.dataset.gymConfirmed = '1';
            button.click();
        });
    }, true);

    // Existing alert() validations use the same branded modal.
    window.gymModalAlert = function (text, titleText) {
        return show(text, { mode: 'alert', title: titleText || 'Thông báo' });
    };
    window.alert = function (text) { window.gymModalAlert(text); };
})();
</script>
