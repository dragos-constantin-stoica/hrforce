<?php

/**@class Companies
 * A simple application controller extension
 */
class Companies extends ApplicationController{
	


	/**
	 * view
	 * Retrieves rows from database.
	 */
	public function view() {
		$res = new Response();
		$res->success = true;
		$res->message = "Loaded data";		
		Company::setSQL("SELECT ID, COMPANYNAME  FROM COMPANIES;");
		$res->data = Company::all();
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
                Company::setSQL("INSERT INTO COMPANIES VALUES(NULL,'". $data->COMPANYNAME."')");
                array_push($res->data, Company::create($data)->to_hash());
            }
            $res->success = true;
            $res->message = "Created " . count($res->data) . ' records';
        } else {
            Company::setSQL("INSERT INTO COMPANIES VALUES(NULL,'". $this->params->COMPANYNAME."')");
            if ($rec =  Company::create($this->params)) {       	
            

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
                Company::setSQL("UPDATE COMPANIES SET COMPANYNAME='".$data->COMPANYNAME."' WHERE ID=" . $data->ID);
                if ($rec = Company::update($data->ID, $data)) {
                    array_push($res->data, $rec->to_hash());
                }
            }
            $res->success = true;
            $res->message = "Updated " . count($res->data) . " records";
        } else {
            $sql = "UPDATE COMPANIES SET ";
            if(!is_null($this->params->COMPANYNAME)){
                $sql = $sql . "COMPANYNAME='".$this->params->COMPANYNAME."' WHERE ID=" . $this->params->ID;
            }
            
            Company::setSQL($sql);
            if ($rec = Company::update($this->params->ID, $this->params)) {
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
                Company::setSQL("DELETE FROM COMPANIES WHERE ID=" . $id);
                if ($rec = Company::destroy($id)) {
                    array_push($destroyed, $rec);
                }
            }
            $res->success = true;
            $res->message = 'Destroyed ' . count($destroyed) . ' records';
        } else {
            Company::setSQL("DELETE FROM COMPANIES WHERE ID=" . $this->id);
            if ($rec = Company::destroy($this->id)) {
                $res->message = "Destroyed User";
                $res->success = true;
            } else {
                $res->message = "Failed to Destroy company";
                
            }
        }
        return $res->to_json();
    }

}