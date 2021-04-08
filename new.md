```
// Todo
// Бесконечные
// Разрешенные дочерние элементы для вложенных (по типу формы)?

1) Все элементы идут от  extends \FluidTYPO3\Flux\Controller\AbstractPageController
2) Все элементы идут от  extends \FluidTYPO3\Flux\Controller\AbstractFluxController
3) Дейтвие по умолчанию default()

$this->settings
$this->data

$fluxSyntax = 'short';
$fluxGrids = [
			'Row|One' => [
				'Column|1|Auto',
				'Column|2|Auto',
				'Column|3|Auto',
				'Column|4|Auto|RowSpan:2',
			],
			'Row|Two' => [
				'Column|6|Auto|ColSpan:2',
				'Column|7|Auto'
			],
		];
    
		'fluxFields' 	=> [
            'Sheet|sheet1|Имя|Описание' => [
				'Input|attr_field1|Имя1|req:1',
				'Input|attr_field2|Имя2',
				'Input|attr_field3|Имя3',
				'Section|phones|Секция' => [
					'SectionObject|sec1|Телефон' => [
						'Input|attr_field1a|ИмяА|req:1',
						'Input|attr_field2b|ИмяБ',
						'Input|attr_field3c|ИмяВ',
					],
					'SectionObject|sec2|Телефон2' => [
						'Input|attr_field1a|ИмяА|req:1',
						'Input|attr_field2b|ИмяБ',
						'Input|attr_field3c|ИмяВ',
					],
					'SectionObject|sec3|Телефон3' => [
						'Input|attr_field1a|ИмяА|req:1',
						'Input|attr_field2b|ИмяБ',
						'Input|attr_field3c|ИмяВ',
					]
				]
			],
            'Sheet|sheet2|Имя2|Описание' => [
				'Input|attr_field1a|ИмяА|req:1',
				'Input|attr_field2b|ИмяБ',
				'Input|attr_field3c|ИмяВ',
			],
			'Input|field1|Имя|req(1)',
			'Input|field2|Имя2',
			'Input|field3|Имя3'
		]
