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
 * Abstract tab container implementation.
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
abstract class TechDivision_SystemConfigDiff_Block_Adminhtml_Overview_Tabs_Abstract
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	
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

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;    
    }
}