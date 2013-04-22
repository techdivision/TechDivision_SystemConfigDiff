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
 * Js for backend.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Javascript
 * @copyright  Copyright (c) 1996-2013 TechDivision GmbH (http://www.techdivision.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    ${release.version}
 * @since      Class available since Release 0.1.0
 * @author     Florian Sydekum <fs@techdivision.com>
 */
jQuery.noConflict();

jQuery(document).ready(function(){
    jQuery('.techdivision-systemconfigdiff-adminhtml-index-index .entry-edit').append('' +
        '<div id="popup" style="display:none">' +
            '<div id="system1"><div class="popup-heading"/><div class="popup-content"/></div>' +
            '<div id="system2"><div class="popup-heading"/><div class="popup-content"/></div>' +
            '<a href="#" id="close" ></a>' +
        '</div>');

    // Open popup System -> Config diff
    jQuery(document).on("click", ".grid tbody tr", function(e){
        console.log("test");
        if(!jQuery("#popup").is(':visible')){

            jQuery("#popup").fadeIn(300);
            jQuery("#popup").css({
                left: (jQuery(window).width() - jQuery('#popup').width()) / 2,
                top: jQuery(this).offset().top,
                position: 'absolute',
                width: 800
            });

            jQuery("#system1 .popup-heading").text(jQuery(".grid .headings .sort-title").eq(2).text());
            jQuery("#system2 .popup-heading").text(jQuery(".grid .headings .sort-title").eq(3).text());
            jQuery("#system1 .popup-content").text(jQuery(this).children("td").eq(2).text());
            jQuery("#system2 .popup-content").text(jQuery(this).children("td").eq(3).text());

            e.stopPropagation();
        }
    });

    // Close popup
    jQuery("#close").click(function(){
        jQuery("#popup").fadeOut(300);
    });

    // If a click gets to the body close popup
    jQuery("body").click(function(){
        jQuery("#popup").fadeOut(300);
    });

    // All clicks on popup -> do not close popup
    jQuery("#popup").click(function(e){
        e.stopPropagation();
    });

    // Change background color of collapseable bar in System -> Config to red if diff error is present
    jQuery(".edit-diff-content").closest(".section-config").children(".entry-edit-head").css('background-color', '#aa0303');
});

// Escape key -> close popup
jQuery(document).keydown(function(e) {
    if (e.keyCode == 27) {
        jQuery("#popup").fadeOut(300);
    }
});

// Calls update function via ajax, deletes diff content view on success
function replaceConfig(caller){
    var sibling = jQuery(caller).parent().next();
    var url = jQuery(sibling).find(".update-url").text();
    var path = jQuery(sibling).find(".path").text();
    var scope = jQuery(sibling).find(".scope").text();
    var scopeId = jQuery(sibling).find(".scope-id").text();

    var r = {options:{loadArea:''}};
    varienLoaderHandler.handler.onCreate(r);

    jQuery.ajax({
        url: url,
        type: "GET",
        data: {'path': path, 'scope': scope, 'scopeId': scopeId},
        success: function(data) {
            if(data == 1){
                var parent = jQuery(caller).parent().parent();
                var toReplace = jQuery(parent).find('.value')[0];
                var replace = jQuery(parent).find('.value')[1];
                jQuery(toReplace).replaceWith(replace);
                jQuery(replace).find(".edit-diff-content").removeClass("edit-diff-content");
                jQuery(".entry-edit-head").removeAttr('style');
                jQuery(".edit-diff-content").closest(".section-config").children(".entry-edit-head").css('background-color', '#aa0303');
                jQuery(caller).remove();
                varienLoaderHandler.handler.onComplete();
            }
        }
    });
}