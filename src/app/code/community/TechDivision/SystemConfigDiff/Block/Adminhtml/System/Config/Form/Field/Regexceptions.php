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
 * Rewrites core block without changes. Was rewritten because of inheritance to
 * Form_Field.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 1996-2013 TechDivision GmbH (http://www.techdivision.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    ${release.version}
 * @since      Class available since Release 0.1.0
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Block_Adminhtml_System_Config_Form_Field_Regexceptions
        extends TechDivision_SystemConfigDiff_Block_Adminhtml_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('regexp', array(
            'label' => Mage::helper('adminhtml')->__('Matched Expression'),
            'style' => 'width:120px',
        ));
        $this->addColumn('value', array(
            'label' => Mage::helper('adminhtml')->__('Value'),
            'style' => 'width:120px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Exception');
        parent::__construct();
    }
}
