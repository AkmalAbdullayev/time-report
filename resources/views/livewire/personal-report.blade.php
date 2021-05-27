<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Опоздания и Ранние уходы</div>
        </div>

        <div class="card-body">
            <div class="row" wire:ignore>
                <div class="form-group col-5">
                    <label for="employees">Сотрудники</label>
                    <select
                        name="employees"
                        id="employees"
                        class="form-control"
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
                        class="form-control date_picker"
                        id="date_picker"
                        placeholder="Выберите Дату..."
                        autocomplete="off"
                    >
                    <div class="form-control-position mt-2">
                        <i class="bx bx-calendar-check"></i>
                    </div>
                </div>

                <div class="col-3 mt-2">
                    <button type="button" class="btn btn-primary" wire:click="check">Составить Отчет</button>
                </div>

            </div>

            <hr>
            <x-table>
                <x-slot name="head">
                    <x-table.heading>#</x-table.heading>
                    <x-table.heading>Ф.И.О</x-table.heading>
                    <x-table.heading>График</x-table.heading>
                    <x-table.heading>Организация</x-table.heading>
                    <x-table.heading>Здание</x-table.heading>
                    <x-table.heading>Подразделения</x-table.heading>
                    <x-table.heading>Должность</x-table.heading>
                    <x-table.heading>Вход, Выход</x-table.heading>
                    <x-table.heading>Время Прибытия</x-table.heading>
                    <x-table.heading>ID</x-table.heading>

                    <x-table.row>
                        <x-table.heading colspan="3"></x-table.heading>
                        <x-table.heading>
                            <form>
                                <select
                                    name="company"
                                    id="company"
                                    class="form-control form-control-sm"
                                    wire:model="company_filter"
                                >
                                    <option selected value="">Выберите...</option>
                                    @foreach($companies as $company)
                                        <option
                                            value="{{ $company->id }}" {{request('company') == 4 ? 'selected' : ''}}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </x-table.heading>
                        <x-table.heading colspan="4"></x-table.heading>
                        <x-table.heading>
                            <form>
                                <select
                                    onchange="$(this).closest('form').submit()"
                                    name="attendance_status"
                                    id="attendance_status"
                                    class="form-control form-control-sm"
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
                        </x-table.heading>
                        <x-table.heading></x-table.heading>
                    </x-table.row>
                </x-slot>

                <x-slot name="body">
                    @php $i = (($comeOuts->currentpage() - 1) * $comeOuts->perpage() + 1); @endphp
                    @forelse ($comeOuts as $comeOut)
                        <x-table.row>
                            <x-table.cell>{{ $i++ }}</x-table.cell>
                            <x-table.cell>{{ $comeOut->first_name }}</x-table.cell>
                            <x-table.cell>{{ $comeOut->schedule_name }}</x-table.cell>
                            <x-table.cell>{{ $comeOut->company_name }}</x-table.cell>
                            <x-table.cell>{{ $comeOut->branch_name }}</x-table.cell>
                            <x-table.cell>{{ $comeOut->department_name }}</x-table.cell>
                            <x-table.cell>{{ $comeOut->position_name }}</x-table.cell>
                            <x-table.cell></x-table.cell>
                            <x-table.cell>{{ $comeOut->action_time }}</x-table.cell>
                            <x-table.cell>{{ $comeOut->employee_id }}</x-table.cell>

                            {{--                            <x-table.cell>{{ $i++ }}</x-table.cell>--}}
                            {{--                            <x-table.cell>{{ $comeOut->employees->first_last_name }}</x-table.cell>--}}
                            {{--                            <x-table.cell>{{ $comeOut->employees->schedule->name }}</x-table.cell>--}}
                            {{--                            <x-table.cell>{{ $comeOut->employees->companies->name }}</x-table.cell>--}}
                            {{--                            <x-table.cell>{{ $comeOut->employees->branches->name }}</x-table.cell>--}}
                            {{--                            <x-table.cell>{{ $comeOut->employees->departments->name }}</x-table.cell>--}}
                            {{--                            <x-table.cell>{{ $comeOut->employees->positions->name }}</x-table.cell>--}}
                            {{--                            <x-table.cell></x-table.cell>--}}
                            {{--                            <x-table.cell>{{ $comeOut->action_time }}</x-table.cell>--}}
                            {{--                            <x-table.cell>{{ $comeOut->employees->id }}</x-table.cell>--}}
                        </x-table.row>
                    @empty
                        <x-table.cell colspan="10">Ничего не найдено.</x-table.cell>
                    @endforelse
                </x-slot>
            </x-table>
            {{--            <div class="table-responsive">--}}
            {{--                <table class="table table-sm table-bordered table-striped text-center">--}}
            {{--                    <thead>--}}
            {{--                    <tr class="bg-light">--}}
            {{--                        <th>#</th>--}}
            {{--                        <th>Ф.И.О</th>--}}
            {{--                        <th>График</th>--}}
            {{--                        <th>Организация</th>--}}
            {{--                        <th>Здание</th>--}}
            {{--                        <th>Подразделения</th>--}}
            {{--                        <th>Должность</th>--}}
            {{--                        <th>Вход, Выход</th>--}}
            {{--                        <th>Время Прибытия</th>--}}
            {{--                        <th>ID</th>--}}
            {{--                    </tr>--}}
            {{--                    <tr>--}}
            {{--                        <th colspan="3"></th>--}}
            {{--                        <th>--}}
            {{--                            <form>--}}
            {{--                                <select--}}
            {{--                                    name="company"--}}
            {{--                                    id="company"--}}
            {{--                                    class="form-control form-control-sm"--}}
            {{--                                    wire:model="company_filter"--}}
            {{--                                >--}}
            {{--                                    <option disabled selected>Выберите...</option>--}}
            {{--                                    @foreach($companies as $company)--}}
            {{--                                        <option--}}
            {{--                                            value="{{ $company->id }}" {{request('company') == 4 ? 'selected' : ''}}>{{ $company->name }}</option>--}}
            {{--                                    @endforeach--}}
            {{--                                </select>--}}
            {{--                            </form>--}}
            {{--                        </th>--}}
            {{--                        <th colspan="4"></th>--}}
            {{--                        <th>--}}
            {{--                            <form>--}}
            {{--                                <select--}}
            {{--                                    onchange="$(this).closest('form').submit()"--}}
            {{--                                    name="attendance_status"--}}
            {{--                                    id="attendance_status"--}}
            {{--                                    class="form-control form-control-sm"--}}
            {{--                                >--}}
            {{--                                    <option disabled selected>Выберите...</option>--}}
            {{--                                    <option value="1" {{ request('attendance_status') == 1 ? 'selected' : '' }}>--}}
            {{--                                        Опоздание--}}
            {{--                                    </option>--}}
            {{--                                    <option value="2" {{ request('attendance_status') == 2 ? 'selected' : '' }}>Во--}}
            {{--                                        время(Вход)--}}
            {{--                                    </option>--}}
            {{--                                    <option value="3" {{ request('attendance_status') == 3 ? 'selected' : '' }}>Во--}}
            {{--                                        время(Выход)--}}
            {{--                                    </option>--}}
            {{--                                    <option value="4" {{ request('attendance_status') == 4 ? 'selected' : '' }}>Заранее--}}
            {{--                                    </option>--}}
            {{--                                </select>--}}
            {{--                            </form>--}}
            {{--                        </th>--}}
            {{--                        <th></th>--}}
            {{--                    </tr>--}}
            {{--                    </thead>--}}
            {{--                    <tbody>--}}
            {{--                    @php $i = (($comeOuts->currentpage() - 1) * $comeOuts->perpage() + 1); @endphp--}}
            {{--                    @forelse($comeOuts as $comeOut)--}}
            {{--                        <tr>--}}
            {{--                            <td>{{ $i++ }}</td>--}}
            {{--                            <td>{{ $comeOut->employees->full_name }}</td>--}}
            {{--                            <td>{{ $comeOut->employees->schedule->name }}</td>--}}
            {{--                            <td>{{ $comeOut->employees->companies->name }}</td>--}}
            {{--                            <td>{{ $comeOut->employees->branches->name }}</td>--}}
            {{--                            <td>{{ $comeOut->employees->departments->name }}</td>--}}
            {{--                            <td>{{ $comeOut->employees->positions->name }}</td>--}}
            {{--                            <td></td>--}}
            {{--                            <td>{{ $comeOut->action_time }}</td>--}}
            {{--                            <td>{{ $comeOut->id }}</td>--}}
            {{--                        </tr>--}}
            {{--                    @empty--}}
            {{--                        <td>Ничего не найдено.</td>--}}
            {{--                    @endforelse--}}
            {{--                    </tbody>--}}
            {{--                </table>--}}
            {{--            </div>--}}
        </div>
    </div>

    {{--    <div class="mt-3 pb-2">--}}
    {{--        {{ $comeOuts->appends(request()->toArray())->links('pagination::bootstrap-4') }}--}}
    {{--    </div>--}}

</div>

@push("js")
    <script>
        $(document).ready(function () {
            let select2_employee = $("#employees");
            select2_employee.select2();
            select2_employee.on("change", function (event) {
                let data = $("#employees").select2("val");
            @this.set("employee_id", data)
            });

            let start = moment().subtract(29, 'days');
            let end = moment();

            function cb(start, end) {
                $('.date_from_to').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            let dateRangePicker = $("#date_picker");
            dateRangePicker.daterangepicker({
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

            dateRangePicker.on("change", function (event) {
            @this.set("date", event.target.value);
            });
        });
    </script>
@endpush
