{{-- Auto-trigger SweetAlert2 from Laravel session flashes (Toast & Banner) --}}
@if (session('success') || session('toast'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.Notify) {
                Notify.toast(@json(session('success') ?? session('toast')), 'success');
            }
        });
    </script>
@endif

@if (session('banner'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.Notify) {
                Notify.banner(@json(session('banner')), @json(session('banner_text') ?? ''), @json(session('banner_icon') ?? 'info'));
            }
        });
    </script>
@endif

