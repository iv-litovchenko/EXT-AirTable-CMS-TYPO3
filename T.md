```
В доку
Свойства это внешние, атрибуты внутренние
->whereRowValues(['column1', 'column2'], '=', ['foo', 'bar']); // orWhereRowValues()
->where created_at between (new DateTime("2021-01-13"))->getTimestamp() and (new DateTime("2021-01-14"))->getTimestamp()
->forPage() $page, $perPage;
{f:if(condition: file.properties.title, then: file.properties.title, else: file.properties.name)}
{f:if(condition: item.current, then: ' active')}
Понравилась идея делать для upload files - прямоугольник
Сделать загрузку items@ для select-ов из файла /typo3conf/ext/ext/Configuration/Items/Item.txt [Список значений]
Разобраться как работает: <flux:field.controllerActions name="switchableControllerActions"> - очень актуально что бы не плодить большое кол-во плагинов одного типа группы (как новости). Способ ограничения полей смотреть в расширении News (файл "public_html\typo3conf\ext\news\Classes\Backend\FormDataProvider\NewsFlexFormManipulation.php") getSwitchableControllerActions ($extensionName, $pluginName) http://man.hubwiz.com/docset/TYPO3.docset/Contents/Resources/Documents/class_t_y_p_o3_1_1_c_m_s_1_1_extbase_1_1_configuration_1_1_frontend_configuration_manager.html

---------
https://github.com/FluidTYPO3/documentation/blob/rewrite/3.Templating/3.2.CreatingTemplateFiles/3.2.1.CommonFileStructure.md
https://github.com/FluidTYPO3/documentation/blob/rewrite/3.Templating/3.1.ProviderExtension/3.1.5.ConfigurationFiles.md
1) Шорткоды
2) Flux-капикатор
3) Pagination


---------------

https://blog.t3bootstrap.de/2020/04/typo3-overridechildtca-mittels-tsconfig/

Array
(
    [class] => 
    [attributes.attr_hellow1] => Element
    [attributes.attr_hellow2] => 
    [attributes.attr_hellow3] => 
    [TV.tv_hellow1] => Sheet 1
    [TV.tv_hellow2] => 
    [TV.tv_hellow3] => Sheet 2
    [TV.tvmedia_image] => 4
    [bild] => 1
    [settings.falimage] => 1
    [settings.sectionObjectAsClass2.60774705990f4289921309.mobile.TV.tv_hellow4] => Hellow (1) [TV.tv_hellow4] Mobile
    [settings.sectionObjectAsClass2.60774705990f4289921309.mobile.TV.tv_hellow5] => Hellow (1) [TV.tv_hellow4] Mobile 3
    [settings.sectionObjectAsClass2.6077470f67fd4317382563.landline.TV.tv_hellow4] => Hellow (1) [TV.tv_hellow4]
    [settings.sectionObjectAsClass2.6077470f67fd4317382563.landline.TV.tv_hellow5] => 
    [field1] => 
    [field2] => 
    [field3] => 
)



#
# Table structure for table 'tx_fluxcapacitor_domain_model_sheet'
#
CREATE TABLE tx_fluxcapacitor_domain_model_sheet (
      
		name varchar(255),
        sheet_label mediumtext,
		source_table varchar(255),
		source_field varchar(255),
        source_uid int(11) DEFAULT '0' NOT NULL,
        form_fields int(11) DEFAULT '0' NOT NULL,
        json_data text,

        PRIMARY KEY (uid),
        KEY parent (pid)
);

#
# Table structure for table 'tx_fluxcapacitor_domain_model_field'
#
CREATE TABLE tx_fluxcapacitor_domain_model_field (
        
        parent_field int(11) DEFAULT '0' NOT NULL,
        sheet int(11) DEFAULT '0' NOT NULL,
        field_name varchar(255),
        field_label mediumtext,
        field_type varchar(32),
        field_value text,
        field_options text,

        PRIMARY KEY (uid),
        KEY parent (pid),
        KEY parent_field (parent_field),
        KEY sheet (sheet),
        KEY field_value (field_value(32))
);


 typo3/sysext/backend/Classes/Form/FormDataProvider/TcaFlexFetch.php deleted
100644 → 0
<?php
namespace TYPO3\CMS\Backend\Form\FormDataProvider;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Resolve and flex data structure and data values.
 *
 * This is the first data provider in the chain of flex form related providers.
 */
class TcaFlexFetch implements FormDataProviderInterface
{
    /**
     * Resolve ds pointer stuff and parse both ds and dv
     *
     * @param array $result
     * @return array
     */
    public function addData(array $result)
    {
        foreach ($result['processedTca']['columns'] as $fieldName => $fieldConfig) {
            if (empty($fieldConfig['config']['type']) || $fieldConfig['config']['type'] !== 'flex') {
                continue;
            }
            $result = $this->initializeDataStructure($result, $fieldName);
            $result = $this->initializeDataValues($result, $fieldName);
            $result = $this->resolvePossibleExternalFileInDataStructure($result, $fieldName);
        }

        return $result;
    }

    /**
     * Fetch / initialize data structure.
     *
     * The sub array with different possible data structures in ['config']['ds'] is
     * resolved here, ds array contains only the one resolved data structure after this method.
     *
     * @param array $result Result array
     * @param string $fieldName Currently handled field name
     * @return array Modified result
     * @throws \UnexpectedValueException
     */
    protected function initializeDataStructure(array $result, $fieldName)
    {
        // Fetch / initialize data structure
        $dataStructureArray = BackendUtility::getFlexFormDS(
            $result['processedTca']['columns'][$fieldName]['config'],
            $result['databaseRow'],
            $result['tableName'],
            $fieldName
        );
        // If data structure can't be parsed, this is a developer error, so throw a non catchable exception
        if (!is_array($dataStructureArray)) {
            throw new \UnexpectedValueException(
                'Data structure error: ' . $dataStructureArray,
                1440506893
            );
        }
        if (!isset($dataStructureArray['meta']) || !is_array($dataStructureArray['meta'])) {
            $dataStructureArray['meta'] = [];
        }
        // This kicks one array depth:  config['ds']['matchingIdentifier'] becomes config['ds']
        $result['processedTca']['columns'][$fieldName]['config']['ds'] = $dataStructureArray;
        return $result;
    }

    /**
     * Parse / initialize value from xml string to array
     *
     * @param array $result Result array
     * @param string $fieldName Currently handled field name
     * @return array Modified result
     */
    protected function initializeDataValues(array $result, $fieldName)
    {
        if (!array_key_exists($fieldName, $result['databaseRow'])) {
            $result['databaseRow'][$fieldName] = '';
        }
        $valueArray = [];
        if (isset($result['databaseRow'][$fieldName])) {
            $valueArray = $result['databaseRow'][$fieldName];
        }
        if (!is_array($result['databaseRow'][$fieldName])) {
            $valueArray = GeneralUtility::xml2array($result['databaseRow'][$fieldName]);
        }
        if (!is_array($valueArray)) {
            $valueArray = [];
        }
        if (!isset($valueArray['data'])) {
            $valueArray['data'] = [];
        }
        if (!isset($valueArray['meta'])) {
            $valueArray['meta'] = [];
        }
        $result['databaseRow'][$fieldName] = $valueArray;
        return $result;
    }

    /**
     * Single fields can be extracted to files again. This is resolved and parsed here.
     *
     * @todo: Why is this not done in BackendUtility::getFlexFormDS() directly? If done there, the two methods
     * @todo: GeneralUtility::resolveSheetDefInDS() and GeneralUtility::resolveAllSheetsInDS() could be killed
     * @todo: since this resolving is basically the only really useful thing they actually do.
     *
     * @param array $result Result array
     * @param string $fieldName Current handle field name
     * @return array Modified item array
     */
    protected function resolvePossibleExternalFileInDataStructure(array $result, $fieldName)
    {
        $modifiedDataStructure = $result['processedTca']['columns'][$fieldName]['config']['ds'];
        if (isset($modifiedDataStructure['sheets']) && is_array($modifiedDataStructure['sheets'])) {
            foreach ($modifiedDataStructure['sheets'] as $sheetName => $sheetStructure) {
                if (!is_array($sheetStructure)) {
                    $file = GeneralUtility::getFileAbsFileName($sheetStructure);
                    if ($file && @is_file($file)) {
                        $sheetStructure = GeneralUtility::xml2array(file_get_contents($file));
                    }
                }
                $modifiedDataStructure['sheets'][$sheetName] = $sheetStructure;
            }
        }
        $result['processedTca']['columns'][$fieldName]['config']['ds'] = $modifiedDataStructure;
        return $result;
    }
}
