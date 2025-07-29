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

            $html = "<{$this->htmlTag}{$attrString}>\n";
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

        public function validPage()
        {
            if ($this->htmlTag !== 'html') {
                return false;
            }

            $elems = [];
            foreach ($this->content as $child) {
                if ($child instanceof Elem) {
                    $elems[] = $child;
                }
            }

            if (count($elems) !== 2 ||
                $elems[0]->htmlTag !== 'head' ||
                $elems[1]->htmlTag !== 'body') {
                return false;
            }

            $head = $elems[0];
            $body = $elems[1];

            if (!$this->validHead($head)) return false;
            if (!$this->validBody($body)) return false;

            return true;
        }


        public function validHead($head)
        {
            $hasTitle = false;
            $hasMeta = false;

            foreach ($head->content as $item) {
                if ($item instanceof Elem) {
                    if ($item->htmlTag === 'title') {
                        if ($hasTitle) return false; // only one title allowed
                        $hasTitle = true;
                    } elseif ($item->htmlTag === 'meta' && isset($item->attributes['charset'])) {
                        if ($hasMeta) return false; // only one meta[charset] allowed
                        $hasMeta = true;
                    }
                }
            }

            return $hasTitle && $hasMeta;
        }

        public function checkTags($item)
        {
            if ($item->htmlTag === 'br' || $item->htmlTag === 'hr') {
                return true;
            }

            if ($item->htmlTag === 'p') {
                foreach ($item->content as $child) {
                    if ($child instanceof Elem) return false;
                }
            }

            if ($item->htmlTag === 'table') {
                foreach ($item->content as $child) {
                    if (!($child instanceof Elem) || $child->htmlTag !== 'tr') {
                        return false;
                    }

                    foreach ($child->content as $cell) {
                        if (!($cell instanceof Elem) || !in_array($cell->htmlTag, ['td', 'th'])) {
                            return false;
                        }
                    }
                }
            }

            if ($item->htmlTag === 'ul' || $item->htmlTag === 'ol') {
                foreach ($item->content as $child) {
                    if (!($child instanceof Elem) || $child->htmlTag !== 'li') {
                        return false;
                    }
                }
            }

            foreach ($item->content as $child) {
                if ($child instanceof Elem) {
                    if (!$this->checkTags($child)) return false;
                }
            }

            return true;
        }

        public function validBody($body)
        {
            foreach ($body->content as $item) {
                if ($item instanceof Elem) {
                    if (!$this->checkTags($item)) {
                        return false;
                    }
                }
            }
            return true;
        }
    }
    
?>