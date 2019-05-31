Feature: Submit a new eGift
  In order to purchase physical gifts for friends and family
  As a customer
  I want to be able to send purchased physical gifts so they are delivered to their recipient

  Scenario: Send a physical gift without specifying delivery date prior to same-day cutoff is delivered immediately
    Given the current time is 5 hour prior to the same-day delivery cutoff
    When I send an physical gift without delivery date
    Then my gift should be saved
    And my gift should be delivered immediately
    And I should be notified it was delivered

  Scenario: Send a physical gift without specifying delivery date after same-day cutoff is delivered immediately
    Given the current time is 5 hour after the same-day delivery cutoff
    When I send an physical gift without delivery date
    Then my gift should be saved
    And my gift should be scheduled for delivery for tomorrow
