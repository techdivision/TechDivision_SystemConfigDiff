<?php
/**
 * TechDivision_SystemConfigDiff_Model_Differ
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Model
 * @copyright  Copyright (c) 1996-2011 TechDivision GmbH (http://www.techdivision.com)
 * @license	   http://www.gnu.org/licenses/gpl.html GPL, version 3
 * @version    ${release.version}
 * @link       http://www.techdivision.com
 * @since      File available since Release 0.1.2
 * @author     Florian Sydekum <fs@techdivision.com>
 */

/**
 * Abstract differ class for all differ implementations.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Model
 * @copyright  Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Florian Sydekum <fs@techdivision.com>
 */
abstract class TechDivision_SystemConfigDiff_Model_Differ
    extends Mage_Core_Model_Abstract
{
    /**
     * Initiates the diff
     */
    abstract function diff($thisConfig, $otherConfig);

    /**
     * Returns the system data as array
     *
     * @return mixed
     */
    abstract function getSystemData();

    /**
     * Depth First Search algorithm to flatten an multi associative array.
     * All keys will be merged to a path, separated by /
     * The last value (leaf of tree) will be stored as: path => value
     *
     * @param $arr The multi associative array
     * @param $path The start path, usually ''
     * @return array Array of path => value
     */
    protected function _flattenArray($arr, $path){
        $result = array();

        if(!is_array($arr)){
            return array($path => $arr);
        }

        foreach($arr as $key => $value){
            $_path = $path;
            $_path = $_path . '/' . $key;
            $res = $this->_flattenArray($value, $_path);
            $result = array_merge($res, $result);
        }

        return $result;
    }

    /**
     * Does the diff of two arrays and returns the diff as array
     */
    protected function _diffFromArrays($arr1, $arr2){
        $result = array();

        $result[1] = array_diff_assoc($arr1, $arr2);
        $result[2] = array_diff_assoc($arr2, $arr1);

        return $result;
    }

    /**
     * Empties the database tables for the given model
     *
     * @param $model The model's database table to be emptied
     */
    protected function _emptyDb($model){
        $collection = Mage::getModel('techdivision_systemconfigdiff/'. $model)->getCollection();
        foreach($collection as $entry){
            $entry->delete();
        }
    }

    /**
     * Returns the config helper
     *
     * @return Mage_Core_Helper_Abstract
     */
    protected function _getConfigHelper(){
        return Mage::helper('techdivision_systemconfigdiff/config');
    }
}