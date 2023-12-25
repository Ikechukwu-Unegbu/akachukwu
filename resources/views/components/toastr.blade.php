<link rel="stylesheet" href="{{ asset('pub-pages/vendors/toastr/toastr.min.css') }}">
<script src="{{ asset('pub-pages/vendors/toastr/toastr.min.js') }}"></script>
<script>

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "3000",
        "hideDuration": "10000",
        "timeOut": "50000",
        "extendedTimeOut": "10000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    @if(session('success'))
        toastr.success('{{ session('success') }}', 'Success')
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}', 'Error!')
    @endif

    @if(session('failed'))
        toastr.error('{{ session('failed') }}', 'Error!')
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}', 'Error!')
        @endforeach
    @endif
</script>
