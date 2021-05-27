<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Пользователы</div>
        </div>

        <div class="card-body">
            <form action="{{ route("users.store") }}" method="post">
                @csrf
                <div class="row col-12">
                    <div class="form-group col-6">
                        <label for="name">Имя <span class="text-danger">*</span></label>
                        <input type="text" name="name" placeholder="Введите Имя" class="form-control">
                    </div>
                    <div class="form-group col-6">
                        <label for="email">Электронная Почта <span class="text-danger">*</span></label>
                        <input type="email" name="email" placeholder="Введите электронную почту" class="form-control">
                    </div>
                </div>

                <div class="row col-12">
                    <div class="form-group col-6">
                        <label for="password">Пароль <span class="text-danger">*</span></label>
                        <input type="text" name="password" placeholder="Введите Пароль" class="form-control">
                    </div>
                    <div class="form-group col-4">
                        <div class="input-group mt-2 mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Роль <span class="text-danger">*</span></label></label>
                            </div>
                            <select class="custom-select" id="inputGroupSelect01" name="role">
                                <option selected disabled>Выберите...</option>
                                @foreach($roles as $k => $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-success mt-2">Добавить</button>
                    </div>
                </div>
            </form>
            <hr>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-gray">
                        <th>#</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Действия</th>
                    </tr>
                    <tr class="bg-gray">
                        <th></th>
                        <th>
                            <form action="" onsubmit="this.submit()">
                                <input type="text"
                                       name="name"
                                       class="form-control form-control-sm"
                                       placeholder="Введите для поиска..."
                                       value="{{ request('name') }}"
                                       required
                                >
                            </form>
                        </th>
                        <th>
                            <form action="">
                                <input type="text"
                                       name="name"
                                       class="form-control form-control-sm"
                                       placeholder="Введите для поиска..."
                                       value="{{ request('name') }}"
                                       required
                                >
                            </form>
                        </th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $k => $user)
                        <tr>
                            <td>{{ $k + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->map->name[0] ?? $user->roles->map->name }}</td>
                            <td>
                                <button
                                    class="bx bxs-edit btn btn-primary btn-sm"
                                    style="font-size:10px;"
                                    data-toggle="modal"
                                    data-target="#editModal"
                                    wire:click="edit({{ $user }})"
                                ></button>
                                <button
                                    title="Удалить"
                                    class="bx bxs-trash-alt btn btn-danger btn-sm"
                                    style="font-size:10px;"
                                    wire:click="deleteConfirm({{ $user->id }})"
                                ></button>
                            </td>
                        </tr>
                    @endforeach
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
                        <label for="editUser">Название</label>
                        <input
                            type="text"
                            class="form-control"
                            id="editUser"
                            wire:model.defer="edit.name"
                        >
                    </div>

                    <div class="form-group">
                        <label for="editRole">Роль</label>
                        <select
                            name="editRole"
                            id="editRole"
                            wire:model.defer="edit.roles"
                            class="form-control"
                        >
                            @if (array_key_exists("roles", $edit))

                                <option selected value="">Выберите...</option>
                                @foreach ($roles as $role)
                                    @if ($edit["roles"] !== $role->id)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endif
                                @endforeach

                            @endif
                        </select>
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
</div>

@push("js")
    <script>
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
@endpush
