<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Fractalizer</title>

        <link rel="stylesheet" href="view/assets/css/fractalize.css">
        <link rel="stylesheet" href="view/assets/css/menu.css">
    </head>
    <body>

<?php require_once("view/include/menu.php"); ?>

        <section>
        <h2>Make your Fractal</h2>
        <p>Take a look at Wikipedia to understand how <a href="http://en.wikipedia.org/wiki/L-system">Lindenmayer system</a> work !</p>
        <div class="rules">
        <ul>
            <li>Variables are one-letter sized.</li>
            <li>One rule per variable. Example where A, B are variables and +, - are constants : <code>A=A+B-</code>.</li>
            <li>Angle in degrees.</li>
        </ul>
        <table>
            <tbody><tr>
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
            </tbody>
        </table>
        </div>
         <form method="POST">
            <label for="title">Title: <input type="text" name="title" id="title"></label>
            <textarea name="formula" id="formula" cols="50" rows="15"><?php echo htmlspecialchars($formula); ?></textarea>
            <button id="drawButton">Draw</button>
            <input  id="submit" type="submit" value="Post">
        </form>

        <canvas id="myCanvas" width="800" height="600">Canvas is not supported !</canvas>

        </section>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="view/assets/js/l-system.js"></script>
        <script src="view/assets/js/fractalize.js"></script>
    </body>
</html>

