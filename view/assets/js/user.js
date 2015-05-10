$(document).ready(function() {
    var canvases = $("canvas");

    canvases.each(function() {
        var formula = JSON.parse($('<textarea/>').html($(this).attr("data-formula")).val());
        var curve = new Curve(formula, $(this));
        curve.draw();
    });
    $("button[data-role=upvote]").click(function(e) {
        var id = $(this).attr("data-id");
        $.post('/', {action: 'upvote', fractal: id}, function (data) {
            $(".vote[data-id=" + id + "]").text(data);
        });
    });
    $("button[data-role=downvote]").click(function() {
        var id = $(this).attr("data-id");
        $.post('/', {action: 'downvote', fractal: id}, function(data) {
            $(".vote[data-id=" + id + "]").text(data);
        });
    });
});

