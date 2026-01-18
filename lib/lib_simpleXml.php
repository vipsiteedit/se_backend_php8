<?php


function append_simplexml(&$simplexml_to, &$simplexml_from)
{

    static $firstLoop = true;

    //Here adding attributes to parent
    if ($firstLoop) {
        foreach ($simplexml_from->attributes() as $attr_key => $attr_value) {
            $simplexml_to->addAttribute($attr_key, $attr_value);
        }
    }

    foreach ($simplexml_from->children() as $simplexml_child) {
        $simplexml_temp = $simplexml_to->addChild($simplexml_child->getName(), str_replace('&', '&amp;', $simplexml_child));
        foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
            $simplexml_temp->addAttribute($attr_key, $attr_value);
        }

        $firstLoop = false;

        append_simplexml($simplexml_temp, $simplexml_child);
    }

    $firstLoop = false;
}

function add_simplexml_from_array(&$simplexml, $namevar, $arraylist)
{
    $child = $simplexml->addChild(strval($namevar));
    foreach ($arraylist as $field => $value) {
        if (is_array($value)) {
            $child->addChild($field . 'count', count($value));
            foreach ($value as $item) {
                add_simplexml_from_array($child, strval($field), $item);
            }
        } else if (is_string($field)) {
            $child->addChild(strval($field), str_replace('&', '&amp;', (string)$value));
        }
    }
    $child->addChild('itemrow', count($simplexml->$namevar));
}

function simplexml_insert_before(SimpleXMLElement $parent, SimpleXMLElement $new_child, SimpleXMLElement $before)
{
    $node1 = dom_import_simplexml($parent);
    $dom_sxe = dom_import_simplexml($new_child);
    $node2 = $node1->ownerDocument->importNode($dom_sxe, true);
    $node1->insertBefore($node2, dom_import_simplexml($before));
}
