<?php


class ArrayStream
{

    /**
     * Check is associate array
     * @param array $array
     * @return boolean
     */
    public static function is_assoc(&$array)
    {
        return array_keys($array) !== range(0, sizeof($array) - 1);
    }

    /**
     * Fetch last item in ordered array
     * @param array $array
     * @return mixed
     */
    public static function last(&$array)
    {
        $c = count($array);
        return $c ? $array[$c - 1] : NULL;
    }
}

/**
 * XSS Class
 */
class XSS
{

    const XSS_READ_INNER = 0; // read tag inner
    const XSS_READ_TAG = 1; // read tag name
    const XSS_READ_TAG_INFO = 2; // read tag attributes
    const XSS_OPEN_TAG = 3; // open tag type
    const XSS_CLOSE_TAG = 4; // close tag type
    const XSS_AUTOCLOSE_TAG = 5; // autoclosed tag taype
    const XSS_ATTR_NAME = 6; // attribute name
    const XSS_ATTR_DELIM = 7; // attribute name/value delimiter
    const XSS_ATTR_VAL = 8; // init attribute's value reading
    const XSS_ATTR_VAL_QUOTE = 9; // quoted attribute's value
    const XSS_ATTR_VAL_BLANK = 10; // unquoted attribute's value
    const XSS_DATA = 11; // no tag/attribute data

    /**
     * Buffer
     * @var string
     */
    private $source;

    /**
     * Last error message
     * @var string
     */
    private $last_error;

    ////////////////////////// Automate states

    /**
     * Current index in source
     * @var integer
     */
    private $index;

    /**
     * Source length
     * @var integer
     */
    private $length;

    /**
     * Automate state
     * @var integer
     */
    private $state = self::XSS_READ_INNER;

    /**
     * Current tag
     * @var string
     */
    private $current_tag = '';

    /**
     * Current attribute name
     * @var string
     */
    private $cur_attr_name = '';

    /**
     * Current attribute value
     * @var string
     */
    private $cur_attr_val = '';

    /**
     * PCDATA before tags
     * @var string
     */
    private $text_buffer = '';

    /**
     * Check for entities decode
     * @var boolean
     */
    private $flag_entity_decode;

    ////////////////////////// Settings

    /**
     * Standalone tags
     * @var array
     */
    protected $autoclosed_tags;

    /**
     * Common allowed attributes for all tags
     * @var array
     */
    protected $common_allowed_attrs;

    /**
     * Output empty attribute
     * @var boolean
     */
    protected $output_empty_attribute;

    /**
     * Allowed tags
     * @var array
     */
    protected $white_tags;

    /**
     * Get persistant plugin state
     * @override
     * @return boolean
     */
    public function is_persistant()
    {
        return TRUE;
    }

    //					'a'                             => array('href','target'),

    private function config()
    {
        $this->autoclosed_tags = array('area', 'base', 'basefont', 'bgsound', 'br', 'col', 'colgroup', 'event-source', 'frame', 'hr', 'img', 'input', 'link', 'meta', 'param');
        $this->common_allowed_attrs = array('class', 'id', 'title');
        $this->output_empty_attribute = FALSE;
        $this->white_tags = array(
            'abbr' => array(),
            'acronym' => array(), // deprecated, use <abbr>
            'address' => array(),
            'article' => array(),
            'b' => array(),
            'blockquote' => array(),
            'br' => array(),
            'caption' => array(),
            'cite' => array(),
            'code' => array(),
            'dd' => array(),
            'del' => array('cite', 'datetime'),
            'div' => array('style'),
            'dfn' => array(),
            'dl' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'hr' => array(),
            'i' => array(),
            'img' => array('alt', 'src'),
            'ins' => array('cite', 'datetime'),
            'kbd' => array(),
            'li' => array(),
            'mark' => array(),
            'nav' => array(),
            'ol' => array(),
            'optgroup' => array('label'),
            'option' => array(),
            'p' => array(),
            'pre' => array(),
            'q' => array(),
            's' => array(), // deprecated, use <del>
            'samp' => array(),
            'select' => array(),
            'small' => array(),
            'span' => array('style'),
            'strike' => array(), // deprecated, use <del>
            'strong' => array(),
            'sub' => array(),
            'sup' => array(),
            'table' => array('cellpadding', 'cellspacing'),
            'tbody' => array(),
            'td' => array('colspan', 'rowspan', 'valign'),
            'textarea' => array(),
            'tfoot' => array(),
            'th' => array('align', 'colspan', 'rowspan', 'valign'),
            'thead' => array(),
            'time' => array('datetime'),
            'tr' => array('valign'),
            'u' => array(), // deprecated, no HTML5
            'ul' => array(),
            'var' => array()
        );
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->config();
    }

    /**
     * Set source string
     * @param string $str
     * @return object
     */
    public function &set_source($str)
    {

        ///////////////////// normalize source
        do {
            // set default flag
            $this->flag_entity_decode = FALSE;
            // normalize
            $str = $this->html_normalize($str);
        } while ($this->flag_entity_decode);

        // set properties
        $this->source = $str;
        $this->state = self::XSS_READ_INNER;
        $this->index = 0;
        $this->length = strlen($str);

        // return self
        return $this;
    }

    /**
     * Fetch only visible chars
     * @param string $ch
     * @return string
     */
    private function only_char(&$ch)
    {
        // 00-08, 11-12, 14-31
        return preg_match('/[\x00-\x08\x0b\x0c\x0e-\x1f]/', $ch) ? '' : $ch;
    }

    /**
     * Convert integer to unicode char
     * @param integer $ch
     * @return string
     */
    private function uchr(&$ch)
    {
        if ($ch < 128)
            return chr($ch);
        else if ($ch < 2048)
            return chr(192 + (($ch - ($ch % 64)) / 64)) . chr(128 + ($ch % 64));
        else
            return chr(224 + (($ch - ($ch % 4096)) / 4096)) . chr(128 + ((($ch % 4096) - ($ch % 64)) / 64)) . chr(128 + ($ch % 64));
    }

    /**
     * Convert entity to char
     * @param integer $buf
     * @param boolean $is_hex
     * @return string
     */
    private function entity_decode(&$buf, $is_hex)
    {
        // convert char to integer
        if ($is_hex)
            $buf = hexdec($buf);

        // set decode flag
        $this->flag_entity_decode = TRUE;

        // return character
        return $this->only_char($this->uchr($buf));
    }

    /**
     * Convert HTML entity to char
     * @param integer $index
     * @param string $source
     * @param integer $length
     * @return string
     */
    private function pack_entity(&$index, &$source, &$length)
    {

        $tchar = ''; // char type
        $buf = ''; // buffer for html entity
        $i = $index; // start index
        $condition = '';
        $is_hex = false;

        // while not end
        while ($i < $length) {

            // read $ch
            $ch = substr($source, $i, 1);

            switch ($tchar) {
                    // start look for html entity
                case '&':
                    // is html entity
                    if ($ch == '#') {
                        $tchar = '#';
                        $i++;
                        break;
                    } // return ampersand and exit
                    else {
                        $index++;
                        return '&';
                    }
                    // continue look for html entity
                    // after &#
                case '#':

                    // set default
                    $is_hex = FALSE; // is hexadecimal number
                    $condition = '1-9';

                    // is hexadecimal number
                    if ($ch == 'x' || $ch == 'X') {
                        $tchar = 'X';
                        $i++;
                    } // is decimal number
                    else if (preg_match('/^[1-9]$/', $ch)) {
                        $buf .= $ch;
                        $tchar = 'D';
                        $condition = '\d'; // numbers
                        $i++;
                    } // skipping leading zero
                    else if ($ch == '0') {
                        $tchar = '0';
                        $i++;
                    } // return ampersand and exit
                    else {
                        $index++;
                        return '&';
                    }
                    break;
                    // continue look for html entity
                    // after &#x
                    // as hexidecimal number
                case 'X':

                    $is_hex = TRUE;

                    // is hexadecimal number
                    if (preg_match('/^[1-9a-f]$/i', $ch)) {
                        $buf .= $ch;
                        $tchar = 'D';
                        $condition = '\da-f'; // include zero
                        $i++;
                    } // skipping leading zero
                    else if ($ch == '0') {
                        $tchar = '0';
                        $condition = '1-9a-f';
                        $i++;
                    } // return ampersand and exit
                    else {
                        $index++;
                        return '&';
                    }
                    break;
                    // skip leading zero
                case '0':
                    // up step
                    $i++;
                    // if not zero
                    if (preg_match("/^[$condition]$/i", $ch)) {
                        $buf .= $ch;
                        $tchar = 'D';
                        $condition .= '0'; // include zero
                    } // skip null entity
                    else if ($ch == ';') {
                        $index = $i;
                        $buf = '';
                        $tchar = '';
                    } // try read next entity
                    else if ($ch == '&') {
                        $index = $i;
                        $buf = '';
                        $tchar = '&';
                    } // no zero read
                    else if ($ch != '0') {
                        $index = $i;
                        return $this->only_char($ch);
                    }
                    break;
                    // continue look for html entity
                    // as number
                case 'D':

                    // is number
                    if (preg_match("/^[$condition]$/i", $ch)) {
                        $buf .= $ch;
                        $i++;
                        break;
                    } // convert to character
                    else {
                        // exclude delimiter
                        if ($ch == ';')
                            $i++;

                        // save index for next search
                        $index = $i;

                        // return character
                        return $this->entity_decode($buf, $is_hex);
                    }
                    // is another character
                default:
                    // continue look for html entity
                    if ($ch == '&') {
                        $tchar = '&';
                        $i++;
                    } else {
                        // return current character
                        $index++;
                        return $this->only_char($ch);
                    }
            }
        }

        // if not buffer empty
        if (!empty($buf)) {
            // save index
            $index = $i;
            // return character
            return $this->entity_decode($buf, $is_hex);
        }

        // no result
        return FALSE;
    }

    /**
     * Remove HTML number entities
     * @param string $str
     * @return string
     */
    private function html_normalize(&$str)
    {

        $i = 0;
        $length = strlen($str);
        $result = '';

        while (($ch = $this->pack_entity($i, $str, $length)) !== FALSE) {

            if (strlen($ch) != 0)
                $result .= $ch;
        }

        return $result;
    }

    /**
     * Fetch next char
     * @return string|boolean
     */
    private function read_char()
    {
        if ($this->index < $this->length) {
            $ch = substr($this->source, $this->index, 1);
            $this->index++;
            return $ch;
        }
        return FALSE;
    }

    /**
     * Next Token
     * @return array
     */
    private function next_token()
    {

        $this->text_buffer = '';
        $tag_operation = self::XSS_DATA;
        $attr_operation = self::XSS_DATA;
        $attr = array();
        $ATTR_QUOTE = '';
        $quote_escaped = FALSE;

        while (($ch = $this->read_char()) !== FALSE) {

            switch ($this->state) {

                    // read tag inner
                case self::XSS_READ_INNER:

                    // try open a tag
                    if ($ch == '<') {
                        $this->state = self::XSS_READ_TAG;
                        $tag_operation = self::XSS_DATA;
                        $this->current_tag = '';
                    } // simple text
                    else {
                        $this->text_buffer .= $ch;
                    }

                    break;

                    // try read a tag
                case self::XSS_READ_TAG:

                    switch ($tag_operation) {

                        case self::XSS_DATA:

                            // success open tag
                            if (preg_match('/[a-z]/i', $ch)) {
                                $this->current_tag = strtolower($ch);
                                $tag_operation = self::XSS_OPEN_TAG;
                            } // closed tag
                            else if ($ch == '/')
                                $tag_operation = self::XSS_CLOSE_TAG;
                            // not tag
                            // return to inner reading
                            else {

                                // save in buffer
                                $this->text_buffer .= '<';

                                // try open tag again
                                if ($ch != '<') {
                                    $this->state = self::XSS_READ_INNER;
                                    $this->text_buffer .= $ch;
                                }
                            }
                            break;

                        case self::XSS_OPEN_TAG:

                            // read tag name
                            if (preg_match('/[\w\-]/i', $ch)) {
                                $this->current_tag .= strtolower($ch);
                            } // auto close tag
                            else if ($ch == '/') {
                                $tag_operation = self::XSS_AUTOCLOSE_TAG;
                            } // go to attributes reading
                            else if (preg_match('/\s/', $ch)) {
                                $this->state = self::XSS_READ_TAG_INFO;
                            } // read tag ineer
                            else if ($ch == '>') {
                                $this->state = self::XSS_READ_INNER;
                                return array($this->current_tag, self::XSS_OPEN_TAG, $attr);
                            } else
                                throw new Exception("Bad tag '$this->current_tag' segment");

                            break;

                        case self::XSS_CLOSE_TAG:

                            // reading tag name
                            if (preg_match('/[\w\-]/i', $ch)) {
                                $this->current_tag .= strtolower($ch);
                            } // close tag and continue read simple text
                            else if ($ch == '>') {
                                $this->state = self::XSS_READ_INNER;
                                return array($this->current_tag, self::XSS_CLOSE_TAG, $attr);
                            } // not another character
                            else if (preg_match('/\S/', $ch))
                                throw new Exception("Bad closed '$this->current_tag' tag segment");

                            break;

                        case self::XSS_AUTOCLOSE_TAG:

                            if ($ch == '>') {
                                $this->state = self::XSS_READ_INNER;
                                return array($this->current_tag, self::XSS_AUTOCLOSE_TAG, $attr);
                            } else
                                throw new Exception("Bad autoclosed '$this->current_tag' tag segment");

                            break;
                    }

                    break;

                    // read tag inforamtion
                case self::XSS_READ_TAG_INFO:

                    switch ($attr_operation) {

                            // try search attribute
                        case self::XSS_DATA:

                            if (preg_match('/[a-z]/i', $ch)) {
                                $attr_operation = self::XSS_ATTR_NAME;
                                $this->cur_attr_name = strtolower($ch);
                            } else if ($ch == '/') {
                                $tag_operation = self::XSS_AUTOCLOSE_TAG;
                                $this->state = self::XSS_READ_TAG;
                            } else if ($ch == '>') {
                                $this->state = self::XSS_READ_INNER;
                                return array($this->current_tag, self::XSS_OPEN_TAG, $attr);
                            } else if (preg_match('/\S/', $ch))
                                throw new Exception("Failure attribute reading in '$this->current_tag' tag");

                            break;

                            // read attribute's name
                        case self::XSS_ATTR_NAME:

                            if (preg_match('/[\w\-]/i', $ch)) {
                                $this->cur_attr_name .= strtolower($ch);
                                break;
                            } else if (preg_match('/\s/', $ch)) {
                                $attr_operation = self::XSS_ATTR_DELIM;
                                break;
                            }

                            // search name/value delimiter '='
                        case self::XSS_ATTR_DELIM:

                            if ($ch == '=') {
                                $attr_operation = self::XSS_ATTR_VAL;
                            } else if ($ch == '>') {
                                $this->state = self::XSS_READ_INNER;
                                $attr[$this->cur_attr_name] = array('', '"');
                                return array($this->current_tag, self::XSS_OPEN_TAG, $attr);
                            } // next attribute
                            else if (preg_match('/[a-z]/i', $ch)) {
                                $attr_operation = self::XSS_ATTR_NAME;
                                $attr[$this->cur_attr_name] = array('', '"');
                                $this->cur_attr_name = $ch;
                            } else if (preg_match('/\S/', $ch))
                                throw new Exception("Invalid attribute value in '$this->current_tag' tag");

                            break;

                            // select value's escaping
                        case self::XSS_ATTR_VAL:

                            $this->cur_attr_val = '';

                            if (preg_match('/[\'"`]/', $ch)) {
                                $attr_operation = self::XSS_ATTR_VAL_QUOTE;
                                $ATTR_QUOTE = $ch;
                            } else if ($ch == '>') {
                                $this->state = self::XSS_READ_INNER;
                                $attr[$this->cur_attr_name] = array('', '"');
                                return array($this->current_tag, self::XSS_OPEN_TAG, $attr);
                            } else if (preg_match('/\S/', $ch)) {
                                $attr_operation = self::XSS_ATTR_VAL_BLANK;
                                $this->cur_attr_val .= $ch;
                            }

                            break;

                            // read quoted value
                        case self::XSS_ATTR_VAL_QUOTE:

                            if ($ch == '\\') {
                                $quote_escaped = TRUE;
                                $this->cur_attr_val .= $ch;
                                break;
                            } else if ($ch == $ATTR_QUOTE) {
                                if ($quote_escaped) {
                                    $this->cur_attr_val .= $ch;
                                } else {
                                    $attr_operation = self::XSS_DATA;
                                    $attr[$this->cur_attr_name] = array($this->cur_attr_val, $ATTR_QUOTE);
                                }
                            } // remove \t\n\r in quoted attribute value
                            else if (preg_match('/[^\t\n\r]/', $ch)) {
                                $this->cur_attr_val .= $ch;
                            }

                            // reset escaping
                            $quote_escaped = FALSE;

                            break;

                            // read unquoted value
                        case self::XSS_ATTR_VAL_BLANK:

                            if ($ch == '>') {
                                $this->state = self::XSS_READ_INNER;
                                $attr[$this->cur_attr_name] = array($this->cur_attr_val, '"');
                                return array($this->current_tag, self::XSS_OPEN_TAG, $attr);
                            } else if ($ch == '"') {
                                // replace double quote to HTML entity
                                $this->cur_attr_val .= '&quot;';
                            } else if (preg_match('/\S/', $ch)) {
                                $this->cur_attr_val .= $ch;
                            } else {
                                $attr_operation = self::XSS_DATA;
                                $attr[$this->cur_attr_name] = array($this->cur_attr_val, '"');
                            }

                            break;
                    }

                    break;
            }
        }

        // no more tags
        return FALSE;
    }

    /**
     * Attributes to string
     * @param array $token
     * @return string
     */
    private function a2s(&$token)
    {

        $s = '';

        // read attributes
        foreach ($token[2] as $key => $value) {

            if (
                // hide empty
                empty($value[0]) && !$this->output_empty_attribute ||
                // hide denied attribute
                !in_array($key, $this->white_tags[$token[0]]) && !in_array($key, $this->common_allowed_attrs)
            ) continue;

            /////////////////////////// filter attribute

            if ($key == 'style') {
                $xss = '(/\*.*\*/|\\\)*';
                $filter =
                    '((vb|java)script|data|content|behavior|-moz-binding)\s*:' .
                    "|:\s*{$xss}e{$xss}x{$xss}p{$xss}r{$xss}e{$xss}s{$xss}s{$xss}i{$xss}o{$xss}n{$xss}";
            } else {
                $filter = '((vb|java)script|data)\s*:';
            }

            if (preg_match_all("#($filter)#i", $value[0], $matches)) {

                if (!$this->output_empty_attribute)
                    continue;

                $value[0] = '';
            }

            // save in result
            $s .= ' ' . $key . '=' . $value[1] . $value[0] . $value[1];
        }

        return $s;
    }

    /**
     * XSS filter
     * @return string
     */
    public function filter()
    {

        try {

            $stack = array();
            $super_deny = '';
            $result = '';
            $level = 0;

            // allowed tags
            $white_tags = array_keys($this->white_tags);

            // Go! Go! Go!
            while (($token = $this->next_token()) !== FALSE) {

                // save PCDATA in result
                if (empty($super_deny))
                    $result .= htmlspecialchars($this->text_buffer, ENT_COMPAT, 'UTF-8');

                switch ($token[1]) {

                    case self::XSS_OPEN_TAG:

                        if (empty($super_deny)) {

                            if (!in_array($token[0], $white_tags)) {
                                if (!in_array($token[0], $this->autoclosed_tags))
                                    $super_deny = $token[0];
                            } else {
                                $result .= '<' . $token[0] . $this->a2s($token);

                                if (in_array($token[0], $this->autoclosed_tags)) {
                                    $result .= ' />';
                                } else {
                                    $result .= '>';
                                    array_push($stack, $token[0]);
                                }
                            }
                        }

                        break;

                    case self::XSS_CLOSE_TAG:

                        if (!empty($super_deny)) {

                            $denied_tag = $super_deny;
                            $super_deny = '';

                            if ($denied_tag == $token[0])
                                break;
                        }

                        if (ArrayStream::last($stack) == $token[0]) {
                            $result .= '</' . $token[0] . '>';
                            array_pop($stack);
                        }

                        break;

                    case self::XSS_AUTOCLOSE_TAG:

                        // only allowed autoclosed tags
                        if (empty($super_deny) && in_array($token[0], $white_tags))
                            $result .= '<' . $token[0] . $this->a2s($token) . ' />';

                        break;
                }
            }

            //////////////////////////////////// POST PROCESSING

            // save PCDATA in result
            if (empty($super_deny))
                $result .= $this->text_buffer;

            // close opened tags
            while ($tag = array_pop($stack))
                $result .= '</' . $tag . '>';

            // success exit
            return $result;
        } catch (Exception $e) {
            $this->last_error = $e->getMessage();
            // failure exit
            return FALSE;
        }
    }

    /**
     * Fetch last error
     * @return string
     */
    public function get_last_error()
    {
        return $this->last_error;
    }
}
