<?php

    class Elem
    {
        private static $singleTags = ['br', 'hr', 'img', 'meta'];
        private static $pairTags = ['html', 'head', 'body', 'title', 'div', 'span', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

        private $htmlTag;
        private $content = [];

        public function __construct($htmlTag, $content = [])
        {
            $this->htmlTag = strtolower($htmlTag);
            if (!in_array($this->htmlTag, self::$singleTags) && !in_array($this->htmlTag, self::$pairTags)) {
                throw new Exception("Invalid HTML tag: {$this->htmlTag}");
            }
            $this->content[] = $content;
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
            $html = "<{$this->htmlTag}>\n";
            if (!in_array($this->htmlTag, self::$singleTags)) {
                foreach ($this->content as $item) {
                    if (is_string($item)) {
                        $html .= htmlspecialchars($item);
                    } elseif ($item instanceof Elem) {
                        $html .= $item->getHTML();
                    }
                }
                $html .= "</{$this->htmlTag}>\n";
            }
            return $html;
        }
    }
    
?>