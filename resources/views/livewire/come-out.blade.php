<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">LIVE</div>
        </div>

        <div class="card-body">
            <h3>Отчеты : {{ __("month." . now()->monthName) }}</h3>

            <hr>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-light">
                        <th>#</th>
                        <th>Ф.И.О</th>
                        <th>График</th>
                        <th>Организация</th>
                        <th>Здание</th>
                        <th>Подразделения</th>
                        <th>Должность</th>
                        <th>Вход, Выход</th>
                        <th>Время Прибытия</th>
                    </tr>
                    <tr>
                        <th colspan="8"></th>
                        <th>
                            <form>
                                <select
                                    onchange="$(this).closest('form').submit()"
                                    name="attendance_status"
                                    id="attendance_status"
                                    class="form-control form-control-sm"
                                >
                                    <option disabled selected>Выберите...</option>
                                    <option value="1">Опоздание</option>
                                    <option value="2">Во время(Вход)</option>
                                    <option value="3">Во время(Выход)</option>
                                    <option value="4">Заранее</option>
                                </select>
                            </form>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1; @endphp
                    @if ($attendance_status === 0)
                        @forelse($comeOuts as $comeOut)
                            <tr>
                                @if ($comeOut->employees === null)
                                    @continue
                                @else
                                    <td>{{ $comeOut->id }}</td>
                                    <td>{{ $comeOut->employees->short_full_name }}</td>
                                    <td {!! $comeOut->employees->schedule->name ?? 'class="text-danger"' !!}>{{ $comeOut->employees->schedule->name ?? 'Не связано график работы' }}</td>
                                    <td>{{ $comeOut->employees->companies->name }}</td>
                                    <td>{{ $comeOut->employees->branches->name }}</td>
                                    <td>{{ $comeOut->employees->departments->name }}</td>
                                    <td>{{ $comeOut->employees->positions->name }}</td>
                                    @if(\Illuminate\Support\Facades\DB::table("door_device")->where("id", $comeOut->doors_has_device_id)->get("is_come")->pluck("is_come")[0] == 1)
                                        <td class="text-success">Вход</td>
                                    @else
                                        <td class="text-danger">Выход</td>
                                    @endif

                                    @isset($comeOut->employees->schedule->range_from)
                                        @if (\Illuminate\Support\Facades\DB::table("door_device")->where("id", $comeOut->doors_has_device_id)->get("is_come")->pluck("is_come")[0] == 1 && $comeOut->employees->schedule->range_from >= date("H:i", strtotime($comeOut->action_time)))
                                            <td class="text-success">{{ $comeOut->action_time }} (ВО ВРЕМЯ)</td>
                                        @elseif(\Illuminate\Support\Facades\DB::table("door_device")->where("id", $comeOut->doors_has_device_id)->get("is_come")->pluck("is_come")[0] == 1 && $comeOut->employees->schedule->range_from < date("H:i", strtotime($comeOut->action_time)))
                                            <td class="text-danger">{{ $comeOut->action_time }} (ОПОЗДАНИЕ)</td>
                                        @elseif(\Illuminate\Support\Facades\DB::table("door_device")->where("id", $comeOut->doors_has_device_id)->get("is_come")->pluck("is_come")[0] == 2 && $comeOut->employees->schedule->range_to > date("H:i", strtotime($comeOut->action_time)))
                                            <td class="text-warning">{{ $comeOut->action_time }} (Заранее)</td>
                                        @elseif(\Illuminate\Support\Facades\DB::table("door_device")->where("id", $comeOut->doors_has_device_id)->get("is_come")->pluck("is_come")[0] == 2 && $comeOut->employees->schedule->range_to <= date("H:i", strtotime($comeOut->action_time)))
                                            <td class="text-success">{{ $comeOut->action_time }} (ВО ВРЕМЯ)</td>
                                        @endif
                                    @endisset
                                @endif
                            </tr>
                        @empty
                            <td colspan="7">No Data.</td>
                        @endforelse
                    @endif

                    @if ($attendance_status === 1)
                        @forelse($comeOuts as $comeOut)
                            @isset($comeOut->employees->schedule->range_from)
                                @if (\Illuminate\Support\Facades\DB::table("door_device")->where("id", $comeOut->doors_has_device_id)->get("is_come")->pluck("is_come")[0] == 1 && $comeOut->employees->schedule->range_from < date("H:i", strtotime($comeOut->action_time)))
                                    <tr>
                                        @if ($comeOut->employees === null)
                                            @continue
                                        @else
                                            <td>{{ $comeOut->id }}</td>
                                            <td>{{ $comeOut->employees->short_full_name }}</td>
                                            <td {!! $comeOut->employees->schedule->name ?? 'class="text-danger"' !!}>{{ $comeOut->employees->schedule->name ?? 'Не связано график работы' }}</td>
                                            <td>{{ $comeOut->employees->companies->name }}</td>
                                            <td>{{ $comeOut->employees->branches->name }}</td>
                                            <td>{{ $comeOut->employees->departments->name }}</td>
                                            <td>{{ $comeOut->employees->positions->name }}</td>
                                            <td class="text-success">Вход</td>
                                            <td class="text-danger">
                                                {{ $comeOut->action_time }}
                                                (ОПОЗДАНИЕ)
                                            </td>
                                        @endif
                                        @endif
                                    </tr>
                                @endisset
                                @empty
                                    <td colspan="7">No Data.</td>
                                @endforelse
                            @endif

                            @if ($attendance_status === 2)
                                @forelse($comeOuts as $comeOut)
                                    @isset($comeOut->employees->schedule->range_from)
                                        @if (\Illuminate\Support\Facades\DB::table("door_device")->where("id", $comeOut->doors_has_device_id)->get("is_come")->pluck("is_come")[0] == 1 && $comeOut->employees->schedule->range_from >= date("H:i", strtotime($comeOut->action_time)))
                                            <tr>
                                                @if ($comeOut->employees === null)
                                                    @continue
                                                @else
                                                    <td>{{ $comeOut->id }}</td>
                                                    <td>{{ $comeOut->employees->short_full_name }}</td>
                                                    <td {!! $comeOut->employees->schedule->name ?? 'class="text-danger"' !!}>{{ $comeOut->employees->schedule->name ?? 'Не связано график работы' }}</td>
                                                    <td>{{ $comeOut->employees->companies->name }}</td>
                                                    <td>{{ $comeOut->employees->branches->name }}</td>
                                                    <td>{{ $comeOut->employees->departments->name }}</td>
                                                    <td>{{ $comeOut->employees->positions->name }}</td>
                                                    <td class="text-success">Вход</td>
                                                    <td class="text-success">
                                                        {{ $comeOut->action_time }}
                                                        (ВО ВРЕМЯ)
                                                    </td>
                                                @endif
                                                @endif
                                            </tr>
                                        @endisset
                                        @empty
                                            <td colspan="7">No Data.</td>
                                        @endforelse
                                    @endif

                                    @if($attendance_status === 3)
                                        @foreach($comeOuts as $comeOut)
                                            @isset($comeOut->employees->schedule->range_to)
                                                @if(\Illuminate\Support\Facades\DB::table("door_device")->where("id", $comeOut->doors_has_device_id)->get("is_come")->pluck("is_come")[0] == 2 && $comeOut->employees->schedule->range_to <= date("H:i", strtotime($comeOut->action_time)))
                                                    <tr>
                                                        @if ($comeOut->employees === null)
                                                            @continue
                                                        @else
                                                            <td>{{ $comeOut->id }}</td>
                                                            <td>{{ $comeOut->employees->short_full_name }}</td>
                                                            <td {!! $comeOut->employees->schedule->name ?? 'class="text-danger"' !!}>{{ $comeOut->employees->schedule->name ?? 'Не связано график работы' }}</td>
                                                            <td>{{ $comeOut->employees->companies->name }}</td>
                                                            <td>{{ $comeOut->employees->branches->name }}</td>
                                                            <td>{{ $comeOut->employees->departments->name }}</td>
                                                            <td>{{ $comeOut->employees->positions->name }}</td>
                                                            <td class="text-danger">Выход</td>
                                                            <td class="text-success">
                                                                {{ $comeOut->action_time }}
                                                                (Во Время)
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endif
                                            @endisset
                                        @endforeach
                                    @endif

                                    @if($attendance_status === 4)
                                        @foreach($comeOuts as $comeOut)
                                            @isset($comeOut->employees->schedule->range_to)
                                                @if(\Illuminate\Support\Facades\DB::table("door_device")->where("id", $comeOut->doors_has_device_id)->get("is_come")->pluck("is_come")[0] == 2 && $comeOut->employees->schedule->range_to > date("H:i", strtotime($comeOut->action_time)))
                                                    <tr>
                                                        @if ($comeOut->employees === null)
                                                            @continue
                                                        @else
                                                            <td>{{ $comeOut->id }}</td>
                                                            <td>{{ $comeOut->employees->short_full_name }}</td>
                                                            <td {!! $comeOut->employees->schedule->name ?? 'class="text-danger"' !!}>{{ $comeOut->employees->schedule->name ?? 'Не связано график работы' }}</td>
                                                            <td>{{ $comeOut->employees->companies->name }}</td>
                                                            <td>{{ $comeOut->employees->branches->name }}</td>
                                                            <td>{{ $comeOut->employees->departments->name }}</td>
                                                            <td>{{ $comeOut->employees->positions->name }}</td>
                                                            <td class="text-danger">Выход</td>
                                                            <td class="text-warning">
                                                                {{ $comeOut->action_time }}
                                                                (Заранее)
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endif
                                            @endisset
                                        @endforeach
                                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 pb-2">
        {{ $comeOuts->links('pagination::bootstrap-4') }}
    </div>

    @push("js")
        <script>
            $(document).ready(function () {
                $("#attendance_status").on("change", function (event) {
                @this.set("attendance_status", event.target.value);
                });
            });
        </script>
    @endpush
</div>
