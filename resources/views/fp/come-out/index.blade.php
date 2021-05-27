{{--@extends("layouts.master", ["title" => "LIVE", "meta" => '<meta http-equiv="refresh" content="300">'])--}}
@extends("layouts.master", ["title" => "LIVE"])
@section("content")
    <livewire:live/>

{{--    <div class="card">--}}
{{--        <div class="card-header d-flex justify-content-between align-items-center">--}}
{{--            <div class="title">LIVE</div>--}}
{{--        </div>--}}

{{--        <div class="card-body">--}}
{{--            <h3>Отчеты : {{ __("month." . now()->monthName) }}</h3>--}}

{{--            <hr>--}}
{{--            <div class="table-responsive">--}}
{{--                <table class="table table-sm table-bordered table-striped text-center">--}}
{{--                    <thead>--}}
{{--                    <tr class="bg-light">--}}
{{--                        <th>#</th>--}}
{{--                        <th>Ф.И.О</th>--}}
{{--                        <th>График</th>--}}
{{--                        <th>Организация</th>--}}
{{--                        <th>Здание</th>--}}
{{--                        <th>Подразделения</th>--}}
{{--                        <th>Должность</th>--}}
{{--                        <th>Вход, Выход</th>--}}
{{--                        <th>Время Прибытия</th>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th colspan="8"></th>--}}
{{--                        <th>--}}
{{--                            <form>--}}
{{--                                <select--}}
{{--                                    onchange="$(this).closest('form').submit()"--}}
{{--                                    name="attendance_status"--}}
{{--                                    id="attendance_status"--}}
{{--                                    class="form-control form-control-sm"--}}
{{--                                >--}}
{{--                                    <option disabled selected>Выберите...</option>--}}
{{--                                    <option value="1" {{request('attendance_status') == 1 ? 'selected' : ''}}>--}}
{{--                                        Опоздание--}}
{{--                                    </option>--}}
{{--                                    <option value="2" {{request('attendance_status') == 2 ? 'selected' : ''}}>Во--}}
{{--                                        время(Вход)--}}
{{--                                    </option>--}}
{{--                                    <option value="3" {{request('attendance_status') == 3 ? 'selected' : ''}}>Во--}}
{{--                                        время(Выход)--}}
{{--                                    </option>--}}
{{--                                    <option value="4" {{request('attendance_status') == 4 ? 'selected' : ''}}>Заранее--}}
{{--                                    </option>--}}
{{--                                </select>--}}
{{--                            </form>--}}
{{--                        </th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @php $i = 1; @endphp--}}
{{--                    @forelse($comeOuts as $comeOut)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $comeOut->id }}</td>--}}
{{--                            <td>{{ $comeOut->first_name }}</td>--}}
{{--                            <td {!! $comeOut->schedule_name ?? 'class="text-danger"' !!}>{{ $comeOut->schedule_name ?? 'Не связано график работы' }}</td>--}}
{{--                            <td>{{ $comeOut->company_name }}</td>--}}
{{--                            <td>{{ $comeOut->branch_name }}</td>--}}
{{--                            <td>{{ $comeOut->department_name }}</td>--}}
{{--                            <td>{{ $comeOut->position_name }}</td>--}}
{{--                            {!! $comeOut->is_come === 1 ? '<td class="text-success">Вход</td>' :  '<td class="text-danger">Выход</td>'!!}--}}

{{--                            @isset($comeOut->range_from)--}}
{{--                                @if($comeOut->is_come == 1)--}}
{{--                                    @if($comeOut->range_from >= date("H:i", strtotime($comeOut->action_time)))--}}
{{--                                        <td class="text-success">{{ $comeOut->action_time }} (ВО ВРЕМЯ)</td>--}}
{{--                                    @elseif($comeOut->range_from < date("H:i", strtotime($comeOut->action_time)))--}}
{{--                                        <td class="text-danger">{{ $comeOut->action_time }} (ОПОЗДАНИЕ)</td>--}}
{{--                                    @endif--}}
{{--                                @else--}}
{{--                                    @if($comeOut->range_to > date("H:i", strtotime($comeOut->action_time)))--}}
{{--                                        <td class="text-warning">{{ $comeOut->action_time }} (Заранее)</td>--}}
{{--                                    @elseif($comeOut->range_to <= date("H:i", strtotime($comeOut->action_time)))--}}
{{--                                        <td class="text-success">{{ $comeOut->action_time }} (ВО ВРЕМЯ)</td>--}}
{{--                                    @endif--}}
{{--                                @endif--}}
{{--                            @endisset--}}
{{--                        </tr>--}}
{{--                    @empty--}}
{{--                        <td colspan="9">No Data.</td>--}}
{{--                    @endforelse--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="mt-3 pb-2">--}}
{{--        {{ $comeOuts->appends(request()->toArray())->links('pagination::bootstrap-4') }}--}}
{{--    </div>--}}
@endsection
