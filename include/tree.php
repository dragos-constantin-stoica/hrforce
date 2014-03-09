<?php

/*
 * Tree operations - Used by Orgchart
 */

class Tree {

    private $dbCNX;
    private $cmd, $id, $target, $point, $text, $newText, $oldText, $root_id;

    public function __construct($config) {
        $this->dbCNX = $config["db"];
        $this->cmd = $config["cmd"];
        $this->id = $config["id"];
        $this->target = $config["target"];
        $this->point = $config["point"];
        $this->text = $config["text"];
        $this->newText = $config["newText"];
        $this->oldText = $config["oldText"];
        $this->root_id = 0;
    }

    private function display_children($parent, $branch) {
        // retrieve all children of $parent

        $result = $this->dbCNX->query("SELECT ID, TITLE, TYPE FROM ORGANISATION WHERE PARENT_ID=" . $parent . " ORDER BY LFT_NODE;");

        // display each child
        while ($row = mysql_fetch_array($result)) {
            if ($parent == -1) {
                $this->root_id = $row['ID'];
            }
            $count_result = $this->dbCNX->query("SELECT COUNT(ID) as CHILDREN FROM ORGANISATION WHERE PARENT_ID=" . $row['ID'] . " ORDER BY LFT_NODE;");
            $count = mysql_fetch_assoc($count_result);
            if ($count['CHILDREN'] > 0) {
                // call this function again to display this
                // child's children
                array_push($branch,
                        array('id' => $row['ID'], 'text' => $row['TITLE'], 'children' => $this->display_children($row['ID'], array())));
            } else {
                // this is a terminal node
                // leaf
                array_push($branch, array('id' => $row['ID'], 'text' => $row['TITLE'], 'leaf' => 'true'));
            }
        }
        return $branch;
    }

    private function renameTreeNode() {
        $result = $this->dbCNX->query("UPDATE ORGANISATION SET TITLE = '" . $this->newText . "' WHERE ID=" . $this->id . ";");
        return $result == 1 ? array('success' => true) : array('success' => false);
    }

    private function removeTreeNode() {
        $crt_result = $this->dbCNX->query("SELECT PARENT_ID, LFT_NODE, RGT_NODE FROM ORGANISATION WHERE ID=" . $this->id . ";");
        $crt = mysql_fetch_assoc($crt_result);

        if ($crt['PARENT_ID'] == -1) {
            return array('success' => false, 'error' => 'Can not delete root node!');
        }

        $result = $this->dbCNX->query("DELETE FROM ORGANISATION WHERE LFT_NODE BETWEEN " . $crt['LFT_NODE'] . " AND " . $crt['RGT_NODE'] . ";");
        //recompute RGT_NODE and LFT_NODE for the resulting tree

        $this->dbCNX->query("UPDATE ORGANISATION SET RGT_NODE = RGT_NODE-" . ($crt['RGT_NODE'] - $crt['LFT_NODE'] + 1) . " WHERE RGT_NODE > " . $crt["RGT_NODE"] . ";");
        $this->dbCNX->query("UPDATE ORGANISATION SET LFT_NODE = LFT_NODE-" . ($crt['RGT_NODE'] - $crt['LFT_NODE'] + 1) . " WHERE LFT_NODE > " . $crt["RGT_NODE"] . ";");

        //TODO recompute LEVEL
        //TODO recompute LINEAGE

        return $result == 1 ? array('success' => true) : array('success' => false);
    }

    private function insertTreeChild() {
        if ($this->id == $this->root_id) {
            return array('success' => false);
        }

        $crt_result = $this->dbCNX->query("SELECT COMPANY_ID, LFT_NODE, RGT_NODE FROM ORGANISATION WHERE ID=" . $this->id . ";");
        $crt = mysql_fetch_assoc($crt_result);


        //recompute LFT_NODE and RGT_NODE for the resulting tree
        $this->dbCNX->query("UPDATE ORGANISATION SET RGT_NODE = RGT_NODE + 2 WHERE RGT_NODE >" . $crt['LFT_NODE'] . ";");
        $this->dbCNX->query("UPDATE ORGANISATION SET LFT_NODE = LFT_NODE + 2 WHERE LFT_NODE >" . $crt['LFT_NODE'] . ";");

        $result = $this->dbCNX->query("INSERT INTO ORGANISATION (ID, TITLE, TYPE, PARENT_ID, COMPANY_ID, LFT_NODE, RGT_NODE) VALUES (NULL, '" . $this->text . "', 'OTHER'," . $this->id . ", " . $crt['COMPANY_ID'] . ", " . ($crt['LFT_NODE'] + 1) . ", " . ($crt['LFT_NODE'] + 2) . " );");
        //TODO recompute LEVEL
        //TODO recompute LINEAGE
        return $result == 1 ? array('success' => true, 'id' => mysql_insert_id()) : array('success' => false);
    }

    private function appendTreeChild() {
        if ($this->id == $this->root_id) {
            return array('success' => false);
        }

        $crt_result = $this->dbCNX->query("SELECT COMPANY_ID, LFT_NODE, RGT_NODE FROM ORGANISATION WHERE ID=" . $this->id . ";");
        $crt = mysql_fetch_assoc($crt_result);

        //recompute LFT_NODE and RGT_NODE for the resulting tree
        $this->dbCNX->query("UPDATE ORGANISATION SET RGT_NODE = RGT_NODE + 2 WHERE RGT_NODE >" . ($crt['RGT_NODE'] - 1) . ";");
        $this->dbCNX->query("UPDATE ORGANISATION SET LFT_NODE = LFT_NODE + 2 WHERE LFT_NODE >" . $crt['RGT_NODE'] . ";");

        $result = $this->dbCNX->query("INSERT INTO ORGANISATION (ID, TITLE, TYPE, PARENT_ID, COMPANY_ID, LFT_NODE, RGT_NODE) VALUES (NULL, '" . $this->text . "', 'OTHER'," . $this->id . ", " . $crt['COMPANY_ID'] . ", " . ($crt['RGT_NODE']) . ", " . ($crt['RGT_NODE'] + 1) . " );");
        //TODO recompute LEVEL
        //TODO recompute LINEAGE
        return $result == 1 ? array('success' => true, 'id' => mysql_insert_id()) : array('success' => false);
    }

    private function moveTreeNode() {

        $crt_result = $this->dbCNX->query("SELECT PARENT_ID, LFT_NODE, RGT_NODE FROM ORGANISATION WHERE ID=" . $this->id . ";");
        $crt = mysql_fetch_assoc($crt_result);
        $tree_width = $crt['RGT_NODE'] - $crt['LFT_NODE'] + 1;
        $node_lft = $crt['LFT_NODE'];
        $node_rgt = $crt['RGT_NODE'];
        $node_parent = $crt['PARENT_ID'];

        $crt_result = $this->dbCNX->query("SELECT PARENT_ID, LFT_NODE, RGT_NODE FROM ORGANISATION WHERE ID=" . $this->target . ";");
        $crt = mysql_fetch_assoc($crt_result);
        $dest_lft = $crt['LFT_NODE'];
        $dest_rgt = $crt['RGT_NODE'];
        $dest_parent = $crt['PARENT_ID'];

        $node_target = 1;
        $node_parent = 1;
        $result = 0;

        switch ($this->point) {
            case 'above':
                if ($dest_parent == -1) {
                    $result = 0;
                } else {
                    $node_target = $dest_lft;
                    $node_parent = $dest_parent;
                    $result = 1;
                }
                break;
            case 'below':
                if ($this->target == $this->root_id) {
                    $result = 0;
                } else {
                    $node_target = $dest_rgt + 1;
                    $node_parent = $dest_parent;
                    $result = 1;
                }
                break;
            case 'append':
                if ($this->target == $this->root_id) {
                    $result = 0;
                } else {
                    $node_target = $dest_rgt;
                    $node_parent = $this->target;
                    $result = 1;
                    // Use stored procedure MoveTree(ID_ToMove, ID_NewParent)
                    //$result = $this->dbCNX->query("CALL MoveTree(". $this->id .",". $this->target .")");
                    //$result = $result>0?1:0;
                }
                break;
            default:
                $result = 0;
        }

        if ($result != 0) {
            $this->dbCNX->query("UPDATE ORGANISATION SET LFT_NODE = LFT_NODE + " . $tree_width . " WHERE LFT_NODE >=" . $node_target . ";");
            $this->dbCNX->query("UPDATE ORGANISATION SET RGT_NODE = RGT_NODE + " . $tree_width . " WHERE RGT_NODE >=" . $node_target . ";");
            if ($node_lft >= $node_target) {
                $node_lft = $node_lft + $tree_width;
                $node_rgt = $node_rgt + $tree_width;

                //$this->dbCNX->query("UPDATE ORGANISATION SET RGT_NODE = ". $node_rgt .
                //                                          ", LFT_NODE = ". $node_lft . "WHERE ID >=".$this->id.";");
            }

            $this->dbCNX->query("UPDATE ORGANISATION SET LFT_NODE = LFT_NODE+" . ($node_target - $node_lft) . " WHERE LFT_NODE >=" . $node_lft . " AND LFT_NODE<=" . $node_rgt . ";");
            $this->dbCNX->query("UPDATE ORGANISATION SET RGT_NODE = RGT_NODE+" . ($node_target - $node_lft) . " WHERE RGT_NODE >=" . $node_lft . " AND RGT_NODE<=" . $node_rgt . ";");
            $this->dbCNX->query("UPDATE ORGANISATION SET LFT_NODE = LFT_NODE+" . (-$tree_width) . " WHERE LFT_NODE >=" . ($node_rgt + 1) . ";");
            $this->dbCNX->query("UPDATE ORGANISATION SET RGT_NODE = RGT_NODE+" . (-$tree_width) . " WHERE RGT_NODE >=" . ($node_rgt + 1) . ";");

            $result = $this->dbCNX->query("UPDATE ORGANISATION SET PARENT_ID = " . $node_parent . " WHERE ID=" . $this->id . ";");
        }
        return $result == 1 ? array('success' => true) : array('success' => false, 'error'=> 'Operation not allowed!');
    }

    public function dispatchCMD() {
        switch ($this->cmd) {
            case 'getTree':
                return $this->display_children(-1, array());
                break;
            case 'renameTreeNode':
                return $this->renameTreeNode();
                break;
            case 'removeTreeNode':
                return $this->removeTreeNode();
                break;
            case 'insertTreeChild':
                return $this->insertTreeChild();
                break;
            case 'appendTreeChild':
                return $this->appendTreeChild();
                break;
            case 'moveTreeNode':
                return $this->moveTreeNode();
                break;
            default:
                return array('success' => false, 'error' => 'Comand not implemented!');
        }
    }

}

?>