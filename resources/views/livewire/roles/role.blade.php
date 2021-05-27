<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Роли</div>
        </div>

        <div class="card-body">
            <form action="{{ route("roles.store") }}" method="post">
                @csrf
                <div class="form-row">
                    <div class="col-5">
                        <input
                            type="text"
                            id="role"
                            name="role"
                            class="form-control"
                            placeholder="Добавьте роль..."
                            aria-label="Добавьте роль..."
                            aria-describedby="basic-addon2"
                        >
                    </div>

                    <div class="form-group col-5">
                        <select name="permission[]" class="form-control" id="select2" multiple>
                            @foreach ($permissions as $permission)
                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-2">
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </div>
            </form>

            <hr>
            <x-table>
                <x-slot name="head">
                    <x-table.heading>#</x-table.heading>
                    <x-table.heading>Имя</x-table.heading>
                    <x-table.heading>Permissions</x-table.heading>
                    <x-table.heading>Действия</x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @forelse ($roles as $k => $role)
                        <x-table.row>
                            <x-table.cell>{{ ++$k }}</x-table.cell>
                            <x-table.cell>{{ $role->name }}</x-table.cell>
                            <x-table.cell style="width: 50%;">
                                @foreach ($role->permissions as $permission)
                                    <div
                                        class="badge badge-pill badge-primary d-inline-flex align-items-center"
                                        @if($role->permissions_count > 3) style="margin: 4px 0;" @endif
                                    >
                                        <span class="mr-50" style="font-size: 11px;">{{ $permission->name }}</span>
                                        <i
                                            class="bx bx-x font-size-large border-left"
                                            style="padding-left: 10px;"
                                            wire:click="deleteConfirm({{ $permission->id }}, 'permission', {{ $role->id }})"
                                        ></i>
                                    </div>
                                @endforeach
                            </x-table.cell>
                            <x-table.cell>
                                <button
                                    type="button"
                                    data-toggle="modal"
                                    data-target="#exampleModal"
                                    title="Изменить"
                                    class="bx bxs-edit btn btn-primary btn-sm"
                                    style="font-size:10px;"
                                    wire:click="edit({{ $role }})"
                                ></button>

                                <button
                                    title="Удалить"
                                    class="bx bxs-trash-alt btn btn-danger btn-sm"
                                    style="font-size:10px;"
                                    id="deleteBtn"
                                    wire:click="deleteConfirm({{ $role->id }}, 'role')"
                                ></button>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.cell colspan="4">Ничего не найдено.</x-table.cell>
                    @endforelse
                </x-slot>
            </x-table>
        </div>
    </div>

    @include("livewire.roles.flash")
    @include("livewire.roles.modal")

    @if (session()->has("success"))
        <script>
            toastr.success(`{{ session("success") }}`, 'Уведомление', {"progressBar": true});
        </script>
    @endif

    @if (session()->has("failed"))
        <script>
            toastr.error(`{{ session("failed") }}`, 'Уведомление', {"progressBar": true});
        </script>
    @endif

    @include("livewire.roles.script")
</div>

@push("js")
    <script>
        $(document).ready(function () {
            $("#select2").select2({
                placeholder: "Выберите Permissions..."
            });
        });
    </script>
@endpush
