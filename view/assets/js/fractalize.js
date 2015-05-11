$(document).ready(function() {
    var canvas  = $("#myCanvas");
    var textarea = $("#formula");

    textarea.bind('input propertychange', function() {
        var obj = {};
        try {
            obj = JSON.parse(textarea.val());
        } catch (e) {
            textarea.css("border", "3px solid red");
            return;
        }
        textarea.css("border", "3px solid green");
    });

    $("#drawButton").click(function (e) {
        e.preventDefault();
        var formula;
        try {
            formula = JSON.parse(textarea.val());
        } catch (e) {
            textarea.css("border", "3px solid red");
            return;
        }
        textarea.css("border", "3px solid green");

        var curve = new Curve(formula, canvas);
        curve.draw();
    });

    $("#submit").click(function (e) {
        var formula;
        try {
            formula = JSON.parse(textarea.val());
        } catch (e) {
            textarea.css("border", "3px solid red");
            e.preventDefault();
            return;
        }
        textarea.css("border", "3px solid green");
    })
});

