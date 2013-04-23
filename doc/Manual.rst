================================================
 Manual for Module TechDivision_SystemConfigDiff
================================================

.. contents::

.. section-numbering::

.. header::

    Manual for TechDivision_SystemConfigDiff

.. footer::

    Page ###Page### of ###Total###


Metadata
========

+---------------------------+------------------------------------------+
| Module Name               | TechDivision_SystemConfigDiff            |
+---------------------------+------------------------------------------+
| Documented Module Version | 0.1.0beta                                |
+---------------------------+------------------------------------------+
| Documentation Date        | April 10, 2013                           |
+---------------------------+------------------------------------------+
| Module Developer(s)       | Florian Sydekum (fs@techdivision.com)    |
+---------------------------+------------------------------------------+
| Documentation Author(s)   | Florian Sydekum (fs@techdivision.com)    |
+---------------------------+------------------------------------------+
| PEAR Channel              | connect.techdivision.com                 |
+---------------------------+------------------------------------------+


Purpose
=======

The purpose of the module is to create and display a diff of the system
configuration, cms blocks and cms pages of two different system instances.
The module requests via a Magento API extension the system data of the
configured reference system the data gets compared with. It saves all
diffs and can display them in the system configuration. There you can
replace the config value with the value from the other system with just
one click. It can differentiate between all scopes (default, websites,
stores) as well as between all scope instances (the different websites,
stores).


Developer documentation
=======================

All templates, css and js will be injected via an observer before the layout
gets rendered. Unfortunately the HTML code for the config fields is hard
coded in PHP so 'Mage_Adminhtml_Block_System_Config_Form_Field' had to be
rewritten as well as a child class called
*Mage_Adminhtml_Block_System_Config_Form_Field_Regexceptions* because rewrites
don't support class inheritance.
The module comes with two differs: One is for the system configuration, the other
is for cms blocks and cms pages. The module can easily be extended with more
differs. Therefore you need to define a new differ in the config.xml under
*<differs>*. The differ class should derive from
*TechDivision_SystemConfigDiff_Model_Differ* and has to implement the following
two functions:
- function diff($thisConfig, $otherConfig): Calculates the diff of two arrays
of system data
- function getSystemData(): Returns the system data for one system (i.e. at
the webservice call)


Installation
============

Installing from scratch
-----------------------

The installation of the module is done by simply installing the pear
package connect.techdivision.com/Techdivision_SystemConfigDiff.
No manual modification of any files is necessary for the module to
function properly.


System Configuration
====================

On the reference system you have to configure a webservice user which has
sufficient rights for *System Config/Getting Config*. On the other system
you have to configure the api settings. As the module uses version 2 of the API
you have to use the following URL: http://magentohost/api/v2_soap/?wsdl=1.
You can define aliases for both systems for displaying the diffs. Also you
can turn on/off the diff display in the system configuration. Optionally you
can declare ignore paths which will not be shown as diff in system configuration.
This could be helpful for paths where you are sure they will never be the same
like the base url.

TechDivision SystemConfigDiff Systemsettings
--------------------------------------------

+-----------------+-----------------+-------------------+
| **Option Name** | **Input Type**  | **Default Value** |
+-----------------+-----------------+-------------------+
| System URL      | text            |                   |
+-----------------+-----------------+-------------------+
| API user        | text            |                   |
+-----------------+-----------------+-------------------+
| API password    | text            |                   |
+-----------------+-----------------+-------------------+


TechDivision SystemConfigDiff Displaysettings
---------------------------------------------

+----------------------------+-----------------+-------------------+
| **Option Name**            | **Input Type**  | **Default Value** |
+----------------------------+-----------------+-------------------+
| Alias for this system      | text            |                   |
+----------------------------+-----------------+-------------------+
| Alias for other system     | text            |                   |
+----------------------------+-----------------+-------------------+
| Show diff in system config | yes/no          | *no*              |
+----------------------------+-----------------+-------------------+
| Ignore paths               | multiselect     |                   |
+----------------------------+-----------------+-------------------+