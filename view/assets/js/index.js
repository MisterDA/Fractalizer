$(document).ready(function() {
    var canvases = $("canvas");

    function draw(canvas) {
        var formula = JSON.parse($('<textarea/>').html(canvas.attr("data-formula")).val());
        var curve = new Curve(formula, canvas);
        curve.draw();
    }

    canvases.each(function() { draw($(this)); });

    $("button[data-role=upvote]").click(function() {
        var id = $(this).attr("data-id");
        $.post('/', {action: 'upvote', fractal: id}, function (data) {
            $("#vote-" + id).text(data);
        });
    });
    $("button[data-role=downvote]").click(function() {
        var id = $(this).attr("data-id");
        $.post('/', {action: 'downvote', fractal: id}, function(data) {
            $("#vote-" + id).text(data);
        });
    });

    var skip = 10;
    $("#more").click(function() {
        $.post('/', {action: 'load', skip: skip, sort: 'votes'}, function (data) {
            data = $(data);
            $("#more").before(data);
            data.find("canvas").each(function() { draw($(this)); });
            skip += 10;
        });
    })
});

