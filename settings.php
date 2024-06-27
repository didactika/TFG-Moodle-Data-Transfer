<?php

/**
 * Tasks definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


if ($hassiteconfig) {

    if ($ADMIN->fulltree) {

        $settings = new admin_settingpage('local_data_transfer', get_string('pluginname', 'local_data_transfer'));

        $settings->add(
            new admin_setting_configtext(
                'local_data_transfer/external_rabbitmq_host',
                get_string('setting:external_rabbitmq_host', 'local_data_transfer'),
                '',
                "localhost",
                PARAM_RAW
            )
        );
        $settings->add(
            new admin_setting_configtext(
                'local_data_transfer/external_rabbitmq_port',
                get_string('setting:external_rabbitmq_port', 'local_data_transfer'),
                '',
                "5672",
                PARAM_RAW
            )
        );
        $settings->add(
            new admin_setting_configtext(
                'local_data_transfer/external_rabbitmq_user',
                get_string('setting:external_rabbitmq_user', 'local_data_transfer'),
                '',
                "guest",
                PARAM_RAW
            )
        );
        $settings->add(
            new admin_setting_configtext(
                'local_data_transfer/external_rabbitmq_password',
                get_string('setting:external_rabbitmq_password', 'local_data_transfer'),
                '',
                "guest",
                PARAM_RAW
            )
        );
        $settings->add(
            new admin_setting_configtext(
                'local_data_transfer/external_rabbitmq_queue', 
                get_string('setting:external_rabbitmq_queue', 'local_data_transfer'),
                "",
                "queue",
                PARAM_RAW
            )
        );
        $settings->add(
            new admin_setting_configtext(
                'local_data_transfer/external_rabbitmq_exchange', 
                get_string('setting:external_rabbitmq_exchange', 'local_data_transfer'),
                "",
                "exchange",
                PARAM_RAW
            )
        );
        $settings->add(
            new admin_setting_configtext(
                'local_data_transfer/external_rabbitmq_vhost', 
                get_string('setting:external_rabbitmq_vhost', 'local_data_transfer'),
                "",
                "/",
                PARAM_RAW
            )
        );
        $settings->add(
            new admin_setting_configtext(
                'local_data_transfer/external_appid', 
                get_string('setting:external_appid', 'local_data_transfer'),
                "",
                "moodle.dominos",
                PARAM_RAW
            )
        );
        $ADMIN->add('localplugins', $settings);
    }
}
