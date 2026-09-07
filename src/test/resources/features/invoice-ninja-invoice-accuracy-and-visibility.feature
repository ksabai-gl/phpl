Feature: Invoice Ninja invoice accuracy and visibility
  As the downstream half of the Klearcom Demo -> Invoice Ninja E2E flow,
  invoices created for a client must be accurate and visible in the UI.

  Background:
    Given the tester is logged into Invoice Ninja
    And an existing client record is available

  # Verifies: REQ-03 / TC-SCRUM-96-01
  @REQ-03 @P1
  Scenario: Create an invoice with a single line item and verify correct total amount
    When the tester creates a new invoice for the existing client
    And adds a line item with description "Consulting" quantity "1" and unit price "100.00"
    And saves the invoice
    Then the invoice detail view shows client name matching the existing client
    And the invoice detail view shows line item "Consulting" with quantity "1" and unit price "100.00"
    And the invoice total equals "100.00"

  # Verifies: REQ-03 / TC-SCRUM-96-02
  @REQ-03 @P1
  Scenario: Create an invoice with multiple line items and verify calculated total
    When the tester creates a new invoice for the existing client
    And adds the following line items:
      | description | quantity | unitPrice |
      | Design       | 2        | 50.00     |
      | Development  | 3        | 75.00     |
      | Support      | 1        | 25.00     |
    And saves the invoice
    Then the invoice detail view shows all 3 line items with correct values
    And the invoice total equals "350.00"

  # Verifies: REQ-03 / TC-SCRUM-96-03
  @REQ-03 @P2
  Scenario: Verify client record details match what is shown on the invoice
    Given a client exists with name "Acme Corp", email "billing@acme.test" and a known billing address
    And an invoice has been created for that client
    When the tester opens the invoice
    And the tester opens the client's own record page
    Then the client name, email, and billing address on the invoice match the client record exactly

  # Verifies: REQ-05 / TC-SCRUM-96-04
  @REQ-05 @P1
  Scenario: Verify a newly created invoice appears correctly in the invoice list
    Given a new invoice has just been created and saved for an existing client
    When the tester navigates to the invoice list view
    Then the invoice list row for that invoice shows the correct client name, dates, and total amount

  # Verifies: REQ-05 / TC-SCRUM-96-05
  @REQ-05 @P1
  Scenario: Verify the end user can open and view full invoice detail from the list
    Given at least one invoice already exists in the invoice list
    When the tester opens the invoice from the invoice list
    Then the invoice detail page renders header, client details, line items, and totals without error

  # Verifies: REQ-05 / TC-SCRUM-96-06
  @REQ-05 @P3
  Scenario: Verify invoice status is correctly displayed as Draft after creation
    When the tester creates a new invoice for the existing client without changing its default status
    And saves the invoice
    Then the invoice list shows status "Draft" for the new invoice
    And the invoice detail page shows status "Draft"

  # Verifies: REQ-03 / TC-SCRUM-96-07 (assumption pending AC confirmation)
  @REQ-03 @P4 @assumption
  Scenario: Verify invoice line item with zero or blank quantity is handled gracefully
    When the tester creates a new invoice for the existing client
    And adds a line item with description "Misc" and a blank quantity
    And attempts to save the invoice
    Then the application either shows a validation message for invalid quantity
    Or the line item is saved with zero value without breaking the invoice total calculation
