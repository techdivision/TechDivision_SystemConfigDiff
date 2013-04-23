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
 * Differ implementation for cms content.
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
class TechDivision_SystemConfigDiff_Model_Differ_Cms extends TechDivision_SystemConfigDiff_Model_Differ
{
    /**
     * The diff key defines the data for this differ in the system data array
     */
    const DIFF_KEY_PAGE = "page";
    const DIFF_KEY_BLOCK = "block";

    /**
     * @inheritdoc
     */
    public function diff($thisConfig, $otherConfig){
        // Empty database tables
        $this->_emptyDb(self::DIFF_KEY_PAGE);
        $this->_emptyDb(self::DIFF_KEY_BLOCK);

        $this->_diff($thisConfig, $otherConfig, self::DIFF_KEY_PAGE);
        $this->_diff($thisConfig, $otherConfig, self::DIFF_KEY_BLOCK);
    }

    /**
     * Returns all cms pages/blocks
     *
     * @param $cmsId Either 'page' or 'block'
     * @return array
     */
    public function getSystemData(){
        return array(
            self::DIFF_KEY_PAGE  => $this->_getSystemData(self::DIFF_KEY_PAGE),
            self::DIFF_KEY_BLOCK => $this->_getSystemData(self::DIFF_KEY_BLOCK)
        );
    }

    /**
     * Diffs the two cms contents
     *
     * @param $thisCms The cms data of this system
     * @param $otherCms The cms data of the other system
     * @param $cmsId Either 'page' or 'block'
     */
    protected function _diff($thisCms, $otherCms, $cmsId){
        // Both system's cms content
        $system1 = $thisCms[$cmsId];
        $system2 = $otherCms[$cmsId];

        // Remember the cms content which already has been persisted
        $cmsAlreadyDone = array();

        // For each cms entry from system1
        foreach($system1 as $identifier => $storeIds){

            // Array for all identifier/store pairs
            $cmsAlreadyDone[$identifier] = array();

            // For each store specific cms content
            foreach($storeIds as $storeId => $value1){

                // The data to save
                $toSave = array();

                // Get the cms content by identifier from system2, can be null if not set in system2
                if(array_key_exists($identifier, $system2) && array_key_exists($storeId, $system2[$identifier])){
                    $value2 = $system2[$identifier][$storeId]['content'];
                } else {
                    $value2 = null;
                }

                $value1 = $value1['content'];

                // If cms content is different save it do database
                if($value1 !== $value2){
                    $toSave['identifier'] = $identifier;
                    $toSave['store_name'] = Mage::getModel('core/store')->load($storeId)->getName();
                    $toSave['system1_content'] = $value1;
                    $toSave['system2_content'] = $value2;

                    Mage::getModel('techdivision_systemconfigdiff/' . $cmsId)->addData($toSave)->save();
                }

                // Cms entry already persisted
                $cmsAlreadyDone[$identifier][] = $storeId;
            }
        }

        // Reverse: if system2 has cms entries which are not set in system1
        foreach($system2 as $identifier => $storeIds){

            // For each store specific cms content
            foreach($storeIds as $storeId => $value2){

                // The data to save
                $toSave = array();

                // Skip all already persisted cms entries
                if(array_key_exists($identifier, $cmsAlreadyDone) && in_array($storeId, $cmsAlreadyDone[$identifier])){
                    continue;
                }

                $value2 = $value2['content'];

                $toSave['identifier'] = $identifier;
                $toSave['store_name'] = Mage::getModel('core/store')->load($storeId)->getName();
                $toSave['system1_content'] = null;
                $toSave['system2_content'] = $value2;

                Mage::getModel('techdivision_systemconfigdiff/' . $cmsId)->addData($toSave)->save();
            }
        }
    }

    /**
     * Returns the content of cms pages/blocks
     *
     * @param $cmsId Either 'page' or 'block'
     * @return array
     */
    protected function _getSystemData($cmsId){
        $result = array();

        // Get cms resource model to load collection
        $cms = Mage::getModel('cms/' . $cmsId);
        $cmsResource = $cms->getResource();

        // For every cms entry
        foreach($cms->getCollection() as $_cms){
            // Get the store ids the cms content belongs to, title, identifier and content
            $storeIds = $cmsResource->lookupStoreIds($_cms->getId());
            $title = $_cms->getTitle();
            $identifier = $_cms->getIdentifier();
            $content = $_cms->getContent();

            // Save the data for every identifier/store pair
            foreach($storeIds as $storeId){
                $result[$identifier][$storeId] = array(
                    'title'   => $title,
                    'content' => $content
                );
            }
        }

        // Return the data array
        return $result;
    }
}