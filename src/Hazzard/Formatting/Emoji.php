<?php

namespace Hazzard\Formatting;

/**
 * @link  https://unicode.org/emoji/charts/full-emoji-list.html
 *
 * Add emoji: Emoji::add(':)', '1f604');
 */
class Emoji
{
    /**
     * @var array
     */
    public static $emoji = array(
        ':)' => '1f604',
        ':D' => '1f603',
        ':P' => '1f61c',
        ':(' => '1f61f',
        ':|' => '1f610',
        ';)' => '1f609',
        ':*' => '1f618',
        ':O' => '1f62e',
        'B)' => '1f60e',
    );

    /**
     * Convert text equivalent of emoji to symbols.
     *
     * @param  string $text
     * @return string
     */
    public static function convert($text)
    {
        if (count(static::$emoji) === 0) {
            return $text;
        }

        $subchar = '';
        $spaces = '[\r\n\t ]|\xC2\xA0|&nbsp;';
        $smiliessearch = '/(?<='.$spaces.'|^)';

        foreach (static::$emoji as $shortcode => $codepoint) {
            $firstchar = substr($shortcode, 0, 1);
            $rest = substr($shortcode, 1);

            if ($firstchar !== $subchar) {
                if ($subchar !== '') {
                    $smiliessearch .= ')(?='.$spaces.'|$)';
                    $smiliessearch .= '|(?<='.$spaces.'|^)';
                }

                $subchar = $firstchar;
                $smiliessearch .= preg_quote($firstchar, '/') . '(?:';
            } else {
                $smiliessearch .= '|';
            }

            $smiliessearch .= preg_quote($rest, '/');
        }

        $smiliessearch .= ')(?='.$spaces.'|$)/m';

        $output  = '';
        $textarr = preg_split('/(<.*>)/U', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        $ignoreTags  = 'code|pre|style|script|textarea';
        $ignoreBlock = '';

        for ($i = 0; $i < count($textarr); $i++) {
            $content = $textarr[$i];

            if ($ignoreBlock === '' && preg_match('/^<('.$ignoreTags.')>/', $content, $matches))  {
                $ignoreBlock = $matches[1];
            }

            if ($ignoreBlock === '' && mb_strlen($content) > 0 && $content[0] !== '<') {
                $content = preg_replace_callback($smiliessearch, array(__CLASS__, 'replace'), $content);
            }

            if ($ignoreBlock !== '' && $content === '</'.$ignoreBlock.'>') {
                $ignoreBlock = '';
            }

            $output .= $content;
        }

        return $output;
    }

    protected static function replace($matches)
    {
        if (count($matches) === 0) {
            return '';
        }

        $shortcode = trim(reset($matches));

        if (!isset(static::$emoji[$shortcode])) {
            return $shortcode;
        }

        $code = hexdec('0x'.static::$emoji[$shortcode]);
        $first = (($code - 0x10000) >> 10) + 0xD800;
        $second = (($code - 0x10000) % 0x400) + 0xDC00;

        return json_decode('"'.sprintf("\\u%X\\u%X", $first, $second).'"');
    }

    /**
     * Add emoji short code.
     * 
     * @param  string|array $shortcode 
     * @param  string|null $codepoint
     * @return void
     */
    public static function add($shortcode, $codepoint = null)
    {
        if (is_array($shortcode)) {
            static::$emoji = array_merge(static::$emoji, $shortcode);
        } else {
            static::$emoji[$shortcode] = $codepoint;
        }
    }
}
