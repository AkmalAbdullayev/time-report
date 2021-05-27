@csrf
<input type="hidden" name="id" value="{{ $comeOut->id }}">
<div class="form-group">
    <label for="name" class="col-form-label">Название отдела</label>
    <select name="door_id" class="form-control" required>
        <option disabled selected>Выберите...</option>
        @foreach($doors as $k => $door)
            <option {{ $comeOut->doorDevice->doors_id == $door->id ? 'selected':'' }} value="{{$door->id}}">{{$door->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="name" class="col-form-label">Название отдела</label>
    <select name="employee_id" class="form-control" required>
        <option disabled selected>Выберите...</option>
        @foreach($employees as $employee)
            <option {{ $comeOut->employee_id == $employee->id ? 'selected':'' }} value="{{$employee->id}}">{{$employee->short_full_name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="name" class="col-form-label">Название отдела</label>
    <input type="datetime-local" value="{{ date('Y-m-d H:i:s', strtotime($comeOut->action_time)) }}" required name="action_time" class="form-control">
</div>
