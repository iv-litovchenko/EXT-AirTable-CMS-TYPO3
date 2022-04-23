<?php
namespace Litovchenko\AirTable\Hooks\Backend;

class GetButtonsHook
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'Убираем лишние кнопки на форме редактирования',
		'description' => '',
		'onlyBackend' => [
			'TYPO3_CONF_VARS|SC_OPTIONS|Backend\Template\Components\ButtonBar|getButtonsHook::editModeButton'
		]
	];
	
    /**
     * @param array $params
     * @param $buttonBar
     * @return array
     */
    public function editModeButton($params, &$buttonBar)
    {
		$buttons = $params['buttons'];
		$feEdit = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('feEdit');
		if($feEdit == 1){ // if($moduleName == 'record_edit' || $moduleName == 'new_content_element'){
			// 1,4,6
			unset($buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT][1]);
			if(isset($buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT][2][1])){
				unset($buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT][2][1]);
			}
			unset($buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT][4]);
			unset($buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT][6]);
		}
        return $buttons;
    }
}
