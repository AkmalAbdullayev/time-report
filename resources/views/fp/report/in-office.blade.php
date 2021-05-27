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
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($online as $key => $comeOut)
                        <tr>
                            <td>{{ $key++ }}</td>
                            <td>{{ $comeOut[0]->employees->short_full_name ?? '' }}</td>
                            <td>{{ $comeOut[0]->employees->companies->name ?? ''}}</td>
                            <td>{{ $comeOut[0]->employees->branches->name ?? ''}}</td>
                            <td>{{ $comeOut[0]->employees->departments->name ?? ''}}</td>
                            <td>{{ $comeOut[0]->employees->positions->name ?? ''}}</td>
                            <td>{{ $comeOut[0]->action_time }}</td>
                        </tr>
                    @empty
                        <td colspan="9">Ничего не найдено.</td>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
