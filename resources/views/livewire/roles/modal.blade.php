<div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
    wire:ignore.self
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Изменить</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="editRole">Название</label>
                        <input
                            type="text"
                            class="form-control"
                            id="editRole"
                            wire:model.defer="edit.name"
                        >
                    </div>

                    <div class="col-12">
                        <select
                            class="form-control"
                            id="select2-modal"
                            multiple
                            wire:model.defer="permission_ids"
                        >
                            @if(array_key_exists("permissions", $edit))
                                @foreach($permissions as $key => $permission)
                                    @if(!array_key_exists($permission->id, $edit["permissions"]))
                                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                    @else
                                        @once
                                            <option disabled selected>Ничего не найдено.</option>
                                        @endonce
                                    @endif
                                @endforeach
                            @endif
                            {{--                            @foreach ($permissions as $permission)--}}
                            {{--                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>--}}
                            {{--                            @endforeach--}}
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                <button
                    type="button"
                    class="btn btn-primary"
                    wire:click="update"
                >
                    Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

