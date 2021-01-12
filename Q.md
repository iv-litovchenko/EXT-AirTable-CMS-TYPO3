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
// NewTable::recSelect('medthod', $id || $filter || $callback); // return result
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
foreach ($rowsResult['get'] as $row) {
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

$filter = [];
$filter['distinct'] = ['title'];
$filter['select'] = ['uid','title', 'uid as aliasID'];
$filter['addSelect'] = ['pid','date_create'];

$filter['where'] = []; // orWhere, =, <, >, <=, >=, <>, !=, LIKE, NOT LIKE, BETWEEN, ILIKE
$filter['where'][] = ['uid','>=',1];
$filter['where'][] = ['uid','<=',10000];

$filter['where'] = function($q) { 
    $q->where('pid','>=',0); 
    $q->orWhere('pid','<=',0);
};

$filter['whereIn']  ['uid',[1,2,3,4,5,6,7,8,9,10]]; // orWhereIn, whereNotIn, orWhereNotIn
$filter['whereNull'] = ['keywords']; // orWhereNull, whereNotNull, orWhereNotNull 
$filter['whereBetween'] = ['uid',[1,1000]]; // whereNotBetween
$filter['whereColumn'] = ['uid','!=','title'];
$filter['whereRaw'] = ['(uid > ? and uid < ?)', [1,1000]]; // DB::raw(1)
$filter['whereRaw'] = [];
$filter['whereRaw'][] = ["FROM_UNIXTIME(date_create, '%d') = 11"];
$filter['whereRaw'][] = ["FROM_UNIXTIME(date_create, '%m') = 01"];
$filter['whereRaw'][] = ["FROM_UNIXTIME(date_create, '%Y') = 2021"];
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

$filter['with'] = []; // has, whereHas
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

$filter['userWherePid'] = 10;
$filter['userWhereUid'] = 4;
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
// RELATIONSHIPS
// Working with relationships between tables "[relname]_row(s)_func"
////////////////////////////////////////////////////////////////////////////////////////
NewTable::refAttach('category_rows_func', 1, [3, 4]); // $relationship, $parentId, $idsToAttach
NewTable::refDetach('category_rows_func', 1, 4); // $relationship, $parentId, $idsToDetach
$countRecord = NewTable::recSelect('count', function ($q) use () {
    $q->with('category_rows_func');
    $q->where('uid', 1);
});

// Todo
// 1 relation function getCounty( -> refProvider() )
// 2 static function GetById (recSelect('userFunc'))
// 3 user function global scope register
// 4 user function local scope register
// 5 sub $filter where & with (without callback function "function ($q) use ()")
