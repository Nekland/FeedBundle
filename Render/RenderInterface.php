<?php

namespace Nekland\FeedBundle\Render;

interface RenderInterface {
    public function setConfig(array $config);
    public function save();
    public function setItems(array $items);
    public function setHost($host);
    public function setRouter(\Symfony\Bundle\FrameworkBundle\Routing\Router $r);
}
