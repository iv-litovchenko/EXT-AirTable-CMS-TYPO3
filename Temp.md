            'propref_beauthor', // M-1
            'propref_content', // <f:vhsExtAirTable.content model="Mynamespace\Myext\Domain\Model\NewTable" uid="2" />
            'propref_attributes',

            // Categorization
            // For this to work, you need:
            // 1) create a category model (NewTableCategory.php) in the current directory
            // 2) add trait "\Litovchenko\AirTable\Domain\Model\Traits\ParentRow" to model "NewTableCategory.php"
            'propref_parent', // M-1
            'category_row_id', // Categorization M-1
            // or 'category_rows', //  Categorization M-M
			
            'foreign_table', // For polymorphic relations
            'foreign_field', // For polymorphic relations
            'foreign_uid', // For polymorphic relations
            'foreign_sortby', // For polymorphic relations
