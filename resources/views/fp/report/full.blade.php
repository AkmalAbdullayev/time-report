@extends("layouts.master", ["title" => "Общий отчет"])

@section("content")
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">ОБЩИЙ ОТЧЕТ</div>
        </div>

        <div class="card-body">
            <form>
                <div class="row">
                    <input type="hidden" name="company_id" value="{{ request('company_id') }}">
                    <input type="hidden" name="attendance_status" value="{{ request('attendance_status') }}">
                    <div class="form-group col-9 position-relative has-icon-left">
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
                        <th colspan="3"></th>
                        <th>
                            <form>
                                <select
                                    onchange="$(this).closest('form').submit()"
                                    name="company_id"
                                    id="company"
                                    class="form-control form-control-sm filter_select"
                                >
                                    <option selected value="">Выберите...</option>
                                    @foreach($companies as $company)
                                        <option
                                            value="{{ $company->id }}" {{request('company') == $company->id ? 'selected' : ''}}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </th>
                        <th colspan="4"></th>
                        <th>
                            <form>
                                <select
                                    onchange="$(this).closest('form').submit()"
                                    name="attendance_status"
                                    id="attendance_status"
                                    class="form-control form-control-sm filter_select"
                                >
                                    <option disabled selected>Выберите...</option>
                                    <option value="1" {{ request('attendance_status') == 1 ? 'selected' : '' }}>
                                        Опоздание
                                    </option>
                                    <option value="2" {{ request('attendance_status') == 2 ? 'selected' : '' }}>Во
                                        время(Вход)
                                    </option>
                                    <option value="3" {{ request('attendance_status') == 3 ? 'selected' : '' }}>Во
                                        время(Выход)
                                    </option>
                                    <option value="4" {{ request('attendance_status') == 4 ? 'selected' : '' }}>Заранее
                                    </option>
                                </select>
                            </form>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1; @endphp
                    @forelse($comeOuts as $comeOut)
                        <tr>
                            <td>{{ $comeOut->id }}</td>
                            <td>{{ $comeOut->first_name }}</td>
                            <td {!! $comeOut->schedule_name ?? 'class="text-danger"' !!}>{{ $comeOut->schedule_name ?? 'Не связано график работы' }}</td>
                            <td>{{ $comeOut->company_name }}</td>
                            <td>{{ $comeOut->branch_name }}</td>
                            <td>{{ $comeOut->department_name }}</td>
                            <td>{{ $comeOut->position_name }}</td>
                            {!! $comeOut->is_come === 1 ? '<td class="text-success">Вход</td>' :  '<td class="text-danger">Выход</td>'!!}

                            @isset($comeOut->range_from)
                                @if($comeOut->is_come == 1)
                                    @if($comeOut->range_from >= date("H:i", strtotime($comeOut->action_time)))
                                        <td class="text-success">{{ $comeOut->action_time }} (ВО ВРЕМЯ)</td>
                                    @elseif($comeOut->range_from < date("H:i", strtotime($comeOut->action_time)))
                                        <td class="text-danger">{{ $comeOut->action_time }} (ОПОЗДАНИЕ)</td>
                                    @endif
                                @else
                                    @if($comeOut->range_to > date("H:i", strtotime($comeOut->action_time)))
                                        <td class="text-warning">{{ $comeOut->action_time }} (Заранее)</td>
                                    @elseif($comeOut->range_to <= date("H:i", strtotime($comeOut->action_time)))
                                        <td class="text-success">{{ $comeOut->action_time }} (ВО ВРЕМЯ)</td>
                                    @endif
                                @endif
                            @endisset
                        </tr>
                    @empty
                        <td colspan="7">No Data.</td>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 pb-2">
        {{ $comeOuts->appends(request()->toArray())->links('pagination::bootstrap-4') }}
    </div>
@endsection

@push("js")
    <script>
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
