```
---------
Общее
---------
* Убрать thisIs
* Hooks авторегистрация
* Проверить foreignWhere и другие аналогичные функиии ENUM, SWITCHER, REL
* PageIdContent
* https://gist.github.com/mawo/f3a49058c3f4fb666c5162d8b77f1ceb#file-contentpostprocesshook-php-L2
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
* Нужно ли для существущий моделей типа страница, контент, пользователи делать отдельный массив вместо "dataFields"?
* Доработать проверки для связей...
* Убрать сложные префиксы scope, globalScope, comments(), user_rules_custom (придумать префикс для регистрации правил)
// C) Relationship (user function register)
// $rows = NewTable::with('userClients')->get();
public function userClients() {
	return $this->hasMany(Client::class);
    return $this->refProvider('proptblref_clients'); // Rel_1To1, Rel_1ToM, Rel_MTo1...
}
* Проверить все поля в т.ч. в стандартных моделях на соответствие новых требованиячм
* ForeignKey - доработать "Связь на самого себя (в рамках одной таблицы) "
```
