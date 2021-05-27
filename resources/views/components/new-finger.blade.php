<div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="finger">Отпечаток</label>
                <select
                    name="finger"
                    class="form-control"
                    required="true"
                    id="finger"
                >
                    <option selected disabled>Выберите...</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option
                            value="{{ $i }}"
                            @if ($fingerNameExists = \Illuminate\Support\Facades\DB::table("employee_fingers")->where("employee_id", "=", $model->id)->where("name", "=", strval($i))->exists()) disabled @endif
                        >
                            Палец
                            #{{ $i }} {!! $fingerNameExists == 1 ? '<span class="text-success"> - Scanned</span>' : '' !!}
                        </option>
                    @endfor
                </select>
            </div>
            <img src="{{ asset("images/qol-raqamlari.png") }}" alt="fingers" height="200">
        </div>
    </div>

    <button class="btn btn-primary col-6 mt-2">Получить отпечаток</button>
</div>
