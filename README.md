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

### app/config.yml

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

Optional (default values):

    nekland_feed:
        feeds:
            ...
        renderers:
            rss:
                id: nekland_feed.renderer.rss
        loaders:
            rss_file:
                id: nekland_feed.loader.rss_file

### Models

To use the NeklandFeedBundle, you must have class that implements the ItemInterface. In most of case, you can do it with your entities/documents

    class Post implements ItemInterface
    {
    .....
    }

Usage
-----

### Retrieve your feed instance

    $container->get('nekland_feed.factory')->get('my_feed');

If your controller extends the base Symfony controller, you can use

    $this->get('nekland_feed.factory')->get('my_feed');


### Render the feed

    $factory->render('my_feed', 'renderer');

### Add an item

    /** @var $post My\MyBundle\Entity\Post */
    $factory->load('my_feed', 'loader');
    $factory->get('my_feed')->add($post);
    

Tests
-----

NeklandFeedBundle is bundled with some behat flavoured tests. Install BehatBundle and launch it with

    app/console -e=test behat @NeklandFeedBundle

TODO
----

 * Atom
 * Annotation configuration

Thank you to
-------------

 * Yohan Giarelli <yohan@giarelli.org> who work and help on this project
