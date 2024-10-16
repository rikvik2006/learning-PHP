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

    <title>Array 20 Elementi Casuale</title>
</head>

<body>
    <?php

    function generate_array_of_20_numbers(): array
    {
        $array_casuale = array();
        for ($i = 0; $i < 20; $i++) {
            $array_casuale[$i] = random_int(0, 100);
        }

        return $array_casuale;
    }

    function numero_di_numeri_pari_dispari(array $array): array
    {
        $pari = 0;
        $dispari = 0;
        foreach ($array as $number) {
            $resto = $number % 2;
            if ($resto == 0) {
                $pari++;
            } else {
                $dispari++;
            }
        }

        return array($pari, $dispari);
    }

    function render_pari_dispari_numero(): string
    {
        $array = generate_array_of_20_numbers();
        $pari_dispari = numero_di_numeri_pari_dispari($array);
        $pari = $pari_dispari[0];
        $dispari = $pari_dispari[1];
        return "Ci sono $pari numeri pari e $dispari numeri dispari";
    }

    ?>
    <div class="container column font-size">
        <p><?php echo render_pari_dispari_numero(); ?></p>
    </div>
</body>

</html>