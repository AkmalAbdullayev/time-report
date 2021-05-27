@push("js")
    @if (session()->has("success"))
        <script>
            toastr.success(`{{ session("success") }}`, 'Уведомление', {"progressBar": true});
        </script>
    @endif
@endpush
