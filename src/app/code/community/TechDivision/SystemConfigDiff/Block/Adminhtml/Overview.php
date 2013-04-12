<?php
/**
 * TechDivision_SystemConfigDiff_Block_Adminhtml_Overview
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
 * Implementation of a form to edit diff result.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Block_Adminhtml_Overview
    extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();

        $this->setId('diff_edit');
        $this->_objectId = 'id';
        $this->_blockGroup = 'techdivision_systemconfigdiff';
        $this->_controller = 'adminhtml_overview';
        $this->removeButton('save');
        $this->removeButton('delete');
        $this->removeButton('reset');
        $this->removeButton('back');
        $this->_addButton('new', array(
            'label'     => $this->defaultHelper()->__('New diff'),
            'onclick'   => 'self.location.href=\'' . $this->getUrl('*/*/new') . '\'',
            'class'     => 'save',
        ), -100);

    }

    /**
     * Returns the default helper instance.
     *
     * @return TechDivision_SystemConfigDiff_Helper_Data
     * 		The helper instance
     */
    public function defaultHelper()
    {
        return Mage::helper('techdivision_systemconfigdiff');
    }

    public function getHeaderText()
    {
        return $this->defaultHelper()->__('System diff');
    }
}