## Database queries - 4 simple functions (Select, Insert, Update, Delete), functions for creating relationships between tables. Eloquent ORM (Laravel)
https://github.com/awes-io/repository
Пользовательский скоуп, пользовательская выборка

```php
<?php
use \Mynamespace\Myext\Domain\Model\NewTable;

////////////////////////////////////////////////////////////////////////////////////////
// Select
////////////////////////////////////////////////////////////////////////////////////////
$recordId = 15;
$rowsFirst = NewTable::recSelect('first', $recordId);
$rowsCount = NewTable::recSelect('count'); // count, max, min, avg, sum, exists, doesntExist
$rowsGet = NewTable::recSelect('get'); // All
print "<pre>";
print_r($rowsFirst);
print_r($rowsCount);
print_r($rowsGet);
print "</pre>";

$filter = [];
$filter['limit'] = 5;
$filter['offset'] = 2;
$rowsResult = NewTable::recSelect('count,get', function ($q) use ($filter)
{
    #$q->withoutGlobalScopes();
    #$q->withoutGlobalScope('FlagDeleted'); // FlagDisabled, DateStart, DateEnd
    #$q->select('uid');
    #$q->addSelect('title');
    #$q->where('field', '=', 1);
    #$q->orWhere('field', '>=', 100);
    #$q->orderByDesc('field');
    #$q->limit($filter['limit']);
    #$q->offset($filter['offset']);
    #$q->with('[relname]_row(s)_func');
    #$q->has('[relname]_row(s)_func');
    #$q->whereHas('[relname]_row(s)_func', function ($q) {
    #	$q->where('field', 'like', 'foo%');
    #});
});

print "<pre>";
print_r($rowsResult);
print "</pre>";

foreach ($rowsResult['get'] as $row)
{
    print $row['title'] . " // ";
    print $row['[relname]_row(s)_func']['title'] . "<br />";
}

////////////////////////////////////////////////////////////////////////////////////////
// Insert
////////////////////////////////////////////////////////////////////////////////////////
$data = [];
$data['title'] = '-- TITLE --';
$insertId = NewTable::recInsert($data);

////////////////////////////////////////////////////////////////////////////////////////
// Update
////////////////////////////////////////////////////////////////////////////////////////
$recordId = 7;
$data = [];
$data['title'] = '-- TITLE --';
$result = NewTable::recUpdate($recordId, $data); // ->withoutGlobalScopes()!!!
if ($result)
{
    echo 'Successfully';
}
else
{
    echo 'Not successful';
}

////////////////////////////////////////////////////////////////////////////////////////
// Delete
////////////////////////////////////////////////////////////////////////////////////////
$recordId = 7;
$result = NewTable::recDelete($recordId); // ->withoutGlobalScopes()!!!
if ($result)
{
    echo 'Successfully';
}
else
{
    echo 'Not successful';
}
```
