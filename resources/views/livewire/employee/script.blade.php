@if (session()->has("success"))
    <script>
        Swal.fire({
            icon: 'success',
            title: `{{ session("success") }}`,
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@endif

@if (session()->has("error"))
    <script>
        Swal.fire({
            icon: 'warning',
            title: `{{ session("error") }}`,
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

@if (session()->has("fingerError"))
    <script>
        Swal.fire({
            icon: "warning",
            title: `{{ session("fingerError") }}`,
            text: "",
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: "Да",
            cancelButtonText: "Нет"
        })
            .then(finger => {
                if (finger.isConfirmed) {
                    window.livewire.emit("addFinger", `{{ session("employee_id") }}`, `{{ session("device_id") }}`);
                }
            });
    </script>
@endif

<script>
    $(document).ready(function () {
        $("#phone").inputmask({
            mask: "+(\\9\\98) ** *** - ** - **",
            clearMaskOnLostFocus: false,
            placeholder: "_"
        });
    });

    window.addEventListener("sweety:confirm-finger", event => {
        Swal.fire({
            icon: event.detail.type,
            title: event.detail.title,
            text: event.detail.text,
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: "Удалить",
            cancelButtonText: "Отменить"
        });
    });

    window.addEventListener("sweety:error", event => {
        Swal.fire({
            icon: event.detail.type,
            title: event.detail.title,
            text: event.detail.text,
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.addEventListener("sweety:update", event => {
        Swal.fire({
            icon: event.detail.type,
            title: event.detail.title,
            text: event.detail.text,
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.addEventListener("sweety:bind-schedule", event => {
        Swal.fire({
            icon: event.detail.type,
            title: event.detail.title,
            text: event.detail.text,
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.addEventListener("sweety:confirm-delete", event => {
        Swal.fire({
            icon: event.detail.type,
            title: event.detail.title,
            text: event.detail.text,
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: "Удалить",
            cancelButtonText: "Отменить"
        })
            .then((willDelete) => {
                if (willDelete.isConfirmed) {
                    window.livewire.emit("destroy", event.detail.id);

                    window.addEventListener("sweety:isDeleted", event => {
                        Swal.fire({
                            icon: event.detail.type,
                            title: event.detail.title,
                            text: event.detail.text,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
                }
            });
    });

    // document.getElementById("editButton").addEventListener("click", () => {
    //     $(".select2-door").select2({
    //         placeholder: "Выберите"
    //     });
    // });

    window.addEventListener("select2", event => {
        $(".select2-door").select2({
            placeholder: "Выберите"
        });
    });

    window.addEventListener("closeModal", event => {
        $('#editModal').modal('hide');
        $('#scheduleModal').modal('hide');
    });

    $(".select2-door").select2({
        placeholder: "Выберите"
    });

    $('select[name=department]').on('change', function () {
        if (!this.value.length) return false;
        $.get(`/admin/aj/positions/${this.value}`, function (data) {
            if (data) {
                $('#createListPositions').html('');
                $("#createListPositions").append($(`<option selected disabled>Выберите...</option>`));
                $.each(data, function (k, v) {
                    $('#createListPositions').append($('<option>', {value: v, text: k}));
                });
            }
        });
    });

    $('select[name=company]').on('change', function () {
        if (!this.value.length) return false;
        $.get(`/admin/aj/branches/${this.value}`, function (data) {
            if (data) {
                $('#createListBranches').html('')
                $("#createListBranches").append($(`<option selected disabled>Выберите...</option>`));
                $.each(data, function (k, v) {
                    $('#createListBranches').append($('<option>', {value: v, text: k}));
                });
            }
        });

        $.get(`/admin/aj/departments/${this.value}`, function (data) {
            if (data) {
                $('#createListDepartments').html('');
                $("#createListDepartments").append($(`<option selected disabled>Выберите...</option>`));
                $.each(data, function (k, v) {
                    $('#createListDepartments').append($('<option>', {value: v, text: k}));
                });
            }
        });
    });
</script>
