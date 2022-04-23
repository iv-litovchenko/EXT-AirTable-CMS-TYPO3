<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "###EXTKEY###".
 *
 * Auto generated ###TIME###
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => '###NAME###',
  'description' => '###DESCRIPTION###',
  'category' => 'distribution',
  'version' => '0.0.1',
  'state' => 'alpha',
  'uploadfolder' => true,
  'clearcacheonload' => true,
  'constructor' => true,
  'author' => '###AUTHOR###',
  'author_email' => '###EMAIL###',
  'author_company' => '',
  'constraints' => array (
    'depends' => array (
      'typo3' => '9.0.0-10.9.99',
      'extbase' => '',
      'fluid' => '',
      'flux' => '',
      'vhs' => '',
      'air_table' => '',
    ),
    'conflicts' => array (),
    'suggests' => array (),
  ),
  'autoload' => array (
    'psr-4' => array (
      '###NAMESPACE_1###\\###NAMESPACE_2###\\' => 'Classes'
    )
  )
);

