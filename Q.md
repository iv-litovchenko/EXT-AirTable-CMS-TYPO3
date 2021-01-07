## Database queries - 4 simple functions, functions for creating relationships between tables. Eloquent ORM (Laravel)

```php

// Select -> with Global Scope: FlagDeleted, FlagDisabled, DateStart, DateEnd
$pageId = 177;
$row = ExampleTable::recSelect($pageId);
var_export($row);

// Insert
$data = [];
$data['title'] = '-- NEW PAGE --';
$insertId = \Litovchenko\AirTable\Domain\Model\Content\Pages::recInsert($data);

// Update
$pageId = 177;
$data = [];
$data['title'] = '-- TITLE --';
\Litovchenko\AirTable\Domain\Model\Content\Pages::recUpdate($pageId, $data); // ->withoutGlobalScopes()!!!

// Delete
$pageId = 177;
\Litovchenko\AirTable\Domain\Model\Content\Pages::recDelete($pageId); // ->withoutGlobalScopes()!!!

		

```
		// Выбрать записи (кол-во)
		$rowsCount = ExampleTable::recSelect(null,'count'); // print $rowsCount;
		
		// Выбрать записи (v1)
		$limit = 8;
		$rows = ExampleTable::recSelect(function($q) use ($limit){
			#$q->withoutGlobalScope('TestScope');
			#$q->withoutGlobalScopes();
			#$q->select('uid','title');
			#$q->where('field', '=', 1)
			#$q->orWhere('field', '>=', 100)
			$q->orderByDesc('title');
			$q->limit($limit);
			$q->offset(1);
			$q->with('exampletable1_row_func');
			#$q->with();
			#$q->has('comments');
			#$q->whereHas('comments', function ($query) { $query->where('content', 'like', 'foo%'); }
		});
		
		foreach ($rows as $row){
			print $row['title'] . " // ";
			print $row['exampletable1_row_func']['title'] . "<br />";
		}
		

		
		ExampleTable::refAttach('exampletable1_row_func',177,2);
		ExampleTable::refDetach('exampletable1_row_func',177,2);
		ExampleTable::refCollection('exampletable1_row_func',177);
		
		
		

    /**
     * Пользовательские значения для полей типа Switch, Enum
     * Возможно использовать выборку из БД
     * @AirTable\Field\ItemsProcFunc:<Litovchenko\AirTable\Domain\Model\Tests\ExampleTable->doItems>
     */
    public static function doItems($config)
    {
        $itemList = [];
        $config['items'][] = [100, 'New item 100'];
        $config['items'][] = [200, 'New item 200'];
        $config['items'][] = [300, 'New item 300'];
        return $config;
    }
