<?php
    class CategoryModel extends Model {
        public $tableName = "categories";
        public $extraFields = array('depends_on_url_available' => ''); 
    }
