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
 * The class contains a test script for the moodle userstatus_timechecker
 *
 * @package userstatus_userstatuswwu
 * @copyright 2016 N Herrmann
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use userstatus_timechecker\timechecker;

defined('MOODLE_INTERNAL') || die();

class userstatus_timechecker_testcase extends advanced_testcase {

    protected function set_up() {
        // Recommended in Moodle docs to always include CFG.
        global $CFG;
        $generator = $this->getDataGenerator()->get_plugin_generator('userstatus_timechecker');
        $data = $generator->test_create_preparation();
        $this->resetAfterTest(true);
        return $data;
    }
    /**
     * Function to test the locallib functions.
     */
    public function test_locallib() {
        global $DB, $CFG, $OUTPUT;
        $data = $this->set_up();
        $myuserstatuschecker = new timechecker();
        // Ruft die Methode auf, die mir das array zurückgibt
        $returnsuspend = $myuserstatuschecker->get_to_suspend();
        $returndelete = $myuserstatuschecker->get_to_delete();
        $returnneverloggedin = $myuserstatuschecker->get_never_logged_in();

        $this->assertEquals($data['userlongnotloggedin'], $returnsuspend[$data['userlongnotloggedin']->id]);
        $this->assertEquals($data['userarchived'], $returndelete[$data['userarchived']->id]);
        $this->assertEquals($data['neverloggedin'], $returnneverloggedin[$data['neverloggedin']->id]);
        $this->assertNotContains($data['user']->username, $returnsuspend);
        $this->assertNotContains($data['user']->username, $returndelete);
        $this->assertNotContains($data['user']->username, $returnneverloggedin);
    }
    /**
     * Methodes recommended by moodle to assure database and dataroot is reset.
     */
    public function test_deleting() {
        global $DB;
        $this->resetAfterTest(true);
        $DB->delete_records('user');
        $DB->delete_records('tool_deprovisionuser');
        $this->assertEmpty($DB->get_records('user'));
        $this->assertEmpty($DB->get_records('tool_deprovisionuser'));
    }
    /**
     * Methodes recommended by moodle to assure database is reset.
     */
    public function test_user_table_was_reset() {
        global $DB;
        $this->assertEquals(2, $DB->count_records('user', array()));
        $this->assertEquals(0, $DB->count_records('tool_deprovisionuser', array()));
    }
}