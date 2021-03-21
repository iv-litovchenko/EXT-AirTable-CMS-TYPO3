```
Call to undefined relationship [customValues] on model [Litovchenko\AirTable\Domain\Model\Eav\SysAttribute]. \
 EAV & DataType, Data - Досравнить оставшиеся TCA typo3conf/ext/air_table/Classes/Domain/Model/Content/_TCA-OLD\
 PageIdContent
Шорткоды\
https://www.slideshare.net/Sebobo/improving-editors-lives-with-neos-cms

ПОсмотри как у M

1) на EAV
+ Используя recSelectSafe() 1.1 Баг (пересмотреть не работает ext_tabels.sql)
1.2. Сущности - pages, tt_conent (3 шт), data
1.3. Общие атрибуты?
1.4. Ограничения для категорий
1.5. Flexform редактирвоание свойства (attr_conf)
1.7. PostBuildConfiguration() для атрибутов
2) на EditIconOnlyHover для страниц
3) DataProccesing для атрибутов что бы можно было в TS - извлечь! См. grid_for_gridelements_2.1.0(1)

EAV связи возможно ли это?
EAV поля дубликаты возможно ли это?

Может поможет для Flexform
https://www.medienreaktor.de/blog/dynamische-backend-formulare-in-typo3-mit-flux
https://www.medienreaktor.de/blog/dynamic-backend-forms-in-typo3-using-flux

Конфигурация сайта:
https://t3terminal.com/blog/typo3-site-configuration/
