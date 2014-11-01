<?php
/*
 * Подключаем класс родителя
 */
require_once '/Translation.php';

class UkrainianTranslation extends Translation {
    /*
     * Словарь ключевых слов для украинского языка, есть дополнительные индексы 
     * для 1 и 2, когда они используются в контектсе тысячных
     * Один мільйон але одна тисяча
     * Два мільйони але дві тисячі
     */
    private $translationWords = array(
        "lang"=>"ua",
        "0"=>"нуль",
        "1"=>"один",
        "1т"=>"одна",
        "2"=>"два",
        "2т"=>"дві",
        "3"=>"три",
        "4"=>"чотири",
        "5"=>"п'ять",
        "6"=>"шість",
        "7"=>"сім",
        "8"=>"вісім",
        "9"=>"дев'ять",
        "10"=>"десять",
        "11"=>"одинадцять",
        "12"=>"дванадцять",
        "13"=>"тринадцять",
        "14"=>"чотирнадцять",
        "15"=>"п'ятнадцять",
        "16"=>"шістнадцять",
        "17"=>"сімнадцять",
        "18"=>"вісімнадцять",
        "19"=>"дев'ятнадцять",
        "20"=>"двадцять",
        "30"=>"тридцять",
        "40"=>"сорок",
        "50"=>"п'ятдесят",
        "60"=>"шістдесят",
        "70"=>"сімдесят",
        "80"=>"вісімдесят",
        "90"=>"дев'яносто"
    );
    public function __construct($numberValue) {
        parent::__construct($numberValue);
    }
    public function makeHundredsNumber($dictionary, $position = 0) {
        /*
         * Метод работает аналогично методу родителя
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
                             * Двісті
                             */
                            $this->translateValue = " двісті ".$this->translateValue;
                            break;
                        }
                        case "3": 
                        case "4": {
                            /*
                             * триста, чотириста
                             */
                            $this->translateValue = $dictionary[$index]."ста ".$this->translateValue;
                            break;
                        }
                        case "7":
                        case "8": {
                            /*
                             * сімсот, вісімсот
                             */                                                      
                            $this->translateValue = $dictionary[$index]."сот ".$this->translateValue;                                                    
                            break;
                        }
                        case "5":
                        case "6":
                        case "9": {
                            /*
                             * п'ять але п'ятсот
                             * в 5 6 9 нужно удалить последний элемент, мягкий знак
                             */
                            $this->translateValue = substr($dictionary[$index],0,strlen($dictionary[$index])-2)."сот ".$this->translateValue;
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
                
                
                $tempValue = $stringNumber[$length-4-$position];
                
                if(isset($stringNumber[$length-5-$position])) {
                    $tempValue = $stringNumber[$length-5-$position].$stringNumber[$length-4-$position];
                    
                }
                if(isset($stringNumber[$length-6-$position])) {
                    $tempValue = $stringNumber[$length-6-$position].$tempValue;
                    
                    
                    
                }
                if ((int)$tempValue == 1) {               
                         $this->translateValue = "тисяча ".$this->translateValue; 
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
                                $this->translateValue = " тисяча ".$this->translateValue;
                                break;
                            }
                            case "2":
                            case "3":
                            case "4": {
                                $this->translateValue = " тисячі ".$this->translateValue;
                                break;
                            }
                            case "5":
                            case "6":
                            case "7":
                            case "8":
                            case "9":
                            case "0": {
                                $this->translateValue = " тисяч ".$this->translateValue;
                                break;
                            }
                        }
                    
                    /*
                     * вызываем методы для десятых, сотых и передаём 3 в параметре, 
                     * для использования в контекте тысячи
                     */   
                    parent::makeTenthsNumber($dictionary, 3+$position);
                    $this->makeHundredsNumber($dictionary, 3+$position);
                }
                if ((int)$tempValue >= 11 and (int)$tempValue <= 20) {
                    
                        
                        $this->translateValue = " тисяч ".$this->translateValue;
                    
                        
                    parent::makeTenthsNumber($dictionary, 3+$position);
                    $this->makeHundredsNumber($dictionary, 3+$position);
                }
            } 
    }
    
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
                    $this->translateValue = "один мільйон ".$this->translateValue;
                }
                if((int)$tempValue >=2 ) {
                    switch($tempValue[strlen($tempValue)-1]) {
                        case "1": {
                            $this->translateValue = " мільйон ".$this->translateValue;
                            break;
                        }
                        case "2":
                        case "3":
                        case "4": {
                            $this->translateValue = " мільйони ".$this->translateValue;
                            break;
                        }
                        case "5":
                        case "6":
                        case "7":
                        case "8":
                        case "9":
                        case "0": {
                            $this->translateValue = " мільйонів ".$this->translateValue;
                        }
                    }
                                          
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
        print "<img src=images/ua.gif width=20px height=15px> ".parent::getTranslateValue();
    }
}

