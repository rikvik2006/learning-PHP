<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./styles/app.css">
    <title>Calcolatrice</title>
</head>

<body>
    <div class="calculator_back_background">
        <div class="calculator_container">
            <div class="screen_container">
                <input type="text" class="screen" value="0">
            </div>
            <div class="error_container">
                <div>Ciao test</div>
            </div>
            <div class="keyboard_container">
                <div class="keypad function">
                    <button class="btn">a^2</button>
                    <button class="btn">b</button>
                    <button class="btn">c</button>
                </div>
                <div class="keypad numbers">
                    <button class="btn">1</button>
                    <button class="btn">2</button>
                    <button class="btn">3</button>
                    <button class="btn">+</button>
                    <button class="btn">4</button>
                    <button class="btn">5</button>
                    <button class="btn">6</button>
                    <button class="btn">-</button>
                    <button class="btn">7</button>
                    <button class="btn">8</button>
                    <button class="btn">9</button>
                    <button class="btn">/</button>
                    <button class="btn">0</button>
                    <button class="btn">C</button>
                    <button class="btn">=</button>
                    <button class="btn">*</button>
                </div>
                <div class="keypad control">
                    <button class="btn">=</button>
                    <button class="btn">&lt;-</button>
                    <button class="btn">-&gt;</button>
                    <button class="btn">&Sqrt;x</button>
                </div>
            </div>
            <div class="design_bar_container"></div>
        </div>
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
</body>

</html>