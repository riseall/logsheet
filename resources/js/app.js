require('./bootstrap');

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

// Level 1: Toast - No response required, auto-dismiss, action confirmation
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

// Level 2: Banner - Noticeable, non-blocking, no immediate response required
window.Banner = Swal.mixin({
    position: 'top',
    grow: 'row',
    backdrop: false,
    showConfirmButton: false,
    showCloseButton: true,
    customClass: {
        popup: 'swal2-banner-popup'
    }
});

// Level 3: Modal - Decision required before proceeding
window.Modal = {
    confirm: function (options) {
        const opts = typeof options === 'string' ? { title: options } : (options || {});
        return Swal.fire({
            icon: opts.icon || 'warning',
            title: opts.title || 'Konfirmasi Tindakan',
            text: opts.text || '',
            html: opts.html,
            showCancelButton: true,
            confirmButtonColor: opts.confirmColor || '#b91c1c',
            cancelButtonColor: opts.cancelColor || '#1d4ed8',
            confirmButtonText: opts.confirmText || 'Ya, Lanjutkan',
            cancelButtonText: opts.cancelText || 'Batal',
            reverseButtons: false,
            ...opts
        });
    },
    alert: function (options) {
        const opts = typeof options === 'string' ? { title: options } : (options || {});
        return Swal.fire({
            icon: opts.icon || 'info',
            title: opts.title || 'Informasi',
            text: opts.text || '',
            confirmButtonColor: opts.confirmColor || '#047857',
            confirmButtonText: opts.confirmText || 'OK',
            ...opts
        });
    }
};

// Concise helper facade (Ponytail YAGNI)
window.Notify = {
    toast: function (title, icon = 'success') {
        return window.Toast.fire({ icon: icon, title: title });
    },
    banner: function (title, text = '', icon = 'info') {
        return window.Banner.fire({ icon: icon, title: title, text: text });
    },
    confirm: function (title, text, onConfirm, icon = 'warning') {
        return window.Modal.confirm({ title: title, text: text, icon: icon }).then((res) => {
            if (res.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
            return res;
        });
    }
};

// Declarative form confirmation listener (data-confirm)
document.addEventListener('submit', function (e) {
    const form = e.target;
    const confirmMsg = form.getAttribute('data-confirm');
    if (confirmMsg && !form.dataset.confirmed) {
        e.preventDefault();
        const title = form.getAttribute('data-confirm-title') || 'Konfirmasi Tindakan';
        const btnText = form.getAttribute('data-confirm-button') || 'Ya, Lanjutkan';

        window.Modal.confirm({
            title: title,
            text: confirmMsg,
            confirmText: btnText
        }).then((res) => {
            if (res.isConfirmed) {
                form.dataset.confirmed = 'true';
                form.submit();
            }
        });
    }
});

// Initialize Alpine.js
Alpine.start();
