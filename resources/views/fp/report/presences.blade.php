@extends("layouts.master", ["title" => "Присутствующие на данный момент"])

@section("content")
{{--    <livewire:report.presences/>--}}
    @livewire('report.presences', ["export" => $export])
@endsection
