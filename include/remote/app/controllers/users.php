<?php
/**
 * @class Users
 * A simple application controller extension
 */
class Users extends ApplicationController {
    /**
     * view
     * Retrieves rows from database.
     */
    public function view() {
        $res = new Response();
        $res->success = true;
        $res->message = "Loaded data";
        User::setSQL("SELECT ID, USERNAME, PASSWORD FROM USERS;");
        $res->data = User::all();
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
                User::setSQL("INSERT INTO USERS VALUES(NULL,'". $data->USERNAME ."','". $data->PASSWORD."')");
                array_push($res->data, User::create($data)->to_hash());
            }
            $res->success = true;
            $res->message = "Created " . count($res->data) . ' records';
        } else {
            User::setSQL("INSERT INTO USERS VALUES(NULL,'". $this->params->USERNAME ."','". $this->params->PASSWORD."')");
            if ($rec =  User::create($this->params)) {
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
                User::setSQL("UPDATE USERS SET USERNAME='".$data->USERNAME."', PASSWORD='".$data->PASSWORD."' WHERE ID=" . $data->ID);
                if ($rec = User::update($data->ID, $data)) {
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
            User::setSQL($sql);
            if ($rec = User::update($this->params->ID, $this->params)) {
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
                User::setSQL("DELETE FROM USERS WHERE ID=" . $id);
                if ($rec = User::destroy($id)) {
                    array_push($destroyed, $rec);
                }
            }
            $res->success = true;
            $res->message = 'Destroyed ' . count($destroyed) . ' records';
        } else {
            User::setSQL("DELETE FROM USERS WHERE ID=" . $this->id);
            if ($rec = User::destroy($this->id)) {
                $res->message = "Destroyed User";
                $res->success = true;
            } else {
                $res->message = "Failed to Destroy user";
            }
        }
        return $res->to_json();
    }
}

