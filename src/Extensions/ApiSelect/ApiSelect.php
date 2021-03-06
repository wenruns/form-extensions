<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/1/28
 * Time: 13:42
 */

namespace Wenruns\Form\Extensions\ApiSelect;


use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\Field;

class ApiSelect extends Field
{
    /**
     * @var string
     */
    protected $view = 'admin.form.apiselect';

    /**
     * @var int|string
     */
    protected $uniqueKey = '';

    /**
     * @var array
     */
    protected $attach = [];

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var string
     */
    protected $uri = '';

    /**
     * @var bool
     */
    protected $isMultiple = false;

    /**
     * @var string
     */
    protected $_changed = '';

    /**
     * @var string
     */
    protected $_updated = '';

    /**
     * @var string
     */
    protected $_beforeUpdate = '';

    /**
     * @var string
     */
    protected $_beforeClear = '';

    /**
     * @var string
     */
    protected $_cleared = '';

    /**
     * @var bool
     */
    protected $_defaultSelect = false;

    /**
     * @var string
     */
    protected $relatedPath = '/wenruns/js/selector.js';

    /**
     * @var array
     */
    protected static $js = ['/wenruns/js/selector.js'];

    /**
     * @var bool
     */
    protected $pagination = false;

    /**
     * @var int
     */
    protected $perPage = 20;

    /**
     * @var int
     */
    protected $totalPages = 0;

    /**
     * ApiSelect constructor.
     * @param string $column
     * @param array $arguments
     */
    public function __construct($column = '', array $arguments = [])
    {
        $this->checkViewFile()->checkJs();
        $this->uniqueKey = mt_rand(100000, 999999);
        parent::__construct($column, $arguments);
        $this->placeholder = '请选择 ' . $this->label;
    }

    public function totalPages($totalPages)
    {
        $this->totalPages = $totalPages;
        return $this;
    }

    public function pagination($bool = true)
    {
        $this->pagination = $bool;
        return $this;
    }

    public function perPage($perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function changed($scriptFunction)
    {
        $this->_changed = $this->compressHtml($scriptFunction);
        return $this;
    }

    public function updated($scriptFunction)
    {
        $this->_updated = $this->compressHtml($scriptFunction);
        return $this;
    }

    public function beforeUpdate($scriptFunction)
    {
        $this->_beforeUpdate = $this->compressHtml($scriptFunction);
        return $this;
    }

    public function beforeClear($scriptFunction)
    {
        $this->_beforeClear = $this->compressHtml($scriptFunction);
        return $this;
    }

    public function cleared($scriptFunction)
    {
        $this->_cleared = $this->compressHtml($scriptFunction);
        return $this;
    }

    public function defaultSelect($bool = true)
    {
        $this->_defaultSelect = $bool;
        return $this;
    }


    /**
     * @param $string
     * @return string
     */
    protected function compressHtml($string)
    {
        $string = preg_replace(array("/\r|\n/", '/\'/', '/\//'), array(' ', "\'", '\/'), $string);
        return $string;
    }


    /**
     * @param bool $bool
     * @return $this
     */
    public function multiple($bool = true)
    {
        $this->isMultiple = $bool;
        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function url($url)
    {
        $this->uri = $url;
        return $this;
    }

    /**
     * @param $method
     * @return $this
     */
    public function method($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param $attach
     * @return $this
     */
    public function attach($attach)
    {
        $this->attach = is_array($attach) ? $attach : [$attach];
        return $this;
    }

    /**
     * @param array $options
     * @return $this|Field
     */
    public function options($options = [])
    {
        $this->options = $options;
        return $this;
    }


    /**
     * @param $default
     * @return Field
     */
    public function default($default): Field
    {
        $this->value = $default;
        return $this;
    }

    /**
     * @return $this
     */
    protected function checkJs()
    {
        $path = __DIR__ . '/selector.js';
        $filePath = public_path($this->relatedPath);
        if (!is_file($filePath) || filemtime($path) > filemtime($filePath)) {
            $dir = substr($filePath, 0, strrpos(str_replace('\\', '/', $filePath), '/'));
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($filePath, file_get_contents($path));
        }
        return $this;
    }

    /**
     * @return $this
     */
    protected function checkViewFile()
    {
        $viewPath = resource_path('views/admin/form/apiselect.blade.php');
        $originPath = __DIR__ . '/apiselect.blade.php';
        if (!is_file($viewPath) || filemtime($originPath) > filemtime($viewPath)) {
            $dir = substr($viewPath, 0, strrpos(str_replace('\\', '/', $viewPath), '/'));
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($viewPath, file_get_contents($originPath));
        }
        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        $this->addVariables([
            'options'       => json_encode($this->options),
            'uniqueKey'     => $this->uniqueKey,
            'url'           => $this->uri,
            'method'        => $this->method,
            'attach'        => json_encode($this->attach),
            'isMultiple'    => $this->isMultiple,
            'values'        => json_encode(is_array($this->value) ? $this->value : [$this->value]),
            'changed'       => $this->_changed,
            'updated'       => $this->_updated,
            'beforeUpdate'  => $this->_beforeUpdate,
            'beforeClear'   => $this->_beforeClear,
            'cleared'       => $this->_cleared,
            'defaultSelect' => $this->_defaultSelect,
            'pagination'    => $this->pagination,
            'perPage'       => $this->perPage,
            'totalPages'    => $this->totalPages,
        ]);
//        dd($this->variables());
        return parent::render();
    }


}