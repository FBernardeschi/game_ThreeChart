<?php

namespace Helpers;

class Checker
{
        public function checkerLevel_2($word_1, $word_2, $pattern)
    {   
        $result = [];

        $count_1 = 0;
        $count_2 = 0;
        foreach(mb_str_split($pattern) as $ch) {
            $count_1 += (mb_strpos($word_1, $ch) === false) ? 1 : 0;
            $count_2 += (mb_strpos($word_2, $ch) === false) ? 1 : 0;
        }

        if($count_1 === 1) { return $result; }

        if($count_1 !== 1) {
            $result[] = 'Слово <span>[' . $word_1 . ']</span> должно отличаться от вашего ровно на 1 символ!<br>Отличается на ' . $count_1;
        }

        if($count_2 !== 1) {
            $result[] = 'Слово <span>[' . $word_2 . ']</span> должно отличаться от вашего ровно на 1 символ!<br>Отличается на ' . $count_2;
        }

        return $result;
    }

    public function checkerLevel_3($word, $word_input)
    {
        $result = [];

        $count = 0;
        foreach(mb_str_split($word_input) as $ch) {
            $count += (mb_strpos($word, $ch) === false) ? 1 : 0;
        }

        if($count !== 1) {
            $result[] = 'Слово ' . $word . ' должно отличаться от вашего ровно на 1 символ!<br>Отличается на ' . $count;
        }

        return $result;
    }
}