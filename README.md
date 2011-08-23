FeedBundle
===========

Under developpement.

Do not use this bundle at this moment.

Big changes are coming.


Features
--------

 * can make atom and rss 2.0 feeds
 * support multiple feeds
 * support edit of feeds
 * extendable
 * On-the-fly and at-save feed generation

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

NeklandFeedBundle is bundled with some behat flavoured tests. Install BehatBundle and launch it with

    app/console -e=test behat @NeklandFeedBundle

TODO
----

 * Loaders
 * Atom
 * Annotation configuration

Thank you to
-------------

 * Yohan Giarelli <yohan@giarelli.org> who work and help on this project
