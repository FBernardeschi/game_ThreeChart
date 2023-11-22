<?php 

session_start();

require_once('Curl.php');

use Helpers\Curl;

// Тайтл
$title = 'ИТ-Полигон: ТриБуквы';

// Если пользователь пришёл с финиша, убиваем
// переменную 'finish' в сессии, она нужна для то
// что бы пользователь не мог перейти на финишную 
// страницу с первой
if(isset($_GET['session'])) {
    unset($_SESSION['finish']);
}

// Функция генерация первёх трёх букв
function genChar() {
    $vowels = ['а', 'о', 'и', 'е', 'у', 'ы', 'ю'];
    $consonants = ['б', 'в', 'п', 'р', 'с', 'т', 'к', 'ш'];

    // Количество элементов в массиве со сдигом на 1 (нулевой индекс)
    $num = count($vowels) - 1;
    $num_2 = count($consonants) - 1;
    
    // Возвращаем сконкатенированную строку
    return $consonants[rand(0, $num_2)] . $vowels[rand(0, $num)] . $consonants[rand(0, $num_2)];
}

// Результат функции записываем в переменную
$gen_str = genChar();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Избавляемся от глобальной переменной $_POST
    // копируя всё в свою переменную
    $data = $_POST;

    // Получаем сгенерированное, поскольку то уже перезаписалось
    $gen = $data['gen'];

    // Объеденяем символы в слово
    $word_1 = $data['one'] . $data['two'] . $gen;
    $word_2 = $gen . $data['three'] . $data['four'];

    // Инстансируем класс проверок
    $check = new Curl;

    // Делаем проверки каждого слова и объеденяе в общий массив
    $result = array_merge($check->getWord($word_1), $check->getWord($word_2));

    if(empty($result)) {
        // Если результат проверок пустой
        // добавляем слова в сессию
        $_SESSION['word_1'] = $word_1;
        $_SESSION['word_2'] = $word_2;

        // Добавляем в сессию лово для третьего уровня
        $_SESSION['word_level_3'] = $data['one'] . $data['two'] . $gen . $data['three'] . $data['four'];

        // Перекидываем на второй уровень
        header('Location: level-2.php');

        // Убиваем дальнейшее выполнение скрипта
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
    <section class="main">
        <div class="container">
            <div class="wrapper">
                <div class="title">
                    <h1>Игра ТриБуквы</h1>
                    <div class="block-rulz">
                        <h2>Правила:</h2>
                        <ul>
                            <li>Дано три буквы</li>
                            <li>Необходимо подобрать два слова, которые начинаются и заканчиваются на эти три буквы.</li>
                        </ul>
                    </div>    
                </div>
                <?php if(!empty($result)) {
                    foreach($result as $res) {
                        echo '<p class="error">' . $res . '</p>';
                    }
                } ?>
                <form action="" method="POST">
                    <div class="block-inputs">
                        <input name='one' class="inp" type="text" maxlength="1" autocomplete="off">
                        <i>Поддерживается только кириллица!</i>
                        <input name='two' class="inp" type="text" maxlength="1" autocomplete="off">
                        <i>Поддерживается только кириллица!</i>
                        <input type="text" value="<?php echo mb_substr($gen_str, 0, 1) ?>" disabled>
                        <input type="text" value="<?php echo mb_substr($gen_str, 1, 1) ?>" disabled>
                        <input type="text" value="<?php echo mb_substr($gen_str, 2, 1) ?>" disabled>
                        <input name="three" class="inp" type="text" maxlength="1" autocomplete="off">
                        <i>Поддерживается только кириллица!</i>
                        <input name="four" class="inp" type="text" maxlength="1" autocomplete="off">
                        <i>Поддерживается только кириллица!</i>
                        <input type="hidden" name="gen" value="<?php echo $gen_str; ?>">
                    </div>
                    <input class="btn" type="submit" value="Проверить">
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
            })
        })

        inputs.forEach((el, key) => {
            el.addEventListener('input', () => {
                if(el.value.match(/[a-z]/i)) {el.classList.add('active')};
                if(el.value.match(/[а-яё]/i)) {el.classList.remove('active')};
                el.classList.remove('red');
                el.value = el.value.toLowerCase().replace(/[^а-яё]/, "");
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