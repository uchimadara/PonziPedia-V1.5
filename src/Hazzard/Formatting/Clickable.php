<?php

namespace Hazzard\Formatting;

/**
 * Convert plaintext URI to HTML links.
 *
 * Implementation borrowed from WordPress with some modifications.
 * https://github.com/WordPress/WordPress/blob/master/wp-includes/formatting.php
 */
class Clickable
{
    /**
     * Convert plaintext URI to HTML links.
     *
     * @param  string $text
     * @return string
     */
    public static function convert($text)
    {
        $urlPattern = '~
            ([\\s(<.,;:!?])
            (
                [\\w]{1,20}+://
                (?=\S{1,2000}\s)
                [\\w\\x80-\\xff#%\\~/@\\[\\]*(+=&$-]*+
                (?:
                    [\'.,;:!?)]
                    [\\w\\x80-\\xff#%\\~/@\\[\\]*(+=&$-]++
                )*
            )
            (\)?)
        ~xS';

        $emailPattern = '#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i';

        $r = '';
        $textarr = preg_split('/(<[^<>]+>)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $nested = 0;

        foreach ($textarr as $piece) {
            if (preg_match('|^<code[\s>]|i', $piece) || preg_match('|^<pre[\s>]|i', $piece)) {
                $nested++;
            } elseif ((strtolower($piece) === '</code>' || strtolower($piece) === '</pre>') && $nested) {
                $nested--;
            }

            if ($nested || empty($piece) || ($piece[0] === '<' && !preg_match('|^<\s*[\w]{1,20}+://|', $piece))) {
                $r .= $piece;
                continue;
            }

            if (mb_strlen($piece) > 10000) {
                foreach (static::splitByWhitespace($piece, 2100) as $chunk) {
                    if (mb_strlen($chunk) > 2101) {
                        $r .= $chunk;
                    } else {
                        $r .= static::convert($chunk);
                    }
                }
            } else {
                $ret = " $piece ";
                $ret = preg_replace_callback($urlPattern, array(__CLASS__, 'urlCallback'), $ret);
                $ret = preg_replace_callback($emailPattern, array(__CLASS__, 'emailCallback'), $ret);
                $ret = substr($ret, 1, -1);

                $r .= $ret;
            }
        }

        return preg_replace('#(<a([ \r\n\t]+[^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i', "$1$3</a>", $r);
    }

    protected static function urlCallback($matches)
    {
        $url = $matches[2];

        if ($matches[3] && strpos($url, '(') == ')') {
            $url .= $matches[3];
            $suffix = '';
        } else {
            $suffix = $matches[3];
        }

        while (substr_count($url, '(') < substr_count($url, ')')) {
            $suffix = strrchr($url, ')') . $suffix;
            $url = substr($url, 0, strrpos($url, ')'));
        }

        return $matches[1] . "<a href=\"$url\" rel=\"nofollow\">$url</a>" . $suffix;
    }

    protected static function emailCallback($matches)
    {
        $email = $matches[2] . '@' . $matches[3];

        return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
    }
    
    protected static function splitByWhitespace($string, $goal)
    {
        $chunks = array();
        $nullspace = strtr($string, "\r\n\t\v\f ", "\000\000\000\000\000\000");

        while (mb_strlen($nullspace) > $goal) {
            $pos = strrpos(substr($nullspace, 0, $goal + 1), "\000");

            if ($pos === false) {
                $pos = strpos($nullspace, "\000", $goal + 1);

                if ($pos === false) {
                    break;
                }
            }

            $chunks[]  = substr($string, 0, $pos + 1);
            $string    = substr($string, $pos + 1);
            $nullspace = substr($nullspace, $pos + 1);
        }

        if ($string) {
            $chunks[] = $string;
        }

        return $chunks;
    }
}
