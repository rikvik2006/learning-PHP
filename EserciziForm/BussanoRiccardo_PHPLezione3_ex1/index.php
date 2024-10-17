<?php

declare(strict_types=1);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <!-- Stile in caso mancasse il file CSS -->
    <style>
        .font-size {
            font-size: 2rem;
        }

        .error {
            color: red;
        }

        .image {
            max-height: 50rem;
        }
    </style>

    <title>Calcolo Form</title>
</head>

<body>
    <?php
    $output = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $number1 = test_input($_POST["number-1"]);
        $number2 = test_input($_POST["number-2"]);
        $operation = test_input($_POST["operation"]);

        // var_dump($number1);
        // var_dump($number2);
        // var_dump($operation);

        if (!is_numeric($number1) or !is_numeric($number2)) {
            $output = "Insert numeric arguments";
        } else {
            $number1 = (float)$number1;
            $number2 = (float)$number2;
            switch ($operation) {
                case "+":
                    $result = $number1 + $number2;
                    break;
                case "-":
                    $result = $number1 - $number2;
                    break;
                case "*":
                    $result = $number1 * $number2;
                    break;
                case "/":
                    if ($number2 != 0) {
                        $result = $number1 / $number2;
                    }
                    break;
                case "^":
                    $result = $number1 ** $number2;
                    break;
            }

            if (empty($result)) {
                if ($number2 != 0) {
                    $output = "Insert a valid operation";
                } else {
                    $output = "Division by Zero";
                }
            } else {
                $output = "The result is equal to $result";
            }
        }
    }
    ?>

    <div class="container column">
        <!-- <?php echo $_SERVER["PHP_SELF"] ?> -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="inputContainer">
                <label for="number-1">Number 1</label>
                <input class="text" type="number" name="number-1" id="number-1">
            </div>

            <div class="inputContainer">
                <label for="number-2">Number 2</label>
                <input class="text" type="number" name="number-2" id="number-2">
            </div>
            <div class="inputContainer">
                <label for="operation">Select the operation</label>
                <select name="operation" id="operation">
                    <option value="+">+</option>
                    <option value="-">-</option>
                    <option value="*">*</option>
                    <option value="/">/</option>
                    <option value="^">^</option>
                </select>
            </div>

            <button type="submit">Calcola</button>
        </form>
        <div class="inputContainer">
            <h3>Ouput:</h3>
            <div><?php echo $output ?></div>
        </div>
    </div>
</body>

</html>