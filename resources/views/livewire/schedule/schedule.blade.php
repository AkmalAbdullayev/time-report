<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">График работы</div>
        </div>

        <div class="card-body">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a
                        class="nav-link active"
                        id="nav-home-tab"
                        data-toggle="tab"
                        href="#nav-home"
                        role="tabpanel"
                        aria-controls="nav-home"
                        aria-selected="true"
                        style="width: 50%; text-align: center"
                    >
                        Гибкий
                    </a>
                    <a
                        class="nav-link"
                        id="nav-active-tab"
                        data-toggle="tab"
                        href="#nav-active"
                        role="tabpanel"
                        aria-controls="nav-active"
                        aria-selected="true"
                        style="width: 50%; text-align: center"
                    >
                        Свободный
                    </a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div
                    class="tab-pane fade show active"
                    id="nav-home"
                    role="tabpanel"
                    aria-labelledby="nav-home-tab"
                >
                    <form wire:submit.prevent="store">
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="name">Название графика
                                    <span class="text-danger">*</span>
                                </label>
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    class="form-control @error("property.name") is-invalid @enderror"
                                    placeholder="Название графика..."
                                    wire:model.defer="property.name"
                                >

                                @error("property.name")
                                <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-6">
                                <label for="description">Описание</label>
                                <input
                                    id="description"
                                    type="text"
                                    name="description"
                                    class="form-control"
                                    placeholder="Описание..."
                                    wire:model.defer="property.description"
                                >
                            </div>

                            {{--                            <div class="form-group col-4">--}}
                            {{--                                <label for="type">Тип графика</label>--}}
                            {{--                                <select name="type" id="type" class="form-control">--}}
                            {{--                                    <option selected disabled>Выберите...</option>--}}
                            {{--                                </select>--}}
                            {{--                            </div>--}}

                            <div class="form-group col-5">
                                <label for="range_from">Интервал(С)</label>
                                <span class="text-danger">*</span>
                                <input
                                    type="text"
                                    class="form-control @error("property.range_from") is-invalid @enderror"
                                    name="range_from"
                                    id="range_from"
                                    placeholder="График с..."
                                    wire:model.defer="property.range_from"
                                />

                                @error("property.range_from")
                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                @enderror

                            </div>

                            <div class="form-group col-5">
                                <label for="range_from">Интервал(До)</label>
                                <span class="text-danger">*</span>
                                <input
                                    type="text"
                                    class="form-control @error("property.range_to") is-invalid @enderror"
                                    name="range_to"
                                    id="range_to"
                                    placeholder="График до..."
                                    wire:model.defer="property.range_to"
                                />

                                @error("property.range_to")
                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                @enderror

                            </div>

                            <div class="form-group col-2 mt-2">
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    style="float: right"
                                >
                                    Сохранить
                                </button>
                            </div>
                        </div>

                        {{--                        @include("livewire.schedule.accordion")--}}
                    </form>

                </div>
                <div
                    class="tab-pane fade show"
                    id="nav-active"
                    role="tabpanel"
                    aria-labelledby="nav-active-tab"
                >
                    <x-schedule.flex-work-hour/>
                </div>
            </div>

            <hr>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-gray">
                        <th>#</th>
                        <th>Название графика</th>
                        <th>Описание</th>
                        <th>Тип графика</th>
                        <th>Интервал(С)</th>
                        <th>Интервал(До)</th>
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
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1; @endphp
                    @foreach ($schedules as $k => $schedule)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $schedule->name }}</td>
                            <td>{{ $schedule->description }}</td>
                            <td @if($schedule->type === null) class="text-danger" @endif>{{ $schedule->type === null ? 'Не задано' : '' }}</td>
                            <td>{{ $schedule->range_from }}</td>
                            <td>{{ $schedule->range_to }}</td>

                            <td>
                                <button
                                    class="bx bxs-edit btn btn-primary btn-sm"
                                    style="font-size:10px;"
                                    data-toggle="modal"
                                    data-target="#editModal"
                                    wire:click="edit({{ $schedule->id }})"
                                ></button>
                                <button
                                    title="Удалить"
                                    class="bx bxs-trash-alt btn btn-danger btn-sm"
                                    style="font-size:10px;"
                                    wire:click="deleteConfirm({{ $schedule->id }})"
                                ></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include("livewire.schedule.modal")
    @push("js")
        @include("livewire.schedule.script")
    @endpush
</div>
