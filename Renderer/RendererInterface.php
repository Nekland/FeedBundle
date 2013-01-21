<?php

namespace Nekland\FeedBundle\Renderer;

use Nekland\FeedBundle\Feed;

/**
 * Base of feed renderer
 */
interface RendererInterface
{
    /**
     * @abstract
     * @param Feed $feed
     * @param $filename
     * @return string
     */
    public function render(Feed $feed);
}
