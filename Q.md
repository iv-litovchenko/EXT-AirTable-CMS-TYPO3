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
$recordId = 7;
$rowsFirst = NewTable::recSelect('first', $recordId);
$rowsCount = NewTable::recSelect('count'); // count
$rowsGet = NewTable::recSelect('get'); // all
$method = NewTable::recSelect('exists', $recordId); // count, max, min, avg, sum, exists (if), doesntExist (if)
$obj = NewTable::recSelect('obj', [])->...->get(); // return obj (to create subqueries)

$limit = 10;
$rowsResult = NewTable::recSelect('count,get', function ($q) use ($limit) { 
    $q->limit($limit); 
});

print "Count: " . $rowsResult['count'] . "<hr />";
foreach ($rowsResult['get'] as $row){
    print $row['title'] . " // ";
    print $row['[relname]_row(s)_func']['title'] . "<br />";
}

$recordId = 18;
$is = NewTable::recIsDeleted($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
if($is === true) {
    echo 'Yes';
} else {
    echo 'No';
}

$recordId = 18;
$is = NewTable::recIsDisabled($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\Disabled;
if($is === true) {
    echo 'Yes';
} else {
    echo 'No';
}

$recordId = 18;
$is = NewTable::recIsPublished($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\DateStart and DateEnd;
if($is === true) {
    echo 'Yes';
} else {
    echo 'No';
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
// ModelName::recUpdate($recordId, $data); // return flag
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
// ModelName::recDelete($recordId, $destroy); // return flag
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



// Todo
// 1 custom relation function getCounty()
// 2 custom static fanction GetById (recSelect userFunc)
// 3 custom user global scope
// 4 custom user local sope


