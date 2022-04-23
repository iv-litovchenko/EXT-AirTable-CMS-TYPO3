<?php
class ModelClinicSpecialDiagnostics {
	
	public $domain = 'Clinic';
	public $name = 'Клиника - специальные диагностики';
	public $table = 'tx_clinic_special_diagnostics';
	public $fields = array(
		'default' => array('hidden', 'deleted', 'sorting', 'seo', 'service_note'),
		'rel_organ' => array('REL_MULTI', 'Орган (локация)', array('allowed' => 'tx_clinic_organ')),
	);

}
?>