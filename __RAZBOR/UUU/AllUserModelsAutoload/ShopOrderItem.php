<?php
class ModelShopOrderItem {
	
	public $name = 'Магазин - Заказы (элемент заказа)';
	public $table = 'tx_shop_order_item';
	public $fields = array(
		'default' => array('sorting'),
		'--div1--'							=> 'Свойства',
		'tx_shop_order_uid' 				=> array('INPUT_NUMBER', 'Заказ к которому прикрелен элемент'),
		'tx_shop_product_uid' 				=> array('INPUT_NUMBER', 'Id-товара'),
		'tx_shop_product_sentence_uid' 		=> array('INPUT_NUMBER', 'Id-предложения'),
		'count' 							=> array('INPUT_NUMBER', 'Кол-во'),
		'price' 							=> array('INPUT_FLOAT',  'Цена'),
	);
	
}
?>