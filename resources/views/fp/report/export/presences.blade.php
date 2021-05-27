<table>
    <tr name="head">
        <td>#</td>
        <td>Ф.И.О</td>
        <td>График</td>
        <td>Организация</td>
        <td>Здание</td>
        <td>Подразделения</td>
        <td>Должность</td>
    </tr>

    <tbody name="body">
        @forelse($currentlyIn as $key => $here)
            @if (!$here->comeOuts->isEmpty() && $here->comeOuts[0]->doorDevice->is_come == 1)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $here->full_name }}</td>
                    <td>{{ $here->schedule->name }}</td>
                    <td>{{ $here->companies->name }}</td>
                    <td>{{ $here->branches->name }}</td>
                    <td>{{ $here->departments->name }}</td>
                    <td>{{ $here->positions->name }}</td>
                </tr>
            @endif
        @empty
            <tr>
                <td colspan="7">Ничего не найдено.</td>
            </tr>
        @endforelse
    </tbody>
</table>
