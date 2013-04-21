<?php
class Model {
    
    /*********************
     * CLASS ATTRIBUTES *
     *********************/ 
    
    const DB_NAME = "db/database.db";
    protected static $DBH = null;
    
    /***********************
     * INSTANCE ATTRIBUTES *
     **********************/
    
    
    protected $id = null;
    protected $fields = array(
                'name' => ''
            );
    protected $extraFields = array();
    
    public $tableName = "";
    
    /*****************
     * CLASS METHODS *
     *****************/
     
    public static function openDB() {
        try {
            static::$DBH = new PDO("sqlite:db/database.db");
            static::$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(Exception $e) {
            die("DB Connection unsuccessfull: " . $e->getMessage());
        }
    }
    public static function closeDB() {
        static::$DBH->close();
    }
    
     
    /******************
     * OBJECT METHODS *
     ******************/
     
     
    private function insert() {
        $numFields = count($this->fields);
        $i = 1;
        $queryFields = "";
        $queryValues = "";
        
        foreach($this->fields as $fieldName => $fieldValue) {
            $queryFields .= $fieldName;
            $queryValues .= ":$fieldName";
            if ($i < $numFields) {
                $queryFields .= ',';
                $queryValues .= ',';
            }
            $i++;
        }
        
        $query = "INSERT INTO ".$this->tableName . " ($queryFields) VALUES ($queryValues)";
        $STH = static::$DBH->prepare($query);
        $STH->execute($this->fields);
        return static::$DBH->lastInsertId();
    }
    
    private function update() {
        
        $numFields = count($this->fields);
        $i = 1;
        $fieldAssignment = '';
        foreach($this->fields as $fieldName => $fieldValue) {
            $fieldAssignment .= "$fieldName = :$fieldName";
            if ($i < $numFields) {
                $fieldAssignment .= ',';
            }
            $i++;
        }
        
        $query = "UPDATE " . $this->tableName . " SET $fieldAssignment ";
        $query .= "WHERE ROWID = ".$this->id;
        
        $STH = static::$DBH->prepare($query);
        $STH->execute($this->fields);
        
    }
    public function delete($id = null) {
        if ($id == null)
            $id = $this->id;
        self::openDB();
        
        $query = "DELETE FROM " . $this->tableName;
        $query .= " WHERE rowid = :id";
        
        $STH = static::$DBH->prepare($query);
        
        return $STH->execute(array("id" => $id));
    }
    
    public function __construct($id = null, $fields = null) {
        $this->setFields($id, $fields);
    }
    public function setFields($id = null, $fields = null) {
        $this->fields = array_merge($this->fields, $this->extraFields);
        
        
        if ($fields != null)
            if (isset($_FILES['file']) && $_FILES["file"]['error'] == 0 && isset($fields['name'])) {
                
                $fields['image_url'] = $this->saveFile($_FILES["file"]["name"],
                                $_FILES['file']['tmp_name']);
              }
            if (isset($_FILES['screenshot'])
                    && $_FILES['screenshot']['error'] == 0 && isset($fields['name'])) {
                        $_FILES["screenshot"]["name"] = $this->tableName . "_screen_" . $fields['name'];
                        
                        
                        $fields['screenshot_url'] = $this->saveFile($_FILES["screenshot"]["name"],
                                    $_FILES["screenshot"]["tmp_name"]);
                        
                    }
            foreach($this->fields as $key => &$field) {
                if (isset($fields[$key]))
                    $field = $fields[$key];
            }
        if ($id != null) {
            $this->id = $id;
        }
        
    }
    public function saveFile($name, $tmpLocation) {
        $filename = "uploads/" . $this->tableName . "/" .  $name;
        $counter = 0;
        while(file_exists($filename . $counter)) {
            $counter++; 
        }
        move_uploaded_file($tmpLocation, $filename);
        return $filename;
    }
    public function getFieldList() {
        return array_merge($this->fields, $this->extraFields);
    }
    /*************
     * PROTECTED *
     *************/
     
    
    
    protected function validate() {
        return true;
    }
    
    
    /**********
     * PUBLIC *
     **********/
     
    public function save() {
        $this->validate();
        
        if ($this->id == null)
            return $this->insert();
        else
            $this->update();
    }
    
    public function load($conditionArray) {
        self::openDB();
        $numFields = count($this->fields);
        $i = 1;
        $fieldList = '';
        foreach($this->fields as $fieldName => $fieldValue) {
            $fieldList .= "$fieldName ";
            if ($i < $numFields) 
                $fieldList .= ', ';
            $i++;
        }
        $query = "SELECT rowid as rowid, $fieldList FROM " . $this->tableName . "";
        
        if (count($conditionArray) != 0) {
            $conditionList = " WHERE ";
        }
        else {
            $conditionList = "";
        }
        foreach ($conditionArray as $fieldName => $fieldValue) {
            $conditionList .= " $fieldName = :$fieldName ";
            $conditionArray[":" . $fieldName] = $fieldValue;
            unset($conditionArray[$fieldName]);
            if ($i < $numFields)
                $conditionList .= " AND ";
            $i++;
        }
       
        $query .= $conditionList;
        try {
            
            $STH = static::$DBH->prepare($query);
            
            $STH->execute($conditionArray);
            
            $results = $STH->fetchAll();
        }
        catch (Exception $e) {
            die("Error processing query: $query <br /><br />" . $e->getMessage());
            
        }
        return $results;
    }
    
}
?>
