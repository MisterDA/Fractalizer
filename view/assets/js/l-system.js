/**
 * Turtle and Curve classes to draw a fractal.
 * Can be used anywhere, requires a canvas.
 *
 * Curve does not depend on a specific context, Turtle does.
 * Define a curve this way:

var canvas = $("#myCanvas");
var formula = JSON.parse($('<textarea/>').html(canvas.attr('data-formula')).val());
var curve = new Curve(formula, canvas);
curve.draw();

 * Example of formula:

{
    "title": "Koch",
    "alphabet": ["F"],
    "constants": ["+", "-"],
    "angle": 90,
    "iter": 2,
    "axiom": "F",
    "rules": {
        "F": "F+F-F-F+F"
    }
}

 */


// Turtle
function Turtle(x, y, l, a, context) {
    this.context = context;

    this.state = [{
        x  : x,
        y  : y,
        dx : 1,
        dy : 0,
        l  : l,
        a  : a * Math.PI / 180,

        _x  : x,
        _y  : y,
        _dx : 1,
        _dy : 0,
        _l  : l,
        _a  : a * Math.PI / 180
    }];

    this.get = function(k) {
        return this.state[this.state.length - 1][k];
    }
    this.g = function(k) {
        return this.get(k);
    }

    this.set = function(k, v) {
        this.state[this.state.length - 1][k] = v;
    }
    this.s = function(k, v) {
        this.set(k, v);
    }

    // radians !
    this.rotate = function(t) {
        var dx = this.g("dx");
        var dy = this.g("dy");

        this.s("dx", dx * Math.cos(t) + dy * Math.sin(t));
        this.s("dy", dy * Math.cos(t) - dx * Math.sin(t));
    }
    this.rotateLeft = function() {
        this.rotate(this.g("a"));
    }
    this.rotateRight = function() {
        this.rotate(-this.g("a"));
    }

    this.drawForward = function() {
        var x = this.g("x") + this.g("dx") * this.g("l");
        var y = this.g("y") + this.g("dy") * this.g("l");

        this.context.lineTo(x, y);
        this.s("x", x);
        this.s("y", y);
    }

    this.reset = function() {
        this.s("x",  this.g("_x"));
        this.s("y",  this.g("_y"));
        this.s("dx", this.g("_dx"));
        this.s("dy", this.g("_dy"));
        this.s("l",  this.g("_l"));
        this.s("a",  this.g("_a"));
    }

    this.push = function() {
        this.state.push({
            x   : this.g("x"),
            y   : this.g("y"),
            dx  : this.g("dx"),
            dy  : this.g("dy"),
            l   : this.g("l"),
            a   : this.g("a"),
            _x  : this.g("_dx"),
            _y  : this.g("_dy"),
            _dx : this.g("_dx"),
            _dy : this.g("_dy"),
            _l  : this.g("_l"),
            _a  : this.g("a")
        });
    }

    this.pop = function() {
        this.state.pop();
    }

    this.clearContext = function() {
        this.context.clearRect(0, 0, this.context.canvas.width, this.context.canvas.height);
    }
}

// Curve
function Curve(formula, canvas) {
    this.alphabet  = formula.alphabet;
    this.constants = formula.constants;
    this.angle     = formula.angle;
    this.axiom     = formula.axiom;
    this.iter      = formula.iter;
    this.rules     = formula.rules;

    this.turtle = new Turtle(0, canvas[0].height, canvas[0].width / 80, formula.angle, canvas[0].getContext('2d'));


    this.draw = function() {
        this.turtle.clearContext();
        this.turtle.context.moveTo(this.turtle.g("x"), this.turtle.g("y"));
        if (this.iter === 0)
            this.execute(this.axiom);
        else
            this.rec(this.axiom, this.iter);
        this.turtle.reset();
        this.turtle.context.stroke();
    }

    this.rec = function(v, n) {
        for (var i = 0, j = this.rules[v].length; i < j; i++) {
            var c = this.rules[v].charAt(i);

            if ((function (constants, c) {
                for (var k = 0, l = constants.length; k < l; k++)
                    if (constants[k] === c)
                        return true;
                return false;
            })(this.constants, c)) {
                this.execute(c);
            } else if ((function (alphabet, c) {
                for (var k = 0, l = alphabet.length; k < l; k++)
                    if (alphabet[k] === c)
                        return true;
                return false;
            })(this.alphabet, c)) {
                if (n > 1)
                    this.rec(c, n-1);
                else
                    this.execute(c);
            }
        }
    }

    this.execute = function(c) {
        if (c === "+") {
            this.turtle.rotateLeft();
        } else if (c === "-") {
            this.turtle.rotateRight();
        } else if (c === "[") {
            this.turtle.push();
        } else if (c === "]") {
            this.turtle.pop();
        } else {
            this.turtle.drawForward();
        }
    }
}

