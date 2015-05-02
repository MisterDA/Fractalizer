$(document).ready(function() {
    $("#addRuleButton").click(function (e) {
        e.preventDefault();
        $(this).before('<input type="text" name="rule[]">');
    });

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
            new Turtle(
                0, 600, 10, parseInt($("#angle").val()) * Math.PI / 180, $("#myCanvas")[0].getContext('2d')
            )
        );
        curve.turtle.clearContext();
        curve.draw(parseInt($("#iter").val()));
    });
});

