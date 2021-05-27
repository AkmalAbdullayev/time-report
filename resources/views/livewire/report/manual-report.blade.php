<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Ручной Отчет</div>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="store" autocomplete="off">
                <div class="form-row" wire:ignore>
                    <div class="form-group col-6">
                        <label for="employees">Сотрудники</label>
                        <select
                            name="employees"
                            id="employees"
                            class="select2 form-control select2-hidden-accessible"
                        >
                            <option selected disabled>Выберите...</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6">
                        <label for="doors">Дверь</label>
                        <select
                            name="doors"
                            id="doors"
                            class="form-control"
                        >
                            <option selected disabled>Выберите...</option>
                            @foreach($doors as $door)
                                <option value="{{ $door->id }}">{{ $door->doors->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 position-relative has-icon-left">
                        <label for="date">Дата</label>
                        <input
                            type="text"
                            class="form-control date"
                            id="date"
                            placeholder="Дата..."
                            name="date"
                        >
                        <div class="form-control-position mt-2">
                            <i class="bx bx-calendar-check"></i>
                        </div>
                    </div>

                    <div class="form-group col-6">
                        <label for="time">Время</label>
                        <input
                            type="text"
                            class="form-control timepicker"
                            name="time"
                            id="time"
                            placeholder="Время..."
                        >
                    </div>
                </div>

                <div class="form-row d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push("js")
    <script>
        function updateValues() {
            $("#employees").on("change", function (event) {
            @this.set("employee_id", event.target.value);
            });
            $("#doors").on("change", function (event) {
            @this.set("doorDeviceId", event.target.value);
            })
            $("#date").on("change", function (event) {
            @this.set("date", event.target.value);
            });
            $("#time").on("change", function (event) {
            @this.set("time", event.target.value);
            })
        }

        $(document).ready(function () {
            updateValues();

            $(".timepicker").pickatime({
                clear: "Очистить",
                format: 'HH:i',
                editable: true,
                closeOnSelect: true,
                closeOnClear: true,
            });

            let start = moment().subtract(29, 'days');
            let end = moment();

            function cb(start, end) {
                $('.date').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $(".date").daterangepicker({
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
