@extends("layouts.master", ["title" => "Отчет персонала"])

@section("content")
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Опоздания и Ранние уходы</div>
        </div>

        <div class="card-body">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a
                        class="text-center nav-item nav-link @if ($tab == "default") active @endif"
                        id="nav-home-tab"
                        data-toggle="tab"
                        href="#nav-home"
                        role="tab"
                        aria-controls="nav-home"
                        aria-selected="true"
                        style="width: 50%;"
                    >
                        Присутствующие
                    </a>
                    <a
                        class="text-center nav-item nav-link @if ($tab == "absent") active @endif"
                        id="nav-profile-tab"
                        data-toggle="tab"
                        href="#nav-profile"
                        role="tab"
                        aria-controls="nav-profile"
                        aria-selected="false"
                        style="width: 47%;"
                    >
                        Отсутствующие сегодня
                    </a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div
                    class="tab-pane fade @if ($tab == "default") show active @endif"
                    id="nav-home"
                    role="tabpanel"
                    aria-labelledby="nav-home-tab"
                >
                    <a href="{{ route('exportPersonalReport') }}" target="_blank" class="btn btn-outline-success bx-pull-right mt-2">Export</a>
                    <form>
                        <div class="row">
                            <div class="form-group col-5">
                                <label for="employees">Сотрудники</label>
                                <select
                                    name="employees"
                                    id="employees"
                                    class="sele8ct2 form-control select2-hidden-accessible"
                                >
                                    <option selected disabled>Выберите...</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-4 position-relative has-icon-left">
                                <label for="date">Дата</label>
                                <input
                                    type="text"
                                    class="form-control date_from_to"
                                    id="date_from_to"
                                    placeholder="Выберите Дату..."
                                    name="date"
                                    value="{{ request('date') ?? date('d.m.Y') }}"
                                >
                                <div class="form-control-position mt-2">
                                    <i class="bx bx-calendar-check"></i>
                                </div>
                            </div>

                            <div class="col-3 mt-2">
                                <button type="submit" class="btn btn-primary">Составить Отчет</button>
                            </div>
                        </div>
                    </form>

                    <hr>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped text-center">
                            <thead>
                            <tr class="bg-light">
                                <th>#</th>
                                <th>Ф.И.О</th>
                                <th>График</th>
                                <th>Организация</th>
                                <th>Здание</th>
                                <th>Подразделения</th>
                                <th>Должность</th>
                                <th>Вход, Выход</th>
                                <th>Время Прибытия</th>
                            </tr>
                            <tr>
                                <form id="filter">
                                    <th colspan="3"></th>
                                    <th>
                                        <select
                                            name="company"
                                            id="company"
                                            class="form-control form-control-sm"
                                        >
                                            <option disabled selected>Выберите...</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                    <th>
                                        <select
                                            name="department"
                                            id="department"
                                            class="form-control form-control-sm"
                                        >
                                            <option disabled selected>Выберите...</option>
                                        </select>
                                    </th>
                                    <th colspan="2"></th>
                                    <th>
                                        <select
                                            name="attendance_status"
                                            id="attendance_status"
                                            class="form-control form-control-sm"
                                        >
                                            <option disabled selected>Выберите...</option>
                                            <option value="1" {{ request('attendance_status') == 1 ? 'selected' : '' }}>
                                                Опоздание
                                            </option>
                                            <option value="2" {{ request('attendance_status') == 2 ? 'selected' : '' }}>
                                                Во
                                                время(Вход)
                                            </option>
                                            <option value="3" {{ request('attendance_status') == 3 ? 'selected' : '' }}>
                                                Во
                                                время(Выход)
                                            </option>
                                            <option value="4" {{ request('attendance_status') == 4 ? 'selected' : '' }}>
                                                Заранее
                                            </option>
                                        </select>
                                    </th>
                                </form>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i = (($comeOuts->currentpage() - 1) * $comeOuts->perpage() + 1); @endphp
                            @forelse($comeOuts as $k => $comeOut)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ "{$comeOut->last_name} {$comeOut->first_name}" }}</td>
                                    <td {!! $comeOut->schedule_name ?? 'class="text-danger"' !!}>{{ $comeOut->schedule_name ?? 'Не связано график работы' }}</td>
                                    <td>{{ $comeOut->company_name }}</td>
                                    <td>{{ $comeOut->branch_name }}</td>
                                    <td>{{ $comeOut->department_name }}</td>
                                    <td>{{ $comeOut->position_name }}</td>
                                    {!! $comeOut->is_come == 1 ? '<td class="text-success">Вход</td>' : '<td class="text-danger">Выход</td>'!!}

                                    @isset($comeOut->range_from)
                                        @if($comeOut->is_come == 1)
                                            @if($comeOut->range_from >= date("H:i", strtotime($comeOut->action_time)))
                                                <td class="text-success">{{ $comeOut->action_time }} (ВО ВРЕМЯ)</td>
                                            @elseif($comeOut->range_from < date("H:i:s", strtotime($comeOut->action_time)))
                                                <td class="text-danger">{{ $comeOut->action_time }} (ОПОЗДАНИЕ)</td>
                                            @endif
                                        @elseif($comeOut->is_come == 0)
                                            @if($comeOut->range_to > date("H:i:s", strtotime($comeOut->action_time)))
                                                <td class="text-warning">{{ $comeOut->action_time }} (Заранее)</td>
                                            @elseif($comeOut->range_to <= date("H:i:s", strtotime($comeOut->action_time)))
                                                <td class="text-success">{{ $comeOut->action_time }} (ВО ВРЕМЯ)</td>
                                            @endif
                                        @endif
                                    @endisset
                                </tr>
                            @empty
                                <td colspan="9">Ничего не найдено.</td>
                            @endforelse

                            @php session()->flash("last_num", $i++) @endphp
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-2">
                        {{ $comeOuts->appends(request()->toArray())->links('pagination::bootstrap-4') }}
                    </div>
                </div>

                <div class="tab-pane fade @if($tab == "absent") show active @endif" id="nav-profile" role="tabpanel"
                     aria-labelledby="nav-profile-tab">
                    <a href="{{ route('exportPersonalReportToday') }}" target="_blank" class="btn btn-outline-success bx-pull-right mb-1">Export</a>
                    <x-absent-report/>
                </div>
            </div>

            {{--            @empty(!$workingHours)--}}
            {{--                <div class="mt-1">--}}
            {{--                    <h6>Сотрудник : {{ $workingHours }}</h6>--}}
            {{--                </div>--}}
            {{--            @endempty--}}
        </div>
    </div>
@endsection

@push("js")
    <script>
        $(document).ready(function () {
            $('select[name=company]').on('change', function () {
                $.get(`/admin/aj/departments/${this.value}`, function (data) {
                    if (data) {
                        $('#department').html('');
                        $("#department").append($(`<option selected disabled>Выберите...</option>`));
                        $.each(data, function (k, v) {
                            $('#department').append($('<option>', {value: v, text: k}));
                        });
                    }
                });

                $("#attendance_status").on("change", function () {
                    $("#filter").submit();
                });
            });
        });
        let start = moment().subtract(29, 'days');
        let end = moment();

        function cb(start, end) {
            $('.date_from_to').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $(".date_from_to").daterangepicker({
            locale: {
                format: "DD.MM.YYYY",
                customRangeLabel: "Произвольная дата",
                cancelLabel: "Очистить",
                applyLabel: "Применить"
            },
            startDate: start,
            endDate: end,
            ranges: {
                'Сегодня': [moment(), moment()],
                'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
                'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
                'Текущий месяц': [moment().startOf('month'), moment().endOf('month')],
                'Предыдущий месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);
    </script>
@endpush
