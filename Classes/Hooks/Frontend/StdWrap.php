<?php
namespace Litovchenko\AirTable\Hooks\Frontend;

/**
 * HOOK interface for classes which hook into tslib_content and do additional stdWrap processing
 *
 */
 
class StdWrap implements \TYPO3\CMS\Frontend\ContentObject\ContentObjectStdWrapHookInterface 
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'Добавление кнопок для "Css content Styled" (аналог варианта stdWrap)',
		'description' => '',
		'onlyFrontend' => [
			'TYPO3_CONF_VARS|SC_OPTIONS|tslib/class.tslib_content.php|stdWrap'
		]
	];
	
	/**
	 * Hook for modifying $content before core's stdWrap does anything
	 *
	 * @param	string		input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
	 * @param	array		TypoScript stdWrap properties
	 * @param	tslib_cObj	parent content object
	 * @return	string		further processed $content
	 */
	public function stdWrapPreProcess($content, array $configuration, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject) 
	{
		// При условии, что это режим редактирования
		if ($GLOBALS['BE_USER']->user['uid'] > 0) {
			if($configuration['editWrapper'] == 1 && $GLOBALS['BE_USER']->uc['phptemplate_checkOption'] == 1){
				$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/';
				$info = [];
				foreach($configuration['editWrapper.'] as $k => $v){
					if($k == 'name'){
						continue;
					}
					$info[] = $k.': '.$v;
				}
				return "
					<tagWrapTtContentElement class=''>
						<!--TYPO3_NOT_SEARCH_begin-->
						<tagEditIcon_wrap class='phptemplate_tagEditIcon_wrap_absolute'>
						<tagEditIcon class='phptemplate_tagEditIcon' style='border: black 1px solid;'>
						<nobr>
							<tagEditIconA class='tagEditIconA'>
							<tagEditIconImg class='tagEditIconImg' style='background-image: url(".$srcAdmPath."isExtbaseObject.svg);'></tagEditIconImg>
							</tagEditIconA>Extbase: ".str_replace('###',$parentObject->data['uid'],$configuration['editWrapper.']['name'])."&nbsp;
							<tagEditIconSpan class='phptemplate_tagEditIconSpan hoverInfoNewLine'>
								".implode("<br />",$info)."
							</tagEditIconSpan>
						</nobr>
						</tagEditIcon>
						</tagEditIcon_wrap>
						<tagEditIcon_br style='clear: left;'></tagEditIcon_br>
						<!--TYPO3_NOT_SEARCH_end-->
						<!--
						<tagWrapTtContentElement_dashed_Top		class='tagWrapTtContentElement_dashed_Top'></tagWrapTtContentElement_dashed_Top>
						<tagWrapTtContentElement_dashed_Right	class='tagWrapTtContentElement_dashed_Right'></tagWrapTtContentElement_dashed_Right>
						<tagWrapTtContentElement_dashed_Bottom	class='tagWrapTtContentElement_dashed_Bottom'></tagWrapTtContentElement_dashed_Bottom>
						<tagWrapTtContentElement_dashed_Left	class='tagWrapTtContentElement_dashed_Left'></tagWrapTtContentElement_dashed_Left>
						-->
						" . $content . "
					</tagWrapTtContentElement>
				";
				
			}elseif($configuration['editPanel'] == 1 && $configuration['editPanel.']['tableName'] == "tt_content" && $GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2){
				$editIcon = new \Litovchenko\AirTable\ViewHelpers\EditIconAbsViewHelper;
				$editIcon->model = 'Litovchenko\AirTable\Domain\Model\Content\TtContent';
				$editIcon->title = '';
				$editIcon->recordId = $parentObject->data['uid'];
				$editIcon->panelType = 'editRecord_tt_content';
				$editIcon->copyFieldsForNewRecord = [
					'colPos',
					// 'foreign_table',
					// 'foreign_field',
					// 'foreign_uid',
				];
				if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('gridelements')){
					$editIcon->copyFieldsForNewRecord[] = 'tx_gridelements_container';
					$editIcon->copyFieldsForNewRecord[] = 'tx_gridelements_columns';
				}
				$editIconContent = $editIcon->processOutput();
				
				$moveIcon = new \Litovchenko\AirTable\ViewHelpers\EditIconCenterViewHelper;
				$moveIcon->model = 'Litovchenko\AirTable\Domain\Model\Content\TtContent';
				$moveIcon->recordId = $parentObject->data['uid'];
				$moveIcon->title = 'Переместить сюда';
				$moveIcon->panelType = 'moveRecord_tt_content';
				$moveIcon->hideHoverInfo = 1;
				$moveIcon->hoverInfoNewLine = 1;
				$moveIcon->changeFieldsForMoveRecord = [
					'uid' 						=> $GLOBALS['BE_USER']->uc['isMoveRecord_tt_content_varible']['uid'], // это Id перемещяемой записи
					'uidAfter' 					=> '-'.$parentObject->data['uid'], // это Id записи после которой разместить
					'pid' 						=> $parentObject->data['pid'],
					'colPos' 					=> $parentObject->data['colPos'],
					// 'foreign_table' 			=> $parentObject->data['foreign_table'],
					// 'foreign_field' 			=> $parentObject->data['foreign_field'],
					// 'foreign_uid'			=> $parentObject->data['foreign_uid'],
				];
				if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('gridelements')){
					$moveIcon->copyFieldsForNewRecord['tx_gridelements_container'] = $parentObject->data['tx_gridelements_container'];
					$moveIcon->copyFieldsForNewRecord['tx_gridelements_container'] = $parentObject->data['tx_gridelements_columns'];
				}
				$moveIconContent = $moveIcon->processOutput();
				
				$newIconContent = '';
				$eIdAjaxContentById = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('eIdAjaxContentById');
				if(empty($eIdAjaxContentById))
				{
					$newIcon = new \Litovchenko\AirTable\ViewHelpers\NewIconCenterViewHelper;
					$newIcon->model = 'Litovchenko\AirTable\Domain\Model\Content\TtContent';
					$newIcon->title = '';
					$newIcon->panelType = 'newRecord_tt_content';
					$newIcon->hideHoverInfo = 0;
					$newIcon->hoverInfoNewLine = 0;
					$newIcon->defaultFieldsForNewRecord = [
						'uid' 						=> '-'.$parentObject->data['uid'], // это Id записи после которой разместить
						'pid' 						=> $parentObject->data['pid'],
						'colPos' 					=> $parentObject->data['colPos'],
						// 'foreign_table' 			=> $parentObject->data['foreign_table'],
						// 'foreign_field' 			=> $parentObject->data['foreign_field'],
						// 'foreign_uid'			=> $parentObject->data['foreign_uid'],
					];
					if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('gridelements')){
						$newIcon->copyFieldsForNewRecord['tx_gridelements_container'] = $parentObject->data['tx_gridelements_container'];
						$newIcon->copyFieldsForNewRecord['tx_gridelements_container'] = $parentObject->data['tx_gridelements_columns'];
					}
					$newIconContent .= "<tagWrapTtContentElementAjax id='c".$parentObject->data['uid']."_newwrap'>";
					$newIconContent .= $newIcon->processOutput();
					$newIconContent .= "</tagWrapTtContentElementAjax>";
				}
				
				// $eIdAjaxContentById = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('eIdAjaxContentById');
				// if(empty($eIdAjaxContentById)){
					$cWrapOpen = "<tagWrapTtContentElementAjax id='c".$parentObject->data['uid']."_wrap'>";
					$cWrapClose = "</tagWrapTtContentElementAjax>";
				// } else {
					// $cWrapOpen = '';
					// $cWrapClose = '';
				// }
				
				// Если элемент выключен
				$hiddenOpacity = '';
				if($parentObject->data['hidden'] == 1){
					$hiddenOpacity = ' opacity: 0.5; ';
				}
				
				// Здесь делаем так, что бы небыло наложений кнопок
				if ($parentObject->data['CType'] == "shortcut" || $parentObject->data['CType'] == "list" || strstr($parentObject->data['CType'],'_gridelements_') || strstr($parentObject->data['CType'],'gridelements_pi1')) {
					return $cWrapOpen."<tagWrapTtContentElement class='tagWrapTtContentElement' style='padding-bottom: 0px; ".$hiddenOpacity."'>
								<tagWrapTtContentElement_dashed_Top		class='tagWrapTtContentElement_dashed_Top'></tagWrapTtContentElement_dashed_Top>
								<tagWrapTtContentElement_dashed_Right	class='tagWrapTtContentElement_dashed_Right'></tagWrapTtContentElement_dashed_Right>
								<tagWrapTtContentElement_dashed_Bottom	class='tagWrapTtContentElement_dashed_Bottom'></tagWrapTtContentElement_dashed_Bottom>
								<tagWrapTtContentElement_dashed_Left	class='tagWrapTtContentElement_dashed_Left'></tagWrapTtContentElement_dashed_Left>
								" . $editIconContent . $content . "
							</tagWrapTtContentElement>".$cWrapClose . $moveIconContent . $newIconContent;
				} else {
					return $cWrapOpen."<tagWrapTtContentElement class='tagWrapTtContentElement' style='".$hiddenOpacity."'>
								<tagWrapTtContentElement_dashed_Top		class='tagWrapTtContentElement_dashed_Top'></tagWrapTtContentElement_dashed_Top>
								<tagWrapTtContentElement_dashed_Right	class='tagWrapTtContentElement_dashed_Right'></tagWrapTtContentElement_dashed_Right>
								<tagWrapTtContentElement_dashed_Bottom	class='tagWrapTtContentElement_dashed_Bottom'></tagWrapTtContentElement_dashed_Bottom>
								<tagWrapTtContentElement_dashed_Left	class='tagWrapTtContentElement_dashed_Left'></tagWrapTtContentElement_dashed_Left>
								" . $editIconContent . $content . "
							</tagWrapTtContentElement>".$cWrapClose . $moveIconContent . $newIconContent;
				}
			} else {
				// $editIcon='';
				# return "<tagWrapTtContentElement class='tagWrapTtContentElement'>
				# 			<tagWrapTtContentElement_dashed_Top		class='tagWrapTtContentElement_dashed_Top'></tagWrapTtContentElement_dashed_Top>
				# 			<tagWrapTtContentElement_dashed_Right	class='tagWrapTtContentElement_dashed_Right'></tagWrapTtContentElement_dashed_Right>
				# 			<tagWrapTtContentElement_dashed_Bottom	class='tagWrapTtContentElement_dashed_Bottom'></tagWrapTtContentElement_dashed_Bottom>
				# 			<tagWrapTtContentElement_dashed_Left	class='tagWrapTtContentElement_dashed_Left'></tagWrapTtContentElement_dashed_Left>
				# 			" . $editIcon . $content . "
				# 		</tagWrapTtContentElement>";
				return $content;
			}
		} else {
			return $content;
		}
	}

	/**
	 * Hook for modifying $content after core's stdWrap has processed setContentToCurrent, setCurrent, lang, data, field, current, cObject, numRows, filelist and/or preUserFunc
	 *
	 * @param	string		input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
	 * @param	array		TypoScript stdWrap properties
	 * @param	tslib_cObj	parent content object
	 * @return	string		further processed $content
	 */
	public function stdWrapOverride($content, array $configuration, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject) {
		return $content;
	}

	/**
	 * Hook for modifying $content after core's stdWrap has processed override, preIfEmptyListNum, ifEmpty, ifBlank, listNum, trim and/or more (nested) stdWraps
	 *
	 * @param	string		input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
	 * @param	array		TypoScript "stdWrap properties".
	 * @param	tslib_cObj	parent content object
	 * @return	string		further processed $content
	 */
	public function stdWrapProcess($content, array $configuration, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject) {
		return $content;
	}

	/**
	 * Hook for modifying $content after core's stdWrap has processed anything but debug
	 *
	 * @param	string		input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
	 * @param	array		TypoScript stdWrap properties
	 * @param	tslib_cObj	parent content object
	 * @return	string		further processed $content
	 */
	public function stdWrapPostProcess($content, array $configuration, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject) {
		return $content;
	}

}
?>