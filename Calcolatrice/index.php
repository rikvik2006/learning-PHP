<?php

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . "./php/class/Memory.php";
session_start();
$memory = Memory::create();

function getFrontEndExpression()
{
    if (!isset($_SESSION['frontEndExpression'])) {
        return '0';
    }

    $frontEndExpression = $_SESSION['frontEndExpression'];

    $_SESSION['frontEndExpression'] = "0";

    return $frontEndExpression;
}

function getError()
{
    if (!isset($_SESSION['error'])) {
        return 'Schermo Errori';
    }

    $error = $_SESSION['error'];

    $_SESSION['error'] = 'Schermo Errori';

    return $error;
}


if (!isset($_SESSION['result'])) {
    $_SESSION['result'] = '';
}


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
                    <div class="expression">0</div>
                    <?php if ($_SESSION['result'] !== null): ?>
                        <div class="result">
                            = <?= htmlspecialchars((string)$_SESSION['result']); ?>
                        </div>
                        <?php
                        $_SESSION['result'] = '';
                        ?>
                    <?php endif; ?>
                </div>
                <input id="invisible_calculator_screen" name="expression" type="text" class="screen" value="<?= getFrontEndExpression() ?>">
                <input id="invisible_store_screen" name="sto" type="text" class="screen" value="false">
            </section>
            <section class="error_container">
                <div class="error"><?= getError() ?></div>
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
                    <button type="button" class="btn" data-type="fact">!</button>
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
                    <button type="button" class="btn" data-mem>MEM</button>
                    <button type="button" class="btn" data-sto>STO</button>
                    <button type="button" class="btn" data-memplus>M+</button>
                    <button type="button" class="btn" data-type="decimal" data-character=".">.</button>
                    <button type="button" class="btn equals" data-equals>=</button>
                </div>
            </section>
            <section class="memory_container">
                <?php if ($memory->getMemory() != 0): ?>
                    <div class="result">M</div>
                    <div><?= $memory->getMemory() ?></div>
                <?php endif; ?>
            </section>
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