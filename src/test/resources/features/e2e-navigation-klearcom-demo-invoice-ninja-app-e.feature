@SCRUM-29 @e2e @kc002 @e2e-kc-002
Feature: E2E navigation — Klearcom Demo → Invoice Ninja App (E2E-KC-002)
  As a QA tester
  I want to navigate from Klearcom Demo into Invoice Ninja
  So that AC-01, AC-02, and AC-03 are verified for SCRUM-29 at Medium priority

  Background:
    Given the Klearcom Demo base URL is configured

  @smoke @regression @TC-001 @TS-01 @SCRUM-29 @e2e @kc002
  Scenario: TC-001 Navigate from Klearcom Demo into Invoice Ninja and confirm landing identity
    When the tester opens the Klearcom Demo home page
    Then the Demo application loads without fatal error
    When the tester activates the Invoice Ninja navigation control
    And the tester waits for Invoice Ninja navigation to complete
    Then the browser URL matches the Invoice Ninja expected URL pattern
    And the Invoice Ninja landing landmark is visible

  @regression @TC-002 @TS-02 @SCRUM-29 @e2e @kc002
  Scenario: TC-002 Guard against wrong destination after Invoice Ninja navigation
    When the tester opens the Klearcom Demo home page
    And the tester activates the Invoice Ninja navigation control
    And the tester waits for Invoice Ninja navigation to complete
    Then the destination is Invoice Ninja and not another Demo module
    And the destination is not a blank or generic browser error page

  @regression @TC-003 @TS-03 @e2e-kc-002 @SCRUM-29 @e2e @kc002
  Scenario: TC-003 Retain E2E-KC-002 and Medium priority in suite tags and report metadata
    Then the SCRUM-29 suite tags include e2e, kc002, and SCRUM-29
    And the Test ID E2E-KC-002 is retained in suite metadata
    And the ticket priority Medium is retained in report metadata
