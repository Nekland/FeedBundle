<?php

namespace Nekland\FeedBundle\Renderer;

use Nekland\FeedBundle\Feed;

/**
 * Base of feed renderer
 */
interface RenderInterface
{
    /**
     * @abstract
     * @param Feed $feed
     * @param $filename
     * @return void
     */
    public function render(Feed $feed, $filename);
}
