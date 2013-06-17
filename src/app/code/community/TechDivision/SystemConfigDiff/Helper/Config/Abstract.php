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
 * Implements abstract block functionality for module.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Helper
 * @copyright  Copyright (c) 1996-2013 TechDivision GmbH (http://www.techdivision.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    ${release.version}
 * @since      Class available since Release 0.1.0
 * @author     Florian Sydekum <fs@techdivision.com>
 */
abstract class TechDivision_SystemConfigDiff_Helper_Config_Abstract
extends Mage_Core_Helper_Abstract
{
    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/systemsettings/system_url .
     *
     * @var string
     */
    const XML_PATH_SYSTEMSETTINGS_SYSTEM_URL
        = 'techdivision_systemconfigdiff/systemsettings/system_url';


    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/systemsettings/user .
     *
     * @var string
     */
    const XML_PATH_SYSTEMSETTINGS_USER
        = 'techdivision_systemconfigdiff/systemsettings/user';


    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/systemsettings/password .
     *
     * @var string
     */
    const XML_PATH_SYSTEMSETTINGS_PASSWORD
        = 'techdivision_systemconfigdiff/systemsettings/password';


    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/systemsettings/wsi .
     *
     * @var string
     */
    const XML_PATH_SYSTEMSETTINGS_WSI
        = 'techdivision_systemconfigdiff/systemsettings/wsi';


    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/systemsettings/cron_enabled .
     *
     * @var string
     */
    const XML_PATH_SYSTEMSETTINGS_CRON_ENABLED
        = 'techdivision_systemconfigdiff/systemsettings/cron_enabled';


    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/systemsettings/cron .
     *
     * @var string
     */
    const XML_PATH_SYSTEMSETTINGS_CRON
        = 'techdivision_systemconfigdiff/systemsettings/cron';


    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/systemsettings/base_url_replace_enabled .
     *
     * @var string
     */
    const XML_PATH_SYSTEMSETTINGS_BASE_URL_REPLACE_ENABLED
        = 'techdivision_systemconfigdiff/systemsettings/base_url_replace_enabled';


    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/displaysettings/alias_this .
     *
     * @var string
     */
    const XML_PATH_DISPLAYSETTINGS_ALIAS_THIS
        = 'techdivision_systemconfigdiff/displaysettings/alias_this';


    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/displaysettings/alias_other .
     *
     * @var string
     */
    const XML_PATH_DISPLAYSETTINGS_ALIAS_OTHER
        = 'techdivision_systemconfigdiff/displaysettings/alias_other';


    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/displaysettings/show_diff .
     *
     * @var string
     */
    const XML_PATH_DISPLAYSETTINGS_SHOW_DIFF
        = 'techdivision_systemconfigdiff/displaysettings/show_diff';


    /**
     * Holds the xml path to the config value
     * techdivision_systemconfigdiff/displaysettings/ignore_paths .
     *
     * @var string
     */
    const XML_PATH_DISPLAYSETTINGS_IGNORE_PATHS
        = 'techdivision_systemconfigdiff/displaysettings/ignore_paths';



    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/systemsettings/system_url .
     *
     * @param void
     * @return mixed
     */
    public function getSystemsettingsSystemUrl($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_SYSTEMSETTINGS_SYSTEM_URL , $storeId
        );

        return $config;
    }

    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/systemsettings/user .
     *
     * @param void
     * @return mixed
     */
    public function getSystemsettingsUser($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_SYSTEMSETTINGS_USER , $storeId
        );

        return $config;
    }

    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/systemsettings/password .
     *
     * @param void
     * @return mixed
     */
    public function getSystemsettingsPassword($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_SYSTEMSETTINGS_PASSWORD , $storeId
        );

        return $config;
    }

    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/systemsettings/wsi .
     *
     * @param void
     * @return mixed
     */
    public function getSystemsettingsWsi($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_SYSTEMSETTINGS_WSI , $storeId
        );

        return $config;
    }

    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/systemsettings/cron_enabled .
     *
     * @param void
     * @return mixed
     */
    public function getSystemsettingsCronEnabled($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_SYSTEMSETTINGS_CRON_ENABLED , $storeId
        );

        return $config;
    }

    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/systemsettings/cron .
     *
     * @param void
     * @return mixed
     */
    public function getSystemsettingsCron($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_SYSTEMSETTINGS_CRON , $storeId
        );

        return $config;
    }

    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/systemsettings/base_url_replace_enabled .
     *
     * @param void
     * @return mixed
     */
    public function getSystemsettingsBaseUrlReplaceEnabled($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_SYSTEMSETTINGS_BASE_URL_REPLACE_ENABLED , $storeId
        );

        return $config;
    }

    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/displaysettings/alias_this .
     *
     * @param void
     * @return mixed
     */
    public function getDisplaysettingsAliasThis($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_DISPLAYSETTINGS_ALIAS_THIS , $storeId
        );

        return $config;
    }

    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/displaysettings/alias_other .
     *
     * @param void
     * @return mixed
     */
    public function getDisplaysettingsAliasOther($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_DISPLAYSETTINGS_ALIAS_OTHER , $storeId
        );

        return $config;
    }

    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/displaysettings/show_diff .
     *
     * @param void
     * @return mixed
     */
    public function getDisplaysettingsShowDiff($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_DISPLAYSETTINGS_SHOW_DIFF , $storeId
        );

        return $config;
    }

    /**
     * Returns the configured value for the config value
     * techdivision_systemconfigdiff/displaysettings/ignore_paths .
     *
     * @param void
     * @return array
     */
    public function getDisplaysettingsIgnorePaths($storeId = null) {
        $config = Mage::getStoreConfig(
            self::XML_PATH_DISPLAYSETTINGS_IGNORE_PATHS , $storeId
        );

        $config = explode(",", $config);


        return $config;
    }
}