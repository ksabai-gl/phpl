@SCRUM-29 @e2e @kc002 @e2e-kc-002
Feature: E2E-KC-002 Klearcom Demo to Invoice Ninja navigation
  Story SCRUM-29 Medium priority.
  Trace: AC-01, AC-02, AC-03 · TC-001, TC-002, TC-003 · TS-01, TS-02, TS-03.
  Environment values come from app.base.url and stlc.* system properties (Test Plan §5).

  Background:
    Given the Klearcom Demo base URL is configured

  @smoke @regression @TC-001 @TS-01 @ui
  Scenario: TC-001 Navigate from Klearcom Demo to Invoice Ninja and confirm landing
    When the tester opens the Klearcom Demo home
    Then the Klearcom Demo console loads without fatal error
    When the tester opens the Ninja app navigation control
    And the tester waits for Invoice Ninja navigation to complete
    Then the browser URL matches the Invoice Ninja landing URL pattern
    And the Invoice Ninja landing landmark is visible

  @regression @TC-002 @TS-02
  Scenario: TC-002 Retain E2E-KC-002 and SCRUM-29 traceability on the smoke run
    Then the suite test id is configured as "E2E-KC-002"
    And the suite story priority is configured as "Medium"
    And the navigable smoke scenario carries tags for SCRUM-29 and kc002

  @regression @TC-003 @TS-03 @ui
  Scenario: TC-003 Fail with evidence when Invoice Ninja navigation does not reach the expected app
    When the tester opens the Klearcom Demo home
    And the tester attempts a broken Invoice Ninja navigation path
    Then failure evidence captures the final browser URL and a screenshot
    And the final browser URL does not match the Invoice Ninja landing URL pattern
