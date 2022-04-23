<?php
class ModelShopProductSentence {
	
	public $dependent = 1; // записи зависят от другой таблицы - будет помечатся черной иконкой
	public $name = 'Магазин - Товар (предложение)';
	public $table = 'tx_shop_product_sentence';
	public $fields = array(
		'default'					=> array('sorting','deleted','hidden'),
		// 'article' 				=> array('INPUT_NUMBER', 'Артикул'),
		'price' 					=> array('INPUT_FLOAT', 'Цена'),
		'volume' 					=> array('INPUT_MAX_12', 'Объем'),
		'volume_type' 				=> array('SELECT', 'Объем (тип)', '0:мл.|1:гр.|2:капсул|3:горошинок|4:табл.'),
	);
	
	// Получить список
	function getList(){
	}
	
	// Получить записи по Id
	function getById($recordId){
		$row = DB::run()->table('tx_shop_product_sentence')->whereIdrecord($recordId)->exec();
		return $row;
	}
	
	// Получить записи по Id
	function getByIds($recordsIds){
		$row = DB::run()->table('tx_shop_product_sentence')->whereIdrecord($recordsIds)->orderFindInSet("uid", $recordsIds)->exec();
		return $row;
	}
	
}
?>