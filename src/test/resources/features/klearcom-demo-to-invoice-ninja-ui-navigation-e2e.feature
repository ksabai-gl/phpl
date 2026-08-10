@SCRUM-29 @ui @e2e @kc002 @e2e-kc-002
Feature: Klearcom Demo to Invoice Ninja UI navigation (E2E-KC-002)
  Jira: SCRUM-29
  Test ID: E2E-KC-002 (Medium)
  Block 3 UI Experience Validation
  Framework: java-selenium-cucumber (XPath-only; glue com.stlc.ephemeral)
  Config: app.base.url and STLC Invoice Ninja tokens from HITL / bridge — do not hardcode production URLs.

  Background:
    Given the STLC app base URL is reachable

  @smoke @regression @TC-001 @TS-01 @P1 @ui
  Scenario: TC-001 Navigate from Klearcom Demo into Invoice Ninja
    When I open the application home page
    Then the page document title is not empty
    And the Demo application loads and shows Demo chrome without an error page
    When the tester opens the Klearcom Demo using the Demo base URL from the test plan
    And the tester activates the Invoice Ninja navigation control and waits up to the plan timeout
    Then navigation completes without a dead link or browser navigation error
    And focus moves to an application surface that is no longer only the originating Demo module home

  @smoke @regression @TC-002 @TS-02 @P1 @ui
  Scenario: TC-002 Confirm Invoice Ninja landing identity
    When I open the application home page
    And the tester opens the Klearcom Demo using the Demo base URL from the test plan
    And the tester activates the Invoice Ninja navigation control and waits up to the plan timeout
    Then the browser URL matches the Invoice Ninja URL pattern from the test plan
    And the page title or primary heading equals the Invoice Ninja landing landmark from the test plan
    And Invoice Ninja app chrome is visible and does not match a non-Invoice Klearcom module shell
    And screenshot evidence of the Invoice Ninja landing is attached for E2E-KC-002

  @regression @TC-003 @TS-03 @P2 @e2e-kc-002 @ui
  Scenario: TC-003 Trace run metadata to E2E-KC-002 and SCRUM-29
    When the suite filter includes scenarios tagged for E2E-KC-002 and SCRUM-29
    Then the active scenario tags include e2e-kc-002 and SCRUM-29
    And the reported Test ID for this run is E2E-KC-002 at Medium priority

  @regression @TC-004 @TS-04 @P3 @ui
  Scenario: TC-004 Reject wrong destination after Invoice Ninja navigation
    When I open the application home page
    And the tester opens the Klearcom Demo using the Demo base URL from the test plan
    And the tester activates the Invoice Ninja navigation control and waits up to the plan timeout
    Then a browser URL that does not match the Invoice Ninja URL pattern from the test plan is treated as a failure
    And a title heading or app chrome that does not match the Invoice Ninja landing landmark is treated as a failed landing identity
    And final URL title and screenshot evidence are recorded for defect triage
