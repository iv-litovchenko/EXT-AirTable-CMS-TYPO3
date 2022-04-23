<?php
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('yii2').'pi1/components/Typo3Helpers.php');
class tx_yii2_tinymce_parsehtml {

	/**
	 * The main method of the Plugin.
	 *
	 * @param string $content The Plugin content
	 * @param array $conf The Plugin configuration
	 */
	function render($content, array $conf) {
		# print "<pre>";
		# print "<h1>parseFunc</h1>";
		# print_r($GLOBALS['TSFE']->tmpl->setup['lib.']['parseFunc.']);
		# print "<hr>";
		# print "<h1>parseFunc_RTE</h1>";
		# print_r($GLOBALS['TSFE']->tmpl->setup['lib.']['parseFunc_RTE.']);
		# exit();
		$returnContent = '';
		switch($conf['function']){
			case 'linkHandler':
				if(file_exists(PATH_site.'typo3conf/TinyMceConfiguration.php')){
					$linkHandlerKey = $conf['key'];
					$TinyMceConfiguration = require(PATH_site.'typo3conf/TinyMceConfiguration.php');
					$linkHandlerConf = $TinyMceConfiguration[0]['TYPO3_linkHandler'][$linkHandlerKey];
					
					$record_id = explode('uid=', $content);
					$conf['record_id'] = rtrim($record_id[1],'"');
					$func = $linkHandlerConf['func'];
					$returnContent = $func($content,$conf);
				}
			break;
			case 'isExternalImage':
				$returnContent = $this->isExternalImage($content, $conf); 
			break;
			case 'attrManipulation':
				$returnContent = $this->attrManipulation($content, $conf); 
			break;
			case 'getAttrParam':
				$returnContent = $this->getAttrParam($content, $conf); 
			break;
		}
		return $returnContent; // здесь возвращяем результат на прямую!
		# print_r($conf);
		# exit();
	}

			// For <A> tag function
		function isNotAnchor($content,$conf) {
			return preg_match('/\s*href\s*=\s*"[^"]+"\s*/i', $content) ? 1 : 0;
		}
		
			// For <IMT> tag function
		function isExternalImage($content,$conf) {
			$src = $this->cObj->parameters['src']; 
			
			// Проверяем, вншенее ли изображение?
			$pUrl = parse_url($src);
			if(!empty($pUrl['scheme'])){
				return 'YES';
			} else {
				return 'NO';
			}
			// print_r($this->cObj->parameters['src']);
			// exit();
			// print_r($conf);
			// return 10;
		}
		
		/*
			Функция производит манимулирование с атрибуатми тэга
			- список разрешенных атрибутов
			- список разрешенных значений атрибута
			  также required - если указанное значение не найдено в атрибуте, оно добавляется в атрбитут
		*/
		function attrManipulation($content, $conf) {
			if (!empty($content)) {
				
				// Вариант 1. - у нас есть тэг и контента <TABLE ...>...
				// Вариант 2. - у нас есть тэг <IMG ...>
				// Вариант 3. - у нас переданы только параметры без тэга (HREF="..." TARGET="...")
				if (substr_count($content, "<") >= 2){
					// return "ТАБЛИЦА";
					preg_match_all("/<([a-z]+)(.*?)>/i", $content, $reg);
					$attrString = $this->_attrManipulation($reg[2][0], $conf, 1); // отдаем атрибуты первого тэга
					$content = preg_replace("/^".$reg[0][0]."/i", "<".$reg[1][0]." ". implode(" ", $attrString). ">", $content);
					
						// Таже для данных типов может быть создана обертка
					// от этого пришлось отказаться -> externalBlocks.table.stdWrap.dataWrap = <div class="table-responsive-example"> | </div>
					if (isset($conf['wrap'])){
						$content = str_replace("|", $content, $conf['wrap']);
					}
					
					return $content;
					
				} elseif (ltrim($content[0]) == "<"){
					// return "КАРИТНКА";
					$attrString = $this->_attrManipulation($content, $conf);
					$content = preg_replace('/<([a-z]+)(.*?)>(.*?)/im','<$1 '.implode(" ", $attrString).'>', $content);
					return $content;
					
				} else {
					// return "ТОЛЬКО ПАРАМЕТРЫ";
					$attrString = $this->_attrManipulation($content, $conf);
					return " " . implode(" ", $attrString). " ";
				}
				#return htmlspecialchars($content);
				#return $content;

			}
		}
		
			// Служебная функция
		function _attrManipulation($content, $conf, $type=0){

			$pattern = '/([a-z0-9-]+)=["\']([^"\']+)["\']/is'; 
			preg_match_all($pattern, $content, $regs);
			
			# print "<pre>";
			# print_r($regs);
			# print "</pre>";
			
			if (isset($conf['fixAttrib.'])){
				foreach ($conf['fixAttrib.'] as $key => $value){
					$keyWithOutDot = str_replace(".", "", $key);
					if (!in_array($keyWithOutDot, $regs[1])){
						$regs[0][] = null;
						$regs[1][] = $keyWithOutDot;
						$regs[2][] = null;
					}	
				}
			}
			
			for($i = 0; $i < count($regs[0]); $i++) {
				if (isset($conf['allowedAttribs'])) {
					$conf['allowedAttribs'] = str_replace(",", ";", $conf['allowedAttribs']).";";
					if (!stristr($conf['allowedAttribs'], $regs[1][$i].";")){
						$regs[0][$i] = null;
						$regs[1][$i] = null;
						$regs[2][$i] = null;
					}
				}
				
				if (isset($conf['disallowAttribs'])){
					$conf['disallowAttribs'] = str_replace(",", ";", $conf['disallowAttribs']).";";
					if (stristr($conf['disallowAttribs'], $regs[1][$i].";")){
						$regs[0][$i] = null;
						$regs[1][$i] = null;
						$regs[2][$i] = null;
					}
				}

				if (isset($conf['fixAttrib.'][$regs[1][$i]."."])){
					foreach ($conf['fixAttrib.'][$regs[1][$i]."."] as $key => $value){
						switch ($key){
							case 'set':
								if (isset($value)){
									$regs[2][$i] = $value;
								}
								break;
							case 'default':
								if (trim($regs[2][$i]) == null && isset($value)){
									$regs[2][$i] = $value;
								}
								break;
							case 'fixed':
								if (!strstr($regs[2][$i], $value)){
									$regs[2][$i] = $value . " " . $regs[2][$i];
								}
								break;
							case 'list':
								if (isset($value)){
									$exp1 = explode(",", $value); 
									foreach($exp1 as $k => $v){ 
										$exp1[$k] = trim($v); 
									}
									$exp2 = explode(" ", $regs[2][$i]);
									foreach ($exp2 as $k => $v){
										if (array_search($v, $exp1)){
											$regs_2_new[] = $v;
										}
									}
									$regs[2][$i] = implode(" ", $regs_2_new);
								}
								break;
							case 'unset':
								if ($value == 1){
									$regs[2][$i] = null;
								}
								break;
						}
					}
				}
			}

				// Собариаем итоговые атрибуты
			for($i = 0; $i < count($regs[0]); $i++){
				if ($regs[1][$i] != null && trim($regs[2][$i]) != null){
					$attrString[] = $regs[1][$i]. '="' .$regs[2][$i].'"';
				}
			}
			
			return $attrString;
		
		}

	
			// Функция получает значение одного атрибута тэга
		function getAttrParam($content,$conf) {
			// $pattern = '/src=[\'"]?([^\'" >]+)[\'" >]/i';
			$pattern = '/'.$conf['AttrName'].'=[\'"]?([^\'" >]+)[\'" >]/i';
			if (preg_match($pattern, $content, $regs)){
				// Если это пути к картинкам и его необходимо проверить
				if($conf['AttrName'] == "src" && $conf['AttrSrcImage'] == 1){
					$content = $regs[1];
					if (!file_exists(PATH_site . $content) || trim($content) == NULL) {
						$content = Typo3Helpers::GetPath('EXT:yii2/pi1/res/imgRes_noImage.png');
					}

				// Если это ширинка картинки
				}elseif($conf['AttrName'] == 'width' && $conf['AttrWidthImage'] == 1){
					$content = $regs[1];
					if (!file_exists(PATH_site . $content) || trim($content) == NULL) {
						$content = Typo3Helpers::GetPath('EXT:yii2/pi1/res/imgRes_noImage.png');
					}
					$src = $this->getAttrParam($content, ['AttrName'=>'src']);
					$src = Typo3Helpers::GetPath($src, true);
					$size = getimagesize($src);
					$content = $size[0];
					 
				// Если это гиперссылка
				}elseif($conf['AttrName'] == "href"){
					// $content = htmlspecialchars_decode($regs[1]);
					$content = $regs[1];
				} else {
					$content = $regs[1];
				}
				return $content;
			}
			# return $regs[1];
			# print "<Pre>";
			# print $content;
			# print_r($regs);
			# print "</pre>";
		}
}

?>