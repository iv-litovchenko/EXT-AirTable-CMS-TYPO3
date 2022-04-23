<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractFieldRelelation;
use Litovchenko\AirTable\Utility\BaseUtility;

// Базовый класс для обратной (двунаправленной) связи между таблицами
abstract class AbstractFieldRelelationInverse extends AbstractFieldRelelation
{
	const REQPREFIXCURRENTFIELD = 'proprefinv_';
    const REQPREFIXCURRENTFIELDATTR = 'attrrefinv_';
	const REQPREFIXFOREIGNFIELD = 'propref_';
    const REQPREFIXFOREIGNCURRENTFIELDATTR = 'attrref_';
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
        return [
			$table=>[
				$field => 'int(11) DEFAULT \'0\' NOT NULL'
			]
		];
    }
	
    /**
     * @modify array $GLOBALS['TCA']
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
	}

}