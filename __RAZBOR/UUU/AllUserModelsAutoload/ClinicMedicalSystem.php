<?php
class ModelClinicMedicalSystem {
	
	public $domain = 'Clinic';
	public $name = 'Клиника - системы человека';
	public $table = 'tx_clinic_medical_system';
	public $fields = array(
		'default'							=> array('deleted','hidden','files','service_note','bodytext_detail','sorting'),
		'title_2'							=> array('INPUT_MAX_48', 'Альтернативное название'),
		'--div0--'							=> 'Порядок сортировки органов',
		'tx_clinic_organ_uid_find_in_set'	=> array('INPUT_MAX_255', 'Порядок сортировки органов (ID-записей через запятую для find_in_set())'),
	
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
		
		'--div5--'						=> 'Иконки',
		'svg_icon_on_white'				=> array('ATTACH_IMAGE', 'Иконка на белый фон'),
		'svg_icon_black'				=> array('ATTACH_IMAGE', 'Иконка черная'),
		'svg_icon_black_hover'			=> array('ATTACH_IMAGE', 'Иконка черная при наведении'),
		// Одинаковое в мед.системе и органе (end)
	);
	
	public $router = array(
		'ClinicMedicalSystemDetail' => array(724,'tx_clinic_medical_system'),
	);
	
	// Получить список
	function getList(){
		$row = DB::run()->table("tx_clinic_medical_system")->select("*")->orderAsc('sorting')->exec();
		return $row;
	}
	
	// Получить количество
	function getCount(){
		// return '';
	}
	
	// Получить по Id
	function getById($recordId){
		$row = DB::run()->table('tx_clinic_medical_system')->whereIdrecord($recordId)->exec();
		return $row;
	}
	
	// Получить по Ids
	function getByIds($recordIds = array()){
		$row = DB::run()->table('tx_clinic_medical_system')->whereIdrecord($recordIds)->exec();
		return $row;
	}
	
}
?>