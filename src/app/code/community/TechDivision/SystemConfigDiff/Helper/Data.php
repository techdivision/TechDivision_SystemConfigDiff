<?php
/**
 * TechDivision_SystemConfigDiff
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * Data helper implementation.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Helper
 * @copyright  Copyright (c) 1996-2013 TechDivision GmbH (http://www.techdivision.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    ${release.version}
 * @since      Class available since Release 0.1.0
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    /**
     * @var The ignore paths configured in backend
     */
    protected $_ignorePaths;

    public function getIgnorePaths(){
        if(!$this->_ignorePaths){
            /* @var TechDivision_SystemConfigDiff_Helper_Config $configHelper */
            $configHelper = Mage::helper('techdivision_systemconfigdiff/config');

            $this->_ignorePaths = $configHelper->getDisplaysettingsIgnorePaths();
        }

        return $this->_ignorePaths;
    }

    /**
     * Returns all ids of the sections which have a diff error.
     * Called by tabs.phtml
     *
     * @return array
     */
    public function getSectionIds()
    {
        $sectionIds = array();
        $collection = Mage::getModel('techdivision_systemconfigdiff/config')->getCollection()->addFieldtoFilter('systemXml', '1');

        // Get actual website and store code
        $configData = Mage::getSingleton('adminhtml/config_data');
        $website = $configData->getWebsite();
        $store = $configData->getStore();

        // Filter collection
        if($website !== '' && $store === ''){
            // Website scope
            $collection->addFieldToFilter('scope', 'websites');
            $collection->addFieldToFilter('code', $website);
        } else if($website !== '' && $store !== ''){
            // Store scope
            $collection->addFieldToFilter('scope', 'stores');
            $collection->addFieldToFilter('code', $store);
        } else {
            // Default scope
            $collection->addFieldToFilter('scope', 'default');
        }

        // Get the path for every entry and add the section part of it to the result array
        foreach($collection as $entry){
            $path = $entry->getPath();

            // Check if we should ignore this path for displaying
            if(in_array($path, $this->getIgnorePaths())) continue;

            $pathExpl = explode('/', $path, 2);
            if(!in_array($pathExpl[0], $sectionIds)){
                $sectionIds[] = $pathExpl[0];
            }
        }

        return $sectionIds;
    }

    /**
     * Returns all scope codes which have a diff error.
     * Called by switcher.phtml
     *
     * @return array
     */
    public function getValues()
    {
        $values = array();
        $collection = Mage::getModel('techdivision_systemconfigdiff/config')->getCollection()->addFieldtoFilter('systemXml', '1');

        // Get the scope for every entry
        foreach($collection as $entry){
            // Check if we should ignore this path for displaying
            if(in_array($entry->getPath(), $this->getIgnorePaths())) continue;

            $scope = $entry->getScope();

            // Add default scope immediately
            if($scope === 'default'){
                if(!in_array($scope, $values)){
                    $values[] = $scope;
                }
                continue;
            }

            // If not default scope get the scope code
            $code = $entry->getCode();

            // Due to inconsistencies of Magento we have to cut the s of websites/stores
            $scope = substr_replace($scope ,"", -1);

            $scope = $scope . '_' . $code;

            // Add scope code
            if(!in_array($scope, $values)){
                $values[] = $scope;
            }
        }

        return $values;
    }

    /**
     * Returns the inline element css style without background definition.
     * Called by switcher.phtml to override the background with diff error image.
     *
     * @param $style The inline css style of the element
     * @return string
     */
    public function getElementStyle($style)
    {
        $styles = explode(';', $style);
        $style = array();
        foreach($styles as $_style){
            if(strncmp(trim($_style), 'background', strlen('background'))){
                $style[] = $_style;
            }
        }

        return implode(';', $style);
    }

    /**
     * Deletes a diff entry and all inherited config diffs specified by path, scope and scopeId
     *
     * @param $entry The diff entry to be deleted
     * @param $path The config path
     * @param $scope Dependent of the scope the inherited config diffs will be deleted
     * @param $scopeId Dependent of the scopeId the inherited config diffs will be deleted
     */
    public function deleteDiff($entry, $path, $scope, $scopeId)
    {
        // The value for actual $scope which is the reference value for all underlying scopes
        $compareValue = $entry->getSystem1Value();

        // Delete this entry (for this $scope and $path)
        $entry->delete();

        // For default scope check all websites and stores
        if($scope === 'default'){
            // The website ids which have been deleted
            $deletedIds = array();

            // Get all websites scope diffs
            $configCollection = Mage::getModel('techdivision_systemconfigdiff/config')->getCollection()
                ->addFieldToFilter('systemXml', '1')
                ->addFieldToFilter('path', $path)
                ->addFieldtoFilter('scope', 'websites');

            // For all found diff entries
            foreach($configCollection as $diffEntry){
                // Get the website scope value and compare it with the reference value (default scope)
                $websiteValue = $diffEntry->getSystem1Value();
                if($websiteValue === $compareValue){
                    // Add the website id to deletedIds array and delete diff entry
                    $deletedIds[] = $diffEntry->getScopeId();
                    $diffEntry->delete();
                }
            }

            // Get all stores
            $storeCollection = Mage::getModel('core/store')->getCollection();
            // Activate default website/store loading
            $storeCollection->setFlag('load_default_store', true);

            // For all stores
            foreach($storeCollection as $store){
                // Check if $store belongs to one of the websites which have been deleted before
                if(!in_array($store->getWebsiteId(), $deletedIds)){
                    continue;
                }

                // Get the store scope diff
                $configCollection = Mage::getModel('techdivision_systemconfigdiff/config')->getCollection()
                    ->addFieldToFilter('systemXml', '1')
                    ->addFieldToFilter('path', $path)
                    ->addFieldtoFilter('scope', 'stores')
                    ->addFieldToFilter('scope_id', $store->getStoreId());

                // It can only be one entry
                $diffEntry = $configCollection->getFirstItem();
                if(count($diffEntry->getData()) > 0){
                    // Get the store scope value and compare it with the reference value (default scope)
                    $storeValue = $diffEntry->getSystem1Value();
                    if($storeValue === $compareValue){
                        // Delete diff entry
                        $diffEntry->delete();
                    }
                }
            }
        } else if($scope === 'websites'){
            // For websites scope check stores

            // Get all stores
            $storeCollection = Mage::getModel('core/store')->getCollection();
            // Activate default website/store loading
            $storeCollection->setFlag('load_default_store', true);

            // For all stores
            foreach($storeCollection as $store){
                // Check if $store belongs to the website
                if($store->getWebsiteId() !== $scopeId){
                    continue;
                }

                // Get the store scope diff
                $configCollection = Mage::getModel('techdivision_systemconfigdiff/config')->getCollection()
                    ->addFieldToFilter('systemXml', '1')
                    ->addFieldToFilter('path', $path)
                    ->addFieldtoFilter('scope', 'stores')
                    ->addFieldToFilter('scope_id', $store->getStoreId());

                // It can only be one entry
                $diffEntry = $configCollection->getFirstItem();
                if(count($diffEntry->getData()) > 0){
                    // Get the store scope value and compare it with the reference value (default scope)
                    $storeValue = $diffEntry->getSystem1Value();
                    if($storeValue === $compareValue){
                        // Delete diff entry
                        $diffEntry->delete();
                    }
                }
            }
        }
    }

    /*
     * Returns the array of paths defined in all system.xml files
     */
    public function getSystemXmlPaths(){
        $systemXmlPaths = array();

        // Get all sections of system config
        $config = Mage::getConfig()->loadModulesConfiguration('system.xml')->applyExtends();
        $configSections = $config->getNode('sections')->asArray();

        // Iterate over sections/*/groups/*/fields/* axis
        foreach($configSections as $key => $sections){
            $sectionName = $key;
            foreach($sections['groups'] as $key => $groups){
                $groupName = $key;
                if(isset($groups['fields']) && is_array($groups['fields'])){
                    foreach($groups['fields'] as $key => $fields){
                        $systemXmlPaths[] = $sectionName . '/' . $groupName . '/' . $key;
                    }
                } else {
                    $systemXmlPaths[] = $sectionName . '/' . $groupName;
                }
            }
        }

        return $systemXmlPaths;
    }

    /**
     * Checks if any diff entry is available.
     *
     * @return bool
     */
    public function diffsAvailable()
    {
        $models = array(
            'config' => Mage::getModel('techdivision_systemconfigdiff/config'),
            'page' => Mage::getModel('techdivision_systemconfigdiff/page'),
            'block' => Mage::getModel('techdivision_systemconfigdiff/block')
        );

        foreach($models as $model){
            if($model->getCollection()->count() > 0) return true;
        }

        return false;
    }
}