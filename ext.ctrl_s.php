<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ctrl+s
 *
 * @package   Ctrl+s
 * @author    Scott-David Jones <enquiries@autumndev.co.uk>
 * @copyright Copyright (c) 2012 AutumnDev 
 */
class Ctrl_s_ext {

    var $name           = "Ctrl+S";
    var $version        = 1.0;
    var $description    = "Shortcut for 'Update' or 'Save' on any Control Panel Screen";
    var $docs_url       = 'www.autumndev.co.uk';
    var $settings_exist = 'n';
    var $globalVars;
    
    /**
     * Constructor
     */
    function __construct()
    {
        // -------------------------------------------
        //  Make a local reference to the EE super object
        // -------------------------------------------

        $this->EE =& get_instance();
    }
        
    // --------------------------------------------------------------------

    /**
     * Activate Extension
     */
    function activate_extension()
    {
        // -------------------------------------------
        //  Add the row to exp_extensions
        // -------------------------------------------

        $this->EE->db->insert('extensions', array(
            'class'    => __CLASS__,
            'method'   => 'addSave',
            'hook'     => 'cp_js_end',
            'settings' => '',
            'priority' => 10,
            'version'  => $this->version,
            'enabled'  => 'y'
        ));
    }

    /**
     * Update Extension
     */
    function update_extension($current = '')
    {
        if ($current == '' OR $current == $this->version)
        {
            return false;
        }
       
        $this->EE->db->where('class', __CLASS__);
        $this->EE->db->update(
            'extensions',
            array('version' => $this->version)
        );
    }

    /**
     * Disable Extension
     */
    function disable_extension()
    {
        // -------------------------------------------
        //  Remove the row from exp_extensions
        // -------------------------------------------

        $this->EE->db->where('class', __CLASS__)
            ->delete('extensions');
    }
    //input name="update"
    //input name="submit" id="submit_button"
    function addSave(){
        $js = "
        $.ctrl = function(key, callback, args) {
            $(document).keydown(function(e) {
            if(!args) args=[]; // IE cries without this
            if(e.keyCode == key.charCodeAt(0) && (e.ctrlKey || e.metaKey)) {
                callback.apply(this, args);
                return false;
            }
            });
        };
        $.ctrl('S', function(s) {
            //find any update/submit buttons
            $('input[name=update], input[name=submit]').click();
        });
        ";

        return $this->EE->extensions->last_call . $js;
    }

}