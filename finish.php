<?php

session_start();

$title = 'Вы выиграли!';

if(!isset($_SESSION['finish'])) {

    // Если в сессии нету переменной 'finish',
    // Отправляем пользователя на главную
    header('Location: index.php');
    die();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $title ?></title>
</head>
<body>
    <section class="finish">
        <div class="container">
            <div class="wrapper">
                <div class="block-title">
                    <h1>Вы выиграли!</h1>
                    <p>Хотите начать заново?</p>

                    <a href="index.php?session=drop" class="btn">Начать сначала</a>
                </div>    
            </div>
        </div>
    </section>    
</body>
</html>