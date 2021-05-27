<div
    class="modal fade"
    id="editModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="editModalLabel"
    aria-hidden="true"
    wire:ignore.self
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="cursor:move;">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-6">
                        <label for="name">Название графика<span class="text-danger">*</span></label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Название графика..."
                            wire:model.defer="edit.name"
                        >
                    </div>

                    <div class="form-group col-6">
                        <label for="description">Описание</label>
                        <input
                            id="description"
                            type="text"
                            name="description"
                            class="form-control"
                            placeholder="Описание..."
                            wire:model.defer="edit.description"
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-6">
                        <label for="range_from">Интервал(С)</label>
                        <span class="text-danger">*</span>
                        <input
                            type="text"
                            class="form-control"
                            name="range_from"
                            id="range_from"
                            placeholder="График с..."
                            wire:model.defer="edit.range_from"
                        />
                    </div>

                    <div class="form-group col-6">
                        <label for="range_from">Интервал(До)</label>
                        <span class="text-danger">*</span>
                        <input
                            type="text"
                            class="form-control"
                            name="range_to"
                            id="range_to"
                            placeholder="График до..."
                            wire:model.defer="edit.range_to"
                        />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" wire:click="update">Сохранить</button>
            </div>
        </div>
    </div>
</div>
