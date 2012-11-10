<?php

namespace Nekland\Bundle\FeedBundle\XML;

class XMLManager
{
    private $filename;
    protected $xml;
    private $new;

    public function __construct($filename)
    {
        $this->filename = $filename;

        if ($this->new = !file_exists($this->filename)) {

            $this->xml = new \DomDocument('1.0', 'utf-8');
        } else {

            $this->xml = new \DomDocument();
            $this->xml->load($this->filename);
        }
    }

    public function addTextNode($nodeName, $content, \DOMNode $parentNode)
    {
        $node = $this->xml->createElement($nodeName);
        $node = $parentNode->appendChild($node);

        if ($this->hasXMLSyntaxMarkers($content)) {
            $node_text = $this->xml->createCDATASection($content);
        } else {
            $node_text = $this->xml->createTextNode($content);
        }

        $node->appendChild($node_text);

        return $node;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getXml()
    {
        return $this->xml;
    }

    public function isNew()
    {
        return $this->new;
    }

    public function save()
    {
        return $this->xml->save($this->filename);
    }

    /**
     * Check if the content has any XML syntax markers that would result in
     * invalid XML if inserted into an XML document. If it does, the content
     * should either be wrapped in a CDATA section or have all of it's
     * special XML characters converted into entities.
     *
     * @param string $content
     * @return boolean
     */
    protected function hasXMLSyntaxMarkers($content)
    {
        foreach (array('<', '&') as $char) {
            if (strpos($content, $char) !== false) {
                return true;
            }
        }

        return false;
    }
}
