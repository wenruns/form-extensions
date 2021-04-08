<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 9:51
 */

namespace Wenruns\Form\Extensions\InputLoan;


use Encore\Admin\Form\Field;

class InputLoan extends Field\Text
{

    protected $view = 'admin.form.inputloan';


    protected $apiUri = '';

    protected $extraData = '';

    protected $method = 'POST';

    protected $javascriptCallback = '';

    protected $_disableDetail = false;

    public function __construct(string $column = '', array $arguments = [])
    {
        $this->checkViewFile();
        parent::__construct($column, $arguments);
    }

    /**
     * @param $url
     * @param string $extraData
     * @param string $method
     * @return $this
     */
    public function config($url, $extraData = '', $method = 'POST')
    {
        $this->apiUri = $url;
        $this->extraData = $extraData;
        $this->method = $method;
        return $this;
    }

    public function disableDetail($disable = true)
    {
        $this->_disableDetail = $disable;
        return $this;
    }

    /**
     * @param $javascriptFunc
     * @return $this
     */
    public function javascriptCallback($javascriptFunc)
    {
        $this->javascriptCallback = preg_replace("/(\r|\n)+/", '', $javascriptFunc);
        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->addVariables([
            'api'                => $this->apiUri,
            'extraData'          => json_encode(['data' => $this->extraData]),
            'method'             => $this->method,
            'javascriptCallback' => $this->javascriptCallback,
            'disableDetail'      => $this->_disableDetail ? 1 : 0,
        ]);
        return parent::render();
    }

    /**
     * @return $this
     */
    protected function checkViewFile()
    {
        $viewDir = resource_path('views/admin/form');
        $viewPath = $viewDir . '/inputloan.blade.php';
        $originPath = __DIR__ . '/inputloan.blade.php';
        if (!is_file($viewPath) || filemtime($originPath) > filemtime($viewPath)) {
            if (!is_dir($viewDir)) {
                mkdir($viewDir, 0777, true);
            }
            file_put_contents($viewPath, file_get_contents($originPath));
        }
        return $this;
    }

}