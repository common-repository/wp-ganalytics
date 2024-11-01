<?php

/*
Plugin Name: Google Analytics Easy
Plugin URI:  http://www.srmilon.com
Description: The plugin will help to add analytics code in your websiteWE
Version:     1.3
Author:      Saidur Rahman Milon
Author URI:  https://www.srmilon.info
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


/**
 * Class gogl_analytics
 * To manage gogl_analytics options
 */
class gogl_plugin_analytics
{
    // to initial add hooks
    public function __construct()
    {
        add_action('admin_menu', [$this, 'gogl_add_admin_menu']);
        add_action('admin_init', [$this, 'gogl_settings_init']);
        add_action('admin_bar_menu', [$this, 'gogl_analytics_admin_bar_link'], 999);
        add_action('wp_footer', [$this, 'gogl_add_analytics_code']);
    }

    // To add Google Analytics Menu in Admin Bar
    function gogl_analytics_admin_bar_link($wp_admin_bar)
    {
        $args = array(
            'id' => 'gogl_analytics_menu',
            'title' => 'Google Analytics',
            'href' => admin_url() . 'options-general.php?page=analytics'
        );
        $wp_admin_bar->add_node($args);
    }

    // To add admin menu under settings tab
    function gogl_add_admin_menu()
    {

        add_options_page('Google Analytics', 'Google Analytics', 'manage_options', 'analytics', [$this, 'gogl_options_page']);
    }

    // To initiate settings of the plugin
    function gogl_settings_init()
    {

        register_setting('pluginPage', 'gogl_settings');

        add_settings_section(
            'gogl_pluginPage_section',
            __('', 'gogl'),
            [$this, 'gogl_settings_section_callback'],
            'pluginPage'
        );

        add_settings_field(
            'gogl_tracking_id',
            __('Analytics Tracking Code: ', 'gogl'),
            [$this, 'gogl_tracking_id_render'],
            'pluginPage',
            'gogl_pluginPage_section'
        );


    }

    // To render Settings section fields
    function gogl_tracking_id_render()
    {

        $options = get_option('gogl_settings');
        ?>
        <input type='text' name='gogl_settings[gogl_tracking_id]' value='<?php echo $options['gogl_tracking_id']; ?>'
               class="regular-text"><br/>
        <span class="description">&nbsp;Ex: UA-00000000-0</span>
        <?php

    }

    // Callback function for settings section
    function gogl_settings_section_callback()
    {

        echo __('Please enter following information.', 'gogl');

    }

    // Settings form
    function gogl_options_page()
    {

        ?>
        <form action='options.php' method='post'>

            <h2>Google Analytics Settings</h2>

            <?php
            settings_fields('pluginPage');
            do_settings_sections('pluginPage');
            submit_button('Update');
            ?>

        </form>
        <?php

    }

    // action hook to add Analytics code in footer
    function gogl_add_analytics_code()
    {
        $options = get_option('gogl_settings');
        $gogl_tracking_id = $options['gogl_tracking_id'];
        ob_start();
        ?>
        <!--Analytics code start-->
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
            ga('create', <?php echo $gogl_tracking_id;?>, 'auto');
            ga('send', 'pageview');
        </script>
        <!--Analytics code end-->
        
        <?php
        return ob_get_contents();
    }
}

new gogl_plugin_analytics();


