<div>
    <div class="card" x-data="{ tab: @entangle('tab') }">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a
                    class="w-25 text-center nav-link {{ $tab === 'company' ? 'active' : '' }}"
                    id="nav-home-tab"
                    data-toggle="tab"
                    href="#nav-home"
                    role="tab"
                    aria-controls="nav-home"
                    aria-selected="true"
                    wire:click="$set('tab', 'company')"
                >
                    Организация
                </a>
                <a
                    class="w-25 text-center nav-link {{ $tab === 'department' ? 'active' : '' }}"
                    id="nav-profile-tab"
                    data-toggle="tab"
                    href="#nav-profile"
                    role="tab"
                    aria-controls="nav-profile"
                    aria-selected="false"
                    wire:click="$set('tab', 'department')"
                >
                    Подразделения
                </a>
                <a
                    class="w-25 text-center nav-link {{ $tab === 'branch' ? 'active' : '' }}"
                    id="nav-profile-tab"
                    data-toggle="tab"
                    href="#nav-profile"
                    role="tab"
                    aria-controls="nav-profile"
                    aria-selected="false"
                    wire:click="$set('tab', 'branch')"
                >
                    Здание
                </a>
                <a
                    class="w-25 text-center nav-link {{ $tab === 'position' ? 'active' : '' }}"
                    id="nav-profile-tab"
                    data-toggle="tab"
                    href="#nav-profile"
                    role="tab"
                    aria-controls="nav-profile"
                    aria-selected="false"
                    wire:click="$set('tab', 'position')"
                >
                    Должность
                </a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div
                class="tab-pane fade {{ $tab === 'company' ? 'show active' : '' }}"
                id="nav-home"
                role="tabpanel"
                aria-labelledby="nav-home-tab"
            >
                @if ($tab === 'company')
                    <div class="card-body">
                        <form wire:submit.prevent="store" id="create">
                            @csrf
                            <div class="input-group mb-3">
                                <input
                                    type="text"
                                    class="form-control @error("create_name") is-invalid @enderror"
                                    placeholder="Введите Название Организации..."
                                    wire:model.lazy="create_name"
                                >
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        Добавить
                                    </button>
                                </div>

                                @error('create_name')
                                <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                                @enderror
                            </div>
                        </form>
                    </div>
                @endif
            </div>
            <div
                class="tab-pane fade {{ $tab === 'department' ? 'show active' : '' }}"
                id="nav-profile"
                role="tabpanel"
                aria-labelledby="nav-profile-tab"
            >
                @if ($tab === 'department')
                    <livewire:department/>
                @endif
            </div>
            <div
                class="tab-pane fade {{ $tab === 'branch' ? 'show active' : '' }}"
                id="nav-profile"
                role="tabpanel"
                aria-labelledby="nav-profile-tab"
            >
                @if ($tab === 'branch')
                    <livewire:branch/>
                @endif
            </div>
            <div
                class="tab-pane fade {{ $tab === 'position' ? 'show active' : '' }}"
                id="nav-profile"
                role="tabpanel"
                aria-labelledby="nav-profile-tab"
            >
                @if ($tab === 'position')
                    <livewire:position/>
                @endif
            </div>
        </div>
    </div>

    @if ($tab === 'company')
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="title">Список организаций</div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped text-center">
                        <thead>
                        <tr class="bg-gray">
                            <th>#</th>
                            <th>Название Организации</th>
                            <th>Действия</th>
                        </tr>
                        <tr class="bg-gray">
                            <th></th>
                            <th>
                                <form action="" onsubmit="this.submit()">
                                    <input type="text"
                                           name="name"
                                           class="form-control form-control-sm"
                                           placeholder="Введите для поиска..."
                                           value="{{ request('name') }}"
                                           required
                                    >
                                </form>
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($companies as $k => $company)
                            <tr>
                                <td>{{$k + 1}}</td>
                                <td>{{$company->name}}</td>
                                <td>
                                    <button
                                        class="bx bxs-edit btn btn-primary btn-sm"
                                        style="font-size:10px;"
                                        data-toggle="modal"
                                        data-target="#editModal"
                                        wire:click="edit({{$company->id}})"
                                    ></button>
                                    <button
                                        title="Удалить"
                                        class="bx bxs-trash-alt btn btn-danger btn-sm"
                                        style="font-size:10px;"
                                        wire:click="deleteConfirm({{$company->id}})"
                                    ></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div
        class="modal fade"
        id="editModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="editModalTitle"
        style="display: none;"
        aria-hidden="true"
        wire:ignore.self
    >
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Изменить</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <label for="name" class="col-form-label">Название Организации</label>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Введите название организации..."
                            wire:model.defer="edit_name"
                        >
                        @error('edit_name')
                        <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light-secondary"
                        data-dismiss="modal"
                        wire:click.prevent="cancel()"
                    >
                        Закрыть
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary ml-1"
                        wire:click.prevent="update()"
                    >
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push("js")

    <script>
        $(document).ready(function () {
            $("#submitDepartment").click(function () {
                $("#nav-profile").addClass("show active");
            });
        });

        window.addEventListener("closeModal", event => {
            $("#editModal").modal("hide");
        });

        window.addEventListener("sweety:create", event => {
            Swal.fire({
                icon: event.detail.type,
                title: event.detail.title,
                text: event.detail.text,
                showConfirmButton: false,
                timer: 1500
            });
        });

        window.addEventListener("sweety:update", event => {
            Swal.fire({
                icon: event.detail.type,
                title: event.detail.title,
                text: event.detail.text,
                showConfirmButton: false,
                timer: 1500
            });
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
                })
        });
    </script>
@endpush
