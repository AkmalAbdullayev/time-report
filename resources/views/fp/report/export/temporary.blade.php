<table class="table table-sm table-bordered table-striped text-center">
    <thead>
    <tr class="bg-light">
        <th width="5">#</th>
        <th width="30">Ф.И.О</th>
        <th width="30">Организация</th>
        <th width="30">Здание</th>
        <th width="30">Подразделения</th>
        <th width="30">Должность</th>
        <th width="10">Посещения<br>сотрудника (за месяц)</th>
        <th width="15">Время</th>
        <th width="5">ID<br>Сотрудника</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($employeeReports as $key => $employeeReport)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $employeeReport->short_full_name ?? '' }}</td>
            <td>{{ $employeeReport->companies->name ?? ''}}</td>
            <td>{{ $employeeReport->branches->name ?? ''}}</td>
            <td>{{ $employeeReport->departments->name ?? ''}}</td>
            <td>{{ $employeeReport->positions->name ?? ''}}</td>
            <td>{{ $employeeReport->monthCount ?? ''}}</td>
            <td>{{ $employeeReport->workTime ?? ''}}</td>
            <td>{{ $employeeReport->id ?? ''}}</td>
        </tr>
    @empty
        <td colspan="9">Ничего не найдено.</td>
    @endforelse
    </tbody>
</table>
