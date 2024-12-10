<?php

declare(strict_types=1);

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./styles/app.css">
    <link rel="stylesheet" href="./styles/blocks.css">
    <title>Calcolatrice</title>
</head>

<body>
    <div class="calculator_back_background">
        <form class="calculator_container" action="./php/controller/calculatorController.php" method="post">
            <section class="screen_container">
                <div class="calculator_screen">
                    <div class="expression"></div>
                    <div class="result">= 10</div>
                </div>
                <input id="invisible_calculator_screen" name="expression" type="text" class="screen" value="0">
            </section>
            <section class="error_container">
                <div>Errors placeholder</div>
            </section>
            <section class="keyboard_container">
                <div class="keypad function">
                    <button type="button" class="btn" data-type="pow2">X^2</button>
                    <button type="button" class="btn" data-type="pow_n">X^N</button>
                    <button type="button" class="btn" data-type="sqrt">SQRT</button>
                    <button type="button" class="btn" data-type="nroot">N_ROOT</button>
                    <button type="button" class="btn" data-type="sin">SIN</button>
                    <button type="button" class="btn" data-type="cos">COS</button>
                    <button type="button" class="btn" data-type="tan">TAN</button>
                </div>
                <div class="keypad numbers">
                    <button type="button" class="btn" data-type="number" data-character="1">1</button>
                    <button type="button" class="btn" data-type="number" data-character="2">2</button>
                    <button type="button" class="btn" data-type="number" data-character="3">3</button>
                    <button type="button" class="btn" data-type="operator" data-character="+">+</button>
                    <button type="button" class="btn" data-type="number" data-character="4">4</button>
                    <button type="button" class="btn" data-type="number" data-character="5">5</button>
                    <button type="button" class="btn" data-type="number" data-character="6">6</button>
                    <button type="button" class="btn" data-type="operator" data-character="-">-</button>
                    <button type="button" class="btn" data-type="number" data-character="7">7</button>
                    <button type="button" class="btn" data-type="number" data-character="8 ">8</button>
                    <button type="button" class="btn" data-type="number" data-character="9">9</button>
                    <button type="button" class="btn" data-type="operator" data-character="/">/</button>
                    <button type="button" class="btn" data-type="number" data-character="0">0</button>
                    <button type="button" class="btn" data-clear>C</button>
                    <button type="button" class="btn" data-backspace>BC</button>
                    <button type="button" class="btn" data-type="operator" data-character="*">*</button>
                </div>
                <div class="keypad control">
                    <button type="button" class="btn">&lt;-</button>
                    <button type="button" class="btn">-&gt;</button>
                    <button type="button" class="btn equals" data-equals>=</button>
                </div>
            </section>
            <section class="design_bar_container"></section>
        </form>
    </div>

    <script>
        function generateDots(n) {
            const container = document.querySelector('.error_container');
            container.innerHTML = ''; // Pulisce il container

            for (let i = 0; i < n; i++) {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                container.appendChild(dot);
            }
        }

        // Chiama la funzione con il numero di pallini desiderato
        // generateDots(100);
    </script>
    <script src="./javascript/blocks/Block.js" type="module"></script>
    <script src="./javascript/buttonClick.js" type="module"></script>
</body>

</html>