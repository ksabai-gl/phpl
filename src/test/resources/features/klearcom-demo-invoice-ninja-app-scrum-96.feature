@SCRUM-96 @regression
Feature: Klearcom Demo → Invoice Ninja App (SCRUM-96)
  Story-scope E2E for Klearcom IVR Journey Console handoff to Invoice Ninja /app.
  Traces provisional AC-P1, AC-P2, AC-P4 and BS-01–04, BS-06.

  @smoke @Klearcom @TC-001 @TS-01
  Scenario: TC-001 Open Klearcom IVR Journey Console at application root
    # Verifies: AC-P1, BS-01, BF-01, UJ-1
    Given the application base URL is configured as APP_BASE_URL
    When the tester navigates to the path "/"
    Then the Klearcom IVR Journey Console is displayed
    And the Open Ninja App control is visible and enabled

  @Klearcom @TC-002 @TS-02
  Scenario: TC-002 Open Klearcom console via /demo and /ivr route aliases
    # Verifies: AC-P1, BS-02, BF-01
    Given the application base URL is configured as APP_BASE_URL
    When the tester navigates to the path "/demo"
    Then the Klearcom IVR Journey Console is displayed
    And the Open Ninja App control is visible and enabled
    When the tester navigates to the path "/ivr"
    Then the Klearcom IVR Journey Console is displayed
    And the Open Ninja App control is visible and enabled

  @smoke @Klearcom @InvoiceNinja @TC-003 @TS-03
  Scenario: TC-003 Navigate from Klearcom Demo to Invoice Ninja via Open Ninja App
    # Verifies: AC-P2, BS-03, BF-03, UJ-2
    Given the application base URL is configured as APP_BASE_URL
    And the Klearcom IVR Journey Console is loaded at "/"
    When the tester clicks the Open Ninja App control
    Then the browser navigates to a same-origin URL under "/app"
    And an Invoice Ninja application shell is displayed
    And the active view is no longer the Klearcom demo console

  @Klearcom @softphone @TC-004 @TS-04
  Scenario: TC-004 Softphone Start test and Support DTMF path
    # Verifies: AC-P4, BS-04, BF-02, UJ-1a — optional depth unless HITL elevates
    Given the application base URL is configured as APP_BASE_URL
    And the Klearcom IVR Journey Console is loaded at "/"
    When the tester clicks Start test on the softphone
    Then the journey map reflects an active test session
    When the tester enters softphone DTMF digit "2"
    Then the demo routes toward the Support journey without a UI crash
    And transcript or status text reflects DTMF 2 Support routing

  @env @TC-005 @TS-05
  Scenario: TC-005 Application unavailable or incorrect base URL
    # Verifies: BS-06
    Given an invalid application base URL is configured as INVALID_APP_BASE_URL
    When the tester navigates to the invalid base URL root
    Then a successful Klearcom IVR Journey Console is not displayed
    And Open Ninja App is not available as a usable demo session
