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
 * Data generator for the userstatus_userstatuswwu plugin.
 *
 * @package    userstatus_userstatuswwu
 * @category   test
 * @copyright  2016/17 Nina Herrmann
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Class for the data generator for the userstatus_userstatuswwu plugin.
 *
 * @package    userstatus_userstatuswwu
 * @category   test
 * @copyright  2016/17 Nina Herrmann
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class userstatus_userstatuswwu_generator extends testing_data_generator {
    /**
     * Creates Users with different suspend, and deleted settings.
     */
    public function test_create_preparation () {
        global $DB;
        $generator = advanced_testcase::getDataGenerator();
        $data = array();
        $mytimestamp = time();

        $user = $generator->create_user(array('username' => 'e_user03', 'lastaccess' => $mytimestamp));
        $data['e_user03'] = $user;

        $unixoneyearago = $mytimestamp - 31536000;
        $userlongnotloggedin = $generator->create_user(array('username' => 'user', 'lastaccess' => $unixoneyearago));
        $data['user'] = $userlongnotloggedin;

        $unixfifteendaysago = $mytimestamp - 1296000;
        $userfifteendays = $generator->create_user(array('username' => 'userm', 'lastaccess' => $unixfifteendaysago));
        $data['userm'] = $userfifteendays;

        $userarchived = $generator->create_user(array('username' => 's_other07', 'lastaccess' => $mytimestamp, 'suspended' => 1));
        $DB->insert_record_raw('tool_deprovisionuser', array('id' => $userarchived->id, 'archived' => true,
            'timestamp' => $mytimestamp), true, false, true);
        $data['s_other07'] = $userarchived;

        $neverloggedin = $generator->create_user(array('username' => 'r_theu9'));
        $data['r_theu9'] = $neverloggedin;

        $unixoneyearnintydays = $mytimestamp - 39528000;
        $deleteme = $generator->create_user(array('username' => 'anonym', 'lastaccess' => $unixoneyearnintydays,
            'suspended' => 1, 'firstname' => 'Anonym'));
        $DB->insert_record_raw('tool_deprovisionuser', array('id' => $deleteme->id, 'archived' => true,
            'timestamp' => $unixoneyearnintydays), true, false, true);

        $DB->insert_record_raw('deprovisionuser_archive', array('id' => $deleteme->id, 'suspended' => 1,
            'deleted' => 0, 'lastaccess' => $unixoneyearnintydays, 'username' => 'd_me09'), true,
            false, true);
        $data['d_me09'] = $deleteme;

        return $data; // Return the user and course objects.
    }
}