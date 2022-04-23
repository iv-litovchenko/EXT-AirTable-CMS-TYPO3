<?php
class ModelShopProductUsefulTips {
	
	public $dependent = 1; // записи зависят от другой таблицы - будет помечатся черной иконкой
	public $name = 'Магазин - Товар (полезные советы)';
	public $table = 'tx_shop_product_useful_tips';
	public $fields = array(
		'default' 	=> array('sorting','deleted','hidden'),
		'pic'		=> array('ATTACH_DOCUMENT', 'Изображение (SVG-икона)'),
		'bodytext'	=> array('TEXTAREA_RTE', 'Описание под изображением'),
	);
	
	// Получить список
	function getList(){
		$row = DB::run()->table('tx_shop_product_useful_tips')->exec();
		return $row;
	}
	
	// Получить количество
	function getCount($categoryId = 0){
		$row = DB::run()->table('tx_shop_product_useful_tips')->count()->exec();
		return $row;
	}

	// Получить по Id-записи(ией)
	function getById($recordId){
		$row = DB::run()->table('tx_shop_product_useful_tips')->whereIdrecord($recordId)->orderAsc('title')->exec();
		return $row;
	}
	
	// Получить по Id-записи(ией) с сортировкой по orderFindInSet()
	function getByIds($recordsIds){
		$row = DB::run()->table('tx_shop_product_useful_tips')->whereIdrecord($recordsIds)->orderFindInSet("uid", $recordsIds)->exec();
		return $row;
	}
	
}
?>