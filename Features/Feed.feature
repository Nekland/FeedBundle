Feature: NeklandFeedBundle Feed
    In order to manage a Feed
    As a developper
    I need to be able to add/set items and parameters to a Feed

    Scenario: count Items when empty
        Given The feed is empty
        When I get the item count
        Then I got "0"

    Scenario: Add and count 1 Item
        Given The feed is empty
        When I add an Item
        And I get the item count
        Then I got "1"

    Scenario: Add and count 2 Items
        Given The feed is empty
        When I add "2" items
        And I get the item count
        Then I got "2"

    Scenario: Remove an item
        Given The feed is empty
        When I add "2" items
        And I remove an Item
        And I get the item count
        Then I got "1"

    Scenario: Replace an item
        Given The feed is empty
        When I add an Item
        And I replace this Item by an other
        And I get the item count
        And I retrieve the Item "0"
        Then I got "1"
        And the item "0" has the "1" id

    Scenario: Keep only x items (autoSlice)
        Given The feed is empty
        When I set the "max_items" param to "10"
        And I add "12" items
        And I get the item count
        Then I got "10"
