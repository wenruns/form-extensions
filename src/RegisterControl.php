<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/3/31
 * Time: 11:31
 */

namespace Wenruns\Form;

use Illuminate\Database\Eloquent\Model;
use Wenruns\Form\Extensions\ApiSelect\ApiSelect;
use Wenruns\Form\Extensions\CheckboxTree\CheckboxTree;
use Wenruns\Form\Extensions\InputLoan\InputLoan;
use Wenruns\Form\Extensions\MultiCheckbox\MultiCheckbox;
use Wenruns\Form\Extensions\MultiList\MultiList;
use Wenruns\Form\Extensions\Tabs\Tabs;

/**
 * Class RegisterControl
 * @package Wenruns\Form
 */
class RegisterControl
{

    protected static $extensions = [
        'multiCheckbox' => [
            'class'  => MultiCheckbox::class,
            'params' => ['$column', '$label=""'],
        ], // 多级下拉复选框组件
        'multiList'     => [
            'class'  => MultiList::class,
            'params' => ['$column', '\Closure $closure', '$label=""'],
        ], // 多功能列表组件
        'inputLoan'     => [
            'class'  => InputLoan::class,
            'params' => ['$column', '$label=""'],
        ], //贷款用户select选项（apiSelect原始模型）
        'apiSelect'     => [
            'class'  => ApiSelect::class,
            'params' => ['$column', '$label=""'],
        ], // api查询单选框组件
        'tabs'          => [
            'class'  => Tabs::class,
            'params' => ['$column', '$label=""'],
        ], // 自定义Tab切换组件
        'checkboxTree'  => [
            'class'  => CheckboxTree::class,
            'params' => ['$column', '$label=""'],
        ], // 自定义树状复选框组件
    ];

    public static function append(array $extensions)
    {
        $arr = array_intersect_key(self::$extensions, $extensions);
        if (!empty($arr)) {
            throw new \Exception('控件名称出现重复定义：' . implode(',', array_keys($arr)));
        }
        self::$extensions = array_merge(self::$extensions, $extensions);
    }

    public static function handle()
    {
        $dir = dirname(__FILE__) . '/Flag/';
        $flagFile = md5(json_encode(self::$extensions)) . '.txt';
        if (file_exists($dir . $flagFile)) {
            self::appendExtension();
        } else {
            $files = scandir($dir);
            foreach ($files as $file) {
                if (!in_array($file, ['.', '..'])) {
                    unlink($dir . $file);
                }
            }
            file_put_contents($dir . $flagFile, 'success');
            self::rewriteNotation();
        }
    }

    protected static function rewriteNotation()
    {
        try {
            $reflect = new \ReflectionClass(new Form(Model::class));
            $doc = $reflect->getDocComment();
            $filePath = $reflect->getFileName();
            $end = ' */';
            if (empty($doc)) {
                $docs = ['/**'];
            } else {
                $docs = explode("\n", $doc);
                $end = array_pop($docs);
            }
            foreach (self::$extensions as $abstract => $item) {
                if (is_array($item)) {
                    $class = $item['class'];
                    $params = $item['params'] ?? ['$column', '$label=""'];
                } else {
                    $class = $item;
                    $params = ['$column', '$label=""'];
                }
                $docs[] = ' * @method \\' . $class . ' ' . $abstract . '(' . implode(', ', $params) . ')';
                \Encore\Admin\Form::extend($abstract, $class);
            }
            $docs[] = $end;
            $newDoc = implode("\n", $docs);
            $content = file_get_contents($filePath);
            $content = str_replace($doc, $newDoc, $content);
            file_put_contents($filePath, $content);
        } catch (\Exception $e) {

        }
    }


    protected static function appendExtension()
    {
        foreach (self::$extensions as $abstract => $item) {
            if (is_array($item)) {
                \Encore\Admin\Form::extend($abstract, $item['class']);
            } else {
                \Encore\Admin\Form::extend($abstract, $item);
            }
        }
    }


}