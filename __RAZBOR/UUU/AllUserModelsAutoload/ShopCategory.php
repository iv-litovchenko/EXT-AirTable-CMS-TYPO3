<?php
class ModelShopCategory {
	
	public $name = 'Магазин - Товар (категория)';
	public $table = 'tx_shop_category';
	public $fields = array(
		'default' => array('hidden', 'deleted', 'sorting', 'seo', 'parent'),
	);
	
	public $router = array(
		'ShopCategoryDetail' => array(7, 'tx_shop_category_uid'),
	);

	// Получить список категорий
	function getList(){
		$row = DB::run()->table('tx_shop_category')->exec();
		return $row;
	}
	
	// Получить список категорий по Id-родителя
	function getListByParentId($idRecord = 0){
		$row = DB::run()->table('tx_shop_category')->where('parent', '=', $idRecord)->orderDesc('sorting')->exec();
		return $row;
	}
	// Получить категорию по Id
	// @recordId Id-записи
	function getById($recordId){
		$row = DB::run()->table('tx_shop_category')->whereIdrecord($recordId)->exec();
		return $row;
	}
	
}
?>