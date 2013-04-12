<?php
/**
 * TechDivision_SystemConfigDiff_Model_Observer
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
 * Observer for injection of templates, css and js before layout rendering.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Model_Observer
{
    /**
     * Interrupts the layout rendering to inject custom templates, js and css
     *
     * @param $observer
     */
    public function updateTemplate($observer){
        $controller = Mage::app()->getRequest()->getControllerName();
        $action = Mage::app()->getRequest()->getActionName();
        $moduleName = Mage::app()->getRequest()->getModuleName();

        // Add css and js to System -> Config diff
        if('admin_techdivision_systemconfigdiff' === $moduleName){
            $headBlock = Mage::getSingleton('core/layout')->getBlock('head');
            $headBlock->addCss('css/techdivision/systemconfigdiff/diff.css');
            $headBlock->addItem('skin_js', 'js/techdivision/systemconfigdiff/jquery-1.9.1.min.js');
            $headBlock->addItem('skin_js', 'js/techdivision/systemconfigdiff/diff.js');
        }

        // Add css and update template to System -> Config
        if('system_config' === $controller && 'edit' === $action){
            $displayDiff = Mage::helper('techdivision_systemconfigdiff/config')->getDisplaysettingsShowDiff();
            $layout = Mage::getSingleton('core/layout');

            $headBlock = $layout->getBlock('head');
            $switcherBlock = $layout->getBlock('adminhtml.system.config.switcher');
            $tabBlock = $layout->getBlock('left.child1');

            // If display diff is set to true
            if($displayDiff){
                $headBlock->addCss('css/techdivision/systemconfigdiff/diff.css');
                $headBlock->addItem('skin_js', 'js/techdivision/systemconfigdiff/jquery-1.9.1.min.js');
                $headBlock->addItem('skin_js', 'js/techdivision/systemconfigdiff/diff.js');
                $switcherBlock->setTemplate('techdivision/systemconfigdiff/switcher.phtml');
                $tabBlock->setTemplate('techdivision/systemconfigdiff/tabs.phtml');
            }
        }
    }
}