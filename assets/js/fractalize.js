$(document).ready(function() {
    $("#addRuleButton").click(function (e) {
        e.preventDefault();
        $("#rules").append('<input type="text" name="rule[]">');
    });

    var parseRule = function() {
        var rules = [];
        $("#rules").children().each(function(e) {
            var str = e.val();

        });
    }

    $("#drawButton").click(function (e) {
        e.preventDefault();
        var context = $("#myCanvas")[0].getContext('2d');


    });
});
