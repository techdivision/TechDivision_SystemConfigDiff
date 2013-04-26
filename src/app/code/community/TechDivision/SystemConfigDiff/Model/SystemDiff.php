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
 * System diff model to call webservice and to start all configured differs.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Model
 * @copyright  Copyright (c) 1996-2013 TechDivision GmbH (http://www.techdivision.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    ${release.version}
 * @since      Class available since Release 0.1.0
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigdiff_Model_SystemDiff
{
    /**
     * Calls the webservice of the other system to get the system data
     * and starts the diff for all configured diffs in the backend.
     */
    public function systemDiff()
    {
        /**
         * @var TechDivision_SystemConfigDiff_Helper_Config $configHelper
         */
        $configHelper = Mage::helper('techdivision_systemconfigdiff/config');

        // Get API credentials
        $apiUrl = $configHelper->getSystemsettingsSystemUrl();
        $apiUser = $configHelper->getSystemsettingsUser();
        $apiPwd = $configHelper->getSystemsettingsPassword();
        if(!$apiUrl){
            throw new Exception('Missing API configuration!');
        }

        // Set up the web service client
        $proxy = new SoapClient($apiUrl);

        // Login and get system config of other system
        if($configHelper->getSystemsettingsWsi()){
            $session = $proxy->login(array(
                'username' => $apiUser,
                'apiKey' => $apiPwd
            ));
            $sessionId = $session->result;
            $otherConfig = $proxy->systemConfigGetConfig(array('sessionId' => $sessionId));
            $otherConfig = $otherConfig->result;
        } else {
            $sessionId = $proxy->login($apiUser, $apiPwd);
            $otherConfig = $proxy->systemConfigGetConfig($sessionId);
        }

        // Deserialize the result
        $otherConfig = json_decode($otherConfig, true);

        // Get system config of this system
        $thisConfig = array();
        foreach($configHelper->getDiffers() as $differ) {
            $thisConfig = array_merge($differ->getSystemData(), $thisConfig);
        }

        // All registered differs do the diff
        foreach($configHelper->getDiffers() as $differ) {
            $differ->diff($thisConfig, $otherConfig);
        }
    }

    /**
     * Replaces the config by the given diff defined by filter array.
     *
     * @param $diffModel The diff model the diff belongs to
     * @param $filterArray The filter array specifies the diff
     * @return bool (Un-)successful
     */
    public function replaceConfig($diffModel, $filterArray)
    {
        $baseUrlPaths = array();
        if(!Mage::helper('techdivision_systemconfigdiff/config')->getSystemsettingsBaseUrlReplaceEnabled()){
            $baseUrlPaths[] = 'web/unsecure/base_url';
            $baseUrlPaths[] = 'web/secure/base_url';
        }

        // Find the diff entry specified by filter array
        $collection = Mage::getModel('techdivision_systemconfigdiff/' . $diffModel)->getCollection();
        foreach($filterArray as $filter => $filterValue){
            $collection->addFieldToFilter($filter, $filterValue);
        }

        // If no data was found
        if($collection->count() === 0){
            return false;
        }

        // If entry was found
        foreach($collection as $entry){
            // Save the config value from other system
            $value = $entry->getSystem2Value();
            $path = $entry->getPath();

            // Skip if path is base url specific
            if(in_array($path, $baseUrlPaths)) continue;

            $scope = $entry->getScope();
            $scopeId = $entry->getScopeId();

            /* @var Mage_Core_Model_Resource_Config $configResource */
            $configResource = Mage::getResourceModel('core/config');
            $configResource->saveConfig($path, $value, $scope, $scopeId);

            // Delete the diff entry
            /* @var TechDivision_SystemConfigDiff_Helper_Data $helper */
            $helper = Mage::helper('techdivision_systemconfigdiff');
            $helper->deleteDiff($entry, $path, $scope, $scopeId);
        }

        // Deletion successful
        return true;
    }

    /**
     * Gets called by cron job and starts diff if enabled.
     */
    public function doCron()
    {
        if(Mage::helper('techdivision_systemconfigdiff/config')->getSystemsettingsCronEnabled()){
            $this->systemDiff();
        }

        return;
    }
}