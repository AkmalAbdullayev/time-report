<div class="modal fade" id="editModal" tabindex="-1" role="dialog"
     aria-labelledby="editModalTitle" style="display: none;" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalCenterTitle">Изменить</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-row col-12">
                        <div class="form-group col-4">
                            <label for="name">Фамилия <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('child.last_name') is-invalid @enderror"
                                placeholder="Фамилия..."
                                required="true"
                                wire:model.defer="child.last_name"
                            >

                            @error('child.last_name')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror

                        </div>

                        <div class="form-group col-4">
                            <label for="name">Имя <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error("child.first_name") is-invalid @enderror"
                                placeholder="Введите имя..."
                                wire:model.defer="child.first_name"
                            >

                            @error("child.first_name")
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>

                        <div class="form-group col-4">
                            <label for="name">Отчество <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error("child.middle_name") is-invalid @enderror"
                                placeholder="Введите имя..."
                                wire:model.defer="child.middle_name"
                            >

                            @error("child.middle_name")
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>

                        {{--                        <div class="mt-2 col-4">--}}
                        {{--                            <div class="input-group">--}}
                        {{--                                <div class="input-group-prepend">--}}
                        {{--                                    <span class="input-group-text">ФОТО</span>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="custom-file">--}}
                        {{--                                    <input type="file" class="custom-file-input" id="inputGroupFile01" name="photo">--}}
                        {{--                                    <label class="custom-file-label" for="inputGroupFile01">Выберите файл...</label>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        {{--                        <div class="mt-2 col-4">--}}
                        {{--                            <div class="input-group">--}}
                        {{--                                <div class="input-group-prepend">--}}
                        {{--                                    <span class="input-group-text">User Verify Mode</span>--}}
                        {{--                                </div>--}}

                        {{--                                <select name="verifyMode" class="form-control">--}}
                        {{--                                    <option selected disabled>Выберите...</option>--}}
                        {{--                                    @foreach ($userVerifyModes[0] as $k => $userVerifyMode)--}}
                        {{--                                        <option value="{{$k}}">{{$userVerifyMode}}</option>--}}
                        {{--                                    @endforeach--}}
                        {{--                                </select>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>

                    <div class="form-row col-12">
                        <div class="form-group col-4">
                            <label for="phone">Телефон</label>
                            <input type="number"
                                   name="phone"
                                   placeholder="998901234567"
                                   class="form-control @error('child.phone') is-invalid @enderror"
                                   wire:model.defer="child.phone"
                            >
                        </div>
                        <div class="form-group col-4">
                            <label for="phone">Telegram ID</label>
                            <input
                                type="text"
                                class="form-control @error('child.telegram_id') is-invalid @enderror"
                                wire:model.defer="child.telegram_id"
                            >
                        </div>

                        <div class="form-group col-4">
                            <label for="tin">ИНН НОМЕР</label>
                            <input
                                type="number"
                                placeholder="Введите ИНН номер..."
                                class="form-control @error('tin') is-invalid @enderror"
                                wire:model.defer="child.description"
                            />

                            @error('tin')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror

                        </div>

                        <div class="form-group col-3">
                            <label for="">Организация</label>
                            <select
                                name="modalCompany"
                                class="form-control @error('company') is-invalid @enderror"
                                required="true"
                                wire:model.defer="child.company_id"
                            >
                                <option>Выберите...</option>
                                @foreach ($companies as $k => $company)
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                @endforeach
                            </select>

                            @error('company')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror

                        </div>

                        <div class="form-group col-3">
                            <label for="">Здание</label>
                            <select
                                id="modalBranches"
                                class="form-control @error('branch') is-invalid @enderror"
                                required="true"
                                wire:model.defer="child.branch_id"
                            >
                                <option selected>Выберите...</option>
                            </select>

                            @error('branch')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror

                        </div>

                        <div class="form-group col-3">
                            <label for="section">Подразделение</label>
                            <select
                                id="modalDepartments"
                                class="form-control @error('department') is-invalid @enderror"
                                required="true"
                                wire:model.defer="child.department_id"
                                name="modalDepartments"
                            >
                                <option selected>Выберите...</option>
                            </select>

                            @error('department')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror

                        </div>

                        <div class="form-group col-3">
                            <label for="position">Должность</label>
                            <select
                                id="modalPositions"
                                class="form-control @error('position') is-invalid @enderror"
                                required="true"
                                wire:model.defer="child.position_id"
                            >
                                <option selected>Выберите...</option>
                            </select>

                            @error('position')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror

                        </div>
                    </div>

                    <div class="form-group col-12">
                        <label for="modalDoor">Шаблон доступа</label>
                        <select
                            name="modalDoor[]"
                            id="modalDoor"
                            class="form-control"
                            required="true"
                            multiple
                            wire:model.defer="child.door_id"
                        >
                            @foreach ($doors as $k => $door)
                                <option value="{{$door->id}}">{{$door->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal" wire:click.prevent="clear">
                    Закрыть
                </button>

                <button
                    type="button"
                    class="btn btn-primary ml-1"
                    wire:click.prevent="update"
                >
                    Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog"
     aria-labelledby="permissionModalTitle" style="display: none;" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <span class="preloader" wire:loading></span>
            <div class="modal-header" style="cursor: move">
                <h5 class="modal-title" id="exampleModalCenterTitle1">Доступы к пользователю:
                    <strong>{{ $employeeDoors[0]->employee->first_name ?? '-' }}</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <table class="table-bordered table table-sm text-center">
                    <thead>
                    <tr width="10">
                        <th width="10">#</th>
                        <th width="10">Существует ли отпечаток</th>
                        {{--                        <th width="10">Количество отсканированных отпечаток</th>--}}
                        <th width="10">Добавлено ли на устройства</th>
                        <th width="10">Шаблон доступа</th>
                        <th width="10">Устройство</th>
                        <th width="10">Статус устройства</th>
                        <th width="10">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $fingerExists = $inDeviceExists = 0; $deviceStatus = ''; ?>
                    @foreach($employeeDoors as $k => $employeeDoor)
                        <?php
                        $deviceStatus = $employeeDoor->doorDevice->device->checkDeviceStatus(true);
                        $fingerExists += ($employeeDoor->employee_finger_status !== 1 && $employeeDoor->doorDevice->device->checkDeviceStatus()) ? 1 : 0;
                        $inDeviceExists += ($employeeDoor->employee_device_status !== 1 && $employeeDoor->doorDevice->device->checkDeviceStatus()) ? 1 : 0;
                        ?>
                        <tr class="permissionModal">
                            <td>{{ $k + 1 }}</td>
                            <td>{!! $employeeDoor->employee_finger_status == 1 ? '<span class="badge badge-success">Да</span>' : '<span class="badge badge-danger">Нет</span>' !!}</td>
                            {{--                            <td>{{ $employeeDoor->employee->fingersCount }}</td>--}}
                            <td>{!! $employeeDoor->employee_device_status === 1 ? '<span class="badge badge-success">Да</span>' : '<span class="badge badge-danger">Нет</span>' !!}</td>
                            <td>{!! isset($employeeDoor->door->name) ? '<span class="badge badge-primary">'. $employeeDoor->door->name .'</span>' : NULL !!}</td>
                            <td>{{ ($employeeDoor->doorDevice->device->name .' ('.$employeeDoor->doorDevice->device->ip.')') ?? '' }}</td>
                            <td>{!! $employeeDoor->doorDevice->device->checkDeviceStatus() !!}</td>
                            <td>
                                <button
                                    {{ $deviceStatus ? '' : 'disabled' }}
                                    type="button"
                                    class="bx bx-fingerprint btn btn-warning btn-sm"
                                    style="font-size: 14px;"
                                    title="Получить отпечаток"
                                    wire:click.prevent="addFinger({{ $employeeDoor->employee->id ?? NULL }}, '{{ $employeeDoor->doorDevice->device->id }}')"
                                >
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button
                    class="btn btn-primary"
                    {{ $inDeviceExists > 0 ? '' : 'disabled' }}
                    wire:click.prevent="connectWithDevice({{ $employeeDoors[0]->employee->id ?? '-' }})"
                >
                    Связать с устройством
                </button>
                <button type="button" class="btn btn-light-danger" data-dismiss="modal" wire:click.prevent="clear">
                    Закрыть
                </button>
            </div>
        </div>
    </div>
</div>

@isset($model)
    <div class="modal fade" id="selectDevice" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content px-2 py-1">
                <h5>Сотрудник : {{ $model->full_name }}</h5>
                {{--                <h5>Устройство : {{ $deviceInfo[0]->name ?? '' }} ({{ $deviceInfo[0]->ip ?? '' }})</h5>--}}
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a
                            class="w-50 text-center nav-link active"
                            id="nav-home-tab"
                            data-toggle="tab"
                            href="#nav-home"
                            role="tab"
                            aria-controls="nav-home"
                            aria-selected="true"
                        >
                            Получить новый отпечаток
                        </a>

                        <a
                            class="w-50 text-center nav-link"
                            id="nav-profile-tab"
                            data-toggle="tab"
                            href="#nav-profile"
                            role="tab"
                            aria-controls="nav-profile"
                            aria-selected="false"
                        >
                            Копировать отпечаток
                        </a>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                         aria-labelledby="nav-home-tab">
                        <form
                            method="post"
                            action="{{ route("add-fp", ["employee" => $model->id ?? 1, "type" => "new", "device" => $deviceId ?? '']) }}"
                            {{--                            target="_blank"--}}
                        >
                            @csrf
                            <x-new-finger :model="$model"/>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <form
                            method="post"
                            action="{{ route("add-fp", ["employee" => $model->id ?? 1, "type" => "existing", "device" => $deviceId ?? '']) }}"
                            {{--                            target="_blank"--}}
                        >
                            @csrf
                            <x-existing-finger :model="$model" :deviceId="$deviceId"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endisset


<div class="modal fade" id="editModal" tabindex="-1" role="dialog"
     aria-labelledby="editModalTitle" style="display: none;" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalCenterTitle">Изменить</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-row col-12">
                        <div class="form-group col-4">
                            <label for="name">Имя <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error("child.name") is-invalid @enderror"
                                   placeholder="Введите имя..." wire:model="child.name">
                            @error("child.name")
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                        <div class="mt-2 col-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">ФОТО</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputGroupFile01" name="photo">
                                    <label class="custom-file-label" for="inputGroupFile01">Выберите файл...</label>
                                </div>
                            </div>
                        </div>

                        {{--                        <div class="mt-2 col-4">--}}
                        {{--                            <div class="input-group">--}}
                        {{--                                <div class="input-group-prepend">--}}
                        {{--                                    <span class="input-group-text">User Verify Mode</span>--}}
                        {{--                                </div>--}}

                        {{--                                <select name="verifyMode" class="form-control">--}}
                        {{--                                    <option selected disabled>Выберите...</option>--}}
                        {{--                                    @foreach ($userVerifyModes[0] as $k => $userVerifyMode)--}}
                        {{--                                        <option value="{{$k}}">{{$userVerifyMode}}</option>--}}
                        {{--                                    @endforeach--}}
                        {{--                                </select>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>

                    <div class="form-row col-12">
                        <div class="form-group col-4 mt-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Отдел</span>
                                </div>

                                <select name="department" class="form-control" wire:model="child.department_id">
                                    <option selected disabled>Выберите...</option>
                                    @foreach ($companies as $k => $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-4 mt-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Должность</span>
                                </div>

                                <select name="position" class="form-control" wire:model="child.position_id">
                                    <option selected disabled>Выберите...</option>
                                    @foreach ($branches as $k => $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-4 mt-2">
                            <select class="form-control" wire:model="child.door_id" multiple>
                                @foreach ($doors as $k => $door)
                                    <option value="{{$door->id}}">{{$door->name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="form-group col-12">
                        <label for="description">Инфо <span class="text-danger">*</span></label>
                        <textarea name="description" placeholder="Инфо..." id="description"
                                  cols="30" rows="5" class="form-control" wire:model="child.description">
                            </textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal" wire:click.prevent="clear">
                    Закрыть
                </button>

                <button
                    type="button"
                    class="btn btn-primary ml-1"
                    wire:click="update"
                >
                    Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog"
     aria-labelledby="scheduleModalTitle" style="display: none;" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white" id="exampleModalCenterTitle">График Работы</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group col-12">
                        <label for="schedule">График</label>
                        <select name="schedule" id="schedule" class="form-control schedule">
                            <option disabled selected>Выберите...</option>
                            @foreach($schedules as $k => $schedule)
                                <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal" wire:click.prevent="clear">
                    Закрыть
                </button>

                <button
                    type="button"
                    class="btn btn-primary ml-1"
                    wire:click="scheduleSave"
                >
                    Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

@push("js")
    <script>
        $(document).ready(function () {
            $("#modalPhone").inputmask({
                mask: "+(\\9\\98) ** *** - ** - **",
                clearMaskOnLostFocus: false,
                placeholder: "_"
            });

            $(".schedule").on("change", function (event) {
            @this.set('schedule', event.target.value);
            });

            $("#permissionModal").draggable({
                handle: ".modal-header",
            });
        });

        $('select[name=modalDepartments]').on('change', function () {
            if (!this.value.length) return false;
            $.get(`/admin/aj/positions/${this.value}`, function (data) {
                if (data) {
                    $('#modalPositions').html('');
                    $("#modalPositions").append($(`<option selected>Выберите...</option>`));
                    $.each(data, function (k, v) {
                        $('#modalPositions').append($('<option>', {value: v, text: k}));
                    });
                }
            });
        });

        $('select[name=modalCompany]').on('change', function () {
            if (!this.value.length) return false;
            $.get(`/admin/aj/branches/${this.value}`, function (data) {
                if (data) {
                    $('#modalBranches').html('')
                    $("#modalBranches").append($(`<option selected>Выберите...</option>`));
                    $.each(data, function (k, v) {
                        $('#modalBranches').append($('<option>', {value: v, text: k}));
                    });
                }
            });

            $.get(`/admin/aj/departments/${this.value}`, function (data) {
                if (data) {
                    $('#modalDepartments').html('');
                    $("#modalDepartments").append($(`<option selected>Выберите...</option>`));
                    $.each(data, function (k, v) {
                        $('#modalDepartments').append($('<option>', {value: v, text: k}));
                    });
                }
            });
        });
    </script>
@endpush
