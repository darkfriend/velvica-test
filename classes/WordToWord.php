<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 26.02.2019
 * Time: 18:51
 */

namespace darkfriend;


use mysql_xdevapi\Exception;

class WordToWord
{
	private static $_instance;
	private $_words=[];

	protected $alphabet = [
		'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й',
		'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф',
		'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
	];

	/**
	 * @return self
	 */
	static public function getInstance() {
		if(!self::$_instance)
			self::$_instance = new self();
		return self::$_instance;
	}

	private function __construct() {}

	/**
	 * @param string $word1
	 * @param string $word2
	 * @return $this
	 */
	public function process($word1,$word2) {
		if(!$word1 || !$word2)
			throw new Exception('Words is not found!');
		$arWord1 = preg_split('//u',$word1,-1, PREG_SPLIT_NO_EMPTY);
		$arWord2 = preg_split('//u',$word2,-1, PREG_SPLIT_NO_EMPTY);
		if(count($arWord1)!=count($arWord2))
			throw new Exception('Words length is not correctly!');

		$arAlphabetKey = array_map(function($symbol,$symbol2){
			return [
				array_search($symbol,$this->alphabet),
				array_search($symbol2,$this->alphabet),
			];
		},$arWord1,$arWord2);
		$iterations = [];
		//var_dump($arAlphabetKey);
		foreach ($arAlphabetKey as $alphabetKey) {
			$itemIteration = [];
			if($alphabetKey[0]==$alphabetKey[1]){
				$itemIteration[] = $this->alphabet[$alphabetKey[1]];
			} elseif($alphabetKey[0]<$alphabetKey[1]) {
				for($i=($alphabetKey[0]+1);$i<=$alphabetKey[1];$i++) {
					$itemIteration[] = $this->alphabet[$i];
				}
			} elseif($alphabetKey[0]>$alphabetKey[1]) {
				for($i=($alphabetKey[0]-1);$i>=$alphabetKey[1];$i--) {
					$itemIteration[] = $this->alphabet[$i];
				}
			}
			$iterations[] = $itemIteration;
		}
		//var_dump($iterations);
		$words = [];
		$itemWord = $arWord1;
		foreach ($iterations as $kWord=>$iteration) {
			foreach ($iteration as $item) {
				$itemWord[$kWord] = $item;
				$words[] = $itemWord;
			}
		}
		$this->_words = [$word1];
		foreach ($words as $word) {
			$this->_words[] = implode('',$word);
		}

		return $this;
	}

	public function __toString() {
		return implode(PHP_EOL,$this->_words);
	}

	public function show() {
		echo $this;
	}
}