########################################################################################################
# See FILE:EXT:fluid_styled_content/Configuration/TypoScript/Helper/ParseFunc.txt
# See /typo3/sysext/css_styled_content/static/setup.txt
########################################################################################################

--------------------------------------------------------------------------------------------------------------------
Описание функции парсинга атрибутов "postUserFunc=" для "lib.parseFunc_RTE"
--------------------------------------------------------------------------------------------------------------------
# https://gist.github.com/ogrosko/5126ebe7249066b26007b46970327475
# https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/Functions/HtmlparserTags.html
#	wrap | Создать обертку для тэга (только для блочных элементов)
#	allowedAttribs	| Разрешенные атрибуты для тэга
#	disallowAttribs	| Запрещенные атрибуты для тэга
#	fixAttrib | Точечная настрока значений атрибутов тэга
#		set (=value) - принудительная установка значения атрибута
#		default (=value) - значение по умолчанию, если пусто
#		fixed (=value) - фиксированное значение (добавляется всегда в начало)
#		list (=value1, value2 , value3) - разрешенные значений
#		unset (=1) - удалить атрибут
#

# lib.parseTarget >
# lib.parseFunc_RTE >
# lib.parseFunc >
# styles.content.parseFunc >
# includeLibs.tx_yii2_tinymce_parsehtml = EXT:yii2/pi1/class.tx_yii2_tinymce_parsehtml.php

	##############################################
	#
	#	TAG "TABLE" (example)
	#	Add wrap div with css-class responsive
	#
	##############################################

		lib.parseFunc_RTE {
				
			externalBlocks.table.stdWrap.postUserFunc = tx_yii2_tinymce_parsehtml->render
			externalBlocks.table.stdWrap.postUserFunc.function = attrManipulation
			externalBlocks.table.stdWrap.postUserFunc {
				wrap = <div class="table-responsive"> | </div>
				allowedAttribs = class, align, width, height, style, cellpadding, cellspacing, border
				fixAttrib {
					# style.unset 			= 0
					# width.unset 			= 0
					# height.unset			= 0
					# cellpadding.unset		= 1
					# cellspacing.unset 	= 1
					# border.unset			= 1
					# style.fixed			= border: yellow 5px solid;
					class {
					
						# 1. Список разрешенных значений
						# list = table-striped, zebra, szebra2, zebra3
						
						# 2. Значение по умолчанию
						# set = zebra3
			
						# 3. Обязательное значение (если не найдено, добавляется)
						fixed = table-striped zebra

					}
				}	
			}
		}


	##############################################
	#
	#	TAG "BLOCKQUOTE" (example)
	#
	##############################################

		# lib.parseFunc_RTE {
		# 
		#	externalBlocks.blockquote.stdWrap.preUserFunc = tx_yii2_tinymce_parsehtml->render
		#	externalBlocks.blockquote.stdWrap.preUserFunc.function = attrManipulation
		#	externalBlocks.blockquote.stdWrap.preUserFunc.allowedAttribs = class
		# 
		# }
		
	##############################################
	#
	#	TAG "DIV" (example)
	#
	##############################################

		# lib.parseFunc_RTE {
		# 
		# 	externalBlocks.div.stdWrap.preUserFunc = tx_yii2_tinymce_parsehtml->render
		# 	externalBlocks.div.stdWrap.preUserFunc.function = attrManipulation
		# 	externalBlocks.div.stdWrap.preUserFunc.allowedAttribs = class, align, style
		# 
		# }
		
	##############################################
	#
	#	TAG "P" (example)
	#
	##############################################

		# lib.parseFunc_RTE {
		# 
		# 	externalBlocks.p.stdWrap.preUserFunc = tx_yii2_tinymce_parsehtml->render
		# 	externalBlocks.p.stdWrap.preUserFunc.function = attrManipulation
		# 	externalBlocks.p.stdWrap.preUserFunc.allowedAttribs = class, align, style
		# 
		# }


	##############################################
	# function to allow custom img attributes
	##############################################
	#
	#	TAG "IMG"
	#	Add css-clss responsive
	#
	##############################################
	
		lib.parseFunc {

			// Т.к. может быть внешнее изображение, для него ничего не меняем!
			tags.img = CASE
			tags.img {
				key.cObject = USER
				key.cObject {
					userFunc = tx_yii2_tinymce_parsehtml->isExternalImage
				}
				
				// Внешняя картинка, оставляем все как есть
				YES = COA
				YES.10 = TEXT
				YES.10.value = <img
				
					YES.20 = TEXT
					YES.20.data = parameters : allParams
					YES.20.postUserFunc = tx_yii2_tinymce_parsehtml->render
					YES.20.postUserFunc.function = attrManipulation
					YES.20.postUserFunc.fixAttrib.style.fixed = max-width: 100%;
				
				YES.30 = TEXT
				YES.30.value = >

				// NO = TEXT
				// NO.value = NO
				NO = IMAGE
				NO {
					file.import.data = parameters : allParams
					file.import.postUserFunc = tx_yii2_tinymce_parsehtml->render
					file.import.postUserFunc.function = getAttrParam
					file.import.postUserFunc.AttrName = src
					file.import.postUserFunc.AttrSrcImage = 1
						
					file.width.data = parameters : allParams
					file.width.postUserFunc = tx_yii2_tinymce_parsehtml->render
					file.width.postUserFunc.function = getAttrParam
					file.width.postUserFunc.AttrName = width
					file.width.postUserFunc.AttrWidthImage = 1
						
					file.maxW = 2500
					file.maxH = 5000c
					
					stdWrap.postUserFunc = tx_yii2_tinymce_parsehtml->render
					stdWrap.postUserFunc.function = attrManipulation
					stdWrap.postUserFunc {
						allowedAttribs = src, style, width, pppp, border, height, align, class, alt, title, data-htmlarea-file-table, data-htmlarea-file-uid 
						fixAttrib {
							# width.set		= 100
							# height.unset 	= 1
							# border.unset	= 1
							# alt.set 		= [подсказка alt по умолчанию]
							# title.set 	= [подсказка title по умолчанию]
							# [example] style.set = border: 1px solid red;
							style.fixed = max-width: 100%; height: auto;
							class {
						
								# 1. Список разрешенных значений
								# list = jyrj, test-class, size-auto, left, center, right
								
								# 2. Значение по умолчанию
								# default = jyrj test-class
				
								# 3. Обязательное значение (если не найдено, добавляется)
								fixed = max-w-100 size-auto

							}
							# pppp.set		= 2332
						}
					}
				}
			}
			
			/*
			tags.img = IMAGE
			tags.img {
			
				file.import.data = parameters : allParams
				file.import.postUserFunc = tx_yii2_tinymce_parsehtml->render
				file.import.postUserFunc.function = getAttrParam
				file.import.postUserFunc.AttrName = src
				file.import.postUserFunc.AttrScrFileExistsValid = 1
					
				file.width.data = parameters : allParams
				file.width.postUserFunc = tx_yii2_tinymce_parsehtml->render
				file.width.postUserFunc.function = getAttrParam
				file.width.postUserFunc.AttrName = width
					
				file.maxW = 2500
				file.maxH = 5000c
					
				params.data = parameters : allParams
				params.postUserFunc = tx_yii2_tinymce_parsehtml->render
				params.postUserFunc.function = attrManipulation
				params.postUserFunc.disallowAttribs = width, height, src
				
				stdWrap.postUserFunc = tx_yii2_tinymce_parsehtml->render
				stdWrap.postUserFunc.function = attrManipulation
				stdWrap.postUserFunc {
					allowedAttribs = src, style, width, pppp, border, height, align, class, alt, title, data-htmlarea-file-table, data-htmlarea-file-uid 
					fixAttrib {
						# width.set		= 100
						# height.unset 	= 1
						border.unset	= 1
						# alt.set 		= [подсказка alt по умолчанию]
						# title.set 	= [подсказка title по умолчанию]
						# [example] style.set = border: 1px solid red;
						class {
					
							# 1. Список разрешенных значений
							# list = jyrj, test-class, size-auto, left, center, right
							
							# 2. Значение по умолчанию
							# default = jyrj test-class
			
							# 3. Обязательное значение (если не найдено, добавляется)
							fixed = img-responsive max-w-100 size-auto

						}
						# pppp.set		= 2332
					}
				}
			}
			*/

		}

			# Add to parseFunc_RTE
			lib.parseFunc_RTE.tags.img < lib.parseFunc.tags.img
			

	##############################################
	# function to allow custom link attributes
	##############################################
	#
	#	TAG "A"
	#
	# 	Здесь немного пришлось "покомбинировать", т.к. необходимо было 
	#	решить задачу что бы тэг <IMG> - мог обрабабатываться в тэге <A>,
	# 	т.к. почему-то так он обрабатывается, а в ссылках не хотел :)!
	############################################################################
	
		lib.parseFunc.tags.a = COA
		lib.parseFunc.tags.a.stdWrap.parseFunc.tags.img < lib.parseFunc.tags.img
		lib.parseFunc.tags.a {
				
			# Parsing of A tag if not an anchor
			10 = TEXT
			10.current = 1

			# Remove empty links
			10.required = 1	
			10.typolink.parameter.data = parameters : allParams
			10.typolink.parameter.postUserFunc = tx_yii2_tinymce_parsehtml->render
			10.typolink.parameter.postUserFunc.function = getAttrParam
			10.typolink.parameter.postUserFunc.AttrName = href
			
			# 10.typolink.extTarget.data = parameters : allParams
			# 10.typolink.extTarget.postUserFunc = tx_yii2_tinymce_parsehtml->render
			# 10.typolink.extTarget.postUserFunc.function = getAttrParam
			# 10.typolink.extTarget.postUserFunc.AttrName = target
			
			10.typolink.ATagParams.data = parameters : allParams
			10.typolink.ATagParams.postUserFunc = tx_yii2_tinymce_parsehtml->render
			10.typolink.ATagParams.postUserFunc.function = attrManipulation
			10.typolink.ATagParams.postUserFunc.allowedAttribs = href, class, title, target, style
			# 10.typolink.ATagParams.postUserFunc.fixAttrib.title.set = Подсказка title по умолчанию
			# 10.typolink.ATagParams.postUserFunc.fixAttrib.style.set = color: red
			
			# [beta]
			# 10.if.isTrue.data = parameters : allParams
			# 10.if.isTrue.postUserFunc = user_tinymce_rte->isNotAnchor
				
				# Parsing of A tag if an anchor
				# 20 = TEXT
				# 20.current = 1
				# 20.dataWrap = <a {parameters : allParams}>|</a>
				# 20.if.isTrue.data = parameters : allParams
				# 20.if.isTrue.postUserFunc = user_tinymce_rte->isNotAnchor
				# 20.if.negate = 1
		}
			
			# Add to parseFunc_RTE
			lib.parseFunc_RTE.tags.a < lib.parseFunc.tags.a

			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			