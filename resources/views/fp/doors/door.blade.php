@extends("layouts.master", ["title" => "Дверь"])

@section("content")
    <div class="card">
        <div class="card-header">Двери</div>
        <div class="card-body">
            <form class="form form-row" method="POST">
                @csrf
                @if(isset($door))
                    <input type="hidden" name="door_id" value="{{ $door->id }}">
                    <input type="hidden" name="method" value="update">
                @else
                    <input type="hidden" name="method" value="create">
                @endif
                <div class="col-10">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm @error('title') is-invalid @enderror" value="{{ old('title', isset($door) ? $door->name : NULL) }}" name="title" placeholder="Введите название *" autofocus required>
                        @error('title')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-block btn-primary">{{ isset($door) ? 'Обновить' : 'Добавить' }}</button>
                    </div>
                </div>
            </form>


            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm text-center">
                    <thead>
                    <tr class="bg-gray">
                        <th>#</th>
                        <th>Действия</th>
                        <th>Название</th>
                        <th>Дата создания</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($doors->count())
                            @foreach($doors as $k => $door)
                                <tr>
                                    <td width="50">{{ $k+1 }}</td>
                                    <td width="150">
                                        <form action="" method="POST">
                                            @csrf
                                            <input type="hidden" name="method" value="delete">
                                            <input type="hidden" name="door_id" value="{{ $door->id }}">
                                            <div class="btn-group">
                                                <a class="bx bxs-edit btn btn-primary btn-sm" href="?method=edit&door_id={{ base64_encode($door->id) }}" style="font-size:10px;" title="Изменить"></a>
                                                <button class="bx bxs-trash-alt btn btn-danger btn-sm confirm" style="font-size:10px;" title="Удалить"></button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-left">{{ $door->name }}</td>
                                    <td width="150">{{ $door->created_at ? $door->created_at->format('d.m.Y H:i') : '-' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4"><i class="small">Пусто</i></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        history.pushState({}, null, location.origin+location.pathname);
    </script>
@endpush
