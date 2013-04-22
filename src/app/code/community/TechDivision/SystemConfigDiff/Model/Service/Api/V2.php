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
 * API extension to deliver system data.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Model
 * @copyright  Copyright (c) 1996-2013 TechDivision GmbH (http://www.techdivision.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    ${release.version}
 * @since      Class available since Release 0.1.0
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Model_Service_Api_V2 extends TechDivision_SystemConfigDiff_Model_Service_Api
{
    /**
     * Returns all data defined by the differs
     *
     * @return string
     */
    public function getWebshopConfig()
    {
        /**
         * @var TechDivision_SystemConfigDiff_Helper_Config $configHelper
         */
        $configHelper = Mage::helper('techdivision_systemconfigdiff/config');

        $systemData = array();
        foreach($configHelper->getDiffers() as $differ) {
            $systemData = array_merge($differ->getSystemData(), $systemData);
        }

        return json_encode($systemData);
    }
}