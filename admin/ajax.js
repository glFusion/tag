/**
 * glFusion Tag Content Scan
 *
 * Iterates over all content
 *
 * LICENSE: This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * @category   glFusion CMS
 * @package    tag
 * @author     Mark R. Evans  mark AT glFusion DOT org
 * @copyright  2015-2016 - Mark R. Evans
 * @license    http://opensource.org/licenses/gpl-2.0.php - GNU Public License v2 or later
 * @since      File available since Release 1.6.3
 *
 */

var tagadminint = (function() {

    // public methods/properties
    var pub = {};

    // private vars
    var plugins  = null,
        plugin   = null,
        url     = null,
        done    = 0,
        count   = 0,
        $msg    = null;

    // update process
    pub.update = function() {
        done = 0;
        url = site_admin_url + '/plugins/tag/scan_ajax.php';

        $("#tag_scanprocesor").show();
        $('#rescanbutton').prop("disabled",true);
        $("#rescanbutton").html(lang_scanning);

        throbber_on();

        $.ajax({
            type: "POST",
            dataType: "json",
            url: url,
            data: {"mode" : "pluginlist" },
            timeout: 30000,
        }).done(function(data) {
            var result = $.parseJSON(data["js"]);
            plugins = result.pluginlist;
            count = plugins.length;
            plugin = plugins.shift();
            message(lang_scanning);
            window.setTimeout(processPlugin,1000);
        });
        return false; // prevent from firing
    };

    /**
    * initialize everything
    */
    pub.init = function() {
        if ( gl != 'log') return;
        // $msg is the status message area
        $msg = $('#ajax_msg');
        $t = $('#t');

        // if $msg does not exist, return.
        if ( ! $msg) {
            return;
        }

        // init interface events
        $('#rescanbutton').click(pub.update);
    };

    var processPlugin = function() {
        if (plugin) {
            // ajax call to process plugin
            $.ajax({
                type: "POST",
                dataType: "json",
                url: url,
                data: {"mode" : "scanplugin", "plugin" : plugin },
                timeout:60000
            }).done(function(data) {
                var result = $.parseJSON(data["js"]);
                if ( result.errorCode != 0 ) {
                    console.log("TAGadmin: The content scan did not complete");
                }
                message(lang_scanning + ' ' + done + '/' + count + ' - '+ plugin);
                plugin = plugins.shift();
                done++;
            }).fail(function(jqXHR, textStatus ) {
                if (textStatus === 'timeout') {
                     console.log("TAGadmin: Timeout scanning plugin " + plugin);
                } else {
                     console.log("TAGadmin: Error scanning plugin " + plugin);
                }
                alert("TAGadmin: Error scanning plugin " + plugin);
                window.location.href = site_admin_url + "/plugins/tag/index.php";
            }).always(function( xhr, status ) {
                var wait = 250;
                window.setTimeout(processPlugin, wait);
            });

        } else {
            finished();
        }
    };

    var finished = function() {
        // we're done
        throbber_off();
        message(lang_success);
        window.setTimeout(function() {
            // ajax call to process plugin
            $.ajax({
                type: "POST",
                dataType: "json",
                url: url,
                data: {"mode" : "complete",},
            }).done(function(data) {
                $('#rescanbutton').prop("disabled",false);
                $("#rescanbutton").html(lang_scan);
               UIkit.modal.confirm(lang_success, function(){
                    $(location).attr('href', site_admin_url + '/plugins/tag/index.php');
                }, function(){}, {labels:{'Ok': lang_ok,'Cancel': lang_cancel } });
            });
        }, 250);
    };


    /**
    * Gives textual feedback
    * updates the ID defined in the $msg variable
    */
    var message = function(text) {
        $msg.html(text);
    };

    /**
    * add a throbber image
    */
    var throbber_on = function() {
        $t.show();
    };

    /**
    * Stop the throbber
    */
    var throbber_off = function() {
        $t.hide();
    };

    // return only public methods/properties
    return pub;
})();

$(function() {
    tagadminint.init();
});