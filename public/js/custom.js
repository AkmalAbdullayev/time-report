$('.confirm').on('click', function (e) {
    e.preventDefault();
    let $this = $(this);
    alertify.confirm($this.data('title'), function () {
        $this.parents('form').unbind('submit').submit();
    }, function () {
    }).set({title:"Тасдикланг"},{labels:{ok:'Ҳа', cancel: 'Йўқ'}});
})
window.addEventListener('modal', event => {
    $(event.detail.name).modal(event.detail.action)
})
window.addEventListener('confirmDelete', event => {
    alertify.confirm("Вы действительно хотите удалить?", function () {
        return true;
    }, function () {
        return false;
    }).set({title:"Подтвердить"},{labels:{ok:'Да', cancel: 'Нет'}});
})

