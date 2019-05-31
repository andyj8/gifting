Feature: Submit a new eGift
  In order to purchase eGifts for friends and family
  As a customer
  I want to be able to send purchased eGifts so they are delivered to their recipient

  Scenario: Send an eGift without specifying delivery date prior to same-day cutoff is delivered immediately
    Given the current time is 5 hour prior to the same-day delivery cutoff
    When I send an eGift without delivery date
    Then my gift should be saved
    And my gift should be delivered immediately
    And I should be notified it was delivered

  Scenario: Send an eGift without specifying delivery date after same-day cutoff is delivered immediately
    Given the current time is 5 hour after the same-day delivery cutoff
    When I send an eGift without delivery date
    Then my gift should be saved
    And my gift should be delivered immediately
    And I should be notified it was delivered

  Scenario: Send an eGift for delayed delivery
    When I send an eGift for delivery in 5 days from now
    Then my gift should be saved
    And my gift should be scheduled for delivery in 5 days from now
