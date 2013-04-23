TechDivision_SystemConfigDiff
=============================
Module to diff a remote Magento system configuration and CMS blocks/pages and sync values.
Please feel free to participate in further development of this module. It is easily extendable
as you can read under developer documentation. Any feedback is welcome!


Features
-
* System configuration diff
* CMS pages diff
* CMS blocks diff
* Table overview for diffs
* Easy and intuitive diff displaying in the system configuration
* Differentiates between all scopes
* Synchronisation between both systems

See our video illustrating the features:

![System config diff](https://github.com/techdivision/TechDivision_SystemConfigDiff/raw/master/doc/systemconfigdiff.png)

Purpose
-
The purpose of the module is to create and display a diff of the system
configuration, cms blocks and cms pages of two different system
instances. The module requests via a Magento API extension the system
data of the configured reference system the data gets compared with. It
saves all diffs and can display them in the system configuration. There
you can replace the config value with the value from the other system
with just one click. It can differentiate between all scopes (default,
websites, stores) as well as between all scope instances (the different
websites, stores).


Developer documentation
-
All templates, css and js will be injected via an observer before the layout
gets rendered. Unfortunately the HTML code for the config fields is hard
coded in PHP so `Mage_Adminhtml_Block_System_Config_Form_Field` had to be
rewritten as well as a child class called
`Mage_Adminhtml_Block_System_Config_Form_Field_Regexceptions` because rewrites
don't support class inheritance.
The module comes with two differs: One is for the system configuration, the other
is for cms blocks and cms pages. The module can easily be extended with more
differs. Therefore you need to define a new differ in the config.xml under
`<differs>`. The differ class should derive from
`TechDivision_SystemConfigDiff_Model_Differ` and has to implement the following
two functions:

* `function diff($thisConfig, $otherConfig)` calculates the diff of two arrays of system data
* `function getSystemData()` returns the system data for one system (i.e. at the webservice call)


System Configuration
-
On the reference system you have to configure a webservice user which has
sufficient rights for *System Config/Getting Config*. On the other system
you have to configure the api settings. As the module uses version 2 of the API
you have to use the following URL: `http://magentohost/api/v2_soap/?wsdl=1`.
You can define aliases for both systems for displaying the diffs. Also you
can turn on/off the diff display in the system configuration. Optionally you
can declare ignore paths which will not be shown as diff in system configuration.
This could be helpful for paths where you are sure they will never be the same
like the base url.