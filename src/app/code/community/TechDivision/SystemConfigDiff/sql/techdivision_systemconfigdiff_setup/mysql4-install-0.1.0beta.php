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
 * Database install script.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Setup
 * @copyright  Copyright (c) 1996-2013 TechDivision GmbH (http://www.techdivision.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    ${release.version}
 * @since      Class available since Release 0.1.0
 * @author     Florian Sydekum <fs@techdivision.com>
 */

/* initiate installer ********************************************************/
/** @var $setup TechDivision_SystemConfigDiff_Model_Resource_Setup */
$setup = $this;

/* start setup ***************************************************************/
$setup->startSetup();

/* do setup ******************************************************************/
$setup->run("
    CREATE TABLE IF NOT EXISTS `{$this->getTable('techdivision_systemconfigdiff_diff_config')}` (
    		`techdivision_systemconfigdiff_diff_config_id` INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    		`path` VARCHAR(255) NOT NULL,
    		`scope` VARCHAR(8) NOT NULL,
    		`scope_id` INT(8) NOT NULL,
    		`scope_name` VARCHAR(255) NOT NULL,
    		`code` VARCHAR(255) NOT NULL,
    		`system1_value` TEXT,
    		`system2_value` TEXT,
    		`systemXml` TINYINT NOT NULL
    	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE IF NOT EXISTS `{$this->getTable('techdivision_systemconfigdiff_diff_page')}` (
    		`techdivision_systemconfigdiff_diff_page_id` INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    		`identifier` VARCHAR(255) NOT NULL,
    		`store_name` VARCHAR(255) NOT NULL,
    		`system1_content` MEDIUMTEXT,
    		`system2_content` MEDIUMTEXT
    	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE IF NOT EXISTS `{$this->getTable('techdivision_systemconfigdiff_diff_block')}` (
    		`techdivision_systemconfigdiff_diff_block_id` INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    		`identifier` VARCHAR(255) NOT NULL,
            `store_name` VARCHAR(255) NOT NULL,
    		`system1_content` MEDIUMTEXT,
    		`system2_content` MEDIUMTEXT
    	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

/* end setup *****************************************************************/
$setup->endSetup();