@extends("layouts.master", ["title" => "Дверь"])

@section("content")
    <div class="card">
        <div class="card-header">Выходные</div>
        <div class="card-body">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a
                        class="text-center nav-item nav-link {{ !isset($weekendExclude) && !isset($attendanceSetting) ? 'active':'' }}"
                        id="nav-home-tab"
                        data-toggle="tab"
                        href="#nav-home"
                        role="tab"
                        aria-controls="nav-home"
                        aria-selected="true"
                        style="width: 32%;"
                    >
                        Каникулы
                    </a>
                    <a
                        class="text-center nav-item nav-link {{ isset($weekendExclude) ? 'active':'' }}"
                        id="nav-weekend-excludes-tab"
                        data-toggle="tab"
                        href="#nav-weekend-excludes"
                        role="tab"
                        aria-controls="nav-profile"
                        aria-selected="false"
                        style="width: 32%;"
                    >
                        Выходные не включают
                    </a>
                    <a
                        class="text-center nav-item nav-link {{ isset($attendanceSetting) ? 'active':'' }}"
                        id="nav-attendance-settings-tab"
                        data-toggle="tab"
                        href="#nav-attendance-settings"
                        role="tab"
                        aria-controls="nav-profile"
                        aria-selected="false"
                        style="width: 32%;"
                    >
                        Настройки посещаемости
                    </a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div
                    class="tab-pane fade {{ !isset($weekendExclude) && !isset($attendanceSetting) ? 'show active':'' }}"
                    id="nav-home"
                    role="tabpanel"
                    aria-labelledby="nav-home-tab"
                >
                    <form class="form form-row" method="POST" action="{{ route('holiday') }}">
                        @csrf
                        @if(isset($holiday))
                            <input type="hidden" name="holiday_id" value="{{ $holiday->id }}">
                            <input type="hidden" name="method" value="update">
                        @else
                            <input type="hidden" name="method" value="create">
                        @endif
                        <div class="col-4">
                            <div class="form-group">
                                <select class="form-control form-control-sm @error('type') is-invalid @enderror" name="type" autofocus required>
                                    <option value="">Выберите...</option>
                                    <option value="0" {{ isset($holiday) && $holiday->type == 0 ? 'selected':'' }}>Регулярный отпуск</option>
                                    <option value="1" {{ isset($holiday) && $holiday->type == 1 ? 'selected':'' }}>Нерегулярный отпуск</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" value="{{ old('name', isset($holiday) ? $holiday->name : NULL) }}" name="name" placeholder="Введите название *" autofocus required>
                                @error('name')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="date" class="form-control date-picker form-control-sm @error('start_date') is-invalid @enderror" value="{{ old('start_date', isset($holiday) ? $holiday->start_date : NULL) }}" name="start_date" placeholder="Введите дата *" autofocus required>
                                @error('start_date')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="number" step="1" max="99" class="form-control form-control-sm @error('number_of_days') is-invalid @enderror" value="{{ old('number_of_days', isset($holiday) ? $holiday->number_of_days : NULL) }}" name="number_of_days" placeholder="Введите Количество дней *" autofocus required>
                                @error('number_of_days')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group row">
                                <label for="repeat_annually" style="text-align: end;" class="col-8">Повторять ежегодно</label>
                                <input type="checkbox" id="repeat_annually" {{ isset($holiday) && $holiday->repeat_annually == 1 ? 'checked="checked':'' }} class="form-control form-control-sm col-2" name="repeat_annually">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-block btn-{{ isset($holiday) ? 'primary' : 'success' }}">{{ isset($holiday) ? 'Обновить' : 'Добавить' }}</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm text-center">
                            <thead>
                            <tr class="bg-gray">
                                <th>#</th>
                                <th>Действия</th>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>Дата начала</th>
                                <th>К-тво дней</th>
                                <th>Р-тать как с-чную р-ту</th>
                                <th>П-рять е-дно</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($holidays->count())
                                @foreach($holidays as $k => $holiday)
                                    <tr>
                                        <td width="50">{{ $k+1 }}</td>
                                        <td width="150">
                                            <form action="{{ route('holiday') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="method" value="delete">
                                                <input type="hidden" name="holiday_id" value="{{ $holiday->id }}">
                                                <div class="btn-group">
                                                    <a class="bx bxs-edit btn btn-primary btn-sm" href="{{ route('holiday') }}?method=edit&holiday_id={{ base64_encode($holiday->id) }}" style="font-size:10px;" title="Изменить"></a>
                                                    <button class="bx bxs-trash-alt btn btn-danger btn-sm confirm" style="font-size:10px;" title="Удалить"></button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-left">{{ $holiday->name }}</td>
                                        <td class="text-left">{{ $holiday->type == 0 ? 'Регулярный':'Нерегулярный' }}</td>
                                        <td class="text-left">{{ date('d.m.Y', strtotime($holiday->start_date)) }}</td>
                                        <td class="text-left">{{ $holiday->number_of_days }}</td>
                                        <td class="text-left">{{ $holiday->calculate_as_overtime }}</td>
                                        <td class="text-left">{{ $holiday->repeat_annually == 1 ? 'Включить':'Выключить' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8"><i class="small">Пусто</i></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div
                    class="tab-pane fade {{ isset($weekendExclude) ? 'show active':'' }}"
                    id="nav-weekend-excludes"
                    role="tabpanel"
                    aria-labelledby="nav-weekend-excludes-tab"
                >
                    <form class="form form-row" action="{{ route('weekend.exclude') }}" method="POST">
                        @csrf
                        @if(isset($weekendExclude))
                            <input type="hidden" name="weekend_exclude_id" value="{{ $weekendExclude->id }}">
                            <input type="hidden" name="method" value="update">
                        @else
                            <input type="hidden" name="method" value="create">
                        @endif
                        <div class="col-5">
                            <div class="form-group">
                                <input type="date" class="form-control form-control-sm @error('date') is-invalid @enderror" value="{{ old('date', isset($weekendExclude) ? $weekendExclude->date : NULL) }}" name="date" placeholder="Введите дата *" autofocus required>
                                @error('date')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm @error('description') is-invalid @enderror" value="{{ old('description', isset($weekendExclude) ? $weekendExclude->description : NULL) }}" name="description" placeholder="Введите Название" autofocus>
                                @error('description')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-block btn-{{ isset($weekendExclude) ? 'primary' : 'success' }}">{{ isset($weekendExclude) ? 'Обновить' : 'Добавить' }}</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm text-center">
                            <thead>
                            <tr class="bg-gray">
                                <th>#</th>
                                <th>Действия</th>
                                <th>Название</th>
                                <th>Дата</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($weekendExcludes as $k => $weekendExclude)
                                    <tr>
                                        <td width="50">{{ $k+1 }}</td>
                                        <td width="150">
                                            <form action="{{ route('weekend.exclude') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="method" value="delete">
                                                <input type="hidden" name="weekend_exclude_id" value="{{ $weekendExclude->id }}">
                                                <div class="btn-group">
                                                    <a class="bx bxs-edit btn btn-primary btn-sm" href="{{ route('weekend.exclude') }}?method=edit&weekend_exclude_id={{ base64_encode($weekendExclude->id) }}" style="font-size:10px;" title="Изменить"></a>
                                                    <button class="bx bxs-trash-alt btn btn-danger btn-sm confirm" style="font-size:10px;" title="Удалить"></button>
                                                </div>
                                            </form>
                                        </td>
                                        <td>{{ $weekendExclude->description }}</td>
                                        <td class="text-left" width="200">{{ date('d.m.Y', strtotime($weekendExclude->date)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"><i class="small">Пусто</i></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div
                    class="tab-pane fade {{ isset($attendanceSetting) ? 'show active':'' }}"
                    id="nav-attendance-settings"
                    role="tabpanel"
                    aria-labelledby="nav-attendance-settings-tab"
                >
                    <form class="form form-row" action="{{ route('attendance.setting') }}" method="POST">
                        @csrf
                        @if(isset($attendanceSetting))
                            <input type="hidden" name="attendance_setting_id" value="{{ $attendanceSetting->id }}">
                            <input type="hidden" name="method" value="update">
                        @else
                            <input type="hidden" name="method" value="create">
                        @endif
                        <div class="col-4">
                            <div class="form-group">
                                <select class="form-control form-control-sm @error('weekend') is-invalid @enderror" name="weekend" autofocus required>
                                    <option value="">Выберите...</option>
                                    @foreach($weekDays as $weekDay)
                                        <option value="{{ $weekDay['number'] }}" {{ isset($attendanceSetting) && $attendanceSetting->weekend == $weekDay['number'] ? 'selected':'' }}>
                                            {{ $weekDay['day'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('weekend')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <input type="date" class="form-control form-control-sm @error('calculate_at') is-invalid @enderror" value="{{ old('calculate_at', isset($attendanceSetting) ? $attendanceSetting->calculate_at : NULL) }}" name="calculate_at" placeholder="Введите дата *" autofocus required>
                                @error('calculate_at')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select class="form-control form-control-sm @error('authentication_mode') is-invalid @enderror" name="authentication_mode" autofocus required>
                                    <option value="">Выберите...</option>
                                    @foreach($authenticationModes as $key => $authenticationMode)
                                        <option value="{{ $key }}" {{ isset($attendanceSetting) && $attendanceSetting->authentication_mode == $key ? 'selected':'' }}>
                                            {{ $authenticationMode }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('authentication_mode')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-block btn-{{ isset($weekendExclude) ? 'primary' : 'success' }}">{{ isset($weekendExclude) ? 'Обновить' : 'Добавить' }}</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm text-center">
                            <thead>
                            <tr class="bg-gray">
                                <th>#</th>
                                <th>Действия</th>
                                <th>Название</th>
                                <th>Рассчитать на</th>
                                <th>Режим аутентификации</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($attendanceSettings as $k => $attendanceSetting)
                                <tr>
                                    <td width="50">{{ $k+1 }}</td>
                                    <td width="150">
                                        <form action="{{ route('attendance.setting') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="method" value="delete">
                                            <input type="hidden" name="attendance_setting_id" value="{{ $attendanceSetting->id }}">
                                            <div class="btn-group">
                                                <a class="bx bxs-edit btn btn-primary btn-sm" href="{{ route('attendance.setting') }}?method=edit&attendance_setting_id={{ base64_encode($attendanceSetting->id) }}" style="font-size:10px;" title="Изменить"></a>
                                                <button class="bx bxs-trash-alt btn btn-danger btn-sm confirm" style="font-size:10px;" title="Удалить"></button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>{{ getDayName($attendanceSetting->weekend) }}</td>
                                    <td class="text-left" width="200">{{ date('d.m.Y', strtotime($attendanceSetting->calculate_at)) }}</td>
                                    <td>{{ getAuthenticationModes($attendanceSetting->authentication_mode) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"><i class="small">Пусто</i></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        history.pushState({}, null, location.origin+location.pathname);
    </script>
@endpush
