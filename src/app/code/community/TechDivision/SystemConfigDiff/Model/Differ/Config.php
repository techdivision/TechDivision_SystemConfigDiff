<?php
/**
 * TechDivision_SystemConfigDiff_Model_Differ_Config
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 1996-2011 TechDivision GmbH (http://www.techdivision.com)
 * @license	   http://www.gnu.org/licenses/gpl.html GPL, version 3
 * @version    ${release.version}
 * @link       http://www.techdivision.com
 * @since      File available since Release 0.1.2
 * @author     Florian Sydekum <fs@techdivision.com>
 */

/**
 * Differ implementation for system config.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Model_Differ_Config extends TechDivision_SystemConfigDiff_Model_Differ
{
    /**
     * The diff key defines the data for this differ in the system data array
     */
    const DIFF_KEY = "config";

    /**
     * @var array Contains all paths specified all system.xml files
     */
    protected $_systemXmlPaths;

    /**
     * @inheritdoc
     */
    public function diff($thisConfig, $otherConfig){
        $thisConfig = $thisConfig[self::DIFF_KEY];
        $otherConfig = $otherConfig[self::DIFF_KEY];

        // Flatten both config arrays
        $thisConfig['default'] = $this->_flattenArray($thisConfig['default'], '');
        $thisConfig['websites'] = $this->_flattenArray($thisConfig['websites'], '');
        $thisConfig['stores'] = $this->_flattenArray($thisConfig['stores'], '');
        $otherConfig['default'] = $this->_flattenArray($otherConfig['default'], '');
        $otherConfig['websites'] = $this->_flattenArray($otherConfig['websites'], '');
        $otherConfig['stores'] = $this->_flattenArray($otherConfig['stores'], '');

        // Do the actual diff
        $diff = array();
        $diff['default'] = $this->_diffFromArrays($thisConfig['default'], $otherConfig['default']);
        $diff['websites'] = $this->_diffFromArrays($thisConfig['websites'], $otherConfig['websites']);
        $diff['stores'] = $this->_diffFromArrays($thisConfig['stores'], $otherConfig['stores']);

        // Empty database tables
        $this->_emptyDb(self::DIFF_KEY);

        // Save new diff
        $this->_saveDiff($diff);
    }

    /**
     * Returns the system config for all scopes (default, websites, stores)
     *
     * @return array
     */
    public function getSystemData(){
        /** @var Mage_Core_Model_Config $config */
        $config = Mage::app()->getConfig();
        $config->loadDb();

        /*
         *  Get config for each scope (default, websites, stores)
         *  default:  default/path
         *  websites: websites/WEBSITE_CODE/path
         *  stores:   stores/STORE_CODE/path
         *
         *  example: WEBSITE_CODE = 'Deutschland', STORE_CODE = 'de_DE'
         *
         *  Contains also content of <default>, <websites>, <stores> tags
         *  of every config.xml
         */
        $result = array(
            'default'  => $config->getNode('default')->asArray(),
            'websites' => $config->getNode('websites')->asArray(),
            'stores'   => $config->getNode('stores')->asArray()
        );

        return array(self::DIFF_KEY => $result);
    }

    /**
     * Saves the diff array to the database
     *
     * @param $diff
     */
    protected function _saveDiff($diff){
        /* @var TechDivision_SystemConfigDiff_Helper_Data $helper */
        $helper = Mage::helper('techdivision_systemconfigdiff');

        // Get array with system.xml paths
        $this->_systemXmlPaths = $helper->getSystemXmlPaths();

        // For every scope (default, websites, stores)
        foreach($diff as $scope => $scopeData){

            // Both system's data
            $system1 = $scopeData[1];
            $system2 = $scopeData[2];

            // The data to save
            $toSave = array();

            // Remember the paths which already have been persisted
            $pathsAlreadyDone = array();

            // For each path from system1
            foreach($system1 as $path => $value1){

                // Ignore config data of this module
                if(strpos($path, $this->_getConfigHelper()->getModuleAlias())){
                    $pathsAlreadyDone[] = $path;
                    continue;
                }

                // Get the value by path from system2, can be null if not set in system2
                if(array_key_exists($path, $system2)){
                    $value2 = $system2[$path];
                } else {
                    $value2 = null;
                }

                // Extract the scope id from path
                $pathScopeData = $this->_extractScopeId($scope, $path);

                $toSave['path'] = $pathScopeData['path'];
                $toSave['scope'] = $scope;
                $toSave['scope_id'] = $pathScopeData['scope_id'];
                $toSave['scope_name'] = $pathScopeData['scope_name'];
                $toSave['code'] = $pathScopeData['code'];
                $toSave['system1_value'] = $value1;
                $toSave['system2_value'] = $value2;
                $this->_isSystemXmlPath($pathScopeData['path']) ? $toSave['systemXml'] = 1 : $toSave['systemXml'] = 0;

                /**
                 * @var TechDivision_SystemConfigDiff_Model_Config $config
                 */
                $config = Mage::getModel('techdivision_systemconfigdiff/config');
                // Save data
                $config->addData($toSave)->save();

                // Path already persisted
                $pathsAlreadyDone[] = $path;
            }

            // Reverse: if system2 has values which are not set in system1
            foreach($system2 as $path => $value2){

                // Skip all already persisted paths
                if(in_array($path, $pathsAlreadyDone)){
                    continue;
                }

                // Extract the scope id from path
                $pathScopeData = $this->_extractScopeId($scope, $path);

                $toSave['path'] = $pathScopeData['path'];
                $toSave['scope'] = $scope;
                $toSave['scope_id'] = $pathScopeData['scope_id'];
                $toSave['scope_name'] = $pathScopeData['scope_name'];
                $toSave['code'] = $pathScopeData['code'];
                $toSave['system1_value'] = null;
                $toSave['system2_value'] = $value2;
                $this->_isSystemXmlPath($pathScopeData['path']) ? $toSave['systemXml'] = 1 : $toSave['systemXml'] = 0;

                /**
                 * @var TechDivision_SystemConfigDiff_Model_Config $config
                 */
                $config = Mage::getModel('techdivision_systemconfigdiff/config');
                // Save data
                $config->addData($toSave)->save();
            }
        }
    }

    /**
     * Extracts the scope code from the path and returns the scope id and path
     *
     * @param $scope The actual scope context
     * @param $path The config path
     * @return array
     */
    protected function _extractScopeId($scope, $path){
        // Return 'Default' for default scope
        if($scope === 'default'){
            return array('scope_id' => 0, 'path' => substr($path, 1), 'scope_name' => 'Default', 'code' => '');
        } else
        if($scope === 'websites'){
            $_scope = 'website';
        } else
        if($scope === 'stores'){
            $_scope = 'store';
        }

        // Get scope specific collection model
        $scopeCode = explode('/', $path, 3);
        $collection = Mage::getModel('core/' . $_scope)->getCollection();

        // Activate default website/store loading
        $collection->setFlag('load_default_' . $_scope, true);

        // Get website/store ($scopeItem)
        $scopeItem = $collection->addFieldToFilter('code', $scopeCode[1])->getFirstItem();

        // Get id and name of website/store
        $id = call_user_func(array($scopeItem, 'get' . ucfirst($_scope) . 'Id'));
        if(!is_null($id)){
            $name = $scopeItem->getName();
        } else {
            // If this system misses website/store
            $name = 'MISSING ' . strtoupper($_scope);
        }

        return array('scope_id' => $id, 'path' => $scopeCode[2], 'scope_name' => $name, 'code' => $scopeCode[1]);
    }

    /**
     * Checks if given path is a path defined by a system.xml file
     *
     * @param $path The path to be checked
     * @return bool
     */
    protected function _isSystemXmlPath($path){
        foreach($this->_systemXmlPaths as $_path){
            if(strstr($_path, $path)){
                return true;
            }
        }
        return false;
    }
}