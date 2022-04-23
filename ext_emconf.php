<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "air_table".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
  'title' => 'AirTable',
  'description' => 'A set of tools for creating page templates, content elements, models for working with records, as well as modules for viewing, exporting and importing records in the system.',
  'category' => 'services',
  'version' => '11.0.9',
  'state' => 'beta',
  'author' => 'Ivan Litovchenko',
  'author_email' => 'iv-litovchenko@mail.ru',
  'author_company' => '',
  'constraints' => [
    'depends' => [
		'typo3' => '9.0.0-10.9.99',
		'extbase' => '',
		'fluid' => '',
		'flux' => '',
		'vhs' => ''
	],
    'conflicts' => [],
    'suggests' => [],
  ],
  'autoload' => [
    'psr-4' => [
      'Litovchenko\\AirTable\\' => 'Classes'
    ]
  ]
];