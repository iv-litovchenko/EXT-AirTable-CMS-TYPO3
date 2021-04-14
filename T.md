```
В доку
Свойства это внешние, атрибуты внутренние
->whereRowValues(['column1', 'column2'], '=', ['foo', 'bar']); // orWhereRowValues()
->where created_at between (new DateTime("2021-01-13"))->getTimestamp() and (new DateTime("2021-01-14"))->getTimestamp()
{f:if(condition: file.properties.title, then: file.properties.title, else: file.properties.name)}
{f:if(condition: item.current, then: ' active')}
Понравилась идея делать для upload files - прямоугольник


forPage



				
				// Если нет аннотаций Flux-пропускаем - не создаем провайдер...
				// $annotationFluxFields = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FluxFields');
				// if(empty($annotationFluxFields)){
				// 	continue;
				// }

---------------

TCEFORM
#################
#### TCEFORM ####
#################
TCEFORM {
    pages {
        layout {
            removeItems = 1,2,3
        }
    }
    tt_content {
        layout {
            removeItems = 1,2,3
            disableNoMatchingValueElement = 1
            types {
                uploads {
                    removeItems = 3
                    altLabels {
                        0 = LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:uploadslayout.default
                        1 = LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:uploadslayout.icons
                        2 = LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:uploadslayout.iconsandpreview
                    }
                }
            }
        }
        header_layout {
            altLabels {
                1 = H1
                2 = H2
                3 = H3
                4 = H4
                5 = H5
            }
        }
        table_class {
            addItems {
                hover = LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:tablelayout.hover
                condensed = LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:tablelayout.condensed
            }
        }
        imageborder {
            disabled = 1
        }
        imagecols {
            removeItems = 7,8
        }
        image_zoom {
            types {
                media {
                    disabled = 1
                }
            }
        }
        imageorient {
            removeItems = 1,2,9,10,17,18
            types {
                image {
                    disabled = 1
                }
                media {
                    disabled = 1
                }
            }
        }
        accessibility_title {
            disabled = 1
        }
        // Disable imageheight and imagewidth for textpic and image
        // to avoid incorrect rendering in frontend
        imageheight.disabled = 1
        imagewidth.disabled = 1
    }
}


->whereRowValues(['column1', 'column2'], '=', ['foo', 'bar']);
