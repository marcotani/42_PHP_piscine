<?php

require_once 'MyException.php';

    class Elem
    {
        private static $singleTags = ['br', 'hr', 'img', 'meta'];
        private static $pairTags = ['html', 'head', 'body', 'title', 'div', 'span', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'table', 'tr', 'th', 'td', 'ul', 'ol', 'li'];

        private $htmlTag;
        private $content = [];
        private $attributes = [];

        public function __construct($htmlTag, $content = [], $attributes = [])
        {
            $this->htmlTag = strtolower($htmlTag);
            if (!in_array($this->htmlTag, self::$singleTags) && !in_array($this->htmlTag, self::$pairTags)) {
                throw new MyException("Invalid HTML tag: {$this->htmlTag}");
            }
            $this->content[] = $content;
            if (is_array($attributes)) {
                foreach ($attributes as $key => $value) {
                    $this->attributes[$key] = htmlspecialchars($value);
                }
            } else {
                throw new MyException("Attributes must be an associative array.");
            }
        }

        public function pushElement($element)
        {
            if (is_array($element)) {
                $this->content = array_merge($this->content, $element);
            } else {
                $this->content[] = $element;
            }
        }

        public function getHTML()
        {
            $attrString = '';
            foreach ($this->attributes as $key => $value) {
                $attrString .= " $key=\"$value\"";
            }

            if (in_array($this->htmlTag, self::$singleTags)) {
                return "<{$this->htmlTag}{$attrString}>\n";
            }

            $html = "<{$this->htmlTag}{$attrString}>\n";
            foreach ($this->content as $item) {
                if (is_string($item)) {
                    $html .= htmlspecialchars($item);
                } elseif ($item instanceof Elem) {
                    $html .= $item->getHTML();
                }
            }
            $html .= "</{$this->htmlTag}>\n";
            return $html;
        }
    }
    
?>