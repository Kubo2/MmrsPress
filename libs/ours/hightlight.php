<?php
/**
 *
 * @author Dušan Vala as rellik
 * http://mmrs.rellik.eu
 * 
 */
        /*         * ** highlight_string + formátování příspěvků v knize *** */

        function formatovani($vypis) {
            $array = explode('[kod]', $vypis);
            for ($i = 0; $i < count($array); $i++) {
                if (substr_count($array[$i], '[/kod]')) {
                    $subarray = explode('[/kod]', $array[$i]);
                    $subarray[0] = str_replace('&lt;', '<', $subarray[0]);
                    $subarray[0] = str_replace('&gt;', '>', $subarray[0]);
                    $subarray[0] = str_replace('<br />', '', $subarray[0]);
                    $subarray[0] = str_replace('&quot;', '"', $subarray[0]);
                    $subarray[0] = str_replace(' - ', '', $subarray[0]);
                    $subarray[0] = highlight_string($subarray[0], true);
                    $array[$i] = implode('</pre>', $subarray);
                }
            }
            $vypis = implode('<pre>', $array);

            $vypis = str_replace('<br /><br />', '<br />', $vypis);
            $vypis = preg_replace('=([^\s]*)(www\.)=', ' http://www.', $vypis);
            $vypis = preg_replace('=([^\s]*)(\w://[www\.]*)([^\s]*)=', ' [url]\\1\\2\\3\\4[/url]', $vypis);
            $vypis = preg_replace("#\[url\](.*?)\[/url\]#si", " <a href='\\1' target='_blank'>\\1</a> ", $vypis);
            $vypis = preg_replace("#\[b\](.*?)\[/b\]#si", "<b>\\1</b>", $vypis);
            $vypis = preg_replace("#\[i\](.*?)\[/i\]#si", "<i>\\1</i>", $vypis);
            $vypis = str_replace('*b*', '[b]', $vypis);
            $vypis = str_replace('*/b*', '[/b] ', $vypis);
            $vypis = str_replace('&quot;', '"', $vypis);
            $vypis = preg_replace("#\[red\](.*?)\[/\]#si", "<span class=\"red\">\\1</span>", $vypis);
            $vypis = preg_replace("#\[green\](.*?)\[/\]#si", "<span class=\"green\">\\1</span>", $vypis);
            $vypis = preg_replace("#\[blue\](.*?)\[/\]#si", "<span class=\"blue\">\\1</span>", $vypis);
            $vypis = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $vypis);
            return $vypis;
        }
