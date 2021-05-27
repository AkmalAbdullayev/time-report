<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Устройства</div>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="store">
                <div class="form-row">
                    <div class="form-group col-6">
                        <label for="name">НАЗВАНИЕ <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="name"
                            placeholder="Введите название..."
                            class="form-control @error("device_name") is-invalid @enderror"
                            wire:model.defer="device_name"
                        >

                        @error('device_name')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror

                    </div>
                    <div class="form-group col-6">
                        <label for="name">IP <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="ip"
                            placeholder="Введите IP..."
                            class="form-control @error("device_ip") is-invalid @enderror"
                            wire:model.defer="device_ip"
                        >

                        @error("device_ip")
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror

                    </div>
                    <div class="form-group col-6">
                        <label for="name">ЛОГИН <span class="text-danger">*</span></label>
                        <input type="text" name="login" placeholder="Введите логин..."
                               class="form-control @error("device_login") is-invalid @enderror"
                               wire:model.defer="device_login">

                        @error("device_login")
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="form-group col-6">
                        <label for="name">ПАРОЛЬ <span class="text-danger">*</span></label>
                        <input type="text" name="password" placeholder="Введите пароль..."
                               class="form-control @error("device_password") is-invalid @enderror"
                               wire:model.defer="device_password">

                        @error("device_password")
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <button class="btn btn-primary btn-block">Добавить</button>
            </form>
        </div>
    </div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible mb-1">
            <div>{{ session('message') }}</div>
            <button type="button" class="close d-flex" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="card">
        <div class="card-header">Список устройств</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-gray">
                        <th width="50">#</th>
                        <th>Название</th>
                        <th>IP</th>
                        <th>Логин</th>
                        <th>Пароль</th>
                        {{--                        <th>Статус</th>--}}
                        <th>Активность</th>
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
                                       required
                                >
                            </form>
                        </th>
                        <th>
                            <form action="">
                                <input type="text" name="name" class="form-control form-control-sm"
                                       placeholder="Введите для поиска..." required>
                            </form>
                        </th>
                        <th>
                            <form action="" onsubmit="this.submit()">
                                <input type="text" name="name" class="form-control form-control-sm"
                                       placeholder="Введите для поиска..." required>
                            </form>
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 0; @endphp
                    @foreach($devices as $k => $device)
                        <tr>
                            <td>{{$k + 1}}</td>
                            <td>{{$device->name}}</td>
                            <td>{{$device->ip}}</td>
                            <td>{{$device->login}}</td>
                            <td>{{$device->password}}</td>
                            <td @if(empty($deviceStatus))
                                class="spinner-grow"
                                role="status"
                                @endif
                            >
                                {!! $deviceStatus[$i++] ?? '' !!}
                            </td>
                            {{--                                <td>{!! $device->checkDeviceStatus() ?? '<span class="badge badge-danger">Нет связи</span>' !!}</td>--}}
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button
                                        class="bx bxs-edit btn btn-primary btn-sm"
                                        style="font-size:10px;"
                                        data-toggle="modal"
                                        data-target="#editModal"
                                        wire:click="edit({{$device->id}})"
                                    ></button>
                                    <button
                                        title="Удалить"
                                        class="bx bxs-trash-alt btn btn-danger btn-sm"
                                        style="font-size:10px;"
                                        wire:click="deleteConfirm({{$device->id}})"
                                    ></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle"
         style="display: none;" aria-hidden="true" wire:ignore.self>
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
                        <div class="form-row col-12">
                            <div class="form-group col-6">
                                <label for="name">НАЗВАНИЕ <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="name"
                                    placeholder="Введите название..."
                                    class="form-control @error("parent.device_name") is-invalid @enderror"
                                    wire:model.defer="parent.device_name"
                                >

                                @error('parent.device_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="name">IP <span class="text-danger">*</span></label>
                                <input type="text" name="ip" placeholder="Введите IP..."
                                       class="form-control @error("parent.device_ip") is-invalid @enderror"
                                       wire:model.defer="parent.device_ip">

                                @error("parent.device_ip")
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row col-12">
                            <div class="form-group col-6">
                                <label for="name">ЛОГИН <span class="text-danger">*</span></label>
                                <input type="text" name="login" placeholder="Введите логин..."
                                       class="form-control @error("parent.device_login") is-invalid @enderror"
                                       wire:model.defer="parent.device_login">

                                @error("parent.device_login")
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="name">ПАРОЛЬ <span class="text-danger">*</span></label>
                                <input type="text" name="password" placeholder="Введите пароль..."
                                       class="form-control @error("parent.device_password") is-invalid @enderror"
                                       wire:model.defer="parent.device_password">

                                @error("parent.device_password")
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>

{{--                        <div class="form-row col-12">--}}
{{--                            <div class="form-group col-6">--}}
{{--                                <label for="name">СТАТУС <span class="text-danger">*</span></label>--}}
{{--                                <input type="text" name="status" placeholder="Введите статус..."--}}
{{--                                       class="form-control @error("parent.device_status") is-invalid @enderror"--}}
{{--                                       wire:model.defer="parent.device_status">--}}

{{--                                @error("parent.device_status")--}}
{{--                                <div class="invalid-feedback" role="alert">--}}
{{--                                    <strong>{{ $message }}</strong>--}}
{{--                                </div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <div class="form-group col-6">--}}
{{--                                <label for="name">АКТИВНОСТЬ <span class="text-danger">*</span></label>--}}
{{--                                <input type="text" name="isActive" placeholder="Введите активность..."--}}
{{--                                       class="form-control @error("parent.device_activity") is-invalid @enderror"--}}
{{--                                       wire:model.defer="parent.device_activity">--}}

{{--                                @error("parent.device_activity")--}}
{{--                                <div class="invalid-feedback" role="alert">--}}
{{--                                    <strong>{{ $message }}</strong>--}}
{{--                                </div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"
                            wire:click.prevent="clear">
                        Закрыть
                    </button>

                    <button type="button" class="btn btn-primary ml-1" data-dismiss="modal" wire:click.prevent="update">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@push("js")
    <script>
        window.onload = () => {
            window.livewire.emit("deviceStatusEvent");
        }

        window.addEventListener("refresh", () => {
            location.reload();
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

        window.addEventListener("sweety:update", () => {
            Swal.fire({
                icon: 'success',
                title: "Успешно обновлено!!!",
                showConfirmButton: false,
                timer: 1500
            });
        });

        window.addEventListener("sweety:create", event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.type,
                showConfirmButton: false,
                timer: 1500
            });
        })
    </script>
@endpush

