<div>
    <form wire:submit.prevent="storeFlexWorkHour">
        <div class="form-row">
            <div class="form-group col-12">
                <label
                    for="name"
                >
                    Название графика
                    <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    class="form-control @error("flexWorkHour.name") is-invalid @enderror"
                    id="name"
                    name="name"
                    placeholder="Название..."
                    wire:model.defer="flexWorkHour.name"
                >

                @error("flexWorkHour.name")
                <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div id="accordion">
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button
                            class="btn btn-success"
                            data-toggle="collapse"
                            data-target="#collapseTwo"
                            aria-expanded="true"
                            aria-controls="collapseTwo"
                            id="schedule-param-btn"
                        >
                            Параметры для свободного графика
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            style="float: right"
                        >
                            Сохранить
                        </button>
                    </h5>
                </div>

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="checkbox checkbox-success" style="margin-top: 8px">
                                    <input class="form-check-input" type="checkbox" id="start_time">
                                    <label
                                        class="form-check-label"
                                        for="start_time"
                                        style="font-size: 1rem;"
                                    >
                                        Время начало работы
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input
                                        type="text"
                                        id="s_time"
                                        class="form-control ml-4"
                                        name="start_time"
                                        wire:model.defer="flexWorkHour.start_time"
                                        disabled
                                    >
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="checkbox checkbox-success" style="margin-top: 8px">
                                    <input class="form-check-input" type="checkbox" id="end_time">
                                    <label
                                        class="form-check-label"
                                        for="end_time"
                                        style="font-size: 1rem;"
                                    >
                                        Время окончания работы
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input
                                        style="margin-left: 26px"
                                        type="text"
                                        id="e_time"
                                        class="form-control"
                                        name="end_time"
                                        wire:model.defer="flexWorkHour.end_time"
                                        disabled
                                    >
                                </div>
                            </div>

                            <div class="form-row mt-2">
                                <h3>Обед</h3>
                            </div>

                            <div class="form-row mt-2">
                                <div class="checkbox checkbox-success" style="margin-top: 8px">
                                    <input class="form-check-input" type="checkbox" id="lunch">
                                    <label
                                        class="form-check-label"
                                        for="lunch"
                                        style="font-size: 1rem;"
                                    >
                                        Обед
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input
                                        style="margin-left: 26px"
                                        type="text"
                                        id="l_time"
                                        class="form-control"
                                        name="lunch_time"
                                        wire:model.defer="flexWorkHour.lunch_time"
                                        disabled
                                    >
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push("js")
    <script>
        $(document).ready(function () {
            $("#schedule-param-btn").on("click", (event) => {
                event.preventDefault();
            });

            $("#start_time").change(function () {
                if (this.checked) {
                    $("#s_time").prop("disabled", false);
                } else {
                    $("#s_time").prop("disabled", true);
                }
            });

            $("#end_time").change(function () {
                if (this.checked) {
                    $("#e_time").prop("disabled", false);
                } else {
                    $("#e_time").prop("disabled", true);
                }
            });

            $("#lunch").change(function () {
                if (this.checked) {
                    $("#l_time").prop("disabled", false);
                } else {
                    $("#l_time").prop("disabled", true);
                }
            });
        });
    </script>
@endpush
