@street_college @theme_street_college
Feature: Clean dashboard
    In order to have my attention focused on the relevant features
    As a user of the street college databank
    I want a dashboard which gives me access to specific relevant features

    @javascript
    Scenario: Dashboard AJAX does not throw any exception
        Given I log in as "admin"
        When I am on homepage
        Then I should not see "invalidparameter"

    Scenario: Access and view the dashboard
        Given I log in as "admin"
        When I am on homepage
        Then I should see "Recently accessed courses" in the "#page-content" "css_element"
        And I should see "Course overview" in the "#page-content" "css_element"
        And I should not see "Timeline" in the "#page-content" "css_element"
        And I should not see "Private files" in the "#page-content" "css_element"
        And I should not see "Online users" in the "#page-content" "css_element"
        And I should not see "Latest badges" in the "#page-content" "css_element"
        And I should not see "Calendar" in the "#page-content" "css_element"
        And I should not see "Upcoming events" in the "#page-content" "css_element"
