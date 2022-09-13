<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package    enrol_courseacepted
 * @author    Fabián Choque (Promace Jujuy) 2022
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_heading('enrol_courseacepted_enrolname', '', get_string('pluginname_desc', 'enrol_courseacepted')));

    // Confirm mail settings...
    $settings->add(new admin_setting_heading(
        'enrol_courseacepted_confirmmail',
        get_string('confirmmail_heading', 'enrol_courseacepted'),
        get_string('confirmmail_desc', 'enrol_courseacepted')));
    $settings->add(new admin_setting_configtext(
        'enrol_courseacepted/confirmmailsubject',
        get_string('confirmmailsubject', 'enrol_courseacepted'),
        get_string('confirmmailsubject_desc', 'enrol_courseacepted'),
        null,
        PARAM_TEXT,
        60));
    $settings->add(new admin_setting_confightmleditor(
        'enrol_courseacepted/confirmmailcontent',
        get_string('confirmmailcontent', 'enrol_courseacepted'),
        get_string('confirmmailcontent_desc', 'enrol_courseacepted'),
        null,
        PARAM_RAW));

}

if ($hassiteconfig) { // Needs this condition or there is error on login page.
    $ADMIN->add('courses', new admin_externalpage('enrol_courseacepted',
            get_string('applymanage', 'enrol_courseacepted'),
            new moodle_url('/enrol/courseacepted/manage.php')));
}
