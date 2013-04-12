<?php
/**
 * TechDivision_SystemConfigDiff_Model_System_Config_Source_Paths
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
 * Model for backend configuration field.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Model_System_Config_Source_Paths
{
    /**
     * @var The system.xml config paths
     */
    protected $_paths;

    public function __construct()
    {
        /* @var TechDivision_SystemConfigDiff_Helper_Data $helper */
        $helper = Mage::helper('techdivision_systemconfigdiff');

        // Get array with system.xml paths
        $this->_paths = $helper->getSystemXmlPaths();
        sort($this->_paths);
    }

    public function toOptionArray(){
        $result = array();

        foreach($this->_paths as $path){
            $result[] = array(
                'value' => $path,
                'label' => $path
            );
        }

        return $result;
    }
}