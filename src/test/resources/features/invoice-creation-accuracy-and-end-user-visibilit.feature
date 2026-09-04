@SCRUM-96
Feature: Invoice creation accuracy and end-user visibility in Invoice Ninja

  Background:
    Given the tester is logged into Invoice Ninja

  # TC-SCRUM96-01 / REQ-03
  Scenario: Verify invoice is created with a single line item and correct total amount
    Given an existing client is available
    When the tester creates a new invoice for that client
    And the tester adds a line item with description "Consulting Service" quantity 1 and unit price 100.00
    And the tester saves the invoice
    Then the invoice detail view shows exactly 1 line item
    And the invoice total amount displayed is "100.00"

  # TC-SCRUM96-02 / REQ-03
  Scenario: Verify invoice is created with multiple line items and correctly summed total amount
    Given an existing client is available
    When the tester creates a new invoice for that client
    And the tester adds a line item with description "Item A" quantity 2 and unit price 50.00
    And the tester adds a line item with description "Item B" quantity 1 and unit price 25.00
    And the tester saves the invoice
    Then the invoice total amount displayed is "125.00"

  # TC-SCRUM96-03 / REQ-03
  Scenario: Verify invoice line item field mapping accuracy
    Given an existing client is available
    And an invoice exists with the following line items
      | description | quantity | unitPrice |
      | Item A       | 2        | 50.00     |
      | Item B       | 1        | 25.00     |
    When the tester opens that invoice from the invoice list
    Then every displayed line item description quantity and unit price matches the entered values exactly

  # TC-SCRUM96-04 / REQ-03
  Scenario: Verify a client record is correctly associated with the invoice
    Given a known client "SCRUM96 Client" with a known email exists
    When the tester creates a new invoice for client "SCRUM96 Client" with one line item
    And the tester saves the invoice
    And the tester opens the saved invoice from the invoice list
    Then the invoice detail view displays client "SCRUM96 Client" with the matching email

  # TC-SCRUM96-05 / REQ-05
  Scenario: Verify the newly created invoice appears in the Invoice Ninja invoice list
    Given an existing client is available
    And the tester has just created and saved a new invoice for that client
    When the tester navigates to the invoice list view
    And the tester locates the invoice by client name
    Then the invoice list row shows the correct client name invoice number status and total amount

  # TC-SCRUM96-06 / REQ-05
  Scenario: Verify invoice detail view displays correct amount client and line items to the end user
    Given an existing client is available
    And an invoice exists with the following line items
      | description | quantity | unitPrice |
      | Item A       | 2        | 50.00     |
      | Item B       | 1        | 25.00     |
    When the tester opens that invoice from the invoice list
    Then the invoice detail view displays the client name every line item and the total amount matching the created record

  # TC-SCRUM96-07 / REQ-05
  Scenario: Verify invoice status is correctly displayed after creation
    Given an existing client is available
    When the tester creates a new invoice for that client with one line item
    And the tester saves the invoice
    Then the invoice status is displayed as "Draft" on the invoice list row
    And the invoice status is displayed as "Draft" on the invoice detail view

  # TC-SCRUM96-08 / REQ-05
  Scenario: Verify searching the invoice list by client name returns the correct invoice
    Given a known client "SCRUM96 Unique Client" with a known email exists
    And the tester has just created and saved a new invoice for client "SCRUM96 Unique Client"
    And another unrelated client and invoice exist
    When the tester navigates to the invoice list view
    And the tester searches the invoice list for client name "SCRUM96 Unique Client"
    Then only the invoice belonging to "SCRUM96 Unique Client" is displayed with correct total and status
