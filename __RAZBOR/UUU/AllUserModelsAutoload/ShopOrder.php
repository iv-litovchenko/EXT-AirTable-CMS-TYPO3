<?php
class ModelShopOrder {

	public $name = 'Магазин - Заказы';
	public $table = 'tx_shop_order';
	public $fields = array(
		'default'				=> array('crdate'),
		'fe_user_uid' 			=> array('REL',  			'Аккаунт, с которого был сделан заказ', array('allowed'=>'fe_users')),
		'username' 				=> array('INPUT_MAX_255', 	'Email покупателя'),
		'name' 					=> array('INPUT_MAX_255', 	'Имя покупателя'),
		'last_name' 			=> array('INPUT_MAX_255', 	'Фамилия покупателя'),
		'telephone' 			=> array('INPUT_MAX_255', 	'Телефон покупателя'),
		'delivery_type'			=> array('INPUT_NUMBER',  	'Способ доставки'),
		'delivery_address' 		=> array('INPUT_MAX_255', 	'Адрес доставки'),
		// '--div1--'			=> 'Вкладка 1',
		'delivery_comment' 		=> array('INPUT_MAX_255', 	'Комментарий к доставке'),
		'payment_type' 			=> array('INPUT_NUMBER', 	'Способ оплаты'),
		'order_total' 			=> array('INPUT_NUMBER', 	'Итоговая сумма заказа'),
		// '--div2--'			=> 'Вкладка 2',
		'order_comment' 		=> array('TEXTAREA', 		'Комментарии к заказу'),
		'order_status' 			=> array('INPUT_NUMBER', 	'Статус заказа'),
		'order_payanyway_info' 	=> array('TEXTAREA', 		'Информация об отстукивании PayAnyWay', array('readOnly'=>1)),
	);
	
	// Получить список заказов по Id-пользователя
	// @userId Id-записи
	function getListByUserId($userId){
		$row = DB::run()->table('tx_shop_order')->where('fe_user_uid','=',$userId)->orderDesc('crdate')->exec();
		return $row;
	}
	
	// Получить список заказов
	function getList(){
		$row = DB::run()->table('tx_shop_order')->orderDesc('crdate')->exec();
		return $row;
	}
	
	// Получить заказ по Id
	// @recordId Id-записи
	function getById($recordId){
		$row = DB::run()->table('tx_shop_order')->whereIdrecord($recordId)->exec();
		return $row;
	}
	
	// Получить содержимое заказа (товары) по его Id-записи
	// Здесь основная идея в том, что цену фиксируем в заказе, и даже если цена 
	// товара изменится, в заказах все равно будет старая цена
	// @recordId Id-записи
	function getListItems($recordId){
		
			// Получаем состав заказа
		$row = DB::run()->table('tx_shop_order_item')->where('tx_shop_order_uid', '=', $recordId)->orderAsc('sorting')->exec();
		foreach($row as $k => $v){
	
			////////////////////////////////////////
			// Todo - оптимизировать
			////////////////////////////////////////

			// Информация о товаре, о предложении
			$row = ModelShopProduct::getByIds(array(0=>$v['tx_shop_product_uid']));
			$row[0]['CUSTOM_price_fixed'] = $v['price'];
			$row[0]['CUSTOM_count'] = $v['count'];
			
				if($v['tx_shop_product_sentence_uid'] > 0){
					$rowSent = ModelShopProductSentence::getById(array(0=>$v['tx_shop_product_sentence_uid']));
					if(!empty($rowSent[0])){
						$row[0]['uid_sentence'] = $v['tx_shop_product_sentence_uid'];
						$row[0]['title'] = $rowSent[0]['title'];
					}
				}
			
			$temp = $row[0];
			$row_result[] = $temp;

		}
		
		return $row_result;
	}
	
}
?>