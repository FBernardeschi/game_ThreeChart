<?php

session_start();

require_once('Helpers.php');

use Helpers\Checker;

// Заголовок
$title = 'Уровень №3';

// Определяем переменную ошибок
$result = [];

// Самоей первое слово из сессии
$word = $_SESSION['word_level_3'];

// Если пришёл пост-запрос
if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Копируем данные из глобальной переменной
    $data = $_POST;
    $one = $data['char-1'];
    $two = $data['char-2'];
    $three = $data['char-3'];
    $four = $data['char-4'];

    // Если 5 и 6 поля нет, определяем переменные
    // как пустые строки
    $five = isset($data['char-5']) ? $data['char-5'] : '';
    $six = isset($data['char-6']) ? $data['char-6'] : '';

    // Конкатенируем все символы в одно слово
    $word_input = $one . $two . $three . $four . $five . $six;

    // Инстансируем класс хелпер и вызываем
    // метод проверки
    $check = new Checker;
    $result = $check->checkerLevel_3($word, $word_input);

    if(empty($result)) {

        // Если массив с ошибками пустой, перекидываем
        // пользователя на финиш
        $_SESSION['finish'] = 1;
        header('Location: finish.php');
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title><?php echo $title ?></title>
</head>
<body>
    <section class="level-3">
        <div class="container">
            <div class="wrapper">
                <div class="title">
                    <h1>Уровень №3</h1>
                    <div class="block-rulz">
                        <h2>Задача:</h2>
                        <ul>
                            <li>
                                Найдите слово из 4 или 6 символов, которое бы 
                                отличалось от начального всего на один символ!
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
                    <?php foreach(mb_str_split($word) as $ch) {
                        echo '<input value="' . $ch . '" disabled>';
                    } ?>
                </div>
                <form action="" method='POST'>
                    <div class="block-inputs del">
                        <?php for($i = 1; $i < 7; $i++) {
                            echo '<input maxlength="1" name="char-' . $i . '" class="inp" autocomplete="off" maxlength="1">';
                            echo '<i>Поддерживается только кириллица!</i>';
                        } ?>
                        <i class='bx bx-trash-alt'></i>
                        <i class='bx bx-plus-circle hidden'></i>
                    </div>
                    <div class="nav">
                        <input type="submit" class="btn" value="Отправить">
                        <a href="index.php" class="btn">Рестарт</a>
                    </div>
                </form>
            </div>
        </div>

    </section>    

<script>
    let trash = document.querySelector('.bx-trash-alt');
    let plus = document.querySelector('.bx-plus-circle');
    let inputsArray = document.querySelectorAll('.del input');

    trash.addEventListener('click', () => {
        let arrLength = inputsArray.length;
        inputsArray[arrLength-1].value = '';
        inputsArray[arrLength-2].value = '';
        inputsArray[arrLength-1].disabled = true;
        inputsArray[arrLength-2].disabled = true;
        inputsArray[arrLength-1].classList.remove('inp');
        inputsArray[arrLength-2].classList.remove('inp');
        trash.classList.add('hidden');
        plus.classList.remove('hidden')
    })

    plus.addEventListener('click', () => {
        let arrLength = inputsArray.length;
        inputsArray[arrLength-1].disabled = false;
        inputsArray[arrLength-2].disabled = false;
        inputsArray[arrLength-1].classList.add('inp');
        inputsArray[arrLength-2].classList.add('inp');
        plus.classList.add('hidden');
        trash.classList.remove('hidden')
    })

    let inputs = document.querySelectorAll('.inp');
    let btn = document.querySelector('.btn');

    btn.addEventListener('click', (event) => {
        inputs = document.querySelectorAll('.inp');
        inputs.forEach((el) => {
            if(el.value.length == 0) {
                console.log(el);
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
        })
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
        });

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