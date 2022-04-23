<?php
class ModelClinicDisease {
	
	public $domain = 'Clinic';
	public $name = 'Клиника - болезни';
	public $table = 'tx_clinic_disease';
	public $fields = array(
		'default'					=> array('deleted','hidden','seo','service_note'),
		
		// 'test'					=> array('REL', 'ТЕСТ', array('allowed'=>'pages')),
		// 'test_2'					=> array('REL_MULTI', 'ТЕСТ2', array('allowed'=>'pages')),
		// 'test_3'					=> array('REL_MULTI', 'ТЕСТ3', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),
		
		'--div1--'					=> 'Описание болезни и локация',
			'rel_organ'				=> array('REL_MULTI', 'Орган (локация)', array('allowed' => 'tx_clinic_organ')),
			'bodytext'				=> array('TEXTAREA', 'Описание болезни'),
		
		'--div2--'					=> 'Диагностика',
			'diagnostic_link_uid'	=> array('REL_MULTI', 'Варианты диагностики', array('allowed'=>'pages(27),tx_clinic_special_diagnostics')),
		
		'--div3--'					=> 'Общие рекомендации лечения',
			'recom_header_1'		=> array('INPUT_MAX_128', 	'1 Заголовок'),
			'recom_bodytext_1'		=> array('TEXTAREA', 		'1 Описание'),
			'recom_link_uid_1'		=> array('REL_MULTI', 		'1 Метод лечения', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),
			
			'recom_header_2'		=> array('INPUT_MAX_128', 	'2 Заголовок'),
			'recom_bodytext_2'		=> array('TEXTAREA', 		'2 Описание'),
			'recom_link_uid_2'		=> array('REL_MULTI', 		'2 Метод лечения', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),
			
			'recom_header_3'		=> array('INPUT_MAX_128', 	'3 Заголовок'),
			'recom_bodytext_3'		=> array('TEXTAREA', 		'3 Описание'),
			'recom_link_uid_3'		=> array('REL_MULTI', 		'3 Метод лечения', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),
			
			'recom_header_4'		=> array('INPUT_MAX_128', 	'4 Заголовок'),
			'recom_bodytext_4'		=> array('TEXTAREA', 		'4 Описание'),
			'recom_link_uid_4'		=> array('REL_MULTI', 		'4 Метод лечения', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),
			
			'recom_header_5'		=> array('INPUT_MAX_128', 	'5 Заголовок'),
			'recom_bodytext_5'		=> array('TEXTAREA', 		'5 Описание'),
			'recom_link_uid_5'		=> array('REL_MULTI', 		'5 Метод лечения', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),
		
		'--div4--'					=> 'Причины и их лечение',
			'treatment_header_1'	=> array('INPUT_MAX_128', 	'1 Заголовок'),
			'treatment_bodytext_1'	=> array('TEXTAREA', 		'1 Описание'),
			'treatment_link_uid_1'	=> array('REL_MULTI', 		'1 Причины и их лечение', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),
			
			'treatment_header_2'	=> array('INPUT_MAX_128', 	'2 Заголовок'),
			'treatment_bodytext_2'	=> array('TEXTAREA', 		'2 Описание'),
			'treatment_link_uid_2'	=> array('REL_MULTI', 		'2 Причины и их лечение', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),

			'treatment_header_3'	=> array('INPUT_MAX_128', 	'3 Заголовок'),
			'treatment_bodytext_3'	=> array('TEXTAREA', 		'3 Описание'),
			'treatment_link_uid_3'	=> array('REL_MULTI', 		'3 Причины и их лечение', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),

			'treatment_header_4'	=> array('INPUT_MAX_128', 	'4 Заголовок'),
			'treatment_bodytext_4'	=> array('TEXTAREA', 		'4 Описание'),
			'treatment_link_uid_4'	=> array('REL_MULTI', 		'4 Причины и их лечение', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),

			'treatment_header_5'	=> array('INPUT_MAX_128', 	'5 Заголовок'),
			'treatment_bodytext_5'	=> array('TEXTAREA', 		'5 Описание'),
			'treatment_link_uid_5'	=> array('REL_MULTI', 		'5 Причины и их лечение', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),

			'treatment_header_6'	=> array('INPUT_MAX_128', 	'6 Заголовок'),
			'treatment_bodytext_6'	=> array('TEXTAREA', 		'6 Описание'),
			'treatment_link_uid_6'	=> array('REL_MULTI', 		'6 Причины и их лечение', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),

			'treatment_header_7'	=> array('INPUT_MAX_128', 	'7 Заголовок'),
			'treatment_bodytext_7'	=> array('TEXTAREA', 		'7 Описание'),
			'treatment_link_uid_7'	=> array('REL_MULTI', 		'7 Причины и их лечение', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),

			'treatment_header_8'	=> array('INPUT_MAX_128', 	'8 Заголовок'),
			'treatment_bodytext_8'	=> array('TEXTAREA', 		'8 Описание'),
			'treatment_link_uid_8'	=> array('REL_MULTI', 		'8 Причины и их лечение', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),

			'treatment_header_9'	=> array('INPUT_MAX_128', 	'9 Заголовок'),
			'treatment_bodytext_9'	=> array('TEXTAREA', 		'9 Описание'),
			'treatment_link_uid_9'	=> array('REL_MULTI', 		'9 Причины и их лечение', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),
		
			'treatment_header_10'	=> array('INPUT_MAX_128', 	'10 Заголовок'),
			'treatment_bodytext_10'	=> array('TEXTAREA', 		'10 Описание'),
			'treatment_link_uid_10'	=> array('REL_MULTI', 		'10 Причины и их лечение', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66),tx_clinic_disease,tx_shop_product')),	
	
		'--div5--' => 'Последствия',
			'effects_title' 		=> array('INPUT_MAX_128', 'Последствия заголовок'),
			'effects_bodytext'		=> array('TEXTAREA', 'Последствия текст'),
			
		'--div6--'					=> 'Вывод на главной',
		'page_index_display'		=> array('CHECKBOX', 'Вывод на главной странице', '1;Выводить на главной;2;Разрешить вывод в блоке'),
	);
	
	public $router = array(
		'ClinicDiseaseDetail' => array(77.1, 'disease_id'),
	);
	
	// Получить список
	function getList(){
		$rows = DB::run()->table('tx_clinic_disease')->orderAsc('title')->exec();
		return $rows;
	}
	
	// Получить список болезней, с указанным списком ID-органов
	function getListWhereOrgansIds($organsIds = array()){
		$rows = DB::run()->table("tx_clinic_disease")->select("*")->whereMultiCat('rel_organ', $organsIds)->orderAsc("title")->exec();
		return $rows;
	}
	
	// Получить для главной
	function getListForPageIndex(){
		$row = DB::run()->table("tx_clinic_disease")->select("*")->where('page_index_display', '=', 1)->exec();
		return $row;
	}
	
	// Получить количество
	function getCount(){
		// return '';
	}
	
	// Получить по Id
	function getById($recordId = 0){
		$row = DB::run()->table('tx_clinic_disease')->whereIdrecord($recordId)->exec();
		return $row;
	}
	
	// Получить по Id
	function getByIds($recordsIds){
		$row = DB::run()->table('tx_clinic_disease')->whereIdrecord($recordsIds)->orderFindInSet("uid", $recordsIds)->exec();
		return $row;
	}
	
	// Получить по Id с сортировкой по названию
	function getByIdOrderByTitle($recordId = 0){
		$row = DB::run()->table('tx_clinic_disease')->whereIdrecord($recordId)->orderAsc("title")->exec();
		return $row;
	}
	
	// Получить заболевание (запись текущей таблицы по полям) - скан таблицы по id-записи с префиксом
	function getScanFieldsByPrefixAndId($recordId = 'pages_1'){
		$rows = DB::run()->table('tx_clinic_disease')
			// ->whereMultiCat('diagnostic_link_uid', $recordId)
			->whereMultiCat('recom_link_uid_1', $recordId)
			->orWhereMultiCat('recom_link_uid_2', $recordId)
			->orWhereMultiCat('recom_link_uid_3', $recordId)
			->orWhereMultiCat('recom_link_uid_4', $recordId)
			->orWhereMultiCat('recom_link_uid_5', $recordId)
			->orWhereMultiCat('treatment_link_uid_1', $recordId)
			->orWhereMultiCat('treatment_link_uid_2', $recordId)
			->orWhereMultiCat('treatment_link_uid_3', $recordId)
			->orWhereMultiCat('treatment_link_uid_4', $recordId)
			->orWhereMultiCat('treatment_link_uid_5', $recordId)
			->orWhereMultiCat('treatment_link_uid_6', $recordId)
			->orWhereMultiCat('treatment_link_uid_7', $recordId)
			->orWhereMultiCat('treatment_link_uid_8', $recordId)
			->orWhereMultiCat('treatment_link_uid_9', $recordId)
			->orWhereMultiCat('treatment_link_uid_10', $recordId)
			->orderAsc("title")
			->exec();
		return $rows;
	}
}
?>