@tool @tool_carcastc
Feature: Add, edit and delete rows for courses by tool_carcast
  As student only need to see rows
  As teacher i could be add, edit and delete rows

  Background:
    Given the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1 | topics |
      | Course 2 | C2 | weeks |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher | User | Teacher | teacher@moodle.com |
      | student | User | Student | student@moodle.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher | C1     | editingteacher |
      | teacher | C2     | editingteacher |
      | student | C1     | student |
      | student | C2     | student |

  @javascript
  Scenario: Add and edit an row
    When I log in as "teacher"
    And I am on "Course 1" course homepage
    And I navigate to "My first Moodle plugin" in current page administration
    And I follow "Add row"
    And I set the following fields to these values:
      | Name      | Row test 1 |
      | Completed | 0            |
    And I press "Save changes"
    Then the following should exist in the "tool_carcastc_overview" table:
      | Name         | Completed |
      | Row test 1 | No        |
    And I click on "Edit row" "link" in the "Row test 1" "table_row"
    And I set the following fields to these values:
      | Completed | 1            |
    And I press "Save changes"
    And the following should exist in the "tool_carcastc_overview" table:
      | Name         | Completed |
      | Row test 1 |  Yes       |
    And I log out

  @javascript
  Scenario: Delete an row
    When I log in as "teacher"
    And I am on "Course 2" course homepage
    And I navigate to "My first Moodle plugin" in current page administration
    And I follow "Add row"
    And I set the field "Name" to "Row test 1"
    And I press "Save changes"
    And I follow "Add row"
    And I set the field "Name" to "Row test 2"
    And I press "Save changes"
    And I click on "Delete row" "link" in the "Row test 1" "table_row"
    Then I should see "Row test 2"
    And I should not see "Row test 1"
    And I log out
