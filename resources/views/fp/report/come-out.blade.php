@extends("layouts.master", ["title" => "Отчеты"])

@section("content")
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Отчёт посещения сотрудников в указанном диапазоне для выбранных подразделений</div>
            <button data-toggle="modal" data-target="#addModal" target="_blank" class="btn btn-outline-success">Добавить</button>
        </div>
        <div class="card-body">
            <form autocomplete="off">
                <div class="form-row">
                    <div class="form-group col-6">
                        <label for="employees">Сотрудники</label>
                        <select name="employee" id="employees" class="select2 form-control select2-hidden-accessible">
                            <option value="0">Выберите...</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee') == $employee->id ? 'selected':'' }}>{{ $employee->full_name }}</option>
                            @endforeach
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
                        <th>Время</th>
                        <th>Вход / Выход</th>
                        <th>ДЕЙСТВИЯ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = (($comeOuts->currentpage() - 1) * $comeOuts->perpage() + 1); @endphp
                    @forelse ($comeOuts as $comeOut)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $comeOut->employees->short_full_name ?? '' }}</td>
                            <td>{{ $comeOut->employees->companies->name ?? ''}}</td>
                            <td>{{ $comeOut->employees->branches->name ?? ''}}</td>
                            <td>{{ $comeOut->employees->departments->name ?? ''}}</td>
                            <td>{{ $comeOut->employees->positions->name ?? ''}}</td>
                            <td>{{ $comeOut->action_time }}</td>
                            <td>{{ ($comeOut->doorDevice->is_come == 1 ? 'Вход' : 'Выход') ?? '' }}</td>
                            <td>
                                <button
                                    class="bx bxs-edit btn btn-primary btn-sm edit-come-out"
                                    data-id="{{ $comeOut->id }}"
                                    style="font-size:10px;"
                                    data-toggle="modal"
                                    data-target="#editModal"
                                ></button>
                                <button
                                    title="Удалить"
                                    class="bx bxs-trash-alt btn btn-danger btn-sm delete-come-out-btn"
                                    data-id="{{ $comeOut->id }}"
                                    style="font-size:10px;"
                                ></button>
                            </td>
                        </tr>
                    @empty
                        <td colspan="9">Ничего не найдено.</td>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="ml-2 pb-2">
            {{ $comeOuts->appends(request()->toArray())->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalTitle" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Добавить</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ route('come.out.store') }}" method="post">
                <div class="modal-body">
                    @csrf
                        <div class="form-group">
                            <label for="name" class="col-form-label">Название отдела</label>
                            <select name="door_id" class="form-control @error("door_id") is-invalid @enderror" required>
                                <option disabled selected>Выберите...</option>
                                @foreach($doors as $k => $door)
                                    <option value="{{$door->id}}">{{$door->name}}</option>
                                @endforeach
                            </select>
                            @error('door_id')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-form-label">Название отдела</label>
                            <select name="employee_id" class="form-control @error("employee_id") is-invalid @enderror" required>
                                <option disabled selected>Выберите...</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->short_full_name}}</option>
                                @endforeach
                            </select>
                            @error('employee_id')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-form-label">Название отдела</label>
                            <input type="datetime-local" name="action_time" class="form-control @error("action_time") is-invalid @enderror" required>
                            @error('action_time')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary ml-1">
                        Сохранить
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle">Добавить</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ route('come.out.update') }}" method="post">
                    <div class="modal-body" id="edit-come-out-form">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary ml-1">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <form action="{{ route('come.out.delete') }}" id="delete-come-out-form" method="post" class="d-none">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" id="delete-come-out-id" name="id">
    </form>
@endsection

@push("js")
    <script>
        $(document).ready(function () {
            $('.delete-come-out-btn').on('click', function () {
                $('#delete-come-out-id').val($(this).data('id'));
                alertify.confirm("Вы уверены?", function () {
                    $('#delete-come-out-form').submit();
                }).set({title:"Подтвердить"}).set({labels:{ok:'Да', cancel: 'Нет'}});
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

            $('.edit-come-out').on('click', function (){
                let id = $(this).data('id')
                var url = "{{ route('home') }}";
                $('#edit-come-out-form').empty();

                $.ajax( {
                    url: url+'/report/'+id+'/come-out-edit',
                    method: 'get',
                    dateType: 'html',
                    success: function (data) {
                        $('#edit-come-out-form').append(data);
                    },
                    error: function (e) {
                        console.log(e);
                    }
                })
            })
        });
    </script>
@endpush
