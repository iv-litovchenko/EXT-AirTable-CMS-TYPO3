<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Flag;
use Litovchenko\AirTable\Utility\BaseUtility;

class FlagDisabled extends Flag
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Служебное',
		'_propertyAnnotations' => [
			'items' => 'userFunc:items',
		]
	];
	
	// listController func
	public static function listControllerHtmlTd(&$obj, $table, $field, $config, $row, &$uriBuilder){
		$deleted = $GLOBALS['TCA'][$table]['ctrl']['delete'];
		if($row[$deleted] == 1){
			$color = '#dc3232'; // красный
			$label = 'Удален в корзину';
		}elseif($row[$field] == 1){
			$color = '#ee7c1b'; // желтый
			$label = 'Выключен';
		}else{
			// $color = '#1e8cbe'; // синий
			// $color = '#888'; // серый
			$color = '#7ad03a'; // зеленый
			$label = 'Активен';
		}
		$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
			'record_edit', [
				'columnsOnly' => $field,
				'edit['.$table.']['.$row['uid'].']' => 'edit',
				'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
			]
		);
		return '<td nowrap style="vertical-align: top;">
			<div style="display: inline-block !important; background-color: '.$color.'; width: 12px !important; height: 12px !important; border-radius: 50% !important;"></div>
			<span class=""><a href="'.$backendLink.'" style=""><!--[0/1]--> '.$label.'</a></span>
		</td>';
	}
}
