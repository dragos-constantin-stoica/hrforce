<?php

/**
 * @class Itadmins
 * A simple application controller extension
 */
class Itadmins extends ApplicationController {
    /**
     * view
     * Retrieves rows from database.
     */
    public function view() {
        $res = new Response();
        $res->success = true;
        $res->message = "Loaded data";
        Itadmin::setSQL("SELECT ID, USERNAME, PASSWORD FROM USERS WHERE TYPE = '1';");
        $res->data = Itadmin::all();
        return $res->to_json();
    }
    /**
     * create
     */
    public function create() {
        $res = new Response();

        // Ugh, php...check if !hash
        if (is_array($this->params) && !empty($this->params) && preg_match('/^\d+$/', implode('', array_keys($this->params)))) {
            foreach ($this->params as $data) {
                Itadmin::setSQL("INSERT INTO USERS VALUES(NULL,'". $data->USERNAME ."','". $data->PASSWORD."','1')");
                array_push($res->data, Itadmin::create($data)->to_hash());
            }
            $res->success = true;
            $res->message = "Created " . count($res->data) . ' records';
        } else {
            Itadmin::setSQL("INSERT INTO USERS VALUES(NULL,'". $this->params->USERNAME ."','". $this->params->PASSWORD."','1')");
            if ($rec =  Itadmin::create($this->params)) {
                $res->success = true;
                $res->data = $rec->to_hash();
                $res->message = "Created record";
               
            } else {
                $res->success = false;
                $res->message = "Failed to create record";
            }
        }
        return $res->to_json();
    }

    /**
     * update
     */
    public function update() {
        $res = new Response();

        if (!get_class($this->params)) {
            $res->data = array();
            foreach ($this->params as $data) {
                Itadmin::setSQL("UPDATE USERS SET USERNAME='".$data->USERNAME."', PASSWORD='".$data->PASSWORD."' WHERE ID=" . $data->ID);
                if ($rec = Itadmin::update($data->ID, $data)) {
                    array_push($res->data, $rec->to_hash());
                }
            }
            $res->success = true;
            $res->message = "Updated " . count($res->data) . " records";
        } else {
            $sql = "UPDATE USERS SET ";
            if(!is_null($this->params->USERNAME) && !is_null($this->params->PASSWORD)){
                $sql = $sql . "USERNAME='".$this->params->USERNAME."' , PASSWORD='".$this->params->PASSWORD."' WHERE ID=" . $this->params->ID;
            }else{
                if(!is_null($this->params->USERNAME)){
                $sql = $sql . "USERNAME='".$this->params->USERNAME."' WHERE ID=" . $this->params->ID;
                }
                if(!is_null($this->params->PASSWORD)){
                $sql = $sql . "PASSWORD='".$this->params->PASSWORD."' WHERE ID=" . $this->params->ID;
                }
            }
            Itadmin::setSQL($sql);
            if ($rec = Itadmin::update($this->params->ID, $this->params)) {
                $res->data = $rec->to_hash();
                $res->success = true;
                $res->message = "Updated record";
            } else {
                $res->message = "Failed to updated record " . $this->params->id;
                $res->success = false;
            }

        }
        return $res->to_json();
    }

    /**
     * destroy
     */
    public function destroy() {
        $res = new Response();

        if (is_array($this->params)) {
            $destroyed = array();
            foreach ($this->params as $id) {
                Itadmin::setSQL("DELETE FROM USERS WHERE ID=" . $id);
                if ($rec = Itadmin::destroy($id)) {
                    array_push($destroyed, $rec);
                }
            }
            $res->success = true;
            $res->message = 'Destroyed ' . count($destroyed) . ' records';
        } else {
            Itadmin::setSQL("DELETE FROM USERS WHERE ID=" . $this->id);
            if ($rec = Itadmin::destroy($this->id)) {
                $res->message = "Destroyed Itadmin";
                $res->success = true;
            } else {
                $res->message = "Failed to Destroy Itadmin";
            }
        }
        return $res->to_json();
    }
}

