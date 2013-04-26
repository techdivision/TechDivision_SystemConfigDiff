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
        // Get system diff model to start the diff
        try{
            $this->getSystemDiffModel()->systemDiff();
        }catch(Exception $e){
            Mage::getSingleton('core/session')->addError('SOAP error: ' . $e->getMessage());
            $this->_redirect('*/*/');
            return false;
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
            $filterArray = array(
                'path' => $params['path'],
                'scope' => $params['scope'],
                'scope_id' => $params['scopeId']
            );

            if($this->getSystemDiffModel()->replaceConfig('config', $filterArray)){
                echo 1;
                return;
            } else {
                // No entry found
                echo 0;
                return;
            }
        } catch(Exception $e){
            // In case of something went wrong
            Mage::logException($e);
            echo 0;
            return;
        }
    }

    /**
     * Replace all action
     *
     * @return void
     */
    public function replaceAllAction(){
        $systemDiff = $this->getSystemDiffModel();

        $systemDiff->replaceConfig('config', array());
        $systemDiff->replaceConfig('page', array());
        $systemDiff->replaceConfig('block', array());

        // Redirect to overview
        $this->_redirect('*/*/');
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

    /**
     * @return false|TechDivision_SystemConfigDiff_Model_SystemDiff
     */
    protected function getSystemDiffModel(){
        return Mage::getModel('techdivision_systemconfigdiff/systemDiff');
    }
}