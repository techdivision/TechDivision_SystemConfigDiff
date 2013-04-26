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