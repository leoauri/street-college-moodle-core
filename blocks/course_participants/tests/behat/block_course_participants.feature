@street_college @block @block_course_participants
Feature: See a list of course participants
    In order to enable navigation to student profiles from courses
    As an admin
    I want to provide a list summarising course participants with links to their profiles

    Background:
        Given the following "courses" exist:
            | fullname | shortname |
            | Course 1 | C1        |
            | Course 2 | C2        |
        And the following "users" exist:
            | username | firstname | lastname | email                   |
            | teacher  | One       | Teacher  | teacher.one@stop.stop   |
            | student1 | One       | Student  | student.one@stop.stop   |
            | student2 | Two       | Student  | student.two@stop.stop   |
            | student3 | Three     | Student  | student.three@stop.stop |
        And the following "course enrolments" exist:
            | user     | course | role    |
            | teacher  | C1     | teacher |
            | teacher  | C2     | teacher |
            | student1 | C1     | student |
            | student2 | C1     | student |
            | student2 | C2     | student |
            | student3 | C2     | student |
    Scenario: Add the course participants block to a course and see course participants
        Given I log in as "admin"
        And I am on "Course 1" course homepage with editing mode on
        When I add the "Course participants" block
        Then I should see "One Student" in the "Course participants" "block"
        And I should see "Two Student" in the "Course participants" "block"
        And I should not see "Three Student" in the "Course participants" "block"

    Scenario: View course participants as a teacher
        Given I log in as "admin"
        And I am on "Course 1" course homepage with editing mode on
        And I add the "Course participants" block
        And I log out
        When I log in as "teacher"
        And I am on "Course 1" course homepage
        Then I should see "One Student" in the "Course participants" "block"
        And I should see "Two Student" in the "Course participants" "block"
        And I should not see "Three Student" in the "Course participants" "block"

    Scenario: Follow a link to student profile
        Given I log in as "admin"
        And I am on "Course 1" course homepage with editing mode on
        And I add the "Course participants" block
        When I follow "One Student"
        Then I should see "User details"
        And I should see "student.one@stop.stop"
