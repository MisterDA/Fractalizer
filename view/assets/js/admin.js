$(document).ready(function() {

    function updateUsersButtons() {
        $('button[data-remove=user]').click(function () {
            var id = $(this).parent().parent().attr('id');
            $.post('/admin', {action: 'remove', entity: 'user', id: id}, function (data) {
                if (data !== "") {
                    $("#users").replaceWith(data);
                    $.post('/admin', {action: 'load', entity: 'fractals'}, function (data) {
                        if (data !== "") {
                            $("#fractals").replaceWith(data);
                            updateFractalsButtons();
                            updateModals();
                        }
                    })
                    updateUsersButtons();
                }
            });
        });
    }

    function updateFractalsButtons() {
        $('button[data-remove=fractal]').click(function () {
            var id = $(this).parent().parent().parent().attr('id');
            $.post('/admin', {action: 'remove', entity: 'fractal', id: id}, function (data) {
                if (data !== "") {
                    $("#fractals").replaceWith(data);
                    updateFractalsButtons();
                    updateModals();
                }
            });
        });
    }

    function updateCommentsButtons() {
        $('button[data-remove=comment]').click(function () {
            var id = $(this).parent().parent().attr('id');
            var table = $(this).parent().parent().parent().parent();
            $.post('/admin', {action: 'remove', entity: 'comment', id: id}, function (data) {
                if (data !== "") {
                    table.replaceWith(data);
                    updateFractalsButtons();
                }
            });
        });
    }

    function updateModals() {
        $('div.modal').on('show.bs.modal', function (event) {
            var modal = $(this);

            var button = $(event.relatedTarget);
            var action = button.attr('data-action');
            var id     = modal.attr('id').substr(6);

            if (action == "edit") {
                $.post('/admin', {action: 'load', entity: 'comments', id: id}, function (data) {
                    var body = modal.find('div.modal-body');
                    var last = body.children().last();
                    if (last[0].tagName === "DIV") {
                        body.append(data);
                    } else {
                        last.replaceWith(data);
                    }
                    updateCommentsButtons();
                });
            }

            var canvas = modal.find('canvas');
            var formula = JSON.parse($('<textarea/>').html(canvas.attr('data-formula')).val());
            var curve = new Curve(formula, canvas);
            curve.draw();
        });
    }

    updateUsersButtons();
    updateFractalsButtons();
    updateModals();
});

