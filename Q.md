## Database queries - 4 simple functions (Select, Insert, Update, Delete), functions for creating relationships between tables. Eloquent ORM (Laravel)

```php
<?php
use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;
use Litovchenko\AirTable\Domain\Model\Content\Pages;
use Litovchenko\AirTableExamples\Domain\Model\ExampleTable;
use Mynamespace\Myext\Domain\Model\NewTable;

////////////////////////////////////////////////////////////////////////////////////////
// SELECT
// NewTable::recSelect('medthod', $filter); // return result
////////////////////////////////////////////////////////////////////////////////////////
$recordId = 15;
$rowsFirst = NewTable::recSelect('first', $recordId);
$rowsCount = NewTable::recSelect('count'); // count, max, min, avg, sum, exists, doesntExist
$rowsGet = NewTable::recSelect('get'); // All

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
// INSERT
// ModelName::recInsert($data); // return last insert id
////////////////////////////////////////////////////////////////////////////////////////
$data = [];
$data['title'] = '-- TITLE --';
$insertId = NewTable::recInsert($data);

////////////////////////////////////////////////////////////////////////////////////////
// UPDATE
// ModelName::recUpdate($id, $data); // return flag
////////////////////////////////////////////////////////////////////////////////////////
$recordId = 7;
$data = [];
$data['title'] = '-- TITLE --';
$result = NewTable::recUpdate($recordId, $data);
if ($result) {
    echo 'Successfully';
} else {
    echo 'Not successful';
}

////////////////////////////////////////////////////////////////////////////////////////
// DELETE 
// ModelName::recDelete($id, $destroy); // return flag
////////////////////////////////////////////////////////////////////////////////////////
$recordId = 7;
$destroy = true; // If use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
$result = NewTable::recDelete($recordId, $destroy);
if ($result) {
    echo 'Successfully';
} else {
    echo 'Not successful';
}







////////////////////////////////////////////////////////////////////////////////////////
// Working with relationships between tables
// ->refProvider()
////////////////////////////////////////////////////////////////////////////////////////

    #$q->with('[relname]_row(s)_func');
    #$q->has('[relname]_row(s)_func');
    #$q->whereHas('[relname]_row(s)_func', function ($q) {
    #	$q->where('field', 'like', 'foo%');
    #});

NewTable::refAttach('category_rows_func', 1, [3,4]); // $relationship, $parentId, $idsToAttach
// NewTable::refDetach('category_rows_func', 1, 4); // $relationship, $parentId, $idsToDetach
#print $allCat;


$countRecord = NewTable::recSelect('count', function ($q) use ($filter){
    $q->with('category_rows_func');
	$q->where('uid',1);
});

#print $countRecord;

$r = NewTable::where('uid','=',1)->with('category_rows_func')->get();
$r = $r->toArray();
print_r($r[0]);




isDeleted

1 rel getCounty()
2 custom func return rec
3 https://github.com/awes-io/repository

