<?php
/**
 * TechDivision_SystemConfigDiff_Block_Adminhtml_Overview_Tabs_Blocks
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
 * Block tab container implementation.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Block_Adminhtml_Overview_Tabs_Block extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('blocksGrid');
		$this->setDefaultSort('identifier');
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
    	$collection = Mage::getModel('techdivision_systemconfigdiff/block')->getCollection();
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
            'identifier',
            array(
                'header' => $this->defaultHelper()->__('Block identifier'),
                'align'  => 'right',
                'width'  => '130px',
                'index'  => 'identifier',
            )
        );

        $this->addColumn(
            'store_name',
            array(
                'header' => $this->defaultHelper()->__('Store name'),
                'align'  => 'right',
                'width'  => '130px',
                'index'  => 'store_name',
            )
        );

    	$this->addColumn(
    			'system1_content',
    			array(
    					'header' => $configHelper->getDisplaysettingsAliasThis(),
    					'align'  => 'right',
    					'index'  => 'system1_content',
    			)
    	);

    	$this->addColumn(
    			'system2_content',
    			array(
    					'header' => $configHelper->getDisplaysettingsAliasOther(),
    					'align'  => 'left',
    					'index'  => 'system2_content',
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
            : $this->getUrl('*/*/blockGrid', array('_current' => true));
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