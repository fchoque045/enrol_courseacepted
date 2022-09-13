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
 * Manual user enrolment UI.
 *
 * @package    enrol_courseacepted
 * @author     Fabián Choque (Promace Jujuy) 2022
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use enrol_courseacepted\form\edit;
require('../../config.php');

$courseid   = required_param('courseid', PARAM_INT);
$instanceid = optional_param('id', 0, PARAM_INT);

$course = get_course($courseid);
$context = context_course::instance($course->id, MUST_EXIST);

require_login($course);
require_capability('enrol/courseacepted:config', $context);

$plugin = enrol_get_plugin('courseacepted');

if ($instanceid) {
    $instance = $DB->get_record(
        'enrol',
        array(
            'courseid' => $course->id,
            'enrol' => 'courseacepted',
            'id' => $instanceid),
        '*', MUST_EXIST);
} else {
    require_capability('moodle/course:enrolconfig', $context);
    // No instance yet, we have to add new instance.
    navigation_node::override_active_url(new moodle_url('/enrol/instances.php', array('id' => $course->id)));
    $instance = (object)$plugin->get_instance_defaults();
    $instance->id       = null;
    $instance->courseid = $course->id;
}

$PAGE->set_url('/enrol/courseacepted/edit.php', array('courseid' => $course->id, 'id' => $instanceid));
$PAGE->set_pagelayout('admin');

$return = new moodle_url('/enrol/instances.php', array('id' => $course->id));
if (!enrol_is_enabled('courseacepted')) {
    redirect($return);
}

$mform = new edit(null, array($instance, $plugin, $context));

if ($mform->is_cancelled()) {
    redirect($return);
} else if ($data = $mform->get_data()) {
    
    if ($instance->id) { //edit enrol method

        foreach ($data as $key => $value) {
            $instance->$key = $value;
        }

        $instance->timemodified = time();

        $DB->update_record('enrol', $instance);


    } else { //new enrol method
        
        $fields = (array) $data;
        $id = $plugin->add_instance($course, $fields);
    }

    redirect($return);
}

$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_title('curso aceptado');


$renderer = $PAGE->get_renderer('enrol_courseacepted');
$renderer->edit_page($mform);

// echo $OUTPUT->header();
// echo $OUTPUT->heading(get_string('enrolusers', 'enrol'));

// $mform->display();

// echo $OUTPUT->footer();