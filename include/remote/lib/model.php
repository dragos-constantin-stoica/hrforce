<?php
/**
 * @class Model
 * Baseclass for Models in this ORM
 */
class Model {
    public $id, $attributes;

    static function setSQL($sql){
        global $dbh;
        $dbh->setSQL($sql);
    }

    static function create($params) {
        global $dbh;
        $obj = new self(get_object_vars($params));
        $dbh->insert();
        $obj->attributes['ID'] = $dbh->pk();
        $dbh->addrs($obj->attributes);
        return $obj;
    }
    
    static function find($id) {
        global $dbh;
        $found = null;
        foreach ($dbh->rs() as $rec) {
            if ($rec['ID'] == $id) {
                $found = new self($rec);
                break;
            }
        }
        return $found;
    }

    static function update($id, $params) {
        global $dbh;
        $rec = self::find($id);

        if ($rec == null) {
            return $rec;
        }
        $rs = $dbh->rs();

        foreach ($rs as $idx => $row) {
            if ($row['ID'] == $id) {
                $rec->attributes = array_merge($rec->attributes, get_object_vars($params));
                $dbh->update($idx, $rec->attributes);
                break;
            }
        }
        return $rec;
    }

    static function destroy($id) {
        global $dbh;
        $rec = null;
        $rs = $dbh->rs();
        foreach ($rs as $idx => $row) {
            if ($row['ID'] == $id) {
                $rec = new self($dbh->destroy($idx));
                break;
            }
        }
        return $rec;
    }

    static function all() {
        global $dbh;
        $dbh->getData();
        return $dbh->rs();
    }

    public function __construct($params) {
        $this->id = isset($params['ID']) ? $params['ID'] : null;
        $this->attributes = $params;
    }

    public function to_hash() {
        return $this->attributes;
    }
}