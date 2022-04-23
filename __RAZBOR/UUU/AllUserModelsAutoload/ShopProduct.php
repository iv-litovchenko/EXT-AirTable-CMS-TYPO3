<?php
class ModelShopProduct {
	
	public $name = 'Магазин - Товар';
	public $table = 'tx_shop_product';
	public $fields = array(
		'default'					=> array('crdate','sorting','deleted','hidden','seo'),
			'title_2' 				=> array('INPUT_MAX_255', 'Альтернативное название (заголовок)'),
			'bodytext_description' 	=> array('TEXTAREA', 'Краткое описание (для органов)'),
			'bodytext_prev' 		=> array('TEXTAREA', 'Превью описание'),
			'bodytext_consist' 		=> array('TEXTAREA', 'Состав препарата'),
			'bodytext' 				=> array('TEXTAREA_RTE', 'Описание'),
			'bodytext_consist_2' 	=> array('TEXTAREA', 'Состав препарата 2'),
			'pic' 					=> array('ATTACH_IMAGE', 'Изображение'),
		
		'--div1--'						=> 'Атрибуты',
			// 'article' 				=> array('INPUT_NUMBER', 'Артикул (основной - заполняется всегда)'),
			'tx_shop_product_sentence' 	=> array('REL_INLINE_MULTI', 'Предложения', array('foreign_table' => 'tx_shop_product_sentence')),
			'price' 					=> array('INPUT_FLOAT', 'Цена (актуально если нет предложений)'),
			'volume' 					=> array('INPUT_MAX_12', 'Объем  (актуально если нет предложений)'),
			'volume_type' 				=> array('SELECT', 'Объем (тип) (актуально если нет предложений)', '0:мл.|1:гр.|2:капсул|3:горошинок|4:табл.'),
		
		'--div3--'					=> 'Категории',
			'category_uid' 			=> array('REL_TREE_MULTI', 'Категории', array('allowed'=>'tx_shop_category','parent_field'=>'parent')),
		
		// '--div4--'				=> 'Видео (НЕ ДОДЕЛАНО)',
		// 'tx_shop_product_video_and_separator_uid' => array('REL_MULTI', 'Закрпленные видео и разделители', array('allowed'=>'tx_shop_product_video_separator,tx_shop_product_video')),
		
		'--div5--'								=> 'Показания и рекомендации',
			'tx_clinic_disease_uid_indications' => array('REL_MULTI', 'Показания при заболеваниях', array('allowed' => 'tx_clinic_disease')),
			'tx_shop_product_uid_recommend' 	=> array('REL_MULTI', 'Рекомендуемые препараты', array('allowed' => 'tx_shop_product')),
			
		'--div6--'								=> 'Иконки',
			'tx_shop_product_icon_1_uid' 		=> array('REL_MULTI', 'Для кого особенно хорош', array('allowed' => 'tx_shop_product_icon_1')),
			'tx_shop_product_icon_2_uid' 		=> array('REL_MULTI', 'Для чего особенно хорош', array('allowed' => 'tx_shop_product_icon_2')),
			'tx_shop_product_icon_uids' 		=> array('REL_MULTI', 'На странице список', array('allowed' => 'tx_shop_product_icon_1,tx_shop_product_icon_2,tx_shop_product_icon_3')),
		
		'--div7--'								=> 'Советы',
			'title_useful_tips'					=> array('INPUT_MAX_255', 'Полезные советы (формула здоровья) - заголовок'),
			'tx_shop_product_useful_tips_uid' 	=> array('REL_INLINE_MULTI', 'Полезные советы (формула здоровья)', array('foreign_table' => 'tx_shop_product_useful_tips')),
		
		'--div8--'								=> 'Служебное',
			'admin_comment' 					=> array('TEXTAREA', 'Комментарий администрации сайта'),
	
		'--div9--'								=> 'Статьи',
			'tx_content_articles_uid' 			=> array('REL_MULTI', 'Прикрепленные статьи', 'tx_content_articles'),
			'articles_position' 				=> array('SELECT', 'Позиция вывода статей', '1:вывод статей снизу'),
	);
	
	public $router = array(
		'ShopProductDetail_LINK_IN_RTE' => array(7, 'tx_shop_product_uid'),
		'ShopProductDetail' => array(7, 'tx_shop_product_uid'),
		'ShopProductDetailWithCategory' => array(7, 'tx_shop_category_uid', 'tx_shop_product_uid'),
	);
	
	// Получить список товаров
	// @categoryId Id-категории
	// @page Номер страницы (если "all" то не учитывается
	function getList($categoryId = 0, $page = 1){
		$q = DB::run()->table('tx_shop_product')->orderDesc('crdate');
		
		if($categoryId > 0){
			$q->whereMultiCat('category_uid', $categoryId);
		}
	
		if($page != 'all'){
			$q->limitWithPagePosition($page, 9);
		}
		
		$row = $q->exec();
		return $row;
	}
	
	// Получить количество товаров
	// @categoryId Id-категории
	function getCount($categoryId = 0){
		$q = DB::run()->count()->table('tx_shop_product')->orderDesc('crdate');
		
		if($categoryId > 0){
			$q->whereMultiCat('category_uid', $categoryId);
		}
			
		$row = $q->exec();
		return $row;
	}
	
	// Получить товар по Id
	// @recordId Id-записи
	function getById($recordId){
		$row = DB::run()->table('tx_shop_product')->whereIdrecord($recordId)->exec();
		return $row;
	}
	
	// Получить товар по Id
	// @recordId Id-записи
	function getByIdOnlyFieldUidAndTitle($recordId){
		$row = DB::run()->table('tx_shop_product')->select('uid,title')->whereIdrecord($recordId)->exec();
		return $row;
	}
	
	// Получить товары по Id
	// @recordsIds Id-записией
	function getByIds($recordsIds){
		$row = DB::run()->table('tx_shop_product')->whereIdrecord($recordsIds)->orderFindInSet("uid", $recordsIds)->exec();
		return $row;
	}
	
}
?>