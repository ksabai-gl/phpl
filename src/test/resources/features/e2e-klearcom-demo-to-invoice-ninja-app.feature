@SCRUM-96 @regression
Feature: E2E Klearcom Demo to Invoice Ninja App
  As a QA tester
  I want to validate Demo → Invoice Ninja journey
  So that SCRUM-96 In Scope Workflow, UI, and Integration capabilities are covered

  Background:
    Given the QA base URL is configured

  @smoke @KlearcomDemo @TC-001 @TS-01
  Scenario: TC-001 Klearcom demo home loads without fatal error
    When I open the application root path "/"
    Then the HTTP navigation succeeds
    And the Klearcom demo content is visible in the main viewport
    And no fatal application error dialog blocks the page

  @smoke @CrossApp @TC-002 @TS-02
  Scenario: TC-002 Navigate from Klearcom demo to Invoice Ninja /app
    Given the Klearcom demo home is loaded
    When I activate the navigation control to Invoice Ninja
    Then the browser URL contains "/app"
    And the Invoice Ninja login form or authenticated shell is displayed

  @smoke @InvoiceNinja @TC-003 @TS-03
  Scenario: TC-003 Invoice Ninja login succeeds and app shell is usable
    Given I am on the Invoice Ninja login screen at "/app"
    When I submit valid QA credentials
    Then the authenticated Invoice Ninja shell is visible

  @InvoiceNinja @Invoices @TC-004 @TS-04
  Scenario: TC-004 Invoice list or create smoke after successful login
    Given I am authenticated in Invoice Ninja
    When I open the invoices area from application navigation
    Then the invoices list or empty state is visible
    And invoice list or create controls are usable without fatal error

  @InvoiceNinja @Negative @TC-005 @TS-05
  Scenario: TC-005 Invalid Invoice Ninja login is rejected
    Given I am on the Invoice Ninja login screen at "/app" without a session
    When I submit intentionally invalid credentials
    Then a login rejection message is displayed
    And the authenticated dashboard is not granted

  @Setup @TC-006 @TS-06
  Scenario: TC-006 Setup path when environment is unconfigured
    When I navigate directly to "/setup"
    Then the setup wizard is shown or the configured environment is gated
    And "/app" remains reachable for authentication or use without a fatal setup loop

  @smoke @E2E @CrossApp @TC-007 @TS-07
  Scenario: TC-007 Cross-app integration Demo to authenticated app outcome
    When I start at "/" and confirm the Klearcom demo loads
    And I navigate from Demo to Invoice Ninja "/app"
    And I authenticate with valid QA credentials when login is required
    And I open the invoices area briefly
    Then the invoices workspace is visible without unhandled error
