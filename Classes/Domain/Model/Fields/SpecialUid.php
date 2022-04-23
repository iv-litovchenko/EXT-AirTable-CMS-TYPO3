<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\InputNumber;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialUid extends Input
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: ID',
		'_propertyAnnotations' => [
			'displayCond' => 'mixed',
			'size' => 'string',
			'max' => 'string',
		]
	];
	
	// listController func
	public static function listControllerHtmlTd(&$obj, $table, $field, $config, $row, &$uriBuilder){
		// Access
		$accessOverlay = '';
		if(BaseUtility::BeUserAccessTableModify($table) == false){
			$accessOverlay = $obj->iconFactory->getIcon('apps-pagetree-drag-place-denied', \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
		}
							
		if($row[$GLOBALS['TCA'][$table]['ctrl']['delete']] == 1) {
			$icon = $obj->getIconForRecord($table, $row, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
			$content = '
			<td nowrap style="vertical-align: top;">
				'.$icon.' '.$row['uid'].'</span>
			</td>';
		} else {
			$icon = $obj->iconFactory->getIconForRecord($table, $row, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
				'record_edit', [
					'edit['.$table.']['.$row['uid'].']' => 'edit',
					'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
				]
			);
			$content = '
			<td nowrap style="vertical-align: top;">
				<a href="'.$backendLink.'">
					'.$accessOverlay.' '.$icon.' '.$row['uid'].'
				</a>
			</td>';
		}
		return $content;
	}
}