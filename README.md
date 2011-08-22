FeedBundle
===========

Under developpement.

Do not use this bundle at this moment.

Big changements are coming.

----------------------------------

Features
--------

 * can make atom and rss 2.0 feeds
 * support multiple feeds
 * support edit of feeds
 * extendable

Configuration
-------------

    nekland_feed:
        feeds:
            my_feed:
                class:        My\MyBundle\Entity\Post
                title:       'My fabulous posts'
                description: 'Here is my very fabulous posts'
                route:       'my_posts'
                language:    'fr'
            my_feed2:
                class:        My\MyBundle\Entity\Comment
                title:       'My fabulous comments'
                description: 'Here is my very fabulous comments'
                route:       'my_posts_comments'
                language:    'fr'

Tests
-----

NeklandFeedBundle is bundled with some behat flavoured tests. Install BehatBundle and launch it with app/console -e=test behat @NeklandFeedBundle

Thank you to
-------------

 * yohang who work and help on this project