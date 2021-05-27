<div>
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
            </tr>
            </thead>
            <tbody>
            @forelse ($employees as $key => $employee)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ "{$employee->first_name} {$employee->last_name}" }}</td>
                    <td>{{ $employee->schedule->name }}</td>
                    <td>{{ $employee->companies->name }}</td>
                    <td>{{ $employee->branches->name }}</td>
                    <td>{{ $employee->departments->name }}</td>
                    <td>{{ $employee->positions->name }}</td>
                </tr>
            @empty
                <td colspan="7">Ничего не найдено.</td>
            @endforelse
            </tbody>
        </table>
    </div>

    @push("js")
        <script>
            let department = $("#department_filter");
            $(document).ready(function () {
                $('select[name=company_filter]').on('change', function () {
                    $.get(`/admin/aj/departments/${this.value}`, function (data) {
                        if (data) {
                            $('#department_filter').html('');
                            $("#department_filter").append($(`<option selected disabled>Выберите...</option>`));
                            $.each(data, function (k, v) {
                                $('#department_filter').append($('<option>', {value: v, text: k}));
                            });
                        }

                        department.on("change", function () {
                            $("#absent_filter").submit();
                        });
                    });
                });
            });
        </script>
    @endpush
</div>
