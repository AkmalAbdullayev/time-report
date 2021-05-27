jQuery(document).ready(function ($) {
    $('.confirmDelete').on('click', function (e) {
        let form = $(this).data('form');
        e.preventDefault();
        alertify.confirm('Вы уверены?', function () {
            $('#' + form).submit();
        })
    })
    $('.confirm').on('click', function (e) {
        e.preventDefault()
        let $this = $(this);
        alertify.confirm('Вы уверены?', function () {
            $this.parents('form').unbind('submit').submit();
        }, function () {
        })
    })

    $('.openProducts').on('click', function (e) {
        e.preventDefault();
        $('.child-elements').fadeOut('fast')
        let child = $(this).next('tr.child-elements');
        if (child.is(':hidden')) {
            child.fadeIn('fast')
        } else {
            child.fadeOut('fast')
        }
    })
    $('.openProductsTD').on('click', function (e) {
        e.preventDefault();
        $('.child-elements').fadeOut('fast')
        let child = $(this).parents('tr').next('tr.child-elements');
        if (child.is(':hidden')) {
            child.fadeIn('fast')
        } else {
            child.fadeOut('fast')
        }
    })
    $('.openProd').on('click', function (e) {
        e.preventDefault();
        $('.child-elements').fadeOut('fast')
        let child = $(this).parents('tr').next('tr.child-elements');
        if (child.is(':hidden')) {
            child.fadeIn('fast')
        } else {
            child.fadeOut('fast')
        }
        let attrId = $(this).data('attrid');
        localStorage.setItem('attrid', attrId)
    })
    if (localStorage.getItem("attrid") !== undefined) {
        $('.attr_'+localStorage.getItem("attrid")).click()
    }

    $('.edit-modal .close').on('click', function (){
        window.history.pushState("object or string", "Title", window.location.href.split("?")[0]);
    })

    $('.qr-modal .close').on('click', function (){
        window.history.pushState("object or string", "Title", window.location.href.split("?")[0]);
    })

    $('.qr-modal .clos').on('click', function (){
        window.history.pushState("object or string", "Title", window.location.href.split("?")[0]);
    })
})
