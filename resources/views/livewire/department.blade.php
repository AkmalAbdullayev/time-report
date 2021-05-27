<div>
    <div class="card">
        <div class="card-body">
            <form action="{{route("department.store")}}" id="create" method="post">
                @csrf
                <div class="form-row">
                    <div class="col-4">
                        <select name="company" class="form-control @error("company") is-invalid @enderror">
                            <option disabled selected>Выберите...</option>
                            @foreach($companies as $k => $company)
                                <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                        @error("company")
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <input type="text" name="name" placeholder="Подразделения..."
                               class="form-control @error("name") is-invalid @enderror"/>
                        @error('name')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="col-2">
                        <button id="submitDepartment" class="btn btn-secondary btn-block" type="submit">
                            Добавить
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">Список подразделений</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-gray">
                        <th>#</th>
                        <th>Организация</th>
                        <th>Раздел</th>
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
                                       required
                                >
                            </form>
                        </th>
                        <th>
                            <form action="" onsubmit="this.submit()">
                                <input type="text"
                                       name="name"
                                       class="form-control form-control-sm"
                                       placeholder="Введите для поиска..."
                                       required
                                >
                            </form>
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($departments as $k => $department)
                        <tr>
                            <td>{{$k + 1}}</td>
                            <td>{{$department->companies->name}}</td>
                            <td>{{$department->name}}</td>
                            <td>
                                <button
                                    class="bx bxs-edit btn btn-primary btn-sm"
                                    style="font-size:10px;"
                                    data-toggle="modal"
                                    data-target="#editModal"
                                    wire:click="edit({{$department->id}})"
                                ></button>
                                <button
                                    title="Удалить"
                                    class="bx bxs-trash-alt btn btn-danger btn-sm"
                                    style="font-size:10px;"
                                    wire:click="deleteConfirm({{$department->id}})"
                                ></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                        <div class="form-group">
                            <input type="hidden" wire:model="department_id">
                            <select
                                class="form-control @error("company_id") is-invalid @enderror"
                                wire:model.defer="company_id"
                            >
                                <option disabled>Выберите...</option>
                                @foreach($companies as $k => $company)
                                    <option
                                        value="{{$company->id}}" {{ $company_id == $company->id ? 'selected' : '' }}>{{$company->name}}</option>
                                @endforeach
                            </select>

                            @error("company_id")
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-form-label">Название отдела</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Введите название отдела..."
                                wire:model.defer="department_name"
                            >
                        </div>
                        @error('department_name')
                        <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"
                            wire:click.prevent="cancel()"> Закрыть
                    </button>
                    <button type="button" class="btn btn-primary ml-1" wire:click.prevent="update()">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push("js")
    @if (session()->has("message"))
        <script>
            Swal.fire({
                icon: 'success',
                title: `{{ session("message") }}`,
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    @endif

    <script>
        window.addEventListener("closeModal", event => {
            $("#editModal").modal("hide");
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
