<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="title">Список сотрудников</div>
    </div>

    <div class="card-body" x-data="{ active: @entangle('isActive') }">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a
                    class="nav-link {{ $isActive === true ? "active" : '' }}"
                    id="nav-active-tab"
                    data-toggle="tab"
                    href="#nav-active"
                    role="tabpanel"
                    aria-controls="nav-active"
                    aria-selected="true"
                    style="width: 50%; text-align: center"
                    @click="active = true"
                >
                    Активные Сотрудники
                </a>

                <a
                    class="nav-link {{ $isActive === false ? "active" : '' }}"
                    id="nav-fired-tab"
                    data-toggle="tab"
                    href="#nav-fired"
                    role="tab"
                    aria-controls="nav-fired"
                    aria-selected="false"
                    style="width: 50%; text-align: center"
                    @click="active = false"
                >
                    Уволенные Сотрудники
                </a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div
                class="tab-pane fade {{ $isActive === true ? 'active show' : '' }} table-responsive"
                id="nav-active"
                aria-labelledby="nav-active-tab"

            >
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-light">
                        <th
                            style="min-width: 50px"
                            @if (!$isAsc)
                            wire:click="$set('isAsc', true)"
                            @else
                            wire:click="$set('isAsc', false)"
                            @endif
                        >
                            #
                        </th>
                        <th>Действия</th>
                        <th>График</th>
                        <th
                            style="min-width: 150px; padding-bottom: 10px;"
                            @if (!$isAsc)
                            wire:click="$set('isAsc', true)"
                            @else
                            wire:click="$set('isAsc', false)"
                            @endif
                        >
                            {!! $isAsc ? '<span class="bx bx-sort-z-a text-center"></span>' : '<span class="bx bx-sort-a-z"></span>' !!}
                            Имя
                        </th>
                        <th
                            style="min-width: 150px"
                            @if (!$isAsc)
                            wire:click="$set('isAsc', true)"
                            @else
                            wire:click="$set('isAsc', false)"
                            @endif
                        >
                            {!! $isAsc ? '<span class="bx bx-sort-z-a text-center"></span>' : '<span class="bx bx-sort-a-z"></span>' !!}
                            Организация
                        </th>
                        <th
                            style="min-width: 150px"
                            @if (!$isAsc)
                            wire:click="$set('isAsc', true)"
                            @else
                            wire:click="$set('isAsc', false)"
                            @endif
                        >
                            {!! $isAsc ? '<span class="bx bx-sort-z-a text-center"></span>' : '<span class="bx bx-sort-a-z"></span>' !!}
                            Здание
                        </th>
                        <th
                            style="min-width: 150px"
                            @if (!$isAsc)
                            wire:click="$set('isAsc', true)"
                            @else
                            wire:click="$set('isAsc', false)"
                            @endif
                            title="Подразделения"
                        >
                            {!! $isAsc ? '<span class="bx bx-sort-z-a text-center"></span>' : '<span class="bx bx-sort-a-z"></span>' !!}
                            Подразд...
                        </th>
                        <th
                            style="min-width: 150px"
                            @if (!$isAsc)
                            wire:click="$set('isAsc', true)"
                            @else
                            wire:click="$set('isAsc', false)"
                            @endif
                        >
                            {!! $isAsc ? '<span class="bx bx-sort-z-a text-center"></span>' : '<span class="bx bx-sort-a-z"></span>' !!}
                            Должность
                        </th>
                        <th>Фото</th>
                        <th style="min-width: 100px">ИНН</th>
                        <td style="min-width: 100px">Telegram ID</td>
                        <td style="min-width: 100px">ID</td>
                    </tr>
                    <tr class="bg-gray">
                        <th colspan="3"></th>
                        <th>
                            <input
                                type="search"
                                class="form-control form-control-sm"
                                placeholder="Введите..."
                                id="filterName"
                                wire:model="filterName"
                            >
                        </th>
                        <th>
                            <input
                                type="text"
                                wire:model="filterOrganization"
                                class="form-control form-control-sm"
                                placeholder="Введите..."
                            >
                        </th>
                        <th colspan="4"></th>
                        <th>
                            <input
                                wire:model="itn"
                                id="itn"
                                type="search"
                                class="form-control form-control-sm"
                                placeholder="Введите..."
                            >
                        </th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = (($employees->currentpage() - 1) * $employees->perpage() + 1); @endphp
                    @forelse($employees as $k => $employee)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>
                                <div class="d-flex justify-content-between align-content-center">
                                    <div class="btn-group btn-group-sm mr-1">
                                        <button
                                            class="bx bxs-edit btn btn-outline-primary btn-sm"
                                            id="editButton"
                                            style="font-size:14px;"
                                            wire:click="edit({{$employee->id}})"
                                            data-toggle="modal"
                                            data-target="#editModal"
                                        >
                                        </button>

                                        <button
                                            title="Удалить"
                                            style="font-size:14px;"
                                            class="bx bxs-trash-alt btn btn-outline-danger btn-sm"
                                            wire:click="sweetyDeleteConfirm({{$employee->id}})"
                                        >
                                        </button>

                                        <button
                                            title="Доступы"
                                            style="font-size:14px;"
                                            wire:click="showPermissions({{$employee->id}})"
                                            class="bx bxs-key btn btn-outline-danger btn-sm"
                                            data-toggle="modal"
                                            data-target="#permissionModal"
                                            id="permissionButton"
                                        >
                                        </button>
                                        <button
                                            title="График Работы"
                                            style="font-size:14px;"
                                            wire:click="schedule({{$employee->id}})"
                                            class="bx bxs-time btn btn-outline-primary btn-sm"
                                            data-toggle="modal"
                                            data-target="#scheduleModal"
                                            id="scheduleButton"
                                        >
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td {!! isset($employee->schedule->name) ? '' : 'class="text-danger"' !!}>{{ $employee->schedule->name ?? 'Нет график работы' }}</td>
                            <td>{{ $employee->short_full_name }}</td>
                            <td>{{ $employee->companies->name }}</td>
                            <td>{{ $employee->branches->name }}</td>
                            <td>{{ $employee->departments->name }}</td>
                            <td>{{ $employee->positions->name }}</td>
                            <td>
                                <a
                                    href="{{ $employee->image !== null ? asset("storage/{$employee->image}") : asset("assets/img/523-5233379_employee-management-system-logo-hd-png-download.png") }}"
                                    target="_blank"
                                >
                                    <img
                                        src="{{ $employee->image !== null ? asset("storage/{$employee->image}") : asset("assets/img/523-5233379_employee-management-system-logo-hd-png-download.png") }}"
                                        alt="{{$employee->full_name}}"
                                        width="50"
                                    >
                                </a>
                            </td>
                            <td>{{ $employee->description }}</td>
                            <td>{{ $employee->telegram_id }}</td>
                            <td>{{ $employee->id }}</td>
                            {{--                            <td>--}}
                            {{--                                @if ($door->pivot->employee_device_status === 1)--}}
                            {{--                                    @if ($employee->fp === 0)--}}
                            {{--                                        <form--}}
                            {{--                                            method="POST"--}}
                            {{--                                            action="{{route("add-fp", ["employee" => $employee->id, "door" => $door->id])}}"--}}
                            {{--                                            target="_blank"--}}
                            {{--                                        >--}}
                            {{--                                            @csrf--}}
                            {{--                                            <button class="btn btn-danger btn-sm" data-toggle="modal"--}}
                            {{--                                                    data-target=".bd-example-modal-sm_{{$employee->id}}">Добавить--}}
                            {{--                                            </button>--}}
                            {{--                                            <div class="modal fade bd-example-modal-sm_{{$employee->id}}" tabindex="-1"--}}
                            {{--                                                 role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">--}}
                            {{--                                                <div class="modal-dialog modal-sm modal-dialog-centered">--}}
                            {{--                                                    <div class="modal-content px-2 py-1">--}}
                            {{--                                                        <div class="form-group">--}}
                            {{--                                                            <label for="door">Выберите устройство <i--}}
                            {{--                                                                    class="text-danger">*</i></label>--}}
                            {{--                                                            <select name="choosed_device_ip" class="form-control"--}}
                            {{--                                                                    required="true">--}}
                            {{--                                                                <option value="" disabled selected>Выберите</option>--}}
                            {{--                                                                @foreach ($devices as $k => $device)--}}
                            {{--                                                                    <option--}}
                            {{--                                                                        value="{{$device->ip}}">{{$device->name}}</option>--}}
                            {{--                                                                @endforeach--}}
                            {{--                                                            </select>--}}
                            {{--                                                        </div>--}}
                            {{--                                                        <button class="btn btn-primary">Получить отпечаток</button>--}}
                            {{--                                                    </div>--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                        </form>--}}
                            {{--                                    @else--}}
                            {{--                                        <button class="btn btn-success btn-sm" disabled>Добавлен</button>--}}
                            {{--                                    @endif--}}
                            {{--                                @else--}}
                            {{--                                    <form method="POST"--}}
                            {{--                                          action="{{route("add-employee", ["employee" => $employee->id, "door" => $door->id])}}"--}}
                            {{--                                          target="_blank">--}}
                            {{--                                        @csrf--}}
                            {{--                                        <button class="btn btn-danger btn-sm">Добавить сотрудника</button>--}}
                            {{--                                    </form>--}}
                            {{--                                @endif--}}
                            {{--                            </td>--}}
                        </tr>
                    @empty
                        <p class="h3">Нет записей</p>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade table-responsive {{ $isActive === false ? "active show" : '' }}" id="nav-fired"
                 role="tabpanel"
                 aria-labelledby="nav-fired-tab">
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-light">
                        <th>#</th>
                        <th>ИМЯ</th>
                        <th>ОРГАНИЗАЦИЯ</th>
                        <th>ЗДАНИЕ</th>
                        <th>Подразделения</th>
                        <th>Должность</th>
                        <th>ФОТО</th>
                        <th>ИНН</th>
                        <th>Telegram ID</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>
                            <input
                                type="text"
                                wire:model="deletedEmployeeName"
                                class="form-control form-control-sm"
                                placeholder="Введите..."
                            >
                        </th>
                        <th colspan="7"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($firedEmployees as $k => $firedEmployee)
                        <tr>
                            <td>{{ $firedEmployee->id }}</td>
                            <td>{{ $firedEmployee->short_full_name }}</td>
                            <td>{{ $firedEmployee->companies->name }}</td>
                            <td>{{ $firedEmployee->branches->name}}</td>
                            <td>{{ $firedEmployee->departments->name}}</td>
                            <td>{{ $firedEmployee->positions->name}}</td>
                            <td>
                                <a
                                    href="{{ asset("assets/img/523-5233379_employee-management-system-logo-hd-png-download.png") }}"
                                    target="_blank"
                                >
                                    <img
                                        src="{{asset("assets/img/523-5233379_employee-management-system-logo-hd-png-download.png")}}"
                                        alt="{{ $firedEmployee->full_name}}"
                                        width="50"
                                    >
                                </a>
                            </td>
                            <td>{{ $firedEmployee->description }}</td>
                            <td>{{ $firedEmployee->telegram_id }}</td>
                            @empty
                                <td colspan="9">СПИСОК ПУСТ.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($isActive)
            <div class="mt-3 pb-2">
                {{ $employees->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="mt-3 pb-2">
                {{ $firedEmployees->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>
