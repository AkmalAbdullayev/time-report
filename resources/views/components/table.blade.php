<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped text-center">
        <thead>
        <tr class="bg-light">
            {{ $head }}
        </tr>
        {{ $slot }}
        </thead>

        <tbody>
        {{ $body }}
        </tbody>
    </table>
</div>
