<div>
    <div class="card">
        <div class="card-header">
            <div class="title">
                <span class="text-center">Клиенты</span>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route("client.store") }}" method="post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-6">
                        <label for="employees">Сотрудники</label>
                        <select
                            name="employee_id"
                            id="employees"
                            class="select2 form-control select2-hidden-accessible"
                        >
                            <option selected disabled value="">Выберите...</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                            @endforeach
                        </select>

                        @error('employee_id')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror

                    </div>

                    <div class="form-group col-6">
                        <label for="client_info">Ф.И.О Клиента</label>
                        <input
                            type="text"
                            class="form-control @error('guest_name') is-invalid @enderror"
                            placeholder="Ф.И.О"
                            name="guest_name"
                            id="client_info"
                        >

                        @error('guest_name')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror

                    </div>

                    <div class="form-group col-6">
                        <label for="client_type">Тип клиента</label>
                        <select name="type" id="client_type" class="form-control">
                            <option disabled selected>Выберите...</option>
                            @foreach($guest_types as $k => $guest_type)
                                <option value="{{ $guest_type }}">{{ $guest_type }}</option>
                            @endforeach
                        </select>

                        @error('type')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <div class="form-group col-6">
                        <label for="description">Дополнительные данные</label>
                        <textarea
                            name="description"
                            id="description"
                            cols="30"
                            rows="5"
                            class="form-control @error('description') is-invalid @enderror"
                        ></textarea>

                        @error('description')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-block btn-primary">Добавить</button>
            </form>

            <hr>
            <x-table>
                <x-slot name="head">
                    <x-table.heading>#</x-table.heading>
                    <x-table.heading>Ф.И.О</x-table.heading>
                    <x-table.heading>Тип</x-table.heading>
                    <x-table.heading>Кому</x-table.heading>
                    <x-table.heading>Дополнительная информация</x-table.heading>
                    <x-table.heading>Действия</x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @php $i = (($clients->currentpage() - 1) * $clients->perpage() + 1); @endphp
                    @forelse ($clients as $client)
                        <x-table.row>
                            <x-table.cell>{{ $i++ }}</x-table.cell>
                            <x-table.cell>{{ $client->guest_name }}</x-table.cell>
                            <x-table.cell>{{ $client->type }}</x-table.cell>
                            <x-table.cell
                                ondblclick="clientInfo({{ $client->employee->id }})">{{ $client->employee->full_name }}</x-table.cell>
                            <x-table.cell>{{ $client->description }}</x-table.cell>
                            <x-table.cell>
                                <button
                                    class="bx bxs-edit btn btn-primary btn-sm"
                                    style="font-size:10px;"
                                    data-toggle="modal"
                                    data-target="#editModal"
                                    wire:click="edit({{ $client }})"
                                ></button>
                                <button
                                    title="Удалить"
                                    class="bx bxs-trash-alt btn btn-danger btn-sm"
                                    style="font-size:10px;"
                                    wire:click="deleteConfirm({{ $client->id }})"
                                ></button>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.cell colspan="6">Ничего не найдено.</x-table.cell>
                    @endforelse
                </x-slot>
            </x-table>

            {{ $clients->links() }}
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle"
         style="display: none;" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Изменить</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="guest_name">Ф.И.О Клиента</label>
                            <input type="text" id="guest_name" class="form-control" wire:model="edit.guest_name">
                        </div>

                        <div class="form-group col-12">
                            <select name="type" id="type" wire:model="edit.type" class="form-control">
                                <option selected disabled>Выберите...</option>
                                @foreach($guest_types as $guest_type)
                                    <option value="{{ $guest_type }}">{{ $guest_type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12">
                            <label for="description">Дополнительные данные</label>
                            <textarea
                                name="description"
                                id="description"
                                cols="30"
                                rows="5"
                                class="form-control"
                                wire:model="edit.description"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"
                            wire:click.prevent="clear">
                        Закрыть
                    </button>

                    <button type="button" class="btn btn-primary ml-1" data-dismiss="modal" wire:click.prevent="update">
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalTitle"
         style="display: none;" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Посетители Сотрудника</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    @if(!is_null($employeeClients))
                        <h5>Количество посетителей : {{ $employeeClients->count() }}</h5>
                        <div class="mt-2">
                            <h6>Последние посетители :</h6>

                            <x-table>
                                <x-slot name="head">
                                    <x-table.heading>#</x-table.heading>
                                    <x-table.heading>Ф.И.О</x-table.heading>
                                    <x-table.heading>Время</x-table.heading>
                                </x-slot>

                                <x-slot name="body">
                                    @foreach($employeeClients as $k => $employeeClient)
                                        <x-table.row>
                                            <x-table.cell>{{ ++$k }}</x-table.cell>
                                            <x-table.cell>{{ $employeeClient->guest_name }}</x-table.cell>
                                            <x-table.cell>{{ $employeeClient->created_at->format("d M Y | H:i:s") }}</x-table.cell>
                                        </x-table.row>
                                    @endforeach
                                </x-slot>
                            </x-table>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"
                            wire:click.prevent="clear">
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push("js")
    <script>
        function clientInfo(id) {
            Livewire.emit('clientInfo', id);
        }

        window.addEventListener("showModal", () => {
            $("#infoModal").modal().draggable({
                handle: ".modal-header"
            });
        });

        window.addEventListener("select2", () => {
            $("#employees").select2();
        });

        window.addEventListener("sweety:confirm-delete", event => {
            Swal.fire({
                icon: event.detail.type,
                title: event.detail.title,
                text: event.detail.text,
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonText: "Удалить",
                cancelButtonText: "Отменить"
            })
                .then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        window.livewire.emit("destroy", event.detail.id);

                        window.addEventListener("sweety:deleted", event => {
                            Swal.fire({
                                icon: event.detail.type,
                                title: event.detail.title,
                                text: event.detail.text,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        });
                    }
                });
        });
    </script>
@endpush
