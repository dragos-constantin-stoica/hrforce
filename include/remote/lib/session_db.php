<?php
 /**
 * @class SessionDB
 * Fake Database.  Stores records in $_SESSION
 */
class SessionDB {
    public function __construct() {
        if (!isset($_SESSION['pk'])) {
            $_SESSION['pk'] = 10;           // <-- start fake pks at 10
            $_SESSION['rs'] = getData();    // <-- populate $_SESSION with data.
        }
    }
    // fake a database pk
    public function pk() {
        return $_SESSION['pk']++;
    }
    // fake a resultset
    public function rs() {
        return $_SESSION['rs'];
    }
    public function insert($rec) {
        array_push($_SESSION['rs'], $rec);
    }
    public function update($idx, $attributes) {
        $_SESSION['rs'][$idx] = $attributes;
    }
    public function destroy($idx) {
        return array_shift(array_splice($_SESSION['rs'], $idx, 1));
    }
}

// Sample data.
function getData() {
    return array(
        array('ID' => 1, 'USER' => "Fred", 'PASSWORD' => 'Flintstone'),
        array('ID' => 2, 'USER' => "Wilma", 'PASSWORD' => 'Flintstone'),
        array('ID' => 3, 'USER' => "Pebbles", 'PASSWORD' => 'Flintstone'),
        array('ID' => 4, 'USER' => "Barney", 'PASSWORD' => 'Rubble'),
        array('ID' => 5, 'USER' => "Betty", 'PASSWORD' => 'Rubble'),
        array('ID' => 6, 'USER' => "BamBam", 'PASSWORD' => 'Rubble')
    );
}