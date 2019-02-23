<?php
namespace Parser;
/*
 * TODO: add class description here
 * @author Oleksandr Shynkariuk oleksandr.shynkariuk@gmail.com
 */


class HiddenLayerNode extends Node {
    private $_parents;
    private $_definitionTable;

    public function __construct($id, $statesMap){
        parent::__construct($id, $statesMap);
        $this->_parents = array();
        $this->_definitionTable = array();
    }

    public function addParent($parent){
        $this->_parents[] = $parent;
    }

    /*
     * @var $parents Array
     * @var $network SMILE_Network
     * */
    public function addAllParents($parents, $network)
    {
        foreach ($parents as $parentName) {
            $parentObj = $network->getNodeByName($parentName);
            if ($parentObj)
                $this->addParent($parentObj);
        }
    }

    public function getParents(){
        return $this->_parents;
    }

    /**
     * @var $probabilities Array
     * generate a table with probabilities for every combination of states of the parents
     */
    public function generateDefinitionTable($probabilities){
        $rows = count(parent::getStatesMap());

        $i = 0;
        $arr = array();
        foreach($probabilities as $prob){
            if($i % $rows == 0){
                if(!empty($arr)){
                    $this->_definitionTable[] = $arr;
                }
                $arr = array();
                $arr[] = $prob;
            } else{
                $arr[] = $prob;
            }
            ++$i;
        }
        $this->_definitionTable[] = $arr;//don't forget to add the last entry :)
    }
}