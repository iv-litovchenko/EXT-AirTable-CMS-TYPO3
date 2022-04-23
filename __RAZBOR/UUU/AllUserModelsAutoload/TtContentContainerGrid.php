<?php
class ModelTtContentContainerGrid {
	
	public $table = 'tt_content';
	public $element_key = 'tt_content_container_grid';
	public $name = 'Сетки и контейнеры';
	public $description = '';
	public $fields = array(
		'grid' => array('SELECT', 'Сетка', '
			2-2-2-2-2-2:6 колонок|
			20-20-20-20-20:5 колонок|
			3-3-3-3:4 колонки|
			4-4-4:3 колонки|
			6-6:2 колонки|
			4-8:30%-70%|
			8-4:70%-30%|
			3-9:20%-80%|
			9-3:80%-20%
		'),	
		'padding_bottom' => array('CHECKBOX', 'Отступы снизу', '1:Запретить отступы снизу'),		
	);

}
?>


	-->>> Сокрашенный, полный варинат!!!
	--> Было бы удобно так сделать...
	
		'page_index_display'			=> array('CHECKBOX', 'Вывод на главной странице', '1;Выводить на главной'),
		'tphone_type' => array('SELECT', 'Тип телефона', '0:Пользователь|1:Сотрудник клиники|:Тестовый адрес'),
<?php
class ModelTest {
	
	public $name = 'Тест';
	public $table = 'tx_test';
	public $fields = array(
		'default' 						=> array('parent', 'tt_content_inline', 'bodytext_detail', 'bodytext_preview', 'rel_x'),
		'rte_tinymce' 					=> array('TEXTAREA_RTE', 'TEXTAREA_RTE'),
		'rte_tinymce_2' 				=> array('TEXTAREA_RTE_MIN', 'TEXTAREA_RTE_MIN'),
		
		/*
		'field_radio' 					=> array('RADIO', 'RADIO', 							'1:Тест5|2:Тест6|3:Тест7|4:Тест4|76:Тест67'),
		'field_checkbox' 				=> array('CHECKBOX', 'CHECKBOX', 					'1:Тест1'),
		'field_checkbox_multi' 			=> array('CHECKBOX_MULTI', 'CHECKBOX_MULTI', 		'1:Тест1|2:Тест2|3:Тест3|4:Тест4'),
		'field_select' 					=> array('SELECT', 'SELECT', 						'1:Тест1|2:Тест2|3:Тест3|4:Тест4'),
		'field_select_multi' 			=> array('SELECT_MULTI', 'SELECT_MULTI', 			'1:Тест1|2:Тест2|3:Тест3|4:Тест4'),
		
		'--div1--'						=> 'REL',
			'tx_specialist_uid'		=> array('REL', 'Специалист', array('allowed' => 'tx_specialist')),
		'field_rel' 					=> array('REL', 'REL', 								'tx_infoblock_db_emails'),
		'field_rel_2table' 				=> array('REL', 'REL_2Table', 						'pages(2|4),tx_comments_of_our_specialists'),
		'field_rel_multi' 				=> array('REL_MULTI', 'REL_MULTI', 					'tx_infoblock_db_tphones'),
		'field_rel_multi_2table' 		=> array('REL_MULTI', 'REL_MULTI_2Table', 			'pages(2|4),tx_infoblock_db_emails,tx_infoblock_db_tphones,tx_specialist'),
		
		'--div2--'						=> 'REL_TREE',
		'field_rel_tree' 				=> array('REL_TREE', 'REL_TREE', 					'tx_specialist_category', 'parent'),
		'field_rel_tree_multi' 			=> array('REL_TREE_MULTI', 'REL_TREE_MULTI', 		'tx_specialist_category', 'parent'),
		
		'--div3--'						=> 'REL_INLINE',
		'field_inline' 					=> array('REL_INLINE', 'REL_INLINE', 				'tx_question_answer'),
		'field_inline_multi' 			=> array('REL_INLINE_MULTI', 'REL_INLINE_MULTI',	'tx_news'),
		*/
	);
}
?>