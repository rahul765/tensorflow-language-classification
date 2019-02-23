<?php
/*======================================================================*\
|| #################################################################### ||
|| # PHP Utilities Alpha 2                                            # ||
|| # ---------------------------------------------------------------- # ||
|| #         Useful Everyday PHP classes, that work as one!           # ||
|| # ---------------------------------------------------------------- # ||
|| #     All PHP code in this file is ©2014 VisionWare Studios        # ||
|| #        This script is released under The MIT License.            # ||
|| #                                                                  # ||
|| #  --------------- PHP VERSION 5.5.0 OR GREATER -----------------  # ||
|| #                                                                  # ||
|| #                  http://visionware-studios.com                   # ||
|| #################################################################### ||
\*======================================================================*/

class Error_Handle
{
    public static function GEN_ERROR($message)
    {
        $output = '<h2>Website Error</h2>';
        $output .= $message . '<hr />';
        $output .= '<small><em>Script by, Matt Kent (@Matt_Kent9)</em></small>';

        exit($output);
    }

    public static function DISPLAY_BANNED()
    {
        $output = '<h2><span style="color: red;">You have been banned!</span></h2>';
        $output .= 'Dear ' . User::username() . ', you have been banned from accessing this website. ';
        $output .= 'This could be for one one of many reasons: ';
        $output .=
            '<ul>
             <li>Spam &amp; Abuse</li>
             <li>Trolling, Upsetting &amp; Disruptive use</li>
            </ul>Please contact the site admin for further information<hr />';
        $output .= '<small><em>Script by, Matt Kent (@Matt_Kent9)</em></small>';

        exit($output);
    }
}
