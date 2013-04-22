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
 * System config tab container implementation.
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
class TechDivision_SystemConfigDiff_Block_Adminhtml_Overview_Tabs_SystemConfig extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('systemConfigGrid');
        $this->setDefaultSort('scope_name');
        $this->setUseAjax(true);
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('techdivision_systemconfigdiff/config')->getCollection()->addFieldToFilter('systemXml', '1');
        $this->setCollection($collection);
        $this->setDefaultLimit(50);

        return parent::_prepareCollection();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $configHelper = Mage::helper('techdivision_systemconfigdiff/config');

        $this->addColumn(
            'scope_name',
            array(
                'header' => $this->defaultHelper()->__('Scope name'),
                'align'  => 'right',
                'width'  => '130px',
                'index'  => 'scope_name',
            )
        );

        $this->addColumn(
            'path',
            array(
                'header' => $this->defaultHelper()->__('Config path'),
                'align'  => 'right',
                'width'  => '270px',
                'index'  => 'path',
            )
        );

        $this->addColumn(
            'system1_value',
            array(
                'header' => $configHelper->getDisplaysettingsAliasThis(),
                'align'  => 'right',
                'index'  => 'system1_value',
            )
        );

        $this->addColumn(
            'system2_value',
            array(
                'header' => $configHelper->getDisplaysettingsAliasOther(),
                'align'  => 'left',
                'index'  => 'system2_value',
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/systemGrid', array('_current' => true));
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