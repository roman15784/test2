<?php
/*
 * Подключаем класс родителя
 */
require_once '/Translation.php';

class RussianTranslation extends Translation {
    /*
     * Словарь ключевых слов русского языка, есть дополнительыне индексы для 1 и 2, 
     * когда они используются в контексте тысячи
     * Потом как один миллиона, но одна тысяча
     * Два миллиона, но две тысячи
     */
    private $translationWords = array(
        "lang"=>"ru",
        "0"=>"ноль",
        "1"=>"один",
        "1т"=>"одна",
        "2"=>"два",
        "2т"=>"две",
        "3"=>"три",
        "4"=>"четыре",
        "5"=>"пять",
        "6"=>"шесть",
        "7"=>"семь",
        "8"=>"восемь",
        "9"=>"девять",
        "10"=>"десять",
        "11"=>"одиннадцать",
        "12"=>"двенадцать",
        "13"=>"тринадцать",
        "14"=>"четырнадцать",
        "15"=>"пятнадцать",
        "16"=>"шестнадцать",
        "17"=>"семнадцать",
        "18"=>"восемьнадцать",
        "19"=>"девятнадцать",
        "20"=>"двадцать",
        "30"=>"тридцать",
        "40"=>"сорок",
        "50"=>"пятьдесят",
        "60"=>"шестдесят",
        "70"=>"семьдесят",
        "80"=>"восемьдесят",
        "90"=>"девяносто"
    );
    public function __construct($numberValue) {
        parent::__construct($numberValue);
    }
    /*
     * Я переопределил метод для сотых потому что склонение в русском языке устроено сложнее чем в английском, 
     * поэтому проще выделить это в отдельном методе для данного языка чем перезагружать множеством условных
     * конструкций в классе  родителе
     */
    public function makeHundredsNumber($dictionary, $position = 0) {
        /*
         * Метод работает аналогично методу родителя, переводим число в строку 
         * и работаем с ней кк с массивом символов этой строки
         */
        $stringNumber = (string)$this->numberValue;
            $length = strlen($stringNumber);
            if(isset($stringNumber[$length-3-$position])) {
                
                $index = $stringNumber[$length-3-$position];
                
                if($index == "0") 
                {
                    $this->translateValue .= "";
                }
                
                if($index == "1") {                   
                    $this->translateValue = "сто ".$this->translateValue;              
                }
                
                if((int)$index >= 2) {
                    switch($index) {
                        case "2": {
                            /*
                             * Двести
                             */
                            $this->translateValue = " двести ".$this->translateValue;
                            break;
                        }
                        case "3": 
                        case "4": {
                            /*
                             * Триста, четыреста
                             */
                            $this->translateValue = $dictionary[$index]."ста ".$this->translateValue;
                            break;
                        }
                        case "5":
                        case "6":
                        case "7":
                        case "8":
                        case "9": {
                            /*
                             * Пятьсот, шестсот..... девятьсот
                             */
                            $this->translateValue = $dictionary[$index]."сот ".$this->translateValue;
                            break;
                        }
                    }
                }
            }
    }
    public function makeThousandsNumber($dictionary, $position = 0) {
            $tempValue = "";
            
            $stringNumber = (string)$this->numberValue;
            $length = strlen($stringNumber);
            
            if(isset($stringNumber[$length-4-$position])) {
                
                /*
                 * Как и методе родителя узнаём количество тысяч записывая элементы 
                 * в переменныю $tempValue и переведя её в число
                 */
                $tempValue = $stringNumber[$length-4-$position];
                
                if(isset($stringNumber[$length-5-$position])) {
                    $tempValue = $stringNumber[$length-5-$position].$stringNumber[$length-4-$position];
                    
                }
                if(isset($stringNumber[$length-6-$position])) {
                    $tempValue = $stringNumber[$length-6-$position].$tempValue;
                    
                    
                    
                }
                if ((int)$tempValue == 1) {               
                         $this->translateValue = "тысяча ".$this->translateValue; 
                }
                if ((int)$tempValue == 0) {
                    $this->translateValue .= "";
                }
                /*
                 * Здесь есть небольшое условие, числа деляться от 1 до 10, от 11 до 20, и от 21 и так далее
                 * Для каждой групы по разному звучит фраза тысяча, тысячи, тысяч, в зависимости от того 
                 * какое число стоит перед тысячей определеям как будет звучать слово тысяча
                 */
                if (((int)$tempValue >= 2 and (int)$tempValue <= 10) or (int)$tempValue > 20) {
                        
                        switch($tempValue[strlen($tempValue)-1]) {
                            case "1": {
                                $this->translateValue = " тысяча ".$this->translateValue;
                                break;
                            }
                            case "2":
                            case "3":
                            case "4": {
                                $this->translateValue = " тысячи ".$this->translateValue;
                                break;
                            }
                            case "5":
                            case "6":
                            case "7":
                            case "8":
                            case "9":
                            case "0": {
                                $this->translateValue = " тысяч ".$this->translateValue;
                                break;
                            }
                        }
                    
                    /*
                     * Определяем сотые и десятые элементы для тысячи, передавая 3 в аргумент
                     */    
                    parent::makeTenthsNumber($dictionary, 3+$position);
                    $this->makeHundredsNumber($dictionary, 3+$position);
                }
                if ((int)$tempValue >= 11 and (int)$tempValue <= 20) {
                    
                        /*
                         * Для чисел от 11 до 20 тысяча будет звучать - тысяч
                         */
                        $this->translateValue = " тысяч ".$this->translateValue;
                    
                        
                    parent::makeTenthsNumber($dictionary, 3+$position);
                    $this->makeHundredsNumber($dictionary, 3+$position);
                }
            } 
    }
    
    /*
     * Метод для формирования миллиона
     */
    public function makeMillion($dictionary) {
        
            $tempValue = "";
            $stringNumber = (string)$this->numberValue;
            $length = strlen($stringNumber);
            
            if(isset($stringNumber[$length-7])) {
                
                for($i = 7; $i<=$length; $i++) {
                    $tempValue = $stringNumber[$length-$i].$tempValue;
                    
                }
                if((int)$tempValue == 0) {
                    $this->translateValue .= "";
                }
                if((int)$tempValue == 1) {
                    $this->translateValue = "один миллион ".$this->translateValue;
                }
                if((int)$tempValue >=2 ) {
                    switch($tempValue[strlen($tempValue)-1]) {
                        case "1": {
                            /*
                             * Один миллион
                             */
                            $this->translateValue = " миллион ".$this->translateValue;
                            break;
                        }
                        case "2":
                        case "3":
                        case "4": {
                            /*
                             * Два миллиона, три миллиона
                             */
                            $this->translateValue = " миллиона ".$this->translateValue;
                            break;
                        }
                        case "5":
                        case "6":
                        case "7":
                        case "8":
                        case "9":
                        case "0": {
                            /*
                             * Пять миллионов, шесть миллионов
                             */
                            $this->translateValue = " миллионов ".$this->translateValue;
                        }
                    }
                    
                    /*
                     * вызываем методы для формирования десятых, сотых и тысячных, передвая 6 в аргументе
                     */
                    parent::makeTenthsNumber($dictionary, 6);
                    $this->makeHundredsNumber($dictionary, 6);
                    $this->makeThousandsNumber($dictionary, 6);
                }
            }
        }
    public function parseNumber() {
        /*
         * Переводим число в текст используя методы для формирования десятых, сотых, 
         * тысячных и миллионных, начинаем с первого элемента передавая в аргументе 0
         */
        parent::makeTenthsNumber($this->translationWords, 0);
        $this->makeHundredsNumber($this->translationWords, 0);
        $this->makeThousandsNumber($this->translationWords, 0);
        $this->makeMillion($this->translationWords);
        /*
         * Показываем полученый текст, на экране, для вида я добавил небольшую 
         * картинку, хотя более логично было бы это сделать в файле index.php 
         * который занимается отображением
         */
        print "<img src=images/ru.png width=20px height=15px> ".parent::getTranslateValue();
    }
}

