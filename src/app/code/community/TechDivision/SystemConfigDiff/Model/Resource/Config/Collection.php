<?php
/**
 * TechDivision_SystemConfigDiff_Model_Resource_Config_Collection
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Model
 * @copyright  Copyright (c) 1996-2011 TechDivision GmbH (http://www.techdivision.com)
 * @license	   http://www.gnu.org/licenses/gpl.html GPL, version 3
 * @version    ${release.version}
 * @link       http://www.techdivision.com
 * @since      File available since Release 0.1.2
 * @author     Florian Sydekum <fs@techdivision.com>
 */

/**
 * Collection resource model to handle configs.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Model
 * @copyright  Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Model_Resource_Config_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Declare base table and mapping of some fields.
     *
     * @return void.
     */
    protected function _construct()
    {
        $this->_init('techdivision_systemconfigdiff/config');
    }
}