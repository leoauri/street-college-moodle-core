@theme_street_college @theme_street_college_navigation
Feature: Site navigation
    In order to navigate and comprehend my position in the structure of the product
    As a user
    I want a nvaigation which provides an overview of the structure and navigation to essential places

    Scenario: Dashboard admin navigation
        Given I log in as "admin"
        And I am on homepage
        When I open flat navigation drawer
        Then I should see "Dashboard" in the "#nav-drawer" "css_element"
        And I should not see "Site home" in the "#nav-drawer" "css_element"
        And I should not see "Calendar" in the "#nav-drawer" "css_element"
        And I should not see "Private files" in the "#nav-drawer" "css_element"
        And I should see "Site administration" in the "#nav-drawer" "css_element"