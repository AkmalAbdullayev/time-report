<div>
    <div class="row d-flex justify-content-end">
        <div class="col-6">
            <div class="form-group">
                <label for="finger">Отпечаток</label>

                <select
                    name="finger"
                    id="finger"
                    class="form-control"
                >
                    <option selected disabled>Выберите...</option>
                    @forelse($fingers as $finger)
                        <option value="{{ $finger->id }}">Палец #{{ $finger->name }}</option>
                    @empty
                        <option disabled>Нет отпечаток</option>
                    @endforelse
                </select>
            </div>

            <div class="form-group">
                <label for="device">Шаблон Доступа</label>

                <select
                    name="device"
                    id="device"
                    class="form-control"
                >
                    <option disabled selected>Выберите...</option>
                    @foreach($employeeDevices as $employeeDevice)
                        <option
                            {{ $employeeDevice->doorDevice->device->id == $deviceId ? 'disabled' : '' }}
                            value="{{ $employeeDevice->doorDevice->device->id }}"
                        >
                            {{ $employeeDevice->doorDevice->device->name }}
                            ({{ $employeeDevice->doorDevice->device->ip }})
                        </option>
                    @endforeach
                </select>
            </div>
            <img src="{{ asset("images/qol-raqamlari.png") }}" alt="fingers" height="200">
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-primary col-6 mt-2">Копировать отпечаток</button>
    </div>
</div>
