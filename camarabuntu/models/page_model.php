<?php
    class PageModel extends Model {
        public $tableName = "pages";
        protected $extraFields = array('content' => '', 
                            'external_url' => '',
                            'image_url' => '',
                            'executable' => '', 
                            'description' => null, 
                            'category' => '');
        
        
    }
