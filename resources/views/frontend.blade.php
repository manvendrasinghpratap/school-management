@extends('frontend.layouts.default') 
@push('scripts')
<script src="{{ asset('common/js/modules/auth.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('alert'))
            @php
                $alert = session('alert');
            @endphp

            Swal.fire({
                icon: @json($alert['type'] ?? 'info'),
                title: @json($alert['title'] ?? ucfirst($alert['type'] ?? 'Information')),
                text: @json($alert['message'] ?? ''),
                confirmButtonText: @json($alert['confirmButtonText'] ?? 'OK'),

                @if(isset($alert['timer']))
                    timer: {{ (int) $alert['timer'] }},
                    timerProgressBar: true,
                @endif

                @if(isset($alert['showConfirmButton']))
                    showConfirmButton: @json($alert['showConfirmButton']),
                @endif

                @if(isset($alert['allowOutsideClick']))
                    allowOutsideClick: @json($alert['allowOutsideClick']),
                @endif
            });

        @endif

    });
    
</script>
@endpush
