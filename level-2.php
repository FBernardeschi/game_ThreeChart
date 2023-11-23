<?php

session_start();

use Helpers\Checker;
use Helpers\Curl;

require_once('Helpers.php');
require_once('Curl.php');

// Заголовок
$title = 'Уровень №2';

// Пустой массив для ошибок
$result = [];

// Принимаем то, что пришло из сессии
$word_1 = $_SESSION['word_1'];
$word_2 = $_SESSION['word_2'];

// Проверка, что форма отправила пост запрос
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST;

    // Конкатенируем все символы в слово
    $word = $data['chair-1'] . $data['chair-2'] . $data['chair-3'] . $data['chair-4'] . $data['chair-5'];

    // Создаём инстанс класса с проверками на
    // Количество одинаковых символов
    $check = new Checker;

    // Инстансируем класс Curl с проверкой на существование
    // слова через Яндекс словарь
    $checkWord = new Curl;

    // Сливаем результат проверок в результирующий массив
    $result = array_merge($check->checkerLevel_2($word_1, $word_2, $word), $checkWord->getWord($word));

    // Смотри описание ниже
    if(count($result) === 0) {

        // Если пришёл пустой массив без ошибок, 
        // перекидываем пользователя на уровень №3,
        // если не пустой, выводим ошибки оставаясь
        // на этом уровне
        header('Location: level-3.php');
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $title; ?></title>
</head>
<body>
    <section class="level-2">
        <div class="container">
            <div class="wrapper">
                <div class="title">
                    <h1>Уровень №2</h1>
                    <div class="block-rulz">
                        <h2>Задача:</h2>
                        <ul>
                            <li>
                                Найдите слово, которое бы 
                                отличалось от одного из двух 
                                предыдущих ровно на один символ.
                            </li>
                        </ul>
                    </div>
                </div>
                <?php if(!empty($result)) {
                            foreach($result as $res) {
                                echo '<p class="error">' . $res . '</p>';
                            }
                } ?>
                <div class="block-inputs">
                    <?php foreach(mb_str_split($word_1) as $ch) {
                        echo '<input value="' . $ch . '" disabled>';
                    }; ?>
                </div>

                <div class="block-inputs">
                    <?php foreach(mb_str_split($word_2) as $ch) {
                        echo '<input value="' . $ch . '" disabled>';
                    }; ?>
                </div>

                <form action="" method='POST'>
                    <div class="block-inputs">
                        <?php for($i = 1; $i < 6; $i++) {
                            echo '<input name="chair-' . $i . '" class="inp" autocomplete="off" maxlength="1">';
                            echo '<i>Поддерживается только кириллица!</i>';
                        } ?>
                    </div>
                    <div class="nav">
                        <input type="submit" class='btn' value="Отправить">
                        <a href="index.php" class="btn">Рестарт</a>
                    </div>
                </form>
                
            </div>
        </div>
    </section>    
    
    <script>
        let inputs = document.querySelectorAll('.inp');
        let btn = document.querySelector('.btn');

        btn.addEventListener('click', (event) => {
            inputs.forEach((el) => {
                if(el.value.length == 0) {
                    el.classList.add('red');
                    event.preventDefault();
                    return
                }
            })

        })

        inputs.forEach((el) => {
            el.addEventListener('focus', () => {
                if(el.value.length > 0) {
                    el.selectionStart = 0;
                    el.selectionEnd = 1;
                }
            });
        })

        inputs.forEach((el, key) => {
            el.addEventListener('input', () => {
                if(el.value.match(/[a-z]/i)) {el.classList.add('active')};
                if(el.value.match(/[а-яё]/i)) {el.classList.remove('active')};
                el.value = el.value.toLowerCase().replace(/[^а-яё]/, "");
                el.classList.remove('red');
                if(key == inputs.length - 1) {
                    return
                }

                if(el.value.length > 0) {
                    inputs[key + 1].focus();
                }
            })

            el.addEventListener('keydown', (event) => {
                if(event.key === 'Backspace' && el.value.length == 0 && key > 0) {
                    inputs[key-1].value = '';
                    inputs[key-1].focus();
                }
            });
        })
    </script>
</body>
</html>