<?php
/**
 * TechDivision_SystemConfigDiff_Block_Adminhtml_System_Config_Form_Field
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
 * Rewrites core block and adds diff html to output.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Block_Adminhtml_System_Config_Form_Field
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * The render method is copied from the core block. Only only one line at the end of this method is added.
     * This method has to be made backwards compatible. Versions lower 1.12.0.1 have different code in this function.
     *
     * @param Varien_Data_Form_Element_Abstract $element The field element to be rendered
     * @return string Html of field
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $id = $element->getHtmlId();

        if(version_compare(Mage::getVersion(), '1.12.0.1') >= 0){
            $html = '<td class="label"><label for="'.$id.'">'.$element->getLabel().'</label></td>';
        } else {
            $useContainerId = $element->getData('use_container_id');
            $html = '<tr id="row_' . $id . '">'
                . '<td class="label"><label for="'.$id.'">'.$element->getLabel().'</label></td>';
        }

        //$isDefault = !$this->getRequest()->getParam('website') && !$this->getRequest()->getParam('store');
        $isMultiple = $element->getExtType()==='multiple';

        // replace [value] with [inherit]
        $namePrefix = preg_replace('#\[value\](\[\])?$#', '', $element->getName());

        $options = $element->getValues();

        $addInheritCheckbox = false;
        if ($element->getCanUseWebsiteValue()) {
            $addInheritCheckbox = true;
            $checkboxLabel = Mage::helper('adminhtml')->__('Use Website');
        }
        elseif ($element->getCanUseDefaultValue()) {
            $addInheritCheckbox = true;
            $checkboxLabel = Mage::helper('adminhtml')->__('Use Default');
        }

        if ($addInheritCheckbox) {
            $inherit = $element->getInherit()==1 ? 'checked="checked"' : '';
            if ($inherit) {
                $element->setDisabled(true);
            }
        }

        if(version_compare(Mage::getVersion(), '1.12.0.1') >= 0){
            if ($element->getTooltip()) {
                $html .= '<td class="value with-tooltip">';
                $html .= $this->_getElementHtml($element);
                $html .= '<div class="field-tooltip"><div>' . $element->getTooltip() . '</div></div>';
            } else {
                $html .= '<td class="value">';
                $html .= $this->_getElementHtml($element);
            };
        } else {
            $html .= '<td class="value">';
            $html .= $this->_getElementHtml($element);
        }

        if ($element->getComment()) {
            $html.= '<p class="note"><span>'.$element->getComment().'</span></p>';
        }
        $html.= '</td>';

        if ($addInheritCheckbox) {

            $defText = $element->getDefaultValue();
            if ($options) {
                $defTextArr = array();
                foreach ($options as $k=>$v) {
                    if ($isMultiple) {
                        if (is_array($v['value']) && in_array($k, $v['value'])) {
                            $defTextArr[] = $v['label'];
                        }
                    } elseif ($v['value']==$defText) {
                        $defTextArr[] = $v['label'];
                        break;
                    }
                }
                $defText = join(', ', $defTextArr);
            }

            // default value
            $html.= '<td class="use-default">';
            //$html.= '<input id="'.$id.'_inherit" name="'.$namePrefix.'[inherit]" type="checkbox" value="1" class="input-checkbox config-inherit" '.$inherit.' onclick="$(\''.$id.'\').disabled = this.checked">';
            $html.= '<input id="'.$id.'_inherit" name="'.$namePrefix.'[inherit]" type="checkbox" value="1" class="checkbox config-inherit" '.$inherit.' onclick="toggleValueElements(this, Element.previous(this.parentNode))" /> ';
            $html.= '<label for="'.$id.'_inherit" class="inherit" title="'.htmlspecialchars($defText).'">'.$checkboxLabel.'</label>';
            $html.= '</td>';
        }

        $html.= '<td class="scope-label">';
        if ($element->getScope()) {
            $html .= $element->getScopeLabel();
        }
        $html.= '</td>';

        // The only line which is added to this core method
        $html .= $this->_addDiffErrorHtml($element);

        $html.= '<td class="">';
        if ($element->getHint()) {
            $html.= '<div class="hint" >';
            $html.= '<div style="display: none;">' . $element->getHint() . '</div>';
            $html.= '</div>';
        }
        $html.= '</td>';

        if(version_compare(Mage::getVersion(), '1.12.0.1') >= 0){
            return $this->_decorateRowHtml($element, $html);
        } else {
            $html.= '</tr>';
            return $html;
        }
    }

    /**
     * Adds the html for the diff error for a specific element
     *
     * @param $element The current element to render
     * @return string
     */
    protected function _addDiffErrorHtml($element)
    {
        $html = '';

        // If diff is not displayed in system config return empty html
        if(!Mage::helper('techdivision_systemconfigdiff/config')->getDisplaysettingsShowDiff()){
            return $html;
        }

        $collection = Mage::getModel('techdivision_systemconfigdiff/config')->getCollection();

        // Get path and scope
        $path = $this->_getPathByName($element->getData('name'));
        $scope = $element->getData('scope');

        // Check if we should ignore this path for displaying
        if(in_array($path, Mage::helper('techdivision_systemconfigdiff')->getIgnorePaths())){
            return $html;
        }

        // Filter collection by current path and scope
        $collection->addFieldToFilter('path', $path);
        $collection->addFieldToFilter('scope', $scope);

        // If not default scope add filter to the collection
        if($scope !== 'default'){
            $collection->addFieldToFilter('scope_id', $element->getScopeId());

            // Get current website/store code
            $configData = Mage::getSingleton('adminhtml/config_data');
            $website = $configData->getWebsite();
            $store = $configData->getStore();

            if($store === ''){
                // Website
                $collection->addFieldToFilter('code', $website);
            } else {
                // Store
                $collection->addFieldToFilter('code', $store);
            }
        }

        // If an entry was found and has data add the diff error html
        $entry = $collection->getFirstItem();
        if(count($entry->getData()) > 0){
            $buttonTitle = Mage::helper('techdivision_systemconfigdiff')->__('Replace config');
            $html .= '<td>';
            $html .=    '<button class="replace-config btn-replace" onclick="replaceConfig(this)" type="button" title="' . $buttonTitle . '">';
            $html .=        '<span>Replace config</span>';
            $html .=    '</button>';
            $html .= '</td>';
            $html .= '<td class="value">';
            $html .=    '<div class="edit-diff-content">';
            $html .= $this->_getDiffElementHtml($element, $entry->getSystem2Value());
            $html .=    '</div>';
            $html .=    '<div class="edit-diff-hidden">';
            $html .=        '<div class="path">';
            $html .= $path;
            $html .=        '</div>';
            $html .=        '<div class="scope">';
            $html .= $scope;
            $html .=        '</div>';
            $html .=        '<div class="scope-id">';
            $html .= $element->getScopeId();
            $html .=        '</div>';
            $html .=        '<div class="update-url">';
            $html .= Mage::helper('adminhtml')->getUrl('techdivision_systemconfigdiff/adminhtml_index/update');
            $html .=        '</div>';
            $html .=    '</div>';
            $html .= '</td>';
        }

        return $html;
    }

    /**
     * The only way to get the path is by the name of the element (i.e. groups[unsecure][fields][base_media_url][value])
     * Magento does not provide the path at this point
     *
     * @param $name
     * @return string
     */
    protected function _getPathByName($name){
        $path = '';
        $name = explode("[", $name);

        $path .= $this->getRequest()->getParam('section');
        $path .= '/' . substr_replace($name[1] ,"",-1);
        $path .= '/' . substr_replace($name[3] ,"",-1);

        return $path;
    }

    /**
     * Returns the element html rendered with the config value of the other system
     * Also deletes the id and name attribute
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getDiffElementHtml(Varien_Data_Form_Element_Abstract $element, $value)
    {
        if($element->getRenderer() instanceof TechDivision_SystemConfigDiff_Block_Adminhtml_System_Config_Form_Field_Regexceptions){
            $value = unserialize($value);
        }
        // Set the value of the other system
        $element->setValue($value);

        // Get the rendered html
        $html = $this->_getElementHtml($element);

        // Adapt html
        $doc = new DOMDocument();
        $doc->loadHTML(utf8_decode($html));

        // Remove !DOCTYPE tag
        $doc->removeChild($doc->firstChild);

        /* @var DOMNode $node */

        // Throw away the id and name attribute of the field html
        // to avoid changes made in this form field get saved on submit button click
        foreach($doc->getElementsByTagName('input') as $node){
            if($node->hasAttribute('type') && $node->getAttribute('type') === 'hidden'){
                $node->parentNode->removeChild($node);
                continue;
            }

            $node->removeAttribute('id');
            $node->removeAttribute('name');
        }

        foreach($doc->getElementsByTagName('select') as $node){
            $node->removeAttribute('id');
            $node->removeAttribute('name');
        }

        foreach($doc->getElementsByTagName('textarea') as $node){
            $node->removeAttribute('id');
            $node->removeAttribute('name');
        }

        // Get all script nodes as string to adapt them
        $script = '';
        foreach($doc->getElementsByTagName('script') as $node){
            $script .= $doc->saveHtml($node);
            $node->parentNode->removeChild($node);
        }

        // Remove all id and name attributes inside the script
        $script = preg_replace('/id\="[^"]*"/', '', $script);
        $script = preg_replace('/name\="[^"]*"/', '', $script);

        // Add the script at the end of the html
        $html = utf8_encode($doc->saveHTML()) . $script;

        // Throw away html and body tags
        $html = str_replace(array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $html);

        return $html;
    }
}