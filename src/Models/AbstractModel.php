<?php
namespace easyPM\Models;
use PDO;

interface Db_Record
{
    public function get($result, $opts);
    public function insert($objs, $opts);
    public function update($objs, $opts);
    public function delete($objs, $opts);
}

abstract class AbstractModel implements Db_Record {
    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    public function get($result, $opts): int
    {
        
    }
}
