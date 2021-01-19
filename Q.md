```php
<?php
use Illuminate\Database\Capsule\Manager as DB;
use Litovchenko\AirTable\Domain\Model\Content\Pages;
use Mynamespace\Myext\Domain\Model\NewTable;

////////////////////////////////////////////////////////////////////////////////////////
// SELECT
// NewTable::recSelect('medthod', $id || $filter || $callback); // return result
////////////////////////////////////////////////////////////////////////////////////////

$recordId = 7;
$rowFirst = NewTable::recSelect('first', $recordId);
$rowExists = NewTable::recSelect('exists', $recordId); // ->exists() (if), ->doesntExist() (if)
$rowsCount = NewTable::recSelect('count'); // count
$rowsGet = NewTable::recSelect('get'); // all
$obj = NewTable::recSelect('obj',[])->...->get(); // return obj (to create subqueries)
$sql = NewTable::recSelect('obj',[])->toSql();
$dd = NewTable::recSelect('obj',[])->dd(); // debugging
$dump = NewTable::recSelect('obj',[])->dump(); // debugging

$limit = 10;
$rowsResult = NewTable::recSelect('count,get', function ($q) use ($limit) { 
    $q->limit($limit); 
});

print "Count: " . $rowsResult['count'] . "<hr />";
foreach ($rowsResult['get'] as $row) {
    print $row['title'] . " // ";
    print $row['[relname]_row(s)_func']['title'] . "<br />";
}

$filter = [];
$filter['withoutGlobalScopes'] = true;
$filter['withoutGlobalScope'] = ['FlagDeleted','FlagDeleted','DateStart', 'DateEnd'];

$filter['distinct'] = 'title';
$filter['select'] = ['uid','title', 'uid as aliasID'];
$filter['addSelect'] = ['pid','date_create'];

$filter['whereUid'] = 1; // dynamic field name
$filter['wherePid'] = 1; // dynamic field name
$filter['whereTitle'] = []; // dynamic field name
$filter['whereFieldName'] = []; // dynamic field name

$filter['where.10'] = []; // ...
$filter['where.20'] = []; // ...
$filter['where'] = ['uid','>=',1]; // ->orWhere() =, <, >, <=, >=, <>, !=, LIKE, NOT LIKE, ILIKE
$filter['where'] = function($q) { 
    $q->where('pid','>=',0); 
    $q->orWhere('pid','<=',0);
};

$filter['whereIn'] = ['uid',[1,2,3,4,5,6,7,8,9,10]]; // ->orWhereIn(), ->whereNotIn(), ->orWhereNotIn()
$filter['whereNull'] = 'keywords'; // ->orWhereNull(), ->whereNotNull(), ->orWhereNotNull()
$filter['whereBetween'] = ['uid',[1,1000]]; // ->whereNotBetween()
$filter['whereColumn'] = ['uid','!=','title'];

$filter['whereRaw'] = ['(uid > ? and uid < ?)', [1,1000]]; // DB::raw(1)
$filter['whereRaw'] = ["FROM_UNIXTIME(date_create, '%j') = ?", 11]; // %d -> with zero
$filter['whereRaw'] = ["FROM_UNIXTIME(date_create, '%n') = ?", 1]; // %m -> with zero
$filter['whereRaw'] = ["FROM_UNIXTIME(date_create, '%Y') = ?", 2021];
$filter['whereExists'] = function($q) { // ->orWhereExists(), ->whereNotExists(), ->orWhereNotExists()
    $q->select(DB::raw(1))->from('tablename')->whereRaw('uid > 0'); 
};

$filter['inRandomOrder'] = false; // true
$filter['orderBy.10'] = ['uid','desc'];
$filter['orderBy.20'] = ['title','desc'];
$filter['groupBy'] = 'title';

$filter['limit'] = 3;
$filter['offset'] = 0;
$filter['having'] = ['aliasID', '>', 0]; // orHaving, havingRaw

// ->with(), ->has(), ->whereHas(), ->doesntHave(), ->whereDoesntHave(), ->withCount()
$filter['with.10']  = [
    'exampletable1_row_func' => function($q) {
        $q->with('exampletable_row_id_func');
        $q->where('uid','>',0);
        $q->where('pid','>',0);
    }
];
$filter['with.20'] = 'exampletable2_rows_func.exampletable_row_id_func';
$filter['with.30'] = 'exampletable3_row_id_func';
$filter['with.40'] = 'exampletable4_rows_func';

#$filter['union'] = ['']; // ->unionAll() // $subQ = NewTable::recSelect('obj', $filter);
#$filter['join'] = ['contacts', 'users.id', '=', 'contacts.user_id'];
#$filter['leftJoin'] = ['posts', 'users.id', '=', 'posts.user_id'];
#$filter['crossJoin'] = 'posts';

$filter['customSelectMinimize'] = true; // or false
$filter['customWhereFlagDeletedIn'] = [0,1]; // 0, 1, [0,1]
$filter['customWhereFlagDisabledIn'] = [0,1]; // 0, 1, [0,1]
$filter['customPagination'] = [30,1]; // $pageLimit, $pageNumber

$count = NewTable::recSelect('count', $filter);
$rows = NewTable::recSelect('get', $filter);

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
$insertIds = NewTable::recInsertMultiple($data); // Inserted ID 99,9% authenticity :)

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

$data = [];
$data['title'] = '-- TITLE --';
$affectedCount = NewTable::recUpdate('full'); // Update all

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
$affectedCount = NewTable::recDelete('full',$destroy); // Truncate
$affectedCount = NewTable::recDelete('full');

////////////////////////////////////////////////////////////////////////////////////////
// RELATIONSHIPS ->withoutGLobalScopes() always!!!
// Create and remove links between table records "[relname]_row(s)_func"
////////////////////////////////////////////////////////////////////////////////////////

NewTable::refAttach('category_rows_func', 1, [3, 4]); // $relationship, $parentId, $idsToAttach ->withoutGLobalScopes() always!!!
NewTable::refDetach('category_rows_func', 1, 4); // $relationship, $parentId, $idsToDetach ->withoutGLobalScopes() always!!!
NewTable::refDetach('category_rows_func', 1, []); // detach all ->withoutGLobalScopes() always!!!
NewTable::refCollection('category_rows_func', 1); // $relationship, $parentId ->withoutGLobalScopes() always!!!
NewTable::refUpdatePivot(); // todo
NewTable::refSort(); // todo

////////////////////////////////////////////////////////////////////////////////////////
// ADDING CUSTOM FUNCTIONS TO THE MODEL
////////////////////////////////////////////////////////////////////////////////////////

// A) Global scope (user function global scope register)
See example: public static function globalScopeFlagDeleted($builder){} // $builder->where()...;

// B) Local scope (user function local scope register)
See example: public function гserPagination($query, $limit, $pagePosition){} // return $query->limit()...;

// C) ------------
// builderUserRef[Name]()
// $rows = NewTable::with('customNameRelationship')->get();
public function builderRefCustomNameRelationship() { // builderUserRef[Name]()
    return $this->refProvider('exampletable4_rows');
}

// D) ------------
// todo, static function GetById (recSelect('userFunc getBy***'))
$rows1 = NewTable::recSelect('getById',230,'title'); // $id, $fields
$rows2 = NewTable::recSelect('getByList','title'); // $id, $fields


// E) Nested Set
// Todo 

////////////////////////////////////////////////////////////////////////////////////////
// OTHER
////////////////////////////////////////////////////////////////////////////////////////

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

