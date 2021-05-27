@extends("layouts.master", ["title" => "Отчеты"])

@section("content")
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Отчёт посещения сотрудников в указанном диапазоне для выбранных подразделений</div>
            <a href="{{ route('exportTemporaryReport') . '?date=' . request('date') . '&employees=' . request('employees') . '&page=' . request('page') }}" target="_blank" class="btn btn-outline-success">Export</a>
        </div>

        <div class="card-body">
            <form autocomplete="off">
                <div class="form-row">
                    <div class="form-group col-6">
                        <label for="employees">Сотрудники</label>
                        <select
                            name="employees"
                            id="employees"
                            class="select2 form-control select2-hidden-accessible"
                        >
                            <option selected disabled>Выберите...</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employees') == $employee->id ? 'selected':'' }}>{{ $employee->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6">
                        <label for="company">Организация</label>
                        <select
                            name="company"
                            id="company"
                            class="form-control"
                        >
                            <option selected value="">Выберите...</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected':'' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6">
                        <label for="department">Подразделения</label>
                        <select
                            name="department"
                            id="department"
                            class="form-control"
                        >
                            <option value="">Выберите...</option>
                        </select>
                    </div>

                    <div class="col-6 position-relative has-icon-left">
                        <label for="date_from_to">Дата</label>
                        <input
                            type="text"
                            class="form-control initial-empty date_from_to"
                            id="date_from_to"
                            placeholder="Выберите Дату..."
                            name="date"
                            value="{{ request('date') }}"
                        >
                        <div class="form-control-position mt-2">
                            <i class="bx bx-calendar-check"></i>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary">Составить Отчет</button>
                </div>
            </form>

            <hr>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-light">
                        <th>#</th>
                        <th>Ф.И.О</th>
                        <th>Организация</th>
                        <th>Здание</th>
                        <th>Подразделения</th>
                        <th>Должность</th>
                        <th>Норма</th>
                        <th>
                            <a href="{{ route('temporary-report') . '?company=' . request('company') . '&date=' . request('date') . '&department=' . request('department') }}&day_count={{ request('day_count') == 'asc' ? 'desc' : 'asc' }}" style="color: #475F7B">
                                {!! request('day_count') == 'desc' ? '<span class="bx bx-sort-z-a text-center"></span>' : '<span class="bx bx-sort-a-z"></span>' !!}
                                Посещения сотрудника (за месяц)
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('temporary-report') . '?company=' . request('company') . '&date=' . request('date') . '&department=' . request('department') }}&work_time={{ request('work_time') == 'asc' ? 'desc' : 'asc' }}" style="color: #475F7B">
                                {!! request('work_time') == 'desc' ? '<span class="bx bx-sort-z-a text-center"></span>' : '<span class="bx bx-sort-a-z"></span>' !!}
                                Время
                            </a>
                        </th>
{{--                        <th>Рабочее время</th>--}}
                        <th>ID Сотрудника</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = (($employeeReports->currentpage() - 1) * $employeeReports->perpage() + 1); @endphp
                    @forelse ($employeeReports as $employeeReport)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $employeeReport->short_full_name ?? '' }}</td>
                            <td>{{ $employeeReport->companies->name ?? ''}}</td>
                            <td>{{ $employeeReport->branches->name ?? ''}}</td>
                            <td>{{ $employeeReport->departments->name ?? ''}}</td>
                            <td>{{ $employeeReport->positions->name ?? ''}}</td>
                            <td class="{{ $normDay <= $employeeReport->work_time ? 'text-success':'danger' }}">{{ $normDay ?? ''}}</td>
                            <td class="{{ $normDay <= $employeeReport->work_time ? 'text-success':'danger' }}">{{ $employeeReport->day_count ?? ''}}</td>
                            <td class="{{ $normDay <= $employeeReport->work_time ? 'text-success':'danger' }}">{{ $employeeReport->work_time ?? ''}}</td>
{{--                            <td>{{ 1 }} часов</td>--}}
                            <td>{{ $employeeReport->id ?? ''}}</td>
                        </tr>
                    @empty
                        <td colspan="9">Ничего не найдено.</td>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="ml-2 pb-2">
            {{ $employeeReports->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection

@push("js")
    <script>
        $(document).ready(function () {
            $("#company").on("change", function () {
                if (!this.value.length) return false;

                $.get(`/admin/aj/departments/${this.value}`, function (data) {
                    if (data) {
                        $('#department').html('');
                        $("#department").append($(`<option selected disabled>Выберите...</option>`));
                        $.each(data, function (k, v) {
                            $('#department').append($('<option>', {value: v, text: k}));
                        });
                    }
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
        });
    </script>
@endpush
