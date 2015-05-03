<?php
session_start();
$_SESSION["url"] = "./fractalize.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Fractalizer</title>

        <link rel="stylesheet" href="../assets/css/fractalize.css">
    </head>
    <body>
        <ul>
            <li>Variables are one-letter sized.</li>
            <li>One rule per variable. Example where A, B are variables and +, - are constants : <code>A=A+B-</code>.</li>
        </ul>
        <table>
            <tr>
                <td>+</td>
                <td>Turn right by angle</td>
            </tr>
            <tr>
                <td>-</td>
                <td>Turn left by angle</td>
            </tr>
            <tr>
                <td>[</td>
                <td>Save current state</td>
            </tr>
            <tr>
                <td>]</td>
                <td>Pop current state</td>
            </tr>
        </table>
        <form>
            <label>Title : <input type="title"  name="title"></label>
            <label>Iter :  <input type="number" name="iter"  id="iter"  min="0" value="0"></label>
            <label>Axiom : <input type="text"   name="axiom" id="axiom"></label>
            <label>Angle (deg): <input type="number" name="angle" id="angle" min="0" max="360" value="0"></label>
            <label id="rules">
                Rules : <input type="text" name="rule[]">
                <button id="addRuleButton">+</button>
            </label>
            <button id="drawButton">Draw</button>
            <input type="submit" value="Post">
        </form>

        <canvas id="myCanvas" width="800" height="600">Canvas is not supported !</canvas>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="../assets/js/l-system.js"></script>
        <script src="../assets/js/fractalize.js"></script>
    </body>
</html>

