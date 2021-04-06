<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/3/31
 * Time: 16:42
 */

namespace Wenruns\Form;


use App\Admin\Extensions\Form\MultiList\MultiList;

class ExtensionController
{

    /**
     * @var array
     */
    protected static $extentions = [
        'multiList' => [
            'model'     => MultiList::class,
            'parameter' => ['$column', '\Closure $closure', '$label'],
        ]
    ];

    /**
     *
     */
    public static function handle()
    {
        $form = new Form();
        dd($form);
        foreach (self::$extentions as $name => $extention) {

        }
    }
}