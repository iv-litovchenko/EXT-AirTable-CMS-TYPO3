<?php
// На данный момент только один вариант "0-вых" настроек TinyMce на всеь проект
return array(
	0 => array(
		'TYPO3_content_css' => array(
			// '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			// '//www.tinymce.com/css/codepen.min.css',
			// 'fileadmin/Resources/Public/example.css',
			// 'EXT:air_table/Resources/Public/Css/yellow.css',
			'EXT:air_table/Resources/Public/Css/example.css',
		),
		'TYPO3_importcss_selector_filter' => 'rte_prefix_',
		'TYPO3_block_formats' => 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre;Blockquote=blockquote;Div=div',
		'TYPO3_font_formats' => 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;AkrutiKndPadmini=Akpdmi-n',
		'TYPO3_fontsize_formats' => '8pt 10pt 12pt 14pt 18pt 24pt 36pt', // параметр не заработл (tinymce bug?)!
		'TYPO3_indentation' => '30px',
		'TYPO3_style_formats' => array(
			'Link (example)' => array(
				array('Link 1', 'selector', 'a', 'test_1'),
				array('Link 2', 'selector', 'a', 'test_2'),
				array('Link 3', 'selector', 'a', 'test_3'),
				array('Link 4', 'selector', 'a', '', 'color: green'),
				array('Link 5', 'selector', 'a', '', 'border: green 1px solid'),
			),
			'Image (example)' => array(
				array('Image Left', 'selector', 'img', 'alignLeft', ''),
				array('Image Right', 'selector', 'img', 'alignRight', ''),
				array('Image marginTop-15', 'selector', 'img', 'mt-15', ''),
				array('Image marginRight-15', 'selector', 'img', 'mr-15', ''),
				array('Image marginBotton-15', 'selector', 'img', 'mb-15', ''),
				array('Image marginLeft-15', 'selector', 'img', 'ml-15', ''),
			),
			'Other (example)' => array(
				array('Strong 8px (red)', 'inline', 'strong', '', 'font-size: 8px; color: red'),
				array('Strong 14px (blue)', 'inline', 'strong', '', 'font-size: 14px; color: blue'),
				array('Strong 22px (green)', 'inline', 'strong', '', 'font-size: 22px; color: green')
			),
		),
		// Если запись выключена, то ссылка на нее не создается!
		'TYPO3_linkHandler' => array(
			'ext_name_link_1' => ['model'=>'air_table\\models\\Blog','label'=>'Запись блог (вариант 1)', 'func'=>function($content, array $conf){
				return \Typo3Helpers::UrlAction('PAGE_ID','CONTROLLER_NAME','ACTION_NAME',['record_id'=>$conf['record_id']]);
			}],
			'ext_name_link_2' => ['model'=>'air_table\\models\\Tag','label'=>'Запись тег (вариант 2)', 'func'=>function($content, array $conf){
				return \Typo3Helpers::UrlAction('PAGE_ID','CONTROLLER_NAME','ACTION_NAME',['record_id'=>$conf['record_id']]);
			}],
		)
	),
);
?>