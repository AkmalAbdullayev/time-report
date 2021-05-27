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
            <tr>
                <form id="absent_filter">
                    <th colspan="3"></th>
                    <th>
                        <select
                            name="company_filter"
                            id="company_filter"
                            class="form-control form-control-sm"
                        >
                            <option selected value="">Выберите...</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th></th>
                    <th>
                        <select
                            name="department_filter"
                            id="department_filter"
                            class="form-control form-control-sm"
                        >
                            <option selected value="">Выберите...</option>
                        </select>
                    </th>
                    <th></th>
                </form>
            </tr>
            </thead>
            <tbody>
            @php $i = (($employees->currentpage() - 1) * $employees->perpage() + 1); @endphp
            @forelse ($employees as $employee)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ "{$employee->first_name} {$employee->last_name}" }}</td>
                    <td>{{ $employee->schedule }}</td>
                    <td>{{ $employee->company_name }}</td>
                    <td>{{ $employee->branch_name }}</td>
                    <td>{{ $employee->department_name }}</td>
                    <td>{{ $employee->position_name }}</td>
                </tr>
            @empty
                <td colspan="7">Ничего не найдено.</td>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        {{ $employees->appends(request()->toArray())->links('pagination::bootstrap-4') }}
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
