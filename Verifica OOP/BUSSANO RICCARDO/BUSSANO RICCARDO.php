<?php

declare(strict_types=1);

// Non inserisco il tipo del parametro perché non ne posso conosecere il tipo
function echoWithBr($value): void
{
    echo $value . "<br>";
}

function testMethods($originalValue, $newValue): void
{
    if ($originalValue != $newValue) {
        echoWithBr("🟩 Test passato");
    } else {
        echoWithBr("🟥 Test non passato");
    }
}

class Auto
{
    public int $posti = 0;
    public int $peso = 0;

    public function __construct(int $posti, int $peso)
    {
        $this->setPosti($posti);
        $this->setPeso($peso);
    }

    public function setPosti(int $posti): void
    {
        if (!is_numeric($posti)) {
            throw new Exception("inserisci un numero valido");
        }

        if ($posti <= 0) {
            throw new Exception("inserisci un numero di posti maggiore di zero");
        }

        $this->posti = $posti;
    }

    public function getPosti(): int
    {
        return $this->posti;
    }

    // Scrivo il setteer anche per l'attributo peso in modo tale da evitare che un untente possa inserire un numero di peso negativo o uguale a zero durante l'inizializzazione della classe
    public function setPeso(int $peso): void
    {
        if (!is_numeric($peso)) {
            throw new Exception("inserisci un numero valido");
        }

        if ($peso <= 0) {
            throw new Exception("inserisci un peso maggiore di zero");
        }

        $this->peso = $peso;
    }

    // Metodo richiesto
    public function getPeso(): int
    {
        return $this->peso;
    }

    /**
     * Restituisce il risultato della operazione posti + peso
     */
    public function getResult(): float
    {
        return $this->posti + $this->peso;
    }
}

class Auto_der extends Auto
{
    private float $numRuote = 0;

    public function __construct(int $posti, int $peso, float $numRuote)
    {
        parent::__construct($posti, $peso);
        $this->setNumRuote($numRuote);
    }

    // Getter e setter
    public function getNumRuote(): float
    {
        return $this->numRuote;
    }

    public function setNumRuote(float $numRuote): void
    {
        if (!is_numeric($numRuote)) {
            throw new Exception("inserisci un numero valido");
        }

        if ($numRuote <= 0) {
            throw new Exception("inserisci un nunmero maggiore di zero");
        }

        $this->numRuote = $numRuote;
    }

    // Metodi richiesti
    public static function tipoAuto(): string
    {
        return "Auto_der";
    }

    // Sovrascriviamo il metodo getResult
    public function getResult(): float
    {
        return $this->posti + $this->peso / $this->numRuote;
    }
}

$auto1 = new Auto(4, 1000);
// $auto2 = new Auto(0, 750);
$auto_der1 = new Auto_der(9, 1500, 7);
// $auto_der2 = new Auto_der(9, 11234, 7);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica OOP Riccardo Bussano</title>
</head>

<body>
    <h1>Classe Auto (salvata in auto1)</h1>
    <h2>Prova geter e setter</h2>
    <?php

    // Prova Posti
    $originalValue = $auto1->getPosti();
    echoWithBr("Posti: " . $originalValue);
    $auto1->setPosti(5);
    $newValue = $auto1->getPosti();
    echoWithBr("Posti: " . $newValue);

    testMethods($originalValue, $newValue);

    // Prova peso
    $originalValue = $auto1->getPeso();
    echoWithBr("Peso: " . $originalValue);
    $auto1->setPeso(1234);
    $newValue = $auto1->getPeso();
    echoWithBr("Peso: " . $newValue);

    testMethods($originalValue, $newValue)
    ?>
    <h2>Prova metodo GetResult</h2>
    <span>Operazione aspettata:</span>
    <?php
    echoWithBr($auto1->posti . " + " . $auto1->peso);
    echoWithBr($auto1->getResult())
    ?>
    <h1>Classe Auto_der</h1>
    <?php


    // Test Posti
    $originalValue = $auto_der1->getPosti();
    echoWithBr("Posti: " . $originalValue);
    $auto_der1->setPosti(15);
    $newValue = $auto_der1->getPosti();
    echoWithBr("Posti: " . $newValue);

    testMethods($originalValue, $newValue);

    // Test Peso
    $originalValue = $auto_der1->getPeso();
    echoWithBr("Peso: " . $originalValue);
    $auto_der1->setPeso(2500);
    $newValue = $auto_der1->getPeso();
    echoWithBr("Peso: " . $newValue);

    testMethods($originalValue, $newValue);

    // Test Numero ruote
    $originalValue = $auto_der1->getNumRuote();
    echoWithBr("Num Ruote: " . $originalValue);
    $auto_der1->setNumRuote(18);
    $newValue = $auto_der1->getNumRuote();
    echoWithBr("Num Ruote: " . $newValue);

    testMethods($originalValue, $newValue);
    ?>

    <h2>Prova metodo GetResult</h2>
    <?php
    echoWithBr($auto_der1->getResult())
    ?>

    <h2>Prova metodo statico</h2>
    <?php
    echoWithBr(Auto_der::tipoAuto())
    ?>
</body>

</html>