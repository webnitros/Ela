<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 02.12.2022
 * Time: 04:12
 */

if (!function_exists('highlightQuery')) {
    /**
     * Highlight search string in given text string
     *
     * @param $text
     * @param string $query
     * @param string $htag_open
     * @param string $htag_close
     * @param boolean $strict
     *
     * @return mixed
     */
    function highlightQuery($text, $query, $htag_open = '<suggest>', $htag_close = '</suggest>', $strict = true)
    {
        if (empty($query)) {
            return $text;
        }
        $from = $to = array();

        $introCutBefore = 50;
        $introCutAfter = 250;

        $split_words = '#\s#';

        $tmp_words = preg_split($split_words, $query, -1, PREG_SPLIT_NO_EMPTY);
        // Exact match
        $pcre = $strict
            ? '#\b' . preg_quote($query) . '\b#imus'
            : '#' . preg_quote($query) . '#imus';
        if (count($tmp_words) > 1 && preg_match($pcre, $text, $matches)) {
            $pos = mb_stripos($text, $matches[0], 0);
            if ($pos >= $introCutBefore) {
                $text_cut = '... ';
                $pos -= $introCutBefore;
            } else {
                $text_cut = '';
                $pos = 0;
            }
            $text_cut .= mb_substr($text, $pos, $introCutAfter);
            if (mb_strlen($text) > $introCutAfter) {
                $text_cut .= ' ...';
            }

            foreach ($matches as $v) {
                $from[$v] = $v;
                $to[$v] = $htag_open . $v . $htag_close;
            }
        } // Matching by separate words
        else {


            $tmp = array_merge(
                $tmp_words,
                explode(' ', $query)
            );

            $tmp = array_unique($tmp);
            #$tmp = $this->getAllForms($tmp);

            $words = $tmp;
            #$words = array_keys($tmp);

            #foreach ($tmp as $v) {
            #    $words = array_merge($words, $v);
            # }

            if (empty($words)) {
                $words = array($query => $query);
            }

            $text_cut = '';
            foreach ($words as $key => $word) {
                /*
                if (!preg_match('/^[0-9]{2,}$/', $word) && mb_strlen($word) < $this->config['min_word_length']) {
                    unset($words[$key]);
                    continue;
                }
                */
                $word = preg_quote($word, '/');
                $words[$key] = $word;

                // Cutting text on first occurrence
                $pcre = $strict ? '/\b' . $word . '\b/imu' : '/' . $word . '/imu';
                if (empty($text_cut) && !empty($word) && preg_match($pcre, $text, $matches)) {
                    $pos = mb_stripos($text, $matches[0], 0);


                    if ($pos >= $introCutBefore) {
                        $text_cut = '... ';
                        $pos -= $introCutBefore;
                    } else {
                        $text_cut = '';
                        $pos = 0;
                    }
                    $text_cut .= mb_substr($text, $pos, $introCutAfter);
                    if (mb_strlen($text) > $introCutAfter) {
                        $text_cut .= ' ...';
                    }
                }
            }

            if (empty($text_cut) && $strict) {
                return highlightQuery($text, $query, $htag_open, $htag_close, false);
            }

            $pcre = $strict ? '/\b(' . implode('|', $words) . ')\b/imu' : '/(' . implode('|', $words) . ')/imu';
            if (preg_match_all($pcre, $text_cut, $matches)) {
                foreach ($matches[0] as $v) {
                    $from[$v] = $v;
                    $to[$v] = $htag_open . $v . $htag_close;
                }
            }

            if (!empty($matches[1])) {
                foreach ($matches[1] as $v) {
                    $from[$v] = $v;
                    $to[$v] = $htag_open . $v . $htag_close;
                }
            } elseif ($strict) {
                return highlightQuery($text, $query, $htag_open, $htag_close, false);
            }
        }

        return str_replace($from, $to, $text_cut);
    }

}
