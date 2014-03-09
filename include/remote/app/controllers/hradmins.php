<?php

/**
 * @class Hradmins
 * A simple application controller extension
 */
class Hradmins extends ApplicationController {
    /**
     * view
     * Retrieves rows from database.
     */
    public function view() {
        $res = new Response();
        $res->success = true;
        $res->message = "Loaded data";
        Hradmin::setSQL("SELECT ID, USERNAME, PASSWORD, COMPANY_ID FROM HRADMINS");
        $res->data = Hradmin::all();
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
                Hradmin::setSQL("INSERT INTO HRADMINS VALUES(NULL,'". $data->USERNAME ."','". $data->PASSWORD."'," . $data->COMPANY_ID . ")");
                array_push($res->data, Hradmin::create($data)->to_hash());
            }
            $res->success = true;
            $res->message = "Created " . count($res->data) . ' records';
        } else {
            Hradmin::setSQL("INSERT INTO HRADMINS VALUES(NULL,'". $this->params->USERNAME ."','". $this->params->PASSWORD."'," . $this->params->COMPANY_ID . ")");
            if ($rec =  Hradmin::create($this->params)) {
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
                Hradmin::setSQL("UPDATE HRADMINS SET USERNAME='".$data->USERNAME."', PASSWORD='".$data->PASSWORD."', COMPANY_ID=".$data->COMPANY_ID ." WHERE ID=" . $data->ID);
                if ($rec = Hradmin::update($data->ID, $data)) {
                    array_push($res->data, $rec->to_hash());
                }
            }
            $res->success = true;
            $res->message = "Updated " . count($res->data) . " records";
        } else {
            $sql = "UPDATE HRADMINS SET ";
            $flag = FALSE;
            if(!is_null($this->params->USERNAME)) {
                $sql = $sql . "USERNAME='".$this->params->USERNAME."'";
                $flag = TRUE;
            }
            if(!is_null($this->params->PASSWORD)) {
                if($flag) $sql = $sql. " , ";
                $sql = $sql . "PASSWORD='" . $this->params->PASSWORD. "'";
                $flag = TRUE;
            }
            if(!is_null($this->params->COMPANY_ID)) {
                if($flag) $sql = $sql. " , ";
                $sql = $sql . "COMPANY_ID=".$this->params->COMPANY_ID;
            }
            $sql = $sql . " WHERE ID=" . $this->params->ID;
            
            Hradmin::setSQL($sql);
            if ($rec = Hradmin::update($this->params->ID, $this->params)) {
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
                Hradmin::setSQL("DELETE FROM HRADMINS WHERE ID=" . $id);
                if ($rec = Hradmin::destroy($id)) {
                    array_push($destroyed, $rec);
                }
            }
            $res->success = true;
            $res->message = 'Destroyed ' . count($destroyed) . ' records';
        } else {
            Hradmin::setSQL("DELETE FROM HRADMINS WHERE ID=" . $this->id);
            if ($rec = Hradmin::destroy($this->id)) {
                $res->message = "Destroyed User";
                $res->success = true;
            } else {
                $res->message = "Failed to Destroy user";
                
            }
        }
        return $res->to_json();
    }
}

