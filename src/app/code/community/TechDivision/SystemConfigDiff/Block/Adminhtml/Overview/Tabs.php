<?php
/**
 * TechDivision_SystemConfigDiff_Block_Adminhtml_Overview_Tabs
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
 * Implementation of the tabs for the form to edit diff result.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Block_Adminhtml_Overview_Tabs
	extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Constructs the tabs section
     */
    public function __construct()
	{
	    parent::__construct();
	    $this->setId('diff_tabs');
	    $this->setDestElementId('edit_form');
	    $this->setTitle($this->defaultHelper()->__('System diff'));
	}

    /**
     * Adds the tabs with ajax class and ajax urls
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        $this->addTab('system_config_diff', array(
            'label'     => $this->defaultHelper()->__('System Config (system.xml)'),
            'title'     => $this->defaultHelper()->__('System Config (system.xml)'),
            'url'       => $this->getUrl('*/*/systemGrid', array('_current' => true)),
            'class'     => 'ajax',
        ));

        $this->addTab('config_diff', array(
            'label'     => $this->defaultHelper()->__('Config (config.xml and more)'),
            'title'     => $this->defaultHelper()->__('Config (config.xml and more)'),
            'url'       => $this->getUrl('*/*/configGrid', array('_current' => true)),
            'class'     => 'ajax',
        ));

        $this->addTab('page_diff', array(
            'label'     => $this->defaultHelper()->__('CMS Pages'),
            'title'     => $this->defaultHelper()->__('CMS Pages'),
            'url'       => $this->getUrl('*/*/pageGrid', array('_current' => true)),
            'class'     => 'ajax',
        ));

        $this->addTab('block_diff', array(
            'label'     => $this->defaultHelper()->__('CMS Blocks'),
            'title'     => $this->defaultHelper()->__('CMS Blocks'),
            'url'       => $this->getUrl('*/*/blockGrid', array('_current' => true)),
            'class'     => 'ajax',
        ));

        return parent::_beforeToHtml();
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
}