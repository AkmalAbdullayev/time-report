@extends('layouts.master')
@push('css')
<style>
    .card-body.py-1 {
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
    }
</style>
@endpush
@section('content')
<section id="dashboard-ecommerce">
    <div class="row mb-1">
        <div class="col-xl-4 col-md-6 col-12">
            <div class="card h-100 text-center">
                <div class="card-body py-1">
                    <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                        <i class="bx bx-user font-medium-5"></i>
                    </div>
                    <div class="text-muted text-bold-500 text-uppercase">Сейчас на работе</div>
                    <h3 class="mb-0">{{ $online ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 col-12">
            <div class="card h-100 text-center">
                <div class="card-body py-1">
                    <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                        <i class="bx bx-user font-medium-5"></i>
                    </div>
                    <div class="text-muted text-bold-500 text-uppercase">Отсутствующие</div>
                    <h3 class="mb-0">{{ $offline ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 col-12">
            <div class="card h-100 text-center">
                <div class="card-body py-1">
                    <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                        <i class="bx bx-user font-medium-5"></i>
                    </div>
                    <div class="text-muted text-bold-500 text-uppercase">Входы и Выходы</div>
                    <h3 class="mb-0">{{ $countInOuts }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Организации</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="text-center bg-gray">
                        <tr>
                        <th width="50">#</th>
                        <th>Организация</th>
                        <th>Кол-во сотрудников</th>
                        <th>Сейчас на работе</th>
                        <th>Отсутствующие</th>
                        <th>Входы и выходы</th>
                    </tr>
                    </thead>
                    @foreach($companies_data as $k => $company)
                    <tr>
                        <td class="text-center">{{ $k+1 }}</td>
                        <td>{{ $company['company']->name ?? '' }}</td>
                        <td class="text-center">{{ $company['company']->employee->count() ?? 0 }}</td>
                        <td class="text-center">{{ $company['online'] ?? 0 }}</td>
                        <td class="text-center">{{ $company['offline'] ?? 0 }}</td>
                        <td class="text-center">{{ $company['in_out'] ?? 0 }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Marketing Campaigns Starts -->
        <div class="col-xl-6 col-12 dashboard-marketing-campaign">
            <div class="card marketing-campaigns">
                <div class="card-header d-flex justify-content-between align-items-center pb-1">
                    <h4 class="card-title">Top-5 Опоздавшие сотрудники</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- table start -->
                        <table id="table-marketing-campaigns"
                        class="table table-striped table-sm table-bordered mb-0">
                        <thead>
                            <tr class="text-center">
                                <th width=50>#</th>
                                <th>Ф.И.О</th>
                                <th>Организация</th>
                                <th>Здание</th>
                                <th>Подразделения</th>
                                <th>Должность</th>
                                <th>Кол-во опозданий</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($employees as $k => $employee)
                            <tr class="text-center">
                                <td class="py-1 line-ellipsis">
                                    {{ $i++ }}
                                </td>
                                <td class="py-1">
                                    {{ "{$employee->first_name} {$employee->last_name}" }}
                                </td>
                                <td class="py-1">
                                    {{ $employee->companies->name }}
                                </td>
                                <td class="py-1">
                                    {{ $employee->branches->name }}
                                </td>
                                <td class="py-1">
                                    {{ $employee->departments->name }}
                                </td>
                                <td class="py-1">
                                    {{ $employee->positions->name }}
                                </td>
                                <td class="py-1">
                                    {{ $employee->absentCount ?? 0 }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- table ends -->
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-12 dashboard-marketing-campaign">
        <div class="card marketing-campaigns">
            <div class="card-header d-flex justify-content-between align-items-center pb-1">
                <h4 class="card-title">Top-5 Рано приходящие</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- table start -->
                    <table id="table-marketing-campaigns"
                    class="table table-sm table-bordered table-striped mb-0">
                    <thead>
                        <tr class="text-center">
                            <th width="50">#</th>
                            <th>Ф.И.О</th>
                            <th>Организация</th>
                            <th>Здание</th>
                            <th>Подразделения</th>
                            <th>Должность</th>
                            <th>Кол-во опозданий</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($early_came as $k => $employee)
                        <tr class="text-center">
                            <td class="py-1 line-ellipsis">
                                {{ $i++ }}
                            </td>
                            <td class="py-1">
                                {{ "{$employee->first_name} {$employee->last_name}" }}
                            </td>
                            <td class="py-1">
                                {{ $employee->companies->name }}
                            </td>
                            <td class="py-1">
                                {{ $employee->branches->name }}
                            </td>
                            <td class="py-1">
                                {{ $employee->departments->name }}
                            </td>
                            <td class="py-1">
                                {{ $employee->positions->name }}
                            </td>
                            <td class="py-1">
                                {{ $employee->numOfEarlyIn ?? 0 }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- table ends -->
            </div>
        </div>
    </div>
</div>
</div>
</section>
@endsection
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.css') }}">
@endpush
@push('js')
<script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
@endpush
