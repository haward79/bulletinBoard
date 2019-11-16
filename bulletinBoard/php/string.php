<?php

    function isNotEmpty($str)
    {
        if($str != '')
            return true;
        else
            return false;
    }

    function toHtml($str)
    {
        $str = str_replace(' ', '&nbsp;', $str);
        $str = str_replace("\n", '<br />', $str);
        $str = str_replace('"', '&quot;', $str);
        $str = str_replace('\'', '&apos;', $str);

        return $str;
    }

