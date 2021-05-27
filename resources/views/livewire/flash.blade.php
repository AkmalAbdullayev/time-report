@foreach (session()->all() as $session)
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:30px;">
        {{ session('successMessage')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endforeach

@if (session()->has('errorMessage'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:30px;">
        {{ session("errorMessage") }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@push("js")
    @if (session()->has("errorMessage"))
        <script !src="">
            toastr.error(`{{ session("errorMessage") }}`, 'Уведомление', {"progressBar": true});
        </script>
    @endif
@endpush

@if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:30px;">
        {{ session('message')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
