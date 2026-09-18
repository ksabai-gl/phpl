@smoke @ui @bf-04
Feature: STLC ephemeral UI smoke (BF-04)
  Bootstrap scaffold — opens the app under test so evidence hooks can capture screenshots.

  Scenario: Open application home
    Given the STLC app base URL is reachable
    When I open the application home page
    Then the page document title is not empty
