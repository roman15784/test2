<?php
/*
 * Подключаем класс родителя
 */
require_once "/Translation.php";

class EnglishTranslation extends Translation {
    /*
     * Словарь основных слов на анлийском языке
     */
    private $translationWords = array(
        "lang"=>"eng",
        "0"=>"zero",
        "1"=>"one",
        "2"=>"two",
        "3"=>"three",
        "4"=>"four",
        "5"=>"five",
        "6"=>"six",
        "7"=>"seven",
        "8"=>"eight",
        "9"=>"nine",
        "10"=>"ten",
        "11"=>"eleven",
        "12"=>"twelve",
        "13"=>"thirteen",
        "14"=>"fourteen",
        "15"=>"fifteen",
        "16"=>"sixteen",
        "17"=>"seventeen",
        "18"=>"eighteen",
        "19"=>"nineteen",
        "20"=>"twenty",
        "30"=>"thirty",
        "40"=>"fourty",
        "50"=>"fifty",
        "60"=>"sixty",
        "70"=>"seventy",
        "80"=>"eighty",
        "90"=>"ninety"
    );
    public function __construct($numberValue) {
        parent::__construct($numberValue);
    }
    public function parseNumber() {
        /*
         * Переводим строку из числа в текст вызывая необходимые методы, 
         * начинаем с первого элемента передавая 0 в аргументе
         */
        parent::makeTenthsNumber($this->translationWords, 0);
        parent::makeHundredsNumber($this->translationWords, 0);
        parent::makeThousandsNumber($this->translationWords, 0);
        parent::makeMillion($this->translationWords);
        /*
         * Показываем полученый текст, на экране, для вида я добавил небольшую 
         * картинку, хотя более логично было бы это сделать в файле index.php 
         * который занимается отображением
         */
        print "<img src=images/eng.png width=20px height=15px> ".parent::getTranslateValue();
    }
}