<?php
/*
 * Класс переводчика, от него наследуются английский, русский и украинский первод
 */
abstract class Translation {
	protected $numberValue; // число полученное для перевода в текст
        protected $translateValue; // число переведённое в текст
	public function __construct($numberValue) {
		$this->numberValue = $numberValue;
	}
        abstract public function parseNumber(); // метод начинает перевод числа
        /*
         * Переведём число в строку и будем работать с ним как массивом символов этой строки
         * Для начала выделим десятю часть числа
         */
        public function makeTenthsNumber($dictionary, $position = 0) {
            /*
             * Десятую часть числа мы также будем выделять в тысячах, в миллионах и в тысячах миллионов
             * Для этого вводится переменная $position, указывающая откуда считать десятые элементы, с начала, с тысячи 
             * или с миллиона
             * 
             * Классы наследники реализуют словари ключевых слов для каждого языка, словарь 
             * передаётся в переменной $dictionary, это позволяет использовть метод 
             * для разных наследников класса
             */
            $stringNumber = (string)$this->numberValue;
            $length = strlen($stringNumber);
            
            
            /*
             * Проверяем состоит ли число из одного или двух элементов
             */
            if(isset($stringNumber[$length-2-$position])) {
                
                
                if ($stringNumber[$length-2-$position].$stringNumber[$length-1-$position] == "00") { 
                    /*
                     * Число может сосотоять из двух нулей, в таком случае идём дальше
                     */
                    $this->translateValue .= "";
                   
                } else
                if ($stringNumber[$length-2-$position] == "0" and $stringNumber[$length-1-$position] != "0") {
                    /*
                     * Число может состоять из нуля и какого нибудь числа
                     * Например так 1223105, здесь проверяемое число будет 05,
                     * слова из словаря выбираются по индексу равному проверяемому числу, однако в нём нет например 05, 
                     * поэтому для индекса используем только второе число
                     */
                    if (($position == 3 or $position == 9) and $dictionary["lang"] != "eng" and 
                                ($stringNumber[$length-1-$position] == "1" 
                                or $stringNumber[$length-1-$position] == "2")) {
                        /*
                         * Следует заметить что в русском и украинском языке есть склонения
                         * Например два миллиона, но две тысячи, для этого в словаре для 1 и 2, 
                         * добавлены дополнительные индексы 1т и 2т, когда перменная $position равна 3 или 9,
                         * что указыает что мы считаем тысячи для 1 и 2, поэтому для них используются индексы для тысяч
                         * "1т"=>"одна"
                         * "2т"=>"две"
                         */
                        $index = $stringNumber[$length-1-$position]."т";
                    } else {
                        /*
                         * Иначе просто используем индекс
                         */
                        $index = $stringNumber[$length-1-$position];
                    }
                    $this->translateValue = $dictionary[$index] . $this->translateValue;       
                } else
                if ($stringNumber[$length-2-$position] != "0")
                {
                    /*
                     * Может быть вариант когда первое число не равно нулю, а остальные могут быть от 0 до 9
                     * Далее мы определяемся больше ли число чем 20, числа до двадцати звучат одинм словом,
                     * числа после 20 звучат двумя словами
                     */
                    if ((int)$stringNumber[$length-2-$position].$stringNumber[$length-1-$position] < 21) {
                        /*
                         * Если до 20 используем индекс со словаря
                         */
                        $index = $stringNumber[$length-2-$position].$stringNumber[$length-1-$position];
                        $this->translateValue = $dictionary[$index] . $this->translateValue;
                    }
                    else
                    {
                        /*
                         * Если после 20 индекс составляется из двух частей,
                         * первая часть десятые, вторая еденичные
                         * Для десятых добавляем 0, что б можно было обратиться по индексу в словаре
                         * Далее объединяем число из двух
                         */
                        $indexTenths = $stringNumber[$length-2-$position]."0";
                        
                        /*
                         * Делаем проверку на предмет 1 и 2 склонений в тысячах,
                         * получаем индекс единичного числа
                         */
                        if (($position == 3 or $position == 9) and $dictionary["lang"] != "eng" and 
                                ($stringNumber[$length-1-$position] == "1" 
                                or $stringNumber[$length-1-$position] == "2")) {
                            $index = $stringNumber[$length-1-$position]."т";                         
                        } else {
                            $index = $stringNumber[$length-1-$position];
                        }
                        /*
                         * Собраное число
                         */
                        $this->translateValue = $dictionary[$indexTenths] 
                                . " " . 
                                $dictionary[$index] . $this->translateValue;
                    }
                }
            } else {
                /*
                 * Число состоит из одного символа
                 * Проверяем 1 и 2 на присутсвие их в тысячных 
                 */
                if (($position == 3 or $position == 9) and $dictionary["lang"] != "eng" and 
                                ($stringNumber[$length-1-$position] == "1" 
                                or $stringNumber[$length-1-$position] == "2")) {
                    $this->translateValue = $dictionary[$stringNumber[$length-1-$position]."т"].$this->translateValue;                          
                } else {
                $this->translateValue = $dictionary[$stringNumber[$length-1-$position]].$this->translateValue;
                }
            }
        }
        /*
         * Метод для формирвоания сотых элементов, склонения в русском и украинском языке 
         * устроены сложнее чем в английском поэтому этот метод переопределяется в классах наследниках
         */
        public function makeHundredsNumber($dictionary, $position = 0) { 
            /*
             * Преобразуем число в стркоу и работаем с ним как с массивом символов строки
             */
            $stringNumber = (string)$this->numberValue;
            $length = strlen($stringNumber);
            /*
             * Проверяем есть ли у нас сотые элементы
             */
            if(isset($stringNumber[$length-3-$position])) {
                /*
                 * Определяем количество сотен для того что б правильно сформулировать 
                 * предложение с hundred
                 */
                $index = $stringNumber[$length-3-$position];
                
                if($index == "0") 
                {
                    /*
                     * Если количество сотых равно нулю идём дальше
                     */
                    $this->translateValue .= "";
                }
                if($index == "1") {  
                    /*
                     * Если количество сотых равно 1 пишем one hundred
                     */
                    $this->translateValue = "one hundred ".$this->translateValue;              
                }
                if((int)$index >= 2) {
                    /*
                     * Если количество сотых >2 пишем слово из словаря где индекс 
                     * это количество сотен и hundreds
                     */
                    $this->translateValue = $dictionary[$index]." hundreds ".$this->translateValue;
                }
            }
        }
        public function makeThousandsNumber($dictionary, $position = 0) { 
            /*
             * Метод для создания тысячных элементов
             * $tempValue = временное значение для определения количества тысяч
             */
            $tempValue = "";
            /*
             * Переводим число в строку и работаем с ней как массивом символов этой строки
             */
            $stringNumber = (string)$this->numberValue;
            $length = strlen($stringNumber);
            
            /*
             * Проверяем есть ли внашем числе тысячные
             */
            if(isset($stringNumber[$length-4-$position])) {
                /*
                 * Тысячные есть, проверяем количество тысячных, записывая в $tempValue, 
                 * столько символов сколько может быть тысяч
                 */
                $tempValue = $stringNumber[$length-4-$position];
               
                if(isset($stringNumber[$length-5-$position])) {
                    $tempValue = $stringNumber[$length-5-$position].$stringNumber[$length-4-$position];     
                }
                if(isset($stringNumber[$length-6-$position])) {
                    $tempValue = $stringNumber[$length-6-$position].$tempValue;   
                }
                /*
                 * Вообще можно было сдеть это через цикл перебирая максимум три значения
                 */
                
                if ((int)$tempValue == 1) {        
                    /*
                     * Переводим строку $tempValue в число и считаем количество тысяч, если 1 пишем one thousand
                     */
                         $this->translateValue = "one thousand ".$this->translateValue; 
                }
                if ((int)$tempValue == 0) {
                    /*
                     * Если 0, то идём дальше
                     */
                    $this->translateValue .= "";
                }
                if ((int)$tempValue >= 2) {
                    /*
                     * Если больше двух записываем thousands и добавляем количесто тысяч
                     */
                    $this->translateValue = " thousands ".$this->translateValue;
                        /*
                         * Вот тут то и пригодились методы для подсчёта тысяч,
                         * вот здесь мы указываем с какой позиции считать десятые 
                         * и сотые передавая 3 в аргументах функции, теперь когда 3 
                         * будет обнаружена в этих аргументах при подсчёте тысяч для 1 и 2 
                         * будут использлваться дополнительные индексы для тысяч из словаря 
                         */
                    $this->makeTenthsNumber($dictionary, 3+$position);
                    $this->makeHundredsNumber($dictionary, 3+$position);
                }
            } 
        }
        /*
         * Метод для подсчёта миллионов
         */
        public function makeMillion($dictionary) {
            
            $tempValue = "";
            $stringNumber = (string)$this->numberValue;
            $length = strlen($stringNumber);
            /*
             * Проверяем есть ли у нас миллионые значения
             */
            if(isset($stringNumber[$length-7])) {
                /*
                 * Миллионные значения есть, считаем сколько их заполняя 
                 * переменную $tempValue и потом перевеля её в число
                 * Далее аналогично исходя из численного значения этой переменной 
                 * выбираем сколько миллионов написать
                 */
                for($i = 7; $i<=$length; $i++) {
                    $tempValue = $stringNumber[$length-$i].$tempValue;
                }
                if((int)$tempValue == 0) {
                    $this->translateValue .= "";
                }
                if((int)$tempValue == 1) {
                    $this->translateValue = "one millione ".$this->translateValue;
                }
                if((int)$tempValue >=2 ) {
                    $this->translateValue = " milliones ".$this->translateValue;
                    /*
                     * Вот тут то используем все функции для формирования чисел, 
                     * передавая с какого аргумента считать
                     */
                    $this->makeTenthsNumber($dictionary, 6);
                    $this->makeHundredsNumber($dictionary, 6);
                    $this->makeThousandsNumber($dictionary, 6);
                }
            }
        }
        /*
         * Метод возвращает число в текстовом варианте
         */
        protected function getTranslateValue() {
            return $this->translateValue;
        }
        /*
         * Мы превращаем исходное число в строку и начинаем обрабатывать строку с конца, 
         * при этом дописывая сколько десятков, сотен, тысяч, десятков тысяч, сотен тысяч, 
         * миллионов, десятков миллионов и так далее у нас есть
         * Таким образом работает парсер
         */
}
