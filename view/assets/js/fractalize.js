$(document).ready(function() {
    $("#addRuleButton").click(function (e) {
        e.preventDefault();
        $(this).before('<input type="text" name="rules[]">');
    });

    var canvas  = $("#myCanvas");
    var context = canvas[0].getContext('2d');

    $("#drawButton").click(function (e) {
        e.preventDefault();
        curve = new Curve(
            $("#axiom").val(),
            (function() {
                var rules = {};
                $("#rules input").each(function() {
                    var str = $(this).val();
                    if (str.length > 3)
                        rules[str.charAt(0)] = str.substr(2);
                });
                return rules;
            })(),
            new Turtle(0, canvas.height(), canvas.width() / 80, parseInt($("#angle").val()), context)
        );
        curve.turtle.clearContext();
        curve.draw(parseInt($("#iter").val()));
    });
});

