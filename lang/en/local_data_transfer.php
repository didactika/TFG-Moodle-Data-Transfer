<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     local_data_transfer
 * @category    strings
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Moodle Course JSON Export/Import Tool';

$string['task:event_task'] = 'Get migration data from external source';

$string['setting:external_rabbitmq_host'] = 'RabbitMQ Host';
$string['setting:external_rabbitmq_port'] = 'RabbitMQ Port';
$string['setting:external_rabbitmq_user'] = 'RabbitMQ User';
$string['setting:external_rabbitmq_password'] = 'RabbitMQ Password';
$string['setting:external_rabbitmq_queue'] = 'RabbitMQ Queue';
$string['setting:external_rabbitmq_exchange'] = 'RabbitMQ Exchange';
$string['setting:external_rabbitmq_vhost'] = 'RabbitMQ Virtual Host';