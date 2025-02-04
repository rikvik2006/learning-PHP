<?php

declare(strict_types=1);

class User
{
    private string $username = "";
    private string $email = "";

    public function __construct(string $username, string $email)
    {
        $this->set_username($username);
        $this->set_email($email);
    }

    public function get_username(): string
    {
        return $this->username;
    }

    public function set_username(string $username): void
    {
        if (!empty($username)) {
            if (!is_numeric($username)) {
                if (strpos($username, ' ') === false) {
                    $this->username = $username;
                } else {
                    throw new Exception("username should be a string without spaces");
                }
            } else {
                throw new Exception("the username should not be numeric");
            }
        } else {
            throw new Exception("insert a username");
        }
    }

    public function get_email(): string
    {
        return $this->email;
    }

    public function set_email(string $email): void
    {
        if (!empty($email)) {
            if (strpos($email, "@") == false) {
                throw new Exception("inserisci una email valida");
            } else {
                $this->email = $email;
            }
        }
    }
}

class StockHolderUser extends User
{
    const STOCK_MARKET = "USD";

    private float $usd_asset = 0;

    public function __construct(string $username, string $email, float $USDAssets)
    {
        parent::__construct($username, $email);
        $this->set_usd_assets($USDAssets);
    }

    public function set_usd_assets(float $usdAssets): void
    {
        if (!is_numeric($usdAssets)) {
            throw new Exception("Inserisci un valore numerico");
        }

        if ($usdAssets < 0) {
            throw new Exception("inserisci un valore maggiore di zero");
        }

        $this->usd_asset = $usdAssets;
    }

    public static function get_market(): string
    {
        return "Market";
    }
}

$user1 = new User("rikrik2006", "r@r.com");
$user2 = new User("vecio", "vecio@r.com");

$stock_user1 = new StockHolderUser("rich_man", "rich_man@gmail.com", 1000.5)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ripasso Verifica OOP Es 1</title>
</head>

<body>
    <?php
    echo "User 1" . "<br>";
    echo $user1->get_username() . "<br>";
    echo $user1->get_email() . "<br>";
    echo "User 2" . "<br>";
    echo $user2->get_username() . "<br>";
    echo $user2->get_email() . "<br>";
    echo "Stock User 1" . "<br>";
    echo $stock_user1->get_username() . "<br>";
    echo $stock_user1->get_email() . "<br>";
    echo StockHolderUser::get_market() . "<br>";
    ?>
</body>

</html>