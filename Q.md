```
---------
Общее
---------
* Убрать thisIs
* Hooks авторегистрация
* Проверить foreignWhere и другие аналогичные функиии ENUM, SWITCHER, REL
* PageIdContent
* https://gist.github.com/mawo/f3a49058c3f4fb666c5162d8b77f1ceb#file-contentpostprocesshook-php-L2
* Нужно ли убрать _partials?
---------
VHS
---------
* Синтаксис регистрации аргументов
* Значене по умолчанию
$tsCode = trim('plugin.tx_projiv_randphotocontroller.settings.example_configuration_value = 123');
ExtensionManagementUtility::addTypoScript('air_table', 'setup', $tsCode, 43);
---------
Модели
---------
* Доработать проверки для связей (что будет если не задан "foreignKey" - 
> Остановился на Content и Eav
> см. "SysNote"...
> см. SysFilemounts
> см. SysFileReference
> см. SysFileMetadata
> см. SysFile
> EAV-отдельно...
* Убрать сложные префиксы scope, globalScope, comments(), user_rules_custom (придумать префикс для регистрации правил)
// C) Relationship (user function register)
// $rows = NewTable::with('userClients')->get();
public function userClients() {
	return $this->hasMany(Client::class);
    return $this->refProvider('proptblref_clients'); // Rel_1To1, Rel_1ToM, Rel_MTo1...
}
* Проверить все поля в т.ч. в стандартных моделях на соответствие новых требованиячм
* ForeignKey - доработать "Связь на самого себя (в рамках одной таблицы) "
* Проверить в Ext-моделях и существующих моделях:

    /**
     * This is an optional feature.
     * Record types similar to "doktype (pages)" and "CType (tt_content)"
     * @return array
     */
    #public static function baseRTypes()
    #{
    #    // This function is not supported for standard models!
    #    // * @AirTable\Field\Position\*:<newtab,0>
    #    $types = parent::baseRTypes();
    #    $types[100] = 'New type 100';
    #    return $types;
    #}

    /**
     * This is an optional feature.
     * Tabs for the edit form
     * @return array
     */
    #public static function baseTabs()
    #{
    #    // This function is not supported for standard models!
    #    // * @AirTable\Field\Position\*:<newtab,0>
    #    $tabs = parent::baseTabs();
    #    $tabs['newtab'] = 'NewTab (###COUNT###)';
    #    return $tabs;
    #}

```
