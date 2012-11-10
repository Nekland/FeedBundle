Feature: NeklandFeedBundle FeedFactory
    In order to easily manage feeds
    As a developper
    I need to be able to retrieve feeds via FeedFactory

    Scenario: Retrieve the factory service
      Given I know the container
      When I try get the service "nekland_feed.factory"
      Then I should get an instance of "Nekland\Bundle\FeedBundle\Factory\FeedFactory"

    Scenario: has() method returns false
      Given The feed "Foo" does not exists
      When I call the has method for "foo"
      Then The has method should return "false"

    Scenario: has method returns true and the feed is here
      Given I add the "bar" feed
      When I call the has method for "bar"
      And I try to get the feed "bar"
      Then The has method should return "true"
      Then I should get an instance of "Nekland\Bundle\FeedBundle\Feed"
