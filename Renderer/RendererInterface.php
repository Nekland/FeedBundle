<?php

namespace Nekland\Bundle\FeedBundle\Renderer;

use Nekland\Bundle\FeedBundle\Feed;

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
