@theme_street_college @theme_street_college_navigation
Feature: Site navigation
    In order to navigate and comprehend my position in the structure of the product
    As a user
    I want a navigation which provides an overview of the structure and navigation to essential places

    Scenario: Add-a-block node in editing mode
        Given I log in as "admin"
        And the following "courses" exist:
            | fullname | shortname |
            | Course 1 | C1        |
        And I am on "Course 1" course homepage
        When I turn editing mode on
        Then I should see "Add a block" in the "#nav-drawer" "css_element"
        When I turn editing mode off
        Then I should not see "Add a block" in the "#nav-drawer" "css_element"


    Scenario: Dashboard admin navigation
        Given I log in as "admin"
        And I am on homepage
        When I open flat navigation drawer
        Then I should see "Dashboard" in the "#nav-drawer" "css_element"
        And I should not see "Site home" in the "#nav-drawer" "css_element"
        And I should not see "Calendar" in the "#nav-drawer" "css_element"
        And I should not see "Private files" in the "#nav-drawer" "css_element"
        And I should see "Site administration" in the "#nav-drawer" "css_element"

    Scenario: Course navigation
        Given the following "courses" exist:
            | fullname | shortname |
            | Course 1 | C1        |
        And the following "users" exist:
            | username | firstname | lastname | email              |
            | teacher1 | Teacher   | One      | teacher.one@e.mail |
        And the following "course enrolments" exist:
            | user     | course | role           |
            | teacher1 | C1     | editingteacher |
        When I log in as "teacher1"
        And I am on "Course 1" course homepage
        Then I should see "Dashboard" in the "#nav-drawer" "css_element"
        And I should see "C1" in the "#nav-drawer" "css_element"
        And there should be "1" instance of "C1" "link"
        And "//*[@id='nav-drawer']//*[text()='Dashboard']" "xpath_element" should appear before "//*[@id='nav-drawer']//*[text()='C1']" "xpath_element"
        And I should not see "Site home" in the "#nav-drawer" "css_element"
        And I should not see "Calendar" in the "#nav-drawer" "css_element"
        And I should not see "Private files" in the "#nav-drawer" "css_element"
        And I should not see "Participants" in the "#nav-drawer" "css_element"
        And I should not see "Competencies" in the "#nav-drawer" "css_element"
        And I should not see "Grades" in the "#nav-drawer" "css_element"
        And I should not see "General" in the "#nav-drawer" "css_element"
        And I should not see "My courses" in the "#nav-drawer" "css_element"
        And I should not see "Site administration" in the "#nav-drawer" "css_element"
