@filter @filter_foldout @street_college @javascript
Feature: Fold out sections
  As a teacher
  I want to hide content in sections that can be folded out
  In order to make courses comprehensible

  Scenario: Add and manipulate fold out sections
    Given the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1        | topics |
    And I log in as "admin"
    And I am on "Course 1" course homepage with editing mode on
    When I edit the section "0" and I fill the form with:
      | Summary | Hi there [foldout Click here to fold out] This will be exposed first [foldout Another fold outer] Inner hidden bit [/foldout] This also exposed first [/foldout] |
    Then I should see "Hi there"
    And "Click here to fold out" "link" should be visible
    And I should not see "This will be exposed first"
    And I should not see "Inner hidden bit"
    And I should not see "This also exposed first"
    And "Another fold outer" "link" should not be visible
    When I follow "Click here to fold out"
    Then I should see "Hi there"
    And "Click here to fold out" "link" should be visible
    And I should see "This will be exposed first"
    And I should not see "Inner hidden bit"
    And I should see "This also exposed first"
    And "Another fold outer" "link" should be visible
    When I follow "Another fold outer"
    Then I should see "Hi there"
    And "Click here to fold out" "link" should be visible
    And I should see "This will be exposed first"
    And I should see "Inner hidden bit"
    And I should see "This also exposed first"
    And "Another fold outer" "link" should be visible
    When I follow "Click here to fold out"
    Then I should see "Hi there"
    And "Click here to fold out" "link" should be visible
    And I should not see "This will be exposed first"
    And I should not see "Inner hidden bit"
    And I should not see "This also exposed first"
    And "Another fold outer" "link" should not be visible
