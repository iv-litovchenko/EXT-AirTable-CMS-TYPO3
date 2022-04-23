<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Switcher;
use Litovchenko\AirTable\Utility\BaseUtility;

class SwitcherStatus extends Switcher
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
			'itemsProcFunc' => 'string',
			'itemsModel' => 'string',
			'itemsWhere' => 'array',
		]
	];
	
	// listController func
	public static function listControllerHtmlTd(&$obj, $table, $field, $config, $row, &$uriBuilder){
		switch($row['status']){
			case -3:
				$color = '#888';
				$label = 'Удален в корзину';
			break;
			case -2:
				$color = '#1e8cbe';
				$label = 'Черновик';
			break;
			case -1:
				$color = '#ee7c1b';
				$label = 'На рассмотрении';
			break;
			case 0:
				$color = '#7ad03a';
				$label = 'Активен';
			break;
			case 1:
				$color = '#dc3232';
				$label = 'Выключен';
			break;
		}
			// $color = '#1e8cbe'; // синий
			// $color = '#888'; // серый
			#$color = '#7ad03a'; // зеленый
			#$label = 'Активен';
		$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
			'record_edit', [
				'columnsOnly' => 'status',
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
