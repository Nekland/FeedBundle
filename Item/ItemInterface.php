<?php

namespace Nekland\FeedBundle\Item;

interface ItemInterface {
    /*
     * @return unique identifiant (required for editing)
     */
    public function getRssId();
}