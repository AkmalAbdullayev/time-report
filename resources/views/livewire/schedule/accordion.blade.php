<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h5 class="mb-0">
                <button
                    id="param-btn"
                    class="btn btn-link"
                    data-toggle="collapse"
                    data-target="#collapseOne"
                    aria-expanded="true"
                    aria-controls="collapseOne"
                >
                    Параметры

                    <span class="ml-1" style="font-size: 17px;">&plus;</span>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        style="float: right"
                    >
                        Сохранить
                    </button>
                </button>
            </h5>
        </div>

        <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
             data-parent="#accordion">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                        <tr class="bg-gray">
                            <th>Рабочий день</th>
                            <th>Начало</th>
                            <th>Окончание</th>
                            <th>Обед</th>
                            <th>Начало</th>
                            <th>Окончание</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for($i = 1; $i <= 7; $i++)
                            <tr>
                                <td style="padding: 10px 0;">
                                    <div class="checkbox checkbox-success">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            id="{{ $i }}-day"
                                        >
                                        <label
                                            for="{{ $i }}-day"
                                            class="form-check-label"
                                        >
                                            {{ $i }} День
                                        </label>
                                    </div>
                                </td>

                                <td style="padding-top: 20px">
                                    <div class="form-group col-8">
                                        <input
                                            type="text"
                                            id="{{ $i }}_day_start"
                                            class="form-control"
                                            name="{{ $i }}_day_start"
                                            wire:model.defer="property.range_from"
                                            disabled
                                        >
                                    </div>
                                </td>

                                <td style="padding-top: 20px">
                                    <div class="form-group col-8">
                                        <input
                                            type="text"
                                            id="{{ $i }}_day_end"
                                            class="form-control"
                                            name="{{ $i }}_day_end"
                                            wire:model.defer="property.range_to"
                                            disabled
                                        >
                                    </div>
                                </td>

                                <td>
                                    <div class="checkbox checkbox-success">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            id="{{ $i }}-lunch"
                                        >
                                        <label
                                            for="{{ $i }}-lunch"
                                            class="form-check-label"
                                        >
                                            Обед
                                        </label>
                                    </div>
                                </td>

                                <td style="padding-top: 20px">
                                    <div class="form-group col-8">
                                        <input
                                            type="text"
                                            id="{{ $i }}_lunch_start"
                                            class="form-control"
                                            name="{{ $i }}_lunch_start"
                                            disabled
                                        >
                                    </div>
                                </td>

                                <td style="padding-top: 20px">
                                    <div class="form-group col-8">
                                        <input
                                            type="text"
                                            id="{{ $i }}_lunch_end"
                                            class="form-control"
                                            name="{{ $i }}_lunch_end"
                                            disabled
                                        >
                                    </div>
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function () {
            $("#param-btn").on("click", function (event) {
                event.preventDefault();
            });

            for (let i = 1; i <= 7; i++) {
                $("#" + i + "-day").change(function () {
                    if (this.checked) {
                        $("#" + i + "_day_start").prop("disabled", false);
                        $("#" + i + "_day_end").prop("disabled", false);
                    } else {
                        $("#" + i + "_day_start").prop("disabled", true);
                        $("#" + i + "_day_end").prop("disabled", true);
                    }
                });

                $("#" + i + "-lunch").change(function () {
                    if (this.checked) {
                        $("#" + i + "_lunch_start").prop("disabled", false);
                        $("#" + i + "_lunch_end").prop("disabled", false);
                    } else {
                        $("#" + i + "_lunch_start").prop("disabled", true);
                        $("#" + i + "_lunch_end").prop("disabled", true);
                    }
                });
            }
        });
    </script>
@endpush
