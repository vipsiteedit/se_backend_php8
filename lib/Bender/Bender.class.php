<?php
/**
 * @author Alex Raven
 * @company ESITEQ
 * @website http://www.esiteq.com/
 * @email bugrov at gmail.com
 * @created 29.10.2013
 * @version 0.2
 * improved by Rolland (rolland at alterego.biz.ua)
 */

class Bender
{
    // CSS minifier
    public $cssmin = "cssmin";
    // JS minifier, can be "packer" or "jshrink"
    public $jsmin = "jshrink";
    // Packed file time to live in sec (-1 = never recompile, 0 = always recompile, default: 3600)
    public $ttl = 3600;
    // Project's root dir
    public $root_dir;

    private $stylesheets = array();
    private $javascripts = array();

    // Constructor
    private $version_key = 'v';
    public function __construct()
    {
        $this->root_dir = defined( 'ROOT_DIR' ) ? ROOT_DIR : $_SERVER['DOCUMENT_ROOT'];
    }
    // Enqueue CSS or Javascript
    public function enqueue( $src )
    {
        if ( !is_array( $src ) )
        {
            $src = array( $src );
        }
        foreach ( $src as $s )
        {
            switch ( $this->get_ext( $s ) )
            {
                case "css":
                    $this->stylesheets[] = array('file'=>$s);
                    break;
                case "js":
                    $this->javascripts[] = array('file'=>$s);
                    break;
            }
        }
    }

    public function script( $text )
    {
//        if (preg_match("/<script.*src=[\"\']([^\.]+\.js*?)[\"\'].*\/script>/sim", $text, $mm)){
        if (preg_match("/<script.*src=[\"\']([\W\w]+\.js*?)[\"\'].*\/script>/sim", $text, $mm)){
            if ($mm[1][0] == '/') $mm[1] = substr($mm[1], 1);
            $this->enqueue($mm[1]);
            //print_r($this->javascripts);
            $text = str_replace($mm[0], '', $text);
        }
        while (preg_match("/<script[^>]+>(.*)<\/script>/sim", $text, $mm)){
            $this->javascripts[] = array('content' => $mm[1]);
            $text = str_replace($mm[0], '', $text);
        }
        return $text;
        //$this->javascripts[] = array('content'=>$text);
    }

    public function style( $text )
    {
        $this->stylesheets[] = array('content'=>$text);
    }

    // Minify CSS / Javascripts and write output
    protected function minify( $scripts, $ext, $output )
    {
        $path = $this->root_dir();
        $outfile = "{$path}/{$output}";
        if ( file_exists( $outfile ) )
        {
            if ( $this->ttl == -1 )
            {
                // never recompile
                return true;
            }
            $fileage = time() - filemtime( $outfile );
            if ( $fileage < $this->ttl )
            {
                return true;
            }
        }

        $str = $this->join_files( $scripts );
        switch ( $ext )
        {
            case "css":
                switch ( $this->cssmin )
                {
                    case "cssmin":
                        require_once realpath( dirname( __file__ ) . "/cssmin.php" );

                        $packed = CssMin::minify( $str );
                        break;
                    default:
                        $packed = $str;
                }
                break;
            case "js":
                switch ( $this->jsmin )
                {
                    case "JSMin":
                        require_once realpath( dirname( __file__ ) ) . "/jsmin.php";
                        $packed = JSMin::minify( $str );
                        break;
                    case "packer":
                        require_once realpath( dirname( __file__ ) ) . "/class.JavaScriptPacker.php";
                        $packer = new JavaScriptPacker( $str, "Normal", true, false );
                        $packed = $packer->pack();
                        break;
                    case "jshrink":
                        if (file_exists(realpath( dirname( __file__ ) ) . "/JShrink.class.php")) {
                            require_once realpath( dirname( __file__ ) ) . "/JShrink.class.php";
                            $packed = Minifier::minify( $str );
                        }
                        break;
                    default:
                        $packed = $str;
                }
                break;
        }
        //$packed = gzencode($packed, 9, FORCE_GZIP);
        file_put_contents( $outfile, $packed );
    }

    private function normalizeUrl($str, $path)
    {
        preg_match_all('/url\(([^\)]*)\)/im', $str, $mm);
        foreach($mm[1] as $it=>$url){
            $new_link = '';
            $url = str_replace(array("'", '"'), '', $url);
            if (strpos($url, '://')!==false) continue;

            @list($url_line, $param) = explode('?', $url);
            if ($url_line[0] !== '/') {
                if ($param) $param = '?' . $param;
                $new_link = (($path[0] == '/') ? '' : '/') . $path . '/' . $url_line;
                $new_link = str_replace($url, $new_link . $param , $mm[0][$it]);
                $str = str_replace($mm[0][$it], $new_link, $str);
            }
        }
        return $str;
    }

    public function getMd5NameJs()
    {
        $md5name = '';
        foreach($this->javascripts as $name)
            $md5name .= $name['file'];
        return md5($md5name);
    }

    public function getMd5NameCss()
    {
        $md5name = '';
        foreach($this->stylesheets as $name)
            $md5name .= $name['file'];
        return md5($md5name);
    }

    // Print output for CSS or Javascript
    public function output( $output )
    {
        $output = ltrim( $output, './' );
        switch ( $this->get_ext( $output ) )
        {
            case "css":
                $this->check_recombine( $output, $this->stylesheets );
                $this->minify( $this->stylesheets, "css", $output );
                return '<link href="' . $this->get_src( $output ) . '" rel="stylesheet" type="text/css"/>';
                break;
            case "js":
                $this->check_recombine( $output, $this->javascripts );
                $this->minify( $this->javascripts, "js", $output );
                return '<script type="text/javascript" src="' . $this->get_src( $output ) . '"></script>';
                break;
        }
    }
    // Get root dir
    protected function root_dir()
    {
        return $this->root_dir;
    }
    // Join array of files into a string
    protected function join_files( $files )
    {
        $path = $this->root_dir();
        if ( !is_array( $files ) )
        {
            return "";
        }
        $c = "";
        foreach ( $files as $file )
        {
            if ($file['file']) {
                if (file_exists("{$path}/{$file['file']}")) {
                    if (strpos($file, '.css') !== false)
                        $t = $this->normalizeUrl(file_get_contents("{$path}/{$file['file']}"), dirname($file['file']));
                    else
                        $t = file_get_contents("{$path}/{$file['file']}");
                }
            } else {
                $t = $file['content'];
            }
            $c .= preg_replace('/[\x{feff}-\x{ffff}]/u', '', $t);
        }
        return $c;
    }
    // Get extension in lowercase
    protected function get_ext( $src )
    {
        return strtolower( pathinfo( $src, PATHINFO_EXTENSION ) );
    }
    /**
     * Gheck if need to recombine output file
     */
    protected function check_recombine( $output, $files )
    {
        $path = $this->root_dir();
        $outfile = "{$path}/{$output}";
        if ( !file_exists( $outfile ) || !is_array( $files ) )
        {
            return;
        }
        // find last modify time of src
        $last = 0;
        foreach ( $files as $file )
        {
            if ( ( $_time = filemtime( $path . "/" . $file['file'] ) ) > $last )
                $last = $_time;
        }
        if ( filemtime( $outfile ) < $last )
        {
            // need to be recombined
            $this->ttl = 0;
        }
        else
        {
            $this->ttl = -1;
        }
    }

    /**
     * returns src for resource due to filemtime
     */
    protected function get_src( $output )
    {
        $path = $this->root_dir();
        return '/' . $output . '?' . $this->version_key . '=' . filemtime( $path . "/" . $output );
    }

}
