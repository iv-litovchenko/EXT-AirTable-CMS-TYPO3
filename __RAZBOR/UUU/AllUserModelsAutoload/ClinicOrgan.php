<?php
class ModelClinicOrgan {

	public $domain = 'Clinic';
	public $name = 'Клиника - органы человека';
	public $table = 'tx_clinic_organ';
	public $fields = array(
		'default'						=> array('deleted','hidden','files','service_note','bodytext_detail'), // ,'picture_detail','picture_preview','bodytext_preview','bodytext_detail',
		'medical_system_uid'			=> array('REL_MULTI', 'Принадлежит следующим системам человека', array('allowed' => 'tx_clinic_medical_system')),
		
		// Одинаковое в мед.системе и органе (start)
		'--div1--'						=> 'Варианты диагностики',
		'diagnostics_uid'				=> array('REL_MULTI', 'Варианты диагностики', array('allowed'=>'pages(27),tx_clinic_special_diagnostics')),
		
		'--div2--'						=> 'Методики лечения',
		'methodologies_uid'				=> array('REL_MULTI', 'Методики лечения', array('allowed'=>'pages(26|28|62|135|64|157|149|148|65|66)')),
		
		'--div3--'						=> 'Рекомендуемые препараты',
		'tx_shop_product_uid'			=> array('REL_MULTI', 'Рекомендуемые препараты', array('allowed'=>'tx_shop_product')),
		
		'--div4--'						=> 'Описания',
		'bodytext_parameters'			=> array('TEXTAREA', 'Параметры'),
		'bodytext_interesting_fact'		=> array('TEXTAREA', 'Интересный факт'),
		
		'--div5--'						=> 'Иконка',
		'svg_icon_on_white'				=> array('ATTACH_IMAGE', 'Иконка на белый фон'),
		
		'--div6--'						=> 'Вывод на главной',
		'page_index_display'			=> array('CHECKBOX', 'Вывод на главной странице', '1:Позиция 1|2:Позиция 2'),
		// Одинаковое в мед.системе и органе (end)
	);
	
	public $router = array(
		'ClinicOrganDetail' => array(724,'tx_clinic_medical_system','tx_clinic_organ'),
	);
	
	// Получить список
	function getList(){
		$row = DB::run()->table("tx_clinic_organ")->select("*")->orderAsc("title")->exec();
		return $row;
	}
	
	// Получить для главной
	function getListForPageIndex($position = '1', $lim = 3){
		$row = DB::run()->table("tx_clinic_organ")->select("*")->whereMultiCat('page_index_display', $position)->limit($lim)->exec();
		return $row;
	}
	
	// Получить список по прикреплению к системе человека
	// Второй параметр передается строка из id-Шек которые будут сортировавться как orderFindInSet()
	function getListWhereMedicalSystemId($medicalSystemId = array(), $orderFindInSetString = array()){
		$q = DB::run()->table("tx_clinic_organ")->select("*")->whereMultiCat('medical_system_uid', $medicalSystemId);
		
		$arex = explode(',',$orderFindInSetString);
		if(count($arex) > 0 && $arex[0] > 0){
			$q = $q->orderFindInSet("uid", $arex);
		} else {
			$q = $q->orderAsc("title");
		}
		
		$row = $q->exec();
		return $row;
	}
	
	// Получить количество
	function getCount(){
		// return '';
	}
	
	// Получить по Id
	function getById($recordId){
		$row = DB::run()->table('tx_clinic_organ')->whereIdrecord($recordId)->exec();
		return $row;
	}
	
	// Получить по Ids
	function getByIds($recordIds = array()){
		$row = DB::run()->table('tx_clinic_organ')->whereIdrecord($recordIds)->exec();
		return $row;
	}
	
}
?>