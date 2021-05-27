<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Permission</div>
        </div>

        <div class="card-body">
            <form action="{{ route("permission.store") }}" method="post">
                @csrf
                <div class="row col-12">
                    <div class="input-group mb-3">
                        <input
                            type="text"
                            id="permission"
                            name="permission"
                            class="form-control"
                            placeholder="Добавьте разрешение..."
                            aria-label="Добавьте разрешение..."
                            aria-describedby="basic-addon2"
                        >
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit">Добавить</button>
                        </div>
                    </div>
                </div>
            </form>

            <hr>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-gray">
                        <th>#</th>
                        <th>Название</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($permissions as $k => $permission)
                        <tr>
                            <td>{{ $k + 1 }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>
                                <button
                                    class="bx bxs-edit btn btn-primary btn-sm"
                                    style="font-size:10px;"
                                    data-toggle="modal"
                                    data-target="#editModal"
                                    wire:click="edit({{$permission}})"
                                ></button>
                                <button
                                    title="Удалить"
                                    class="bx bxs-trash-alt btn btn-danger btn-sm"
                                    style="font-size:10px;"
                                    wire:click="deleteConfirm({{$permission->id}})"
                                ></button>
                            </td>
                        </tr>
                    @empty
                        <td colspan="3">Ничего не найдено.</td>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="editModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="editModalLabel"
        aria-hidden="true"
        wire:ignore.self
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="cursor:move;">
                    <h5 class="modal-title" id="exampleModalLabel">Изменить</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editRole">Название</label>
                        <input
                            type="text"
                            class="form-control"
                            id="editRole"
                            wire:model.defer="edit.name"
                        >
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        wire:click="update"
                    >
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <script>
            toastr.success(`{{ session("success") }}`, 'Уведомление', {"progressBar": true});
        </script>
    @endif

    @if (session()->has('failed'))
        <script>
            toastr.error(`{{ session("failed") }}`, 'Уведомление', {"progressBar": true});
        </script>
    @endif

</div>

@push("js")
    <script>
        $(document).ready(function () {
            $(".modal-dialog").draggable({
                handle: ".modal-header",
            })
        });

        window.addEventListener("closeModal", () => {
            $("#editModal").modal("hide");
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
                .then(willDelete => {
                    if (willDelete.isConfirmed) {
                        window.livewire.emit("destroy", event.detail.id);

                        window.addEventListener("sweety:deleted", event => {
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
    </script>

    @if (session()->has('success'))
        <script>
            Swal.fire({
                icon: "success",
                title: `{{ session("success") }}`,
                text: "",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
@endpush
