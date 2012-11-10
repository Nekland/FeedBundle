FeedBundle
===========

Version 2.0
Changes:
 * Tested for Symfony 2.1
 * Added composer support
 * Namespace changed
 * (versioning change)

Version 1.1
Changes:
 * in the configuration you need to defined an url, not a route. (Maybe another website ? You can type what you want)
 * the method getFeedRoute become getFeedRoutes in the ItemInterface (it's an array of routes).
 For more information, see the ItemInterface or the wiki.
 * for naming your feeds you must add |format| (where you want in the filename)


Version 1.0
Warning: finally the new version (1.1) make substantial changes, but not in the code logic.


Features
--------

 * can make atom and rss 2.0 feeds
 * support multiple feeds
 * support edit of feeds
 * extensible
 * On-the-fly and at-save feed generation

Read about configuration and more on the wiki
---------------------------------------------

https://github.com/Nek-/FeedBundle/wiki

Configuration
-------------

### app/config.yml

```JSON
    nekland_feed:
        feeds:
            my_feed:
                class:        My\MyBundle\Entity\Post
                title:       'My fabulous posts'
                description: 'Here is my very fabulous posts'
                url:         'http://my-website.com'
                language:    'fr'
            my_feed2:
                class:        My\MyBundle\Entity\Comment
                title:       'My fabulous comments'
                description: 'Here is my very fabulous comments'
                url:         'http://my-website.com'
                language:    'fr'
```
Optional (default values):
```JSON
    nekland_feed:
        feeds:
            ...
        renderers:
            rss:
                id: nekland_feed.renderer.rss
        loaders:
            rss_file:
                id: nekland_feed.loader.rss_file
```

Notice that if you want change the path where are saved files, you can redefined this option:
```JSON
# Default path
nekland_feed.feeds.base_path: %kernel.root_dir%/../web/feeds
```

### Models

To use the NeklandFeedBundle, you must have class that implements the ItemInterface. In most of case,
you can do it with your entities/documents
```PHP
    class Post implements ItemInterface
    {
        //.....
    }
```
Usage
-----

### Retrieve your feed instance
```PHP
    $container->get('nekland_feed.factory')->get('my_feed');
```
If your controller extends the base Symfony controller, you can use
```PHP
    $this->get('nekland_feed.factory')->get('my_feed');
```

### Render the feed
```PHP
    $factory->render('my_feed', 'renderer');
```
### Add an item
```PHP
    /** @var $post My\MyBundle\Entity\Post */
    $factory->load('my_feed', 'loader');
    $factory->get('my_feed')->add($post);
```



Tests
-----

NeklandFeedBundle is bundled with some behat flavoured tests. Install BehatBundle and launch it with

    app/console -e=test behat @NeklandFeedBundle

TODO
----

 * Annotation configuration

Author :
-------------
 * Nek <nek.dev+github@gmail.com> ( http://twitter.com/#!/Nekdev )

Contributors :
-------------

 * Yohan Giarelli <yohan@giarelli.org>
