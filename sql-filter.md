```sql
where uid > 5 and (pid != 1 and pid != 2)
	
	select = uid,title
	where = ?
	where.func {
		where.func {
			where = uid,>,?
			orWhere = uid,>,?
		}
		orWhere.func {
			where = uid,>,?
			orWhere = uid,>,?
		}
	}
	where.10 = uid,>,?
	where.20 = uid,>,?
	
	with.10 = city_rows
	with.10.func {
		whereUid = 1
		wherePid = 1
		with = user_row
	}
		
	with.20 = category_rows
	with.20.func {
		whereUid = 1
		wherePid = 1
	}
	
	order.10 = title,desc
	order.20 = subtitle,asc
```

```php
<?php

$test = DB::table('table1 AS a')
        ->leftJoin('table2 AS b', 'a.field2', '=', 'b.field2')
        ->leftJoin('table3 AS c', function($join){
            $join->on('a.field2', '=', 'c.field2');
            $join->on(DB::raw('a.field3 = c.field3'), DB::raw(''), DB::raw(''));
        })
        ->select('e.field1', 'b.field2', 'c.field3', 'd.field4', 'a.field5', 'a.field6')
        ->where('a.field6', 'LIKE', '%$bintara%')
        ->orWhere('b.field2', 'LIKE', '%$bintara%')
        ->orderBy('e.field1', 'ASC')
        ->orderBy('b.field2', 'ASC')
        ->orderBy('c.field3', 'ASC')
        ->orderBy('ca.field5', 'ASC')
        ->get();
		
$filter = [];
$filter['where'] = ['a.field6', 'LIKE', '%$bintara%'];
$filter['orWhere'] = ['b.field2', 'LIKE', '%$bintara%'];
$filter['orderBy.10'] = ['e.field1','Asc'];
$filter['orderBy.20'] = ['b.field2','Asc'];
$filter['orderBy.30'] = ['c.field3','Asc'];
$filter['orderBy.40'] = ['ca.field5','Asc'];

$filter = [];
$filter['select'] 								= ['id','title'];
$filter['where.10'] 							= ['id','>',5];
$filter['where.20']['where'] 					= ['id','<',3000];
$filter['where.20']['orWhere.10'] 				= ['id','<',2000];
$filter['where.20']['orWhere.20']['where'] 		= ['id','<',1000];
$filter['where.20']['orWhere.20']['orWhere'] 	= ['id','<',500];
$filter['where.30'] 							= ['id','<',5];

$filter['with.10' = 'city_rows_func' = [];
$filter['with.10']['city_rows_func']['where'] = ['city_id','=',5];
$filter['with.10']['city_rows_func']['limit'] = 5;
$filter['with.10']['city_rows_func']['with.10']['user_id_func'];
$filter['with.10']['city_rows_func']['with.10'] = ['user_id_func'];

// $q->with('relname',1);

$filter2 = [];
$filter2['select'] 	= ['id','title'];
$filter2['where.10'] = ['id','>',5];
$filter2['where.20'] = [
	'where' 		=> ['id','<',3000],
	'orWhere.10' 	=> ['id','<',2000],
	'orWhere.20' 	=> [
		'where' 	=> ['id','<',1000],
		'orWhere' 	=> ['id','<',500]
	]
];
$filter2['where.30'] 					= ['id','<',5]; ['id','<',5];

$filter2 = [];
$filter2['select'] 	= ['id','title'];
$filter2['10.where'] = ['id','>',5];
$filter2['20.where'] = [
	'10.where' 		=> ['id','<',3000],
	'20.orWhere' 	=> ['id','<',2000],
	'30.orWhere' 	=> [
		'10.where' 	=> ['id','<',1000],
		'20.orWhere' 	=> ['id','<',500]
	]
];
$filter2['where.30'] 					= ['id','<',5];
```
