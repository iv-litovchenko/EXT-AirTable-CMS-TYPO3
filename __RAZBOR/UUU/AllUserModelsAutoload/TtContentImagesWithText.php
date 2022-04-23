<?php
class ModelTtContentImagesWithText {
	
	public $table = 'tt_content';
	public $element_key = 'tt_content_images_with_text';
	public $name = 'Адаптивные изображения + текст';
	public $description = '';
	public $fields = array(
		'tt_content_images' => array('ATTACH_DOCUMENTS', 'Изображения'),
		'grid_wrap' => array('SELECT', 'Сетка (для блока текста и для блока изображений)', '
			40-60:		текст 40%, изображения 60%|
			50-50:		текст 50%, изображения 50%|
			80-20:		текст 80%, изображения 20%|
			100-100:	текст 100%, изображения 100%|
			auto:		текст ширина-авто, изображения ширина-авто|
			auto-16:	текст ширина-авто, изображения ширина-авто (max-width-16)|
			auto-32:	текст ширина-авто, изображения ширина-авто (max-width-32)|
			auto-48:	текст ширина-авто, изображения ширина-авто (max-width-48)|
			auto-64:	текст ширина-авто, изображения ширина-авто (max-width-64)|
			auto-128:	текст ширина-авто, изображения ширина-авто (max-width-128)|
			auto-256:	текст ширина-авто, изображения ширина-авто (max-width-256)
		'),	
		'grid' => array('SELECT', 'Сетка (для изображений) - НЕ актуально для "ширины-авто"', '
			2-2-2-2-2-2:	6 колонок|
			20-20-20-20-20:	5 колонок|
			3-3-3-3:		4 колонки|
			4-4-4:			3 колонки|
			6-6:			2 колонки|
			12:				1 колонка
		'),	
		'grid_options' => array('CHECKBOX_MULTI', 'Опции показа изображения)', '
			with-100:					Изображения в 100%-ширины сетки - НЕ актуально для "ширины-авто"|
			display-figcaption-top:		Выводить подписи к изображениям (сверху)|
			display-figcaption-bottom:	Выводить подписи к изображениям (снизу)
		'),
		'bodytext' => array('TEXTAREA_RTE', 'Текст'),
		
		// leftBottom:	Слева-снизу (Beta)|
		// rightBottom:	Справа-снизу (Beta)|
		'bodytext_alignment' => array('SELECT', 'Выравнивание текста', '
			left:				Слева|
			right:				Справа|
			leftWithoutClear:	Слева (картинки в ряд - только для ширины-авто)|
			rightWithoutClear:	Справа (картинки в ряд - только для ширины-авто)
		'),	
		'bodytext_around_disable' => array('CHECKBOX', 'Обтекание текстом', '1:Запретить обтекание текстом'),
		'margin_after' => array('CHECKBOX', 'Отступы снизу', '1:Запретить отступы снизу'),	
	);

}
?>