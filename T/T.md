[...]
```
(TV) Название атрибутов attrubutes.sheet1 attr_ префикс...
Структура папок и контроллеров элементов контента и страниц (как ее оставляем)... + Не забудь проверить Link router

Как в N
  Бесконечные - это контейнер - как его описать
  Разрешенне дочерние элементы для вложенных (по типу формы) - active для Flex-лампочка.

Сопоставление моих колонок + CHECKBOX, FLAG, SWITCH
2 дополнительных колонки - особый тип связей - выбор страницы (field.tree), выбор категории sysCategory (field.tree.category)
Картинки FLEX

 Далее:
 ---
 https://phpclub.ru/talk/threads/%D0%9D%D0%B0%D1%83%D1%87%D0%BD%D0%BE%D0%B5-%D0%BD%D0%B0%D0%B7%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5-%D1%81%D0%B2%D1%8F%D0%B7%D0%B8-m-m-%D0%BD%D0%BE-%D0%B1%D0%B5%D0%B7-%D0%BF%D1%80%D0%BE%D0%BC%D0%B5%D0%B6%D1%83%D1%82%D0%BE%D1%87%D0%BD%D0%BE%D0%B9-%D1%82%D0%B0%D0%B1%D0%BB%D0%B8%D1%86%D1%8B.87415/
 Посмотри Backend-модулинг
 Шоркоды переделать
 Остановился на разработке каталога (пагинация и хлебныекрошки)
 Info Свойства это внутренние, атрибуты внешние...

1) Product provider (Опыт магазинов - продукты проанализировать)...
2) Выборка with + keyBy (вместо select)
3) Eav-связи
4) Общие атрибуты
5) Ограничения для категорий
6) PostBuilConfiguration
7) FlexForm insert, update + mutator (setPiFlexForm).



---
https://github.com/FluidTYPO3/documentation/blob/rewrite/3.Templating/3.1.ProviderExtension/3.1.5.ConfigurationFiles.md
https://github.com/FluidTYPO3/documentation/blob/rewrite/3.Templating/3.1.ProviderExtension/3.1.5.ConfigurationFiles.md
https://github.com/FluidTYPO3/documentation/blob/rewrite/3.Templating/3.2.CreatingTemplateFiles/3.2.1.CommonFileStructure.md

https://jsonformatter.org/php-formatter
Хороший префикс для рашсирений site_package/
Todo-Для страниц и элементов содержимого добавить вложенность... Нужно будет писать исправленный путь в "AnnotationRegistrationExtTables" к файлу Index.php $provider->setTemplatePathAndFilename('EXT:'.$extensionKey.'/Resources/Private/Templates/Pages/'.$nameclassWithoutControllerPostfix.'/Index.html');
