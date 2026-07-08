@use('SweetAlert2\Laravel\Swal')

@php
    $alert = session()->pull(Swal::SESSION_KEY);

    // Force the modified session to be written immediately
    session()->save();
@endphp

@if($alert)
<script type="module">
    const getSweetAlert2 = async () => {
        if (window.Swal) {
            return window.Swal;
        }

        try {
            return (await import('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.esm.all.min.js')).default;
        } catch (error) {
            console.error('Failed to load SweetAlert2:', error);
            return { fire: () => {} };
        }
    };

    (async () => {
        window.Swal = await getSweetAlert2();
        {!! Swal::renderFireCall($alert) !!};
    })();
</script>
@endif
