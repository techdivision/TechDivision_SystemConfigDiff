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
 * Implements controller logic for system diff.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Controller
 * @copyright  Copyright (c) 1996-2013 TechDivision GmbH (http://www.techdivision.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    ${release.version}
 * @since      Class available since Release 0.1.0
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Adminhtml_IndexController
    extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('techdivision_systemconfigdiff/diff_overview')
            ->_addBreadcrumb('diff overview','diff overview');

        return $this;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * New action
     *
     * @return void
     */
    public function newAction()
    {
        /**
         * @var TechDivision_SystemConfigDiff_Helper_Config $configHelper
         */
        $configHelper = Mage::helper('techdivision_systemconfigdiff/config');

        try{
            // Get API credentials
            $apiUrl = $configHelper->getSystemsettingsSystemUrl();
            $apiUser = $configHelper->getSystemsettingsUser();
            $apiPwd = $configHelper->getSystemsettingsPassword();
            if(!$apiUrl){
                Mage::getSingleton('core/session')->addError('SOAP error: Missing api configuration.');
                $this->_redirect('*/*/');
                return false;
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
            } else {
                $sessionId = $proxy->login($apiUser, $apiPwd);
                $otherConfig = $proxy->systemConfigGetConfig($sessionId);
            }

            // Deserialize the result
            $otherConfig = json_decode($otherConfig, true);
        }catch(Exception $e){
            Mage::getSingleton('core/session')->addError('SOAP error: ' . $e->getMessage());
            $this->_redirect('*/*/');
            return false;
        }

        // Get system config of this system
        $thisConfig = array();
        foreach($configHelper->getDiffers() as $differ) {
            $thisConfig = array_merge($differ->getSystemData(), $thisConfig);
        }

        // All registered differs do the diff
        foreach($configHelper->getDiffers() as $differ) {
            $differ->diff($thisConfig, $otherConfig);
        }

        // Redirect to overview
        $this->_redirect('*/*/');
    }

    /**
     * Update action
     *
     * @return void
     */
    public function updateAction(){
        try{
            // Get path, scope and scopeId from Ajax call
            $params = $this->getRequest()->getparams();
            $path = $params['path'];
            $scope = $params['scope'];
            $scopeId = $params['scopeId'];

            // Find the diff entry specified by path, scope and scopeId
            $collection = Mage::getModel('techdivision_systemconfigdiff/config')->getCollection()
                ->addFieldToFilter('systemXml', '1')
                ->addFieldToFilter('path', $path)
                ->addFieldtoFilter('scope', $scope)
                ->addFieldToFilter('scope_id', $scopeId);

            // If entry was found
            $entry = $collection->getFirstItem();
            if(count($entry->getData()) > 0){
                // Save the config value from other system
                $value = $entry->getSystem2Value();
                /* @var Mage_Core_Model_Resource_Config $configResource */
                $configResource = Mage::getResourceModel('core/config');
                $configResource->saveConfig($path, $value, $scope, $scopeId);

                // Delete the diff entry
                /* @var TechDivision_SystemConfigDiff_Helper_Data $helper */
                $helper = Mage::helper('techdivision_systemconfigdiff');
                $helper->deleteDiff($entry, $path, $scope, $scopeId);

                // Deletion successful
                echo 1;
                return;
            }

            // No entry found
            echo 0;
            return;
        } catch(Exception $e){
            // In case of something went wrong
            Mage::logException($e);
            echo 0;
            return;
        }
    }

    /**
     * SystemGrid action
     *
     * @return void
     */
    public function systemGridAction(){
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('techdivision_systemconfigdiff/adminhtml_overview_tabs_systemConfig', 'tab_systemConfig')
                ->toHtml()
        );
    }

    /**
     * ConfigGrid action
     *
     * @return void
     */
    public function configGridAction(){
        $this->getResponse()->setBody(
            $this->getLayout()
                 ->createBlock('techdivision_systemconfigdiff/adminhtml_overview_tabs_config', 'tab_config')
                 ->toHtml()
        );
    }

    /**
     * PageGrid action
     *
     * @return void
     */
    public function pageGridAction(){
        $this->getResponse()->setBody(
            $this->getLayout()
                 ->createBlock('techdivision_systemconfigdiff/adminhtml_overview_tabs_page', 'tab_page')
                 ->toHtml()
        );
    }

    /**
     * BlockGrid action
     *
     * @return void
     */
    public function blockGridAction(){
        $this->getResponse()->setBody(
            $this->getLayout()
                 ->createBlock('techdivision_systemconfigdiff/adminhtml_overview_tabs_block', 'tab_block')
                 ->toHtml()
        );
    }
}