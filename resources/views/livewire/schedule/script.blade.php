<script>
    window.addEventListener("closeModal", () => {
        $("#editModal").modal("hide");
    });

    $(document).ready(function () {
        $("#editModal").draggable({
            handle: ".modal-header",
        });
    });

    window.addEventListener("sweety:create", event => {
        Swal.fire({
            icon: event.detail.type,
            title: event.detail.title,
            text: event.detail.text,
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.addEventListener("sweety:updated", event => {
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
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                window.livewire.emit("destroy", event.detail.id);

                window.addEventListener("sweety:deleted", event => {
                    Swal.fire({
                        icon: event.detail.type,
                        title: event.detail.title,
                        text: event.detail.text,
                        showConfirmButton: false,
                        timer: 1500
                    })
                });
            }
        });
    });
</script>
