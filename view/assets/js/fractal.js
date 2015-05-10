$(document).ready(function() {
    var canvas = $("#canvas");
    var id = canvas.attr("data-id");

    var formula = JSON.parse($('<textarea/>').html(canvas.attr("data-formula")).val());
    var curve = new Curve(formula, canvas);
    curve.draw();

    $("#upvote").click(function(e) {
        $.post('/', {action: 'upvote', fractal: id}, function (data) {
            $("#vote").text(data);
        });
    });
    $("#downvote").click(function() {
        $.post('/', {action: 'downvote', fractal: id}, function(data) {
            $("#vote").text(data);
        });
    });
    $("#post").click(function(e) {
        e.preventDefault();
        var text = $(this).prev().val();
        $.post('/fractal', {text: text, fractal: id}, function(data) {
            if (data.success) {
                var str = '<div class="comment"><h3><span class="author">' + data.author + '</span> on <span class="date">' + data.date + '</span></h3><p class="text">' + data.text + '</p></div>';
                $("#comments form").before(str);
                $("textarea[name='text']").val('');
            } else {
                $(this).parent().before('<p class="error">There has been an error posting your comment. Check it\'s length !</p>');
            }
        }, "json");
    });
});

