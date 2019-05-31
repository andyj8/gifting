Feature: Redeem a gift
  In order to read the book that has been gifted to me
  As a customer
  I want to send my voucher code and redeem my gift

  Scenario: Redeem my gift
    Given I have a gift ready for redemption
    When I send my voucher code
    Then my gift should be marked as redeemed

  Scenario: Attempt to redeem already redeemed gift
    Given my gift has already been redeemed
    When I send my voucher code
    Then I should receive a gift already redeemed error

  Scenario: Attempt to redeem expired gift
    Given my gift has expired
    When I send my voucher code
    Then I should receive a gift expired error

  Scenario: Send an invalid voucher
    When I send my voucher code
    Then I should receive a gift not found error
