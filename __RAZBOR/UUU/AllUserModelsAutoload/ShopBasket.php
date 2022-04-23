<?php
class ModelShopBasket {
	
	public $name = 'Корзина покупок';
	public $table = ''; // Таблица не используется
	
	// Получить список товаров в корзине
	function getList(){
		session_start();
		// unset($GLOBALS['_SESSION']['basket']);
		
		// Собираем Id-товаров в корзине
		$recordsIds = array();
		$sentencesIds = array();
		foreach($GLOBALS['_SESSION']['basket'] as $k => $v){
			// Т.к. теперь идет запись с предложением "103-3" Id-Записи, Id-предложения
			$arex = explode('-', $k);
			$recordsIds[] = $arex[0];
			$sentencesIds[] = $arex[1];
		}
		
		// Список продуктов
		$productsList = array();
		$rows = ModelShopProduct::getById($recordsIds);
		foreach($rows as $k => $v){
			$productsList[$v['uid']] = $v;
		}
		
		// Список предложений
		$rows = ModelShopProductSentence::getById($sentencesIds);
		foreach($rows as $k => $v){
			$sentencesIds[$v['uid']] = $v;
		}
		
		$rowsResult = array();
		foreach($GLOBALS['_SESSION']['basket'] as $k => $v){
			$arex = explode('-', $k);
			$productsList[$arex[0]]['CUSTOM_count'] = $GLOBALS['_SESSION']['basket'][$arex[0].'-'.$arex[1]]['count'];
			$productsList[$arex[0]]['CUSTOM_sentenceId'] = $arex[1];
			
				// Если это предложение - переписываем информацию
			if($arex[1] > 0){
				$productsList[$arex[0]]['uid_sentence'] = $sentencesIds[$arex[1]]['uid'];
				$productsList[$arex[0]]['title'] = $sentencesIds[$arex[1]]['title'];
				$productsList[$arex[0]]['price'] = $sentencesIds[$arex[1]]['price'];
				$productsList[$arex[0]]['volume'] = $sentencesIds[$arex[1]]['volume'];
				$productsList[$arex[0]]['volume_type'] = $sentencesIds[$arex[1]]['volume_type'];
				// $productsList[$arex[0]]['uid'] = 456;
			}
			
			$rowsResult[] =  $productsList[$arex[0]];
		}
		
		return $rowsResult;
		# return $GLOBALS['_SESSION']['basket'];
	}
	
	// Получить кол-во товаров в корзине
	function getCount(){
		session_start();
		$c = count($GLOBALS['_SESSION']['basket']);
		return $c;
	}
	
	// Получить кол-во товаров в корзине (всего)
	function getCount2(){
		session_start();
		$c = 0;
		foreach($GLOBALS['_SESSION']['basket'] as $k => $v){
			$c = $c + $v['count'];
		}
		return $c;
	}
	
	// Добавить 1 товар в корзину
	// @ recordId // товар
	// @ sentenceId // предложение
	function add($recordId = 0, $sentenceId = 0){
		// Получаем информацию о товаре
		// $row = ModelShopProduct::getById($recordId);
		// $row = $row[0];
		
		// Заносим в сессию и после данная информация пойдет в serialize-заказа (order)
		session_start();
		$GLOBALS['_SESSION']['basket'][$recordId.'-'.$sentenceId]['count'] = 1;
		
		
		// Всю данную информацию выбираем по Id-записи из БД
		// $GLOBALS['_SESSION']['basket'][$recordId]['price'] = $row['price'];
		// $GLOBALS['_SESSION']['basket'][$recordId]['uid']   = $recordId;
		// $GLOBALS['_SESSION']['basket'][$recordId]['info']['title'] 		= $row['title'];
		// $GLOBALS['_SESSION']['basket'][$recordId]['info']['article'] 	= $row['article'];
		// $GLOBALS['_SESSION']['basket'][$recordId]['info']['pic'] 		= $row['pic'];
	}
	
	// Обновляем кол-во 1-ого товар в корзине
	// @ recordId // товар
	// @ sentenceId // предложение
	// @ count // 1
	function updateCountById($recordId = 0, $sentenceId = 0, $count = 1){
		session_start();
		$GLOBALS['_SESSION']['basket'][$recordId.'-'.$sentenceId]['count'] = $count;
	}
	
	// Удалить товар из корзины
	// @ recordId // товар
	// @ sentenceId // предложение
	function delete($recordId = 0, $sentenceId = 0){
		session_start();
		unset($GLOBALS['_SESSION']['basket'][$recordId.'-'.$sentenceId]);
	}
	
	// Очистить всю корзину
	// @ recordId
	function clearAll($recordId){
		session_start();
		unset($GLOBALS['_SESSION']['basket']);
	}
	
	// Получить итоговую стоимость товаров в корзине
	function getTotal(){
		$row = self::getList(); // получаем содержимое корзины
		$costSum = 0; // Итого
		foreach($row as $k => $v){
			$cost = $v['CUSTOM_count']*$v['price'];
			$costSum += $cost;
		}
		return $costSum;
	}
	
	// Проверить наличие товара(товаров в корзине)
	// @ recordId // товар
	// @ sentenceId // предложение
	function exists($recordId = 0, $sentenceId = 0){
		session_start();
		if($GLOBALS['_SESSION']['basket'][$recordId.'-'.$sentenceId]['count'] > 0){
			return true;
		} else {
			return false;
		}
	}
	
}
?>