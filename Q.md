```php
<?php
use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;
use Litovchenko\AirTable\Domain\Model\Content\Pages;
use Litovchenko\AirTableExamples\Domain\Model\ExampleTable;
use Mynamespace\Myext\Domain\Model\NewTable;

////////////////////////////////////////////////////////////////////////////////////////
// SELECT
// NewTable::recSelect('medthod', $id || $filter || $callback); // return result
////////////////////////////////////////////////////////////////////////////////////////

$recordId = 7;
$rowsFirst = NewTable::recSelect('first', $recordId);
$rowsCount = NewTable::recSelect('count'); // count
$rowsGet = NewTable::recSelect('get'); // all
$method = NewTable::recSelect('exists', $recordId); // obj, count, exists (if), doesntExist (if)
$obj = NewTable::recSelect('obj', [])->...->get(); // return obj (to create subqueries)
$dd = Pages::recSelect('obj',8)->dd(); // debugging
$dump = Pages::recSelect('obj',8)->dump(); // debugging

$limit = 10;
$rowsResult = NewTable::recSelect('count,get', function ($q) use ($limit) { 
    $q->limit($limit); 
});

print "Count: " . $rowsResult['count'] . "<hr />";
foreach ($rowsResult['get'] as $row) {
    print $row['title'] . " // ";
    print $row['[relname]_row(s)_func']['title'] . "<br />";
}

$recordId = 18;
$is = NewTable::recIsDeleted($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
if($is === true) {
    echo 'Yes';
}

$recordId = 18;
$is = NewTable::recIsDisabled($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\Disabled;
if($is === true) {
    echo 'Yes';
}

$recordId = 18;
$is = NewTable::recIsPublished($recordId); // If use \Litovchenko\AirTable\Domain\Model\Traits\DateStart and DateEnd;
if($is === true) {
    echo 'Yes';
}

$filter = [];
$filter['distinct'] = ['title'];
$filter['select'] = ['uid','title', 'uid as aliasID'];
$filter['addSelect'] = ['pid','date_create'];

$filter['whereUid'] = 1; // Dynamic
$filter['wherePid'] = 1; // Dynamic
$filter['whereTitle'] = []; // Dynamic
$filter['whereFieldName'] = []; // Dynamic

$filter['where'] = []; // orWhere, =, <, >, <=, >=, <>, !=, LIKE, NOT LIKE, BETWEEN, ILIKE
$filter['where'][] = ['uid','>=',1];
$filter['where'][] = ['uid','<=',10000];

$filter['where'] = function($q) { 
    $q->where('pid','>=',0); 
    $q->orWhere('pid','<=',0);
};

$filter['whereIn'] = ['uid',[1,2,3,4,5,6,7,8,9,10]]; // orWhereIn, whereNotIn, orWhereNotIn
$filter['whereNull'] = ['keywords']; // orWhereNull, whereNotNull, orWhereNotNull 
$filter['whereBetween'] = ['uid',[1,1000]]; // whereNotBetween
$filter['whereColumn'] = ['uid','!=','title'];
$filter['whereRaw'] = ['(uid > ? and uid < ?)', [1,1000]]; // DB::raw(1)
$filter['whereRaw'] = [];
$filter['whereRaw'][] = ["FROM_UNIXTIME(date_create, '%j') = ?", 11]; // %d -> with zero
$filter['whereRaw'][] = ["FROM_UNIXTIME(date_create, '%n') = ?", 1]; // %m -> with zero
$filter['whereRaw'][] = ["FROM_UNIXTIME(date_create, '%Y') = ?", 2021];
$filter['whereExists'] = function($q) { // ->orWhereExists(), ->whereNotExists(), ->orWhereNotExists()
    $q->select(DB::raw(1))->from('pages')->whereRaw('uid > 0'); 
};

$filter['inRandomOrder'] = false; // true
$filter['orderBy'] = [];
$filter['orderBy'][] = ['uid','desc'];
$filter['orderBy'][] = ['title','desc'];
$filter['groupBy'] = 'title';

$filter['limit'] = 3;
$filter['offset'] = 0;
$filter['having'] = ['aliasID', '>', 0]; // orHaving, havingRaw

$filter['with'] = []; // has, whereHas, doesntHave, whereDoesntHave, withCount
$filter['with'][]  = [
    'exampletable1_row_func' => function($q) {
        $q->with('exampletable_row_id_func');
        $q->where('uid','>',0);
        $q->where('pid','>',0);
    }
];

$filter['with'][] = ['exampletable2_rows_func.exampletable_row_id_func'];
$filter['with'][] = ['exampletable3_row_id_func'];
$filter['with'][] = ['exampletable4_rows_func'];

#$filter['union'] = ['']; // unionAll // $subQ = NewTable::recSelect('obj', $filter);
#$filter['join'] = ['contacts', 'users.id', '=', 'contacts.user_id'];
#$filter['leftJoin'] = ['posts', 'users.id', '=', 'posts.user_id'];
#$filter['crossJoin'] = ['posts'];

$filter['withoutGlobalScopes'] = true;
$filter['withoutGlobalScope'] = ['FlagDeleted','FlagDeleted','DateStart', 'DateEnd'];

$filter['userPagination'] = [30,1]; // $pageLimit, $pageNumber
$filter['userWhereFlagDeleted'] = [0,1]; // 0, 1, [0,1]
$filter['userWhereFlagDisabled'] = [0,1]; // 0, 1, [0,1]

$sql = NewTable::recSelect('toSql', $filter);
$count = NewTable::recSelect('count', $filter);
$rows = NewTable::recSelect('get', $filter);

print "Sql: " . $sql . "<hr />";
print "Count: " . $count . "<hr />";
foreach ($rows as $row) {
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

$data = [];
$data[]['title'] = '-- TITLE №1 --';
$data[]['title'] = '-- TITLE №2 --';
$data[]['title'] = '-- TITLE №3 --';
$insertIds = NewTable::recInsertMultiple($data);

////////////////////////////////////////////////////////////////////////////////////////
// UPDATE
// ModelName::recUpdate($id || $filter || $callback, $data); // return affectedCount
////////////////////////////////////////////////////////////////////////////////////////

$recordId = 7;
$data = [];
$data['title'] = '-- TITLE --';
$affectedCount = NewTable::recUpdate($recordId, $data);
if ($affectedCount > 0) {
    echo 'Successfully ' . $affectedCount;
}

////////////////////////////////////////////////////////////////////////////////////////
// DELETE 
// ModelName::recDelete($id || $filter || $callback, $destroy); // return affectedCount
////////////////////////////////////////////////////////////////////////////////////////

$recordId = 7;
$destroy = true; // If use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
$affectedCount = NewTable::recDelete($recordId, $destroy);
if ($affectedCount > 0) {
    echo 'Successfully ' . $affectedCount;
}

$destroy = true; // If use \Litovchenko\AirTable\Domain\Model\Traits\Deleted;
$affectedCount = Pages::recDelete('full',$destroy); // Truncate
$affectedCount = Pages::recDelete('full');

////////////////////////////////////////////////////////////////////////////////////////
// RELATIONSHIPS ->withoutGLobalScopes()!!!
// Working with relationships between tables "[relname]_row(s)_func"
////////////////////////////////////////////////////////////////////////////////////////

NewTable::refAttach('category_rows_func', 1, [3, 4]); // $relationship, $parentId, $idsToAttach ->withoutGLobalScopes()!!!
NewTable::refDetach('category_rows_func', 1, 4); // $relationship, $parentId, $idsToDetach ->withoutGLobalScopes()!!!
NewTable::refDetach('category_rows_func', 1); // detach all
NewTable::refCollection('category_rows_func', 1); // $relationship, $parentId ->withoutGLobalScopes()!!!

////////////////////////////////////////////////////////////////////////////////////////
// ADDING FUNCTIONS TO THE MODEL
////////////////////////////////////////////////////////////////////////////////////////

// A)
// Todo relation function getCounty( -> refProvider() )

// B)
// Todo static function GetById (recSelect('userFunc'))

// C) Global scope (user function global scope register)
See example: 

// D) Local scope (user function local scope register)
See example: scopeUserPagination($query, $limit, $pagePosition) // return $query->limit()...;

// E) Nested Set
// Todo 

////////////////////////////////////////////////////////////////////////////////////////

 * hasOne / hasMany (1-1, 1-M)
    -save(new or existing child)
    -saveMany(array of models new or existing)
    -create(array of attributes)
    -createMany(array of arrays of attributes)
    ---------------------------------------------------------------------------

 * belongsTo (M-1, 1-1)
    -associate(existing model)
    ---------------------------------------------------------------------------

 *  belongsToMany (M-M)
    -save(new or existing model, array of pivot data, touch parent = true)
    -saveMany(array of new or existing model, array of arrays with pivot ata)
    -create(attributes, array of pivot data, touch parent = true)
    -createMany(array of arrays of attributes, array of arrays with pivot data)
    -attach(existing model / id, array of pivot data, touch parent = true)
    -sync(array of ids OR ids as keys and array of pivot data as values, detach = true)
    -updateExistingPivot(relatedId, array of pivot data, touch)
    ---------------------------------------------------------------------------

 *  morphTo (polymorphic M-1)
    // the same as belongsTo
    ---------------------------------------------------------------------------

 *  morphOne / morphMany (polymorphic 1-M)
    // the same as hasOne / hasMany
    ---------------------------------------------------------------------------

 *  morphedToMany /morphedByMany (polymorphic M-M)
    // the same as belongsToMany
```

