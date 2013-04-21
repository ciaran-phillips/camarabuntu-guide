<?php
    class ApplicationSubjectModel extends Model {
        public $tableName = "application_subject";
        protected $extraFields = array(
                        'application' => null,
                        'subject' => null
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
    
