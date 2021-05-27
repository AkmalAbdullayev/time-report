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
    </thead>
    <tbody>
    @forelse($comeOuts as $k => $comeOut)
        <tr>
            <td>{{ $k+1 }}</td>
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
    </tbody>
</table>
