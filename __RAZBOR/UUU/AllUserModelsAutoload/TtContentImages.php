<?php
class ModelTtContentImages {
	
	public $table = 'tt_content';
	public $element_key = 'tt_content_images';
	public $name = 'Адаптивные изображения';
	public $description = '';
	public $fields = array(
		'tt_content_images' => array('ATTACH_DOCUMENTS', 'Изображения'),
		'grid' => array('SELECT', 'Сетка', '
			2-2-2-2-2-2:6 колонок|
			20-20-20-20-20:5 колонок|
			3-3-3-3:4 колонки|
			4-4-4:3 колонки|
			6-6:2 колонки|
			12:1 колонка|
			3-9:3-9 колонки - только для 2 картинок |
			9-3:9-3 колонки - только для 2 картинок |
			4-8:4-8 колонки - только для 2 картинок |
			8-4:8-4 колонки - только для 2 картинок 
		'),	
		'grid_options' => array('CHECKBOX_MULTI', 'Опции показа изображений', '
			with-100:Изображения в 100%-ширины сетки|
			display-figcaption-top:Выводить подписи к изображениям (сверху)|
			display-figcaption-bottom:Выводить подписи к изображениям (снизу)
		'),
		'bodytext' => array('TEXTAREA_RTE', 'Текст'),	
		'margin_after' => array('CHECKBOX', 'Отступы снизу', '1:Запретить отступы снизу'),			
	);

}
?>