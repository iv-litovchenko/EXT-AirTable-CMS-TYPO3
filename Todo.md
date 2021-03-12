```
================
Общее 
================
1 Проверить ForeignWhere и другие аналогичные функции ENUM, SWITCHER, REL

================
VHS
================
А) RegisterDefaultArgs - https://gist.github.com/tal512/5f7cf641a9fac1c38806cb6be7cbfc5a
B) название с маленьких букв в шаблнах и без префикса EXT?
C) $TYPO3 вместо inizializationArgumens
D) if (TYPO3_AJAX_MODE === true) if (TYPO3_EDIT_MODE === true)

================
Формы
================
1 Mail https://github.com/georgringer/templatedMail
2 Чистый валидатор вместо ModelDynamic? Validator::make...
public function actionSearch($name, $email)
{
    $model = new DynamicModel(compact('name', 'email'));
    $model->addRule(['name', 'email'], 'string', ['max' => 128])
        ->addRule('email', 'email')
        ->validate();

    if ($model->hasErrors()) {
        // валидация завершилась с ошибкой
    } else {
        // Валидация успешно выполнена
    }
}


3 CSS typo3-messages. message-error на TS
4 Смтрти: Ваше имя Some Errors related to something (есть идея добавить параметр в <f:formI>
fErrorViewHelper && IfPropertyHasErrorViewHelper
https://github.com/cabservicesag/cabag_extbase/blob/master/Classes/ViewHelpers/IfErrorViewHelper.php
https://github.com/koninklijke-collective/koning_library/blob/master/Classes/ViewHelper/Form/IfPropertyHasErrorViewHelper.php

5 Переименовать кастомную функцию validatora (придумать префикс для регистрации)
```
