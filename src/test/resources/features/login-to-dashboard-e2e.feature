@SCRUM-96 @ui
Feature: Login to Dashboard E2E (SCRUM-96)
  As a QA tester
  I want to verify Sign in entry and navigation to Dashboard
  So that login→dashboard E2E is covered as navigation-only auth

  Background:
    Given the Mobile Banking App base URL is configured

  @smoke @regression @TC-001 @TS-01
  Scenario: TC-001 Application opens on Sign in page
    When the tester navigates to the application base URL
    Then the application loads without a crash
    And the browser path is the login entry route
    And the Sign in form shows username, password, and Login control
    And the landing UI is Sign in and not the Dashboard heading

  @regression @TC-002 @TS-02
  Scenario: TC-002 Login submit navigates to Dashboard with welcome placeholder
    Given the tester is on the Sign in page
    When the tester enters username "test.user" or leaves username empty
    And the tester enters password "any" or leaves password empty
    And the tester activates the Login control
    Then the browser path is "/dashboard"
    And the Dashboard heading is visible
    And the welcome placeholder text is visible

  @regression @TC-003 @TS-03
  Scenario: TC-003 Direct navigation to unguarded Dashboard route
    Given the tester starts a clean browser session for the app
    When the tester navigates directly to the dashboard URL without Login
    Then the Dashboard page renders without redirect to "/login"
    And the Dashboard heading is visible
    And the welcome placeholder text is visible
