$(document).ready(function() {
    var canvases = $("canvas");

    canvases.each(function() {
        var formula = JSON.parse($('<textarea/>').html($(this).attr("data-formula")).val());
        var context = $(this)[0].getContext('2d');
        var curve = new Curve(formula.axiom, formula.rules,
            new Turtle(0, 600, 10, formula.angle, context));
        curve.draw(4);
    });
    $("button[data-role=upvote]").click(function(e) {
        var id = $(this).attr("data-id");
        $.post('index.php', {action: 'upvote', fractal: id}, function (data) {
            $("#vote-" + id).text(data);
        });
    });
    $("button[data-role=downvote]").click(function() {
        var id = $(this).attr("data-id");
        $.post('index.php', {action: 'downvote', fractal: id}, function(data) {
            $("#vote-" + id).text(data);
        });
    });
});

