<?php
namespace RESTAPI;
use mysqli;
class Model {
   
    private $mysqli;

    public function __construct() {
        
        
        $this->mysqli = '';
        $this->sql = '';
        $this->select_clause = '';
        $this->from_clause = '';
        $this->where_clause = '';
        $this->or_where_clause = '';
        $this->like_clause = '';
        $this->or_like_clause = '';
        $this->order_by_clause = '';
        $this->group_by_clause = '';
        $this->offset = 0;
        $this->data = '';
        $this->fields = '';
        $this->limit = DATABASE_DEFAULT_LIMIT;
        $this->max = DATABASE_MAX;
        $this->blacklist_fields = '';
    }

    
    private function errorhandling($code) {
        $this->errorhandling = new \RESTAPI\libraries\Errorhandling();
        $this->errorhandling->generate_error($code);
    }
    private function connect() {
        $this->mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);
        /* check connection */
        if (mysqli_connect_errno()) {
            $this->errorhandling(8001);
        }
    }
    
    protected function query($query) {
        $this->connect();
        $rows = '';
        if(isset($this->fields))
        $this->field_check();
        $result = $this->mysqli->query($query);
        if (is_object($result)) {
            $ctr = 0;
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $key => $val)
                $rows[$ctr][$key] = $val;
                ++$ctr;
            }
            if (count($rows) <= 0)
            $this->errorhandling(8004);
        }
        else
        $rows = $result;
        $this->close();
        if (method_exists($result, 'free'))
        $result->free();
        return $rows;
    }

    private function field_check() {
        $userfields = explode(',', $this->fields);
        foreach ($userfields as $validate_fields) {
            if (is_array($this->blacklist_fields)) {
                if(in_array($validate_fields, $this->blacklist_fields))
                $this->errorhandling(8003);
            }
        }
    }
    
    public function close() {
        $this->mysqli->close();
        
    }
    

}
