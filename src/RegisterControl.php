<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/3/31
 * Time: 11:31
 */

namespace Wenruns\Form;

/**
 * Class RegisterControl
 * @package Wenruns\Form
 */
class RegisterControl
{

    public static function handle()
    {
        file_put_contents(__DIR__ . '/test.txt', '');
    }
}