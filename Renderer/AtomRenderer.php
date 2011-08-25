<?php

/**
 * This class render an xml file
 * Using format Atom 1
 *
 * @author Nek' <nek.dev+github@gmail.com>
 */

namespace Nekland\FeedBundle\Renderer;

use Symfony\Component\Routing\Router;
use Nekland\FeedBundle\Feed;
use Nekland\FeedBundle\XML\XMLManager;


class AtomRenderer implements RendererInterface {

    /**
     * @var Symfony\Component\Routing\Router
     */
    protected $router;

    /**
     * @var string
     */
    protected $basePath;

    public function __construct(Router $router, $basePath)
    {
        $this->router = $router;
        $this->basePath = $basePath;
    }

    /**
     * Renders the feed
     *
     * @param \Nekland\FeedBundle\Feed $feed
     * @return void
     */
    public function render(Feed $feed) {
        $filename = sprintf('%s/%s', $this->basePath, $feed->getFilename('rss'));
        if (is_file($filename)) {
            unlink($filename);
        }

        $xml = new XMLManager($filename);
        $this->init($xml, $feed);
        $this->writeItems($xml, $feed);
        $xml->save();
    }

    private function init(XMLManager $xml, Feed $feed) {
        $root = $xml->getXml()->createElement('feed');
        $root->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');


    }
}
