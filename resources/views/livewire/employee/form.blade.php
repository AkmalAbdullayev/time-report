<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="title">Сотрудники</div>

        <a href="#" class="nav-link nav-link-label pt-0 pb-0" data-toggle="dropdown" aria-expanded="false">
            <i class="ficon bx bx-bell bx-tada bx-flip-horizontal danger" style="font-size: 25px;"></i>
            <span
                class="badge badge-pill badge-danger badge-up"
                style="font-size: 10px; top: 8px; right: 40px; padding: 0.25em 0.4em 0.18rem 0.35rem;"
            >
                {{ $errorTasks->count() }}
            </span>
        </a>


        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right" style="width: 50%;">
            <li class="dropdown-menu-header">
                <div class="dropdown-header px-1 py-75 d-flex justify-content-between">
                    <span class="notification-title">{{ $errorTasks->count() }} уведомление</span>
                </div>
            </li>
            @foreach($errorTasks as $errorTask)
                <li class="scrollable-container media-list ps">
                    <div class="d-flex justify-content-between cursor-pointer">
                        <div class="media d-flex align-items-center">
                            <div class="media-left pr-0">
                                <div class="avatar bg-primary bg-lighten-5 mr-1 m-0 p-25">
                                    <span class="avatar-content text-primary font-medium-2">N</span>
                                </div>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading">
                                    <span class="text-bold-500">{{ $errorTask->description }} <br>(ФИО : {{ $errorTask->employee }})</span>
                                </h6>
                                <small class="notification-text">{{ $errorTask->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="ps__rail-x" style="left: 0; bottom: 0;">
                        <div class="ps__thumb-x" tabindex="0" style="left: 0; width: 0;"></div>
                    </div>
                    <div class="ps__rail-y" style="top: 0; right: 0;">
                        <div class="ps__thumb-y" tabindex="0" style="top: 0; height: 0;"></div>
                    </div>
                </li>
            @endforeach
        </ul>

    </div>
    <div class="card-body">
        <form action="{{route("employees.store")}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-6">
                    <label for="name">Фамилия <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="last_name"
                        class="form-control @error('last_name') is-invalid @enderror"
                        placeholder="Фамилия..."
                        value="{{ old("last_name") }}"
                    >

                    @error('last_name')
                    <div class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror

                </div>
                <div class="form-group col-6">
                    <label for="name">Имя <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="first_name"
                        class="form-control @error('first_name') is-invalid @enderror"
                        placeholder="Имя..."
                        value="{{ old("first_name") }}"
                    >

                    @error('first_name')
                    <div class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror

                </div>
                <div class="form-group col-6">
                    <label for="name">Отчество</label>
                    <input
                        type="text"
                        name="middle_name"
                        class="form-control @error('middle_name') is-invalid @enderror"
                        placeholder="Отчество..."
                        value="{{ old("middle_name") }}"
                    >

                    @error('middle_name')
                    <div class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror

                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="number"
                               name="phone"
                               value="{{ old("phone") }}"
                               placeholder="998901234567"
                               class="form-control @error('child.phone') is-invalid @enderror"
                               >
                    </div>
                </div>

                <div class="form-group col-6">
                    <label for="tin">ИНН НОМЕР</label>
                    <input
                        name="tin"
                        type="number"
                        placeholder="Введите ИНН номер..."
                        class="form-control @error('tin') is-invalid @enderror"
                        value="{{ old("tin") }}"
                    />

                    @error('tin')
                    <div class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror

                </div>

                <div class="form-group col-6">
                    <label for="">Организация</label>
                    <select name="company" class="form-control @error('company') is-invalid @enderror">
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
                <div class="form-group col-6">
                    <label for="">Здание</label>
                    <select
                        name="branch"
                        id="createListBranches"
                        class="form-control @error('branch') is-invalid @enderror"
                    >
                        <option selected disabled>Выберите...</option>
                    </select>

                    @error('branch')
                    <div class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror

                </div>
                <div class="form-group col-6">
                    <label for="section">Подразделение</label>
                    <select name="department" id="createListDepartments"
                            class="form-control @error('department') is-invalid @enderror">
                        <option selected disabled>Выберите...</option>
                    </select>

                    @error('department')
                    <div class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror

                </div>
                <div class="form-group col-6">
                    <label for="position">Должность</label>
                    <select name="position" id="createListPositions"
                            class="form-control @error('position') is-invalid @enderror">
                        <option selected disabled>Выберите...</option>
                    </select>

                    @error('position')
                    <div class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror

                </div>

                <div class="form-group col-6">
                    <label for="door">Шаблон доступа</label>
                    <select name="door[]" class="select2 select2-door form-control" multiple>
                        @foreach ($doors as $k => $door)
                            <option value="{{ $door->id }}">{{ $door->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="photo">ФОТО</label>
                        <input type="file" name="photo" id="photo" class="form-control">
                        @error('photo')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>


                <div class="col-6">
                    <div class="form-group">
                        <label for="phone">Telegram ID</label>
                        <input
                            type="text"
                            class="form-control"
                            id="telegram_id"
                            name="telegram_id"
                            value="{{ old("telegram_id") }}"
                        >
                    </div>
                </div>

                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary btn-block mt-1">Добавить</button>
                </div>
            </div>
        </form>
    </div>
</div>
