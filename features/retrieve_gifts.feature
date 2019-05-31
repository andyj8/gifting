Feature: Retrieve saved gifts
  In order to verify a gift is valid so it can be redeemed
  As a customer
  I want to be able to retrieve a gift using a voucher code

  Scenario: Retrieve gift
    Given I have a gift ready for redemption
    When I retrieve the gift
    Then I should receive the gift data

  Scenario: Retrieve gift that has expired
    Given my gift has expired
    When I retrieve the gift
    Then I should receive a gift expired error

  Scenario: Retrieve gift that has already been redeemed
    Given my gift has already been redeemed
    When I retrieve the gift
    Then I should receive a gift already redeemed error
