<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Шаблоны доступа</div>
        </div>

        <div class="card-body">
            <form action="{{route("doors.store")}}" method="post">
                @csrf
                <div class="row">
                    <div class="form-group col-3">
                        <select name="name" class="form-control @error("name") is-invalid @enderror" required>
                            <option value="" selected disabled>Выберите дверь *</option>
                            @foreach($doors as $door_item)
                                <option value="{{ $door_item->id }}">{{ $door_item->name }}</option>
                            @endforeach
                        </select>
                        @error("name")
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <div class="form-group col-4">
                        <select class="select2 select2-device form-control form-control-sm" name="devices[]" multiple>
                            <option disabled>Выберите...</option>
                            @foreach($devices as $k => $device)
                                <option value="{{$device->id}}">{{$device->name}}</option>
                            @endforeach
                        </select>

                        @error("devices")
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <div class="form-group col-3">
                        <select name="in_out" class="form-control" required>
                            <option value="" selected disabled>Выберите *</option>
                            <option value="1">Вход</option>
                            <option value="0">Выход</option>
                            <option value="2">Не важно</option>
                        </select>
                    </div>
                    <div class="form-group col-2">
                        <button type="submit" class="btn btn-primary btn-block" id="saveBtn">
                            Добавить
                        </button>
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
                        <th>Девайс</th>
                        <th>Направление</th>
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
                            <form action="" onsubmit="this.submit()">
                                <input type="text"
                                       name="device"
                                       class="form-control form-control-sm"
                                       placeholder="Введите для поиска..."
                                       required
                                >
                            </form>
                        </th>
                        <th>
{{--                            <form action="">--}}
{{--                                <select name="" class="form-control form-control-sm">--}}
{{--                                    <option value="">Выбрать</option>--}}
{{--                                </select>--}}
{{--                            </form>--}}
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($door_devices as $k => $dd)
                        <tr>
                            <td>{{ $k+1 }}</td>
                            <td>{{ $dd->doors->name ?? '' }}</td>
                            <td>{{ $dd->device->name ?? '' }} ({{ $dd->device->ip ?? '' }})</td>
                            <td>
                                <div class="badge badge-primary">{{ $dd->is_come == 0 ? 'Выход' : ($dd->is_come == 1 ? 'Вход' : 'Не важно')  }}</div>
                            </td>
                            <td>
                                <button
                                    class="bx bxs-edit btn btn-primary btn-sm"
                                    style="font-size:10px;"
                                    data-toggle="modal"
                                    data-target="#editModal"
                                    wire:click="edit({{$dd->id}}, {{$dd->id}})"
                                ></button>
                                <button
                                    title="Удалить"
                                    class="bx bxs-trash-alt btn btn-danger btn-sm"
                                    style="font-size:10px;"
                                    type="button"
                                    wire:click="deleteConfirm({{$dd->id}},{{$dd->id}})"
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
        aria-labelledby="editModalTitle"
        style="display: none;"
        aria-hidden="true"
        wire:ignore.self
    >
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Изменить</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input
                            type="text"
                            id="role"
                            name="name"
                            class="form-control @error("name") is-invalid @enderror"
                            placeholder="Добавьте дверь..."
                            wire:model="door.device_name"
                        >
                        <br>
                        <select
                            class="form-control @error("device") is-invalid @enderror"
                            multiple="multiple"
                            wire:model="device.id"
                        >
                            <option disabled>Выберите...</option>
                            @foreach($devices as $k => $device)
                                <option value="{{$device->id}}">{{$device->name}}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light-secondary"
                        data-dismiss="modal"
                        wire:click.prevent="clear"
                    >
                        Закрыть
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary ml-1 confirm"
                        wire:click.prevent="update"
                    >
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push("js")
    @if (session()->has("message"))
        <script>
            Swal.fire({
                icon: 'success',
                title: `{{ session("message") }}`,
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    @endif

    @if (session()->has("errorMessage"))
        <script>
            toastr.error(`{{ session("errorMessage") }}`, "Уведомление", {"progressBar": true});
        </script>
    @endif

    <script>
        window.addEventListener("confirm-delete", event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.type,
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonText: "Удалить",
                cancelButtonText: "Отменить"
            })
                .then((willDelete) => {
                    if (willDelete) {
                        window.livewire.emit("destroy", event.detail.door_id, event.detail.device_id);

                        Swal.fire({
                            icon: 'success',
                            title: "Успешно Удалено!!!",
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
        });

        window.addEventListener("updateModel", event => {
            Swal.fire({
                icon: 'success',
                title: "Успешно Обновлено!!!",
                showConfirmButton: false,
                timer: 1500
            });
        })

        window.addEventListener("closeModal", event => {
            $("#editModal").modal("hide");
        });

        window.addEventListener("select2", event => {
            $(".select2-device").select2({
                placeholder: "Выберите Устройство"
            });
        });

        $(".select2-device").select2({
            placeholder: "Выберите Устройство"
        })
    </script>
@endpush
