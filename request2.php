<?php
define('DEBUG', 'off');
/*
 * Подключаем классы наследники
 */
require 'Translatings/EnglishTranslation.php';
require 'Translatings/RussianTranslation.php';
require 'Translatings/UkrainianTranslation.php';
/*
 * Проверяем инициализирован ли елемент в глобальном массиве $_GET
 */
if(isset($_GET['number'])) {
	$newRequest = new Request($_GET['number']);
        
        /*
         * Отображаем перевод в разных языках
         */
        $newRequest->showTranslate("ua"); echo "<br>";
        $newRequest->showTranslate("eng"); echo "<br>";
        $newRequest->showTranslate("ru"); echo "<br>";
}
/*
 * Если нет, то перенаправляем на страницу index.php
 */
else 
{
	header('Location: index.php');
}

/*
 * Принимает значение числа в конструкторе и создаёт переводы числа
 * в разные текстовые варианты в методе showTranslate
 */
class Request {
	private $requestValue;
	public function __construct($requestValue) {
            
            /*
             * Если вдруг число начинается с нуля
             */
            if($requestValue[0] == "0" and isset($requestValue[1])) {
                echo "<img src=images/error.jpg width=20px height=15px> Число не может начинаться с нуля";
                exit();
            }
            /*
             * Если вдруг число больше чем допустимо, 999 999 999 999
             */
            if(strlen($requestValue) > 12) {
                echo "<img src=images/error.jpg width=20px height=15px> Недопустимая величина числа, максимальное число 999 999 999 999";
                exit();
            }
            /*
             * Если всё нормально приравниваем число члену класса
             */
            $this->requestValue = $requestValue;
            
	}
	public function setRequestValue($requestValue) {
		$this->requestValue = $requestValue;
	}
	public function getRequestValue() {
		return $this->requestValue;
	}
	public function showTranslate($lang) {
            switch($lang) {
                case "eng": {       
                    /*
                     * Создаём экземпляр ангийского перевода и показываем его
                     */
                    $engTranslation = new EnglishTranslation($this->requestValue);
                    $engTranslation->parseNumber();
                    break;
                }
                case "ru": {
                    /*
                     * Создаём экземпляр русского перевода и показываем его
                     */
                    $ruTranslation = new RussianTranslation($this->requestValue);
                    $ruTranslation->parseNumber();
                    break;
                }
                case "ua": {
                    /*
                     * Создаём экземпляр украинского перевода и показываем его
                     */
                    $uaTranslation = new UkrainianTranslation($this->requestValue);
                    $uaTranslation->parseNumber();
                    break;
                }
            }
        }
}

