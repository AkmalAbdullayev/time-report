<div>
    @if (session()->has('successMessage'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:30px;">
            {{ session('successMessage') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session()->has("errorMessage"))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:30px;">
            {{ session('errorMessage') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Сотрудники</div>
        </div>
        <div class="card-body">
            <form action="{{route("employees.store")}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-4">
                                <label for="name">Фамилия <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="first_name"
                                    class="form-control"
                                    placeholder="Фамилия..."
                                    required="true"
                                >
                            </div>
                            <div class="form-group col-4">
                                <label for="name">Имя <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="last_name"
                                    class="form-control"
                                    placeholder="Имя..."
                                    required="true"
                                >
                            </div>
                            <div class="form-group col-4">
                                <label for="name">Отчество</label>
                                <input
                                    type="text"
                                    name="middle_name"
                                    class="form-control"
                                    placeholder="Отчество..."
                                >
                            </div>
                            <div class="form-group col-4">
                                <label for="description">ИНН НОМЕР</label>
                                <input name="description" type="number" placeholder="Введите ИНН номер..." class="form-control"/>
                            </div>
                            {{--                    <div class="mt-2 col-4">--}}
                            {{--                        <div class="input-group">--}}
                            {{--                            <div class="input-group-prepend">--}}
                            {{--                                <span class="input-group-text">ФОТО</span>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="custom-file">--}}
                            {{--                                <input type="file" class="custom-file-input" id="inputGroupFile01" name="photo">--}}
                            {{--                                <label class="custom-file-label" for="inputGroupFile01">Выберите файл...</label>--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            {{--                    </div>--}}

                            {{--                    <div class="col-4">--}}
                            {{--                        <div class="form-group">--}}
                            {{--                            <label for="phone">Телефон</label>--}}
                            {{--                            <input--}}
                            {{--                                type="tel"--}}
                            {{--                                class="form-control"--}}
                            {{--                                id="phone"--}}
                            {{--                                name="phone"--}}
                            {{--                            >--}}
                            {{--                        </div>--}}
                            {{--                    </div>--}}
                            <div class="form-group col-4">
                                <label for="">Организация</label>
                                <select name="company" class="form-control" required="true">
                                    <option disabled>Выберите...</option>
                                    @foreach ($companies as $k => $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-4">
                                <label for="">Филиал</label>
                                <select name="branch" class="form-control" required="true">
                                    <option selected disabled>Выберите...</option>
                                    @foreach ($branches as $k => $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-4">
                                <label for="section">Подразделение</label>
                                <select name="department" id="" class="form-control" required="true">
                                    <option disabled selected>Выберите...</option>
                                    @foreach ($departments as $k => $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-4">
                                <label for="position">Должность</label>
                                <select name="position" id="" class="form-control" required="true">
                                    <option disabled selected>Выберите...</option>
                                    @foreach($positions as $k => $position)
                                        <option value="{{$position->id}}">{{$position->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-4">
                                <label for="door">Шаблон доступа</label>
                                <select name="door[]" class="select2 select2-door form-control" required="true" multiple>
                                    @foreach ($doors as $k => $door)
                                        <option value="{{$door->id}}">{{$door->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <button type="submit" class="btn btn-primary btn-block mt-1">Добавить</button>
                            </div>
                        </div>
                    </form>
        </div>
    </div>


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Список сотрудников</div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-gray">
                        <th style="min-width: 50px">#</th>
                        <th>Действия</th>
                        <th style="min-width: 150px">Имя</th>
                        <th style="min-width: 150px">Организация</th>
                        <th style="min-width: 150px">Филиал</th>
                        <th style="min-width: 150px" title="Подразделения">Подразд...</th>
                        <th style="min-width: 150px">Должность</th>
                        <th>Фото</th>
                        <th style="min-width: 100px">Дверь</th>
                        <th style="min-width: 100px">ИНН</th>
                        <th>Отпечатка(FP)</th>
                    </tr>
                    <tr class="bg-gray">
                        <th></th>
                        <th></th>
                        <th>
                            <input type="text" wire:model="filterName"
                                   class="form-control form-control-sm" placeholder="Введите...">
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($employees as $k => $employee)
                        @foreach($employee->doors as $door)
                            <tr>
                                <td>{{$employee->id}}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="bx bxs-edit btn btn-outline-primary btn-sm" id="editButton"
                                            style="font-size:14px;" data-toggle="modal" data-target="#editModal"
                                            wire:click="edit({{$employee->id}}, {{$door->id}})">
                                        </button>
                                        <button title="Удалить" style="font-size:14px;"
                                            class="bx bxs-trash-alt btn btn-outline-danger btn-sm"
                                            wire:click="destroy({{$employee->id}},{{$door->id}})">
                                        </button>
                                    </div>
                                </td>
                                <td>{{$employee->first_name}} {{$employee->last_name[0]}}. {{$employee->middle_name[0]}}
                                    .
                                </td>
                                <td>{{$employee->companies->name}}</td>
                                <td>{{$employee->branches->name}}</td>
                                <td>{{$employee->departments->name}}</td>
                                <td>{{$employee->positions->name}}</td>
                                <td>
                                    <a href="{{ asset("assets/img/523-5233379_employee-management-system-logo-hd-png-download.png") }}" target="_blank">
                                        <img src="{{asset("assets/img/523-5233379_employee-management-system-logo-hd-png-download.png")}}"
                                            alt="{{$employee->full_name}}" width="50">
                                    </a>
                                </td>
                                <td class="text-success">{{$door->name}}</td>
                                <td>{{\Illuminate\Support\Str::limit($employee->description, 20)}}</td>
                                <td>
                                    @if ($door->pivot->employee_device_status === 1)
                                        @if ($employee->fp === 0)
                                            <form
                                                method="POST"
                                                action="{{route("add-fp", ["employee" => $employee->id, "door" => $door->id])}}"
                                                target="_blank"
                                            >
                                                @csrf
                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bd-example-modal-sm_{{$employee->id}}">Добавить</button>
                                                <div class="modal fade bd-example-modal-sm_{{$employee->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm modal-dialog-centered">
                                                        <div class="modal-content px-2 py-1">
                                                            <div class="form-group">
                                                                <label for="door">Выберите устройство <i class="text-danger">*</i></label>
                                                                <select name="choosed_device_ip" class="form-control" required="true">
                                                                    <option value="" disabled selected>Выберите</option>
                                                                    @foreach ($devices as $k => $device)
                                                                        <option value="{{$device->ip}}">{{$device->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <button class="btn btn-primary">Получить отпечаток</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        @else
                                            <button class="btn btn-success btn-sm" disabled>Добавлен</button>
                                        @endif
                                    @else
                                        <form method="POST"
                                              action="{{route("add-employee", ["employee" => $employee->id, "door" => $door->id])}}"
                                              target="_blank">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">Добавить сотрудника</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <p class="h3">No data found</p>
                    @endforelse
                    </tbody>
                </table>
            </div>


            <div class="mt-3 pb-2">
                {{$employees->links()}}
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
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error("child.name") is-invalid @enderror"
                                    placeholder="Введите имя..."
                                    wire:model="child.name"
                                >
                                @error("child.name")
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
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

                            <div class="mt-2 col-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">User Verify Mode</span>
                                    </div>

                                    <select name="verifyMode" class="form-control">
                                        <option selected disabled>Выберите...</option>
                                        @foreach ($userVerifyModes[0] as $k => $userVerifyMode)
                                            <option value="{{$k}}">{{$userVerifyMode}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                                <select
                                    class="form-control"
                                    wire:model="child.door_id"
                                    multiple
                                >
                                    @foreach ($doors as $k => $door)
                                        <option value="{{$door->id}}">{{$door->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="form-group col-12">
                            <label for="description">Инфо <span class="text-danger">*</span></label>
                            <textarea
                                name="description"
                                placeholder="Инфо..."
                                id="description"
                                cols="30"
                                rows="5"
                                class="form-control"
                                wire:model="child.description"
                            >
                            </textarea>
                        </div>
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
                        class="btn btn-primary ml-1"
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
        $(document).ready(function () {
            $("#phone").inputmask({
                mask: "+(\\9\\98) ** *** - ** - **",
                clearMaskOnLostFocus: false,
                placeholder: "_"
            });
        });

        document.getElementById("editButton").addEventListener("click", () => {
            $(".select2-door").select2({
                placeholder: "Выберите Дверь"
            });
        });

        window.addEventListener("select2", event => {
            $(".select2-door").select2({
                placeholder: "Выберите Дверь"
            });
        });

        window.addEventListener("closeModal", event => {
            $('#editModal').modal('hide');
        });
        $(".select2-door").select2({
            placeholder: "Выберите Дверь"
        });
    </script>
@endpush
