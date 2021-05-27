@if ($message = Session::get('successMessage'))
    <script !src="">
        alertify.success(`{{ $message }}`);
    </script>
@endif
@if ($message = Session::get('success'))
    <script !src="">
        alertify.success(`{{ $message }}`);
    </script>
@endif
@if ($message = Session::get('error'))
    <script !src="">
        alertify.error(`{{ $message }}`);
    </script>
@endif
@if ($message = Session::get('status'))
    <script !src="">
        alertify.success(`{{ $message }}`);
    </script>
@endif
@if ($message = Session::get('danger'))
    <script !src="">
        alertify.error(`{{ $message }}`);
    </script>
@endif
@if ($message = Session::get('warning'))
    <script !src="">
        alertify.warning(`{{ $message }}`);
    </script>
@endif
@if ($message = Session::get('info'))
    <script !src="">
        alertify.message(`{{ $message }}`);
    </script>
@endif
@if ($errors->any())
    @foreach($errors->all() as $error)
        <script !src="">
            alertify.error(`{{ $error }}`);
        </script>
    @endforeach
@endif

@push('js')
    <script>
        // window.addEventListener('alert', event => {
        //     // alert(event.detail.message);
        //     var type_message = event.detail.type;
        //     var message = event.detail.message;
        //
        //     switch (type_message) {
        //         case 'success':
        //             alertify.success(message);
        //             break;
        //         case 'error':
        //             alertify.error(message);
        //             break;
        //         case 'warning':
        //             alertify.warning(message);
        //             break;
        //         default:
        //             alertify.message(message);
        //
        //     }
        // });
        //
        // window.addEventListener('log', event => {
        //     console.log(event.detail);
        // });

    </script>
@endpush
