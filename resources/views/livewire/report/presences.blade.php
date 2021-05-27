<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Присутствующие на данный момент</div>
        </div>

        <div class="card-body">
            <a href="{{ route('exportPresencesReport') }}" target="_blank" class="btn btn-outline-success bx-pull-right mt-2 ml-4">Export</a>
            <form wire:submit.prevent="check" class="mr-2">
                <div class="form-row">
                    <div class="col-6">
                        <label for="company">Организация</label>
                        <select name="company" id="company" class="form-control" wire:model="selectedCompany">
                            <option selected value="">Выберите...</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6">
                        <label for="department">Подразделения</label>
                        <select
                            name="department"
                            id="department"
                            class="form-control"
                        >
                            <option selected value="">Выберите...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <hr>
            <div class="form-row d-flex justify-content-end">
                <div class="form-group col-4">
                    <input type="text" class="form-control" placeholder="Поиск..." wire:model="search">
                </div>
            </div>

            <x-table>
                <x-slot name="head">
                    <x-table.heading>#</x-table.heading>
                    <x-table.heading>Ф.И.О</x-table.heading>
                    <x-table.heading>График</x-table.heading>
                    <x-table.heading>Организация</x-table.heading>
                    <x-table.heading>Здание</x-table.heading>
                    <x-table.heading>Подразделения</x-table.heading>
                    <x-table.heading>Должность</x-table.heading>
{{--                    <x-table.heading>Время Прибытия</x-table.heading>--}}
                </x-slot>

                <x-slot name="body">
                    @php $i = 1; @endphp
                    @forelse($currentlyIn as $k => $here)
                        @if (!$here->comeOuts->isEmpty() && $here->comeOuts[0]->doorDevice->is_come == 1)
                            <x-table.row>
                                <x-table.cell>{{ $i++ }}</x-table.cell>
                                <x-table.cell>{{ $here->full_name }}</x-table.cell>
                                <x-table.cell>{{ $here->schedule->name }}</x-table.cell>
                                <x-table.cell>{{ $here->companies->name }}</x-table.cell>
                                <x-table.cell>{{ $here->branches->name }}</x-table.cell>
                                <x-table.cell>{{ $here->departments->name }}</x-table.cell>
                                <x-table.cell>{{ $here->positions->name }}</x-table.cell>
{{--                                <x-table.cell>{{ $here->comeOuts[0]->action_time }}</x-table.cell>--}}
                            </x-table.row>
                        @endif
                    @empty
                        <x-table.cell colspan="7">Ничего не найдено.</x-table.cell>
                    @endforelse
                </x-slot>
            </x-table>

{{--            <div class="mt-2">--}}
{{--                {{ $currentlyIn->links() }}--}}
{{--            </div>--}}
        </div>
    </div>
</div>

@push("js")
    <script>
        $(document).ready(function () {
            $("#department").on("change", function (event) {
            @this.set("selectedDepartment", event.target.value);
            })
        });
    </script>
@endpush
