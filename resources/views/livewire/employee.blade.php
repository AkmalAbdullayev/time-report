<div>
    <div class="preloader" wire:loading></div>

    {{--    @include('livewire.flash')--}}

    @include('livewire.employee.form')

    @include('livewire.employee.list')

    @include('livewire.employee.modal')

    {{--    @include('layouts.partials._alerts')--}}
</div>

@push("js")
    @include('livewire.employee.script')
@endpush
