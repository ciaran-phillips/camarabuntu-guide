<?php
    class ApplicationLevelModel extends Model {
        public $tableName = "application_level";
        protected $extraFields = array(
                        'application' => null,
                        'level' => null
                    );
        protected $fields = array();
                                
                                
        public function clearApplication($id) {
            self::openDB();
            
            $query = "DELETE FROM " . $this->tableName;
            $query .= " WHERE application = :id";
            
            $STH = static::$DBH->prepare($query);
            
            return $STH->execute(array(":id" => $id));
        }
    }
    
