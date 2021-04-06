<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/1/26
 * Time: 9:33
 */

namespace Wenruns\Form\Extensions\MultiList;

use Encore\Admin\Facades\Admin;

/**
 * Class TableList
 * @package App\Admin\Extensions\Form\MultiList
 */
class TableList
{
    use CommonMethods;

    /**
     * @var MultiList|null
     */
    protected $multiList = null;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var null
     */
    protected $parentColumn = null;

    /**
     * @var array
     */
    protected $defaultValues = [];

    /**
     * @var string
     */
    protected $content = '';

    /**
     * @var string
     */
    protected $unique = '';

    /**
     * @var null
     */
    protected $value = null;

    /**
     * @var int
     */
    protected $key = 0;

    /**
     * @var string
     */
    protected $buttonEventClosure = null;

    /**
     * @var bool
     */
    protected $showButton = false;

    /**
     * TableList constructor.
     * @param MultiList $multiList
     * @param null $parentColumn
     */
    public function __construct(MultiList $multiList, $parentColumn = null)
    {
        $this->multiList = $multiList;
        $this->parentColumn = empty($parentColumn) ? $multiList->column() : $parentColumn;
    }

    /**
     * @return int|string
     */
    public function getUniqueKey()
    {
        if (empty($this->unique)) {
            $this->unique = mt_rand(10000, 99999);
        }
        return $this->unique;
    }

    /**
     * @param $value
     * @param int $key
     * @return $this
     */
    public function value($value, $key = 0)
    {
        $this->value = $value;
        $this->key = $key;
        return $this;
    }

    /**
     * @return int
     */
    public function getColumnLen()
    {
        return count($this->columns);
    }


    public function default($value)
    {
        $this->defaultValues = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->content = $this->tableListHead() . $this->tbodyStart();
        if (!empty($this->defaultValues)) {
            if (count($this->defaultValues) == count($this->defaultValues, true)) {
                $this->content .= $this->value($this->defaultValues)->tableListBody();
            } else {
                foreach ($this->defaultValues as $k => $item) {
                    $this->content .= $this->value($item, $k)->tableListBody();
                }
            }
        }
        $this->content .= $this->tableListEnd();
        return $this->content;
    }

    /**
     * @return string
     */
    public function tableListHead()
    {
        $content = $this->tableStart() . $this->theadStart() . $this->trStart();
        foreach ($this->columns as $key => $column) {
            if ($column->type == 'hidden') {
                continue;
            }
            $content .= $this->thStart($column) . $column->render() . $this->thEnd();
        }
        $content .= $this->addButton();
        $content .= $this->trEnd() . $this->theadEnd();

        return $content;
    }

    /**
     * @return string
     */
    protected function addButton()
    {
        if (!$this->showButton) {
            return '';
        }
        $this->buttonEvent(true);
        return <<<HTML
<th >
    <span class="btn btn-xs btn-success table-list-btn-{$this->getUniqueKey()}" data-type="add">
        <i class="fa fa-edit fa-fw"></i>新增
    </span>
</th>
HTML;
    }

    /**
     * @return string
     */
    protected function removeButton()
    {
        if (!$this->showButton) {
            return '';
        }
        $this->buttonEvent();
        return <<<HTML
<td>
    <span class="btn btn-xs btn-danger table-list-btn-{$this->getUniqueKey()}" data-type="remove">
        <i class="fa fa-remove fa-fw"></i>移除
    </span>
</td>
HTML;
    }

    protected function buttonEvent($addBtn = false)
    {
        $eventClosure = $this->buttonEventClosure ? $this->buttonEventClosure : 0;
        $number = count($this->multiList->getOptions());
        $emptyBody = str_replace('/', '\/', $this->compressHtml($this->multiList->emptyBody()));
        $oneRowHtml = '';
        if ($addBtn) {
            $oneRowHtml = $this->getOneRowHtml();
//            $path = 'E:\wens\CompanyProject\bkqw_loan.cc\test.html';
//            file_put_contents($path, $oneRowHtml);
            $oneRowHtml = str_replace('/', '\/', $this->compressHtml($oneRowHtml));
        }
        $script = <<<SCRIPT
$(function(){
    var number_{$this->getUniqueKey()} = $number;
    $(document).on("click", ".table-list-btn-{$this->getUniqueKey()}", function(e){
        var fn = {$eventClosure};
        if(!fn){
            fn  = function(type, e){
                var parentColumn = '{$this->parentColumn}';
                if(type == 'add'){
                    var oneRowHtml = `{$oneRowHtml}`.replace(/@{$this->getKeyVariableName()}@/mg, number_{$this->getUniqueKey()});
                    number_{$this->getUniqueKey()}++;
                    Array.from(e.currentTarget.parentElement.parentElement.parentElement.parentElement.children).forEach(function(item, k){
                        if(item.tagName == 'TBODY'){
                            if(item.dataset.empty){
                                item.innerHTML = "";
                                item.removeAttribute("data-empty")
                            }
                            var table = document.createElement("table");
                            table.innerHTML = oneRowHtml;
                            var children = table.children[0] ? table.children[0].children : null; 
                            if(children){
                                Array.from(children).forEach(function(vo){
                                    item.append(vo);
                                    var script = vo.querySelectorAll("script");
                                    if(script){
                                        Array.from(script).forEach(function(v){
                                            console.log(v.innerHTML);
                                            eval(v.innerHTML);
                                        });
                                    }
                                })
                            }
                           
                        }
                    });
                }else{
                    function remove(ele){
                        if(ele.nextSibling && ele.nextSibling.dataset && ele.nextSibling.dataset.sub == 'true'){
                            remove(ele.nextSibling);
                        }
                        ele.remove();
                    }
                    var parent = e.currentTarget.parentElement.parentElement.parentElement;
                    console.log(e, parent);
                    remove(e.currentTarget.parentElement.parentElement);
                    if(parent && Array.from(parent.children).length == 0){
                        parent.innerHTML = `{$emptyBody}`;
                        parent.setAttribute("data-empty","true");
                    }
                }
            }
        }
        fn.call(this, e.currentTarget.dataset.type, e);
    });
})
SCRIPT;
        Admin::script($script);
        return $this;
    }

    /**
     * @param $jsEventClosure
     * @return $this
     */
    public function showButton($jsEventClosure)
    {
        $this->showButton = true;
        if (is_callable($jsEventClosure)) {
            $this->buttonEventClosure = $this->compressHtml(call_user_func($jsEventClosure));
            return $this;
        }
        $this->buttonEventClosure = $this->compressHtml($jsEventClosure);
        return $this;
    }


    public function getKeyVariableName()
    {
        return $this->getUniqueKey();
    }


    /**
     * @param bool $createEmpty
     * @return string
     */
    public function tableListBody()
    {
        if (empty($this->value) && empty($this->createEmpty)) {
            return '';
        }
        $expandOrModal = '';
        $bodyContent = $this->trStart();
        foreach ($this->columns as $key => $column) {
            $field = $column->createEmpty($this->createEmpty)
                ->keyIsVariable($this->keyIsVariable, $this->getKeyVariableName())
                ->renderValue($this->value, $this->key);
            if ($column->type == 'hidden') {
                $bodyContent .= $field->render();
                continue;
            }
            $bodyContent .= $this->tdStart($field) . $field->render() . $this->tdEnd();
            $expandOrModal .= $field->renderSub();
        }
        $bodyContent .= $this->removeButton() . $this->trEnd() . $expandOrModal;
        return $bodyContent;
    }


    public function getOneRowHtml()
    {
        return $this->createEmpty(true)->keyIsVariable(true)->tableListBody();
    }


    /**
     * @return string
     */
    public function tableListEnd()
    {
        return $this->tbodyEnd() . $this->tableEnd();
    }


    public function trStart()
    {
        return '<tr>';
    }

    public function trEnd()
    {
        return '</tr>';
    }

    public function tbodyStart($empty = false)
    {
        if ($empty) {
            return '<tbody data-empty="true">';
        }
        return '<tbody>';
    }

    public function tbodyEnd()
    {
        return '</tbody>';
    }

    public function theadStart()
    {
        return '<thead>';
    }

    public function theadEnd()
    {
        return '</thead>';
    }

    public function tableStart()
    {
        $key = $this->multiList->getUniqueKey();
        return '<table class="table table-hover" id="grid-table-' . $key . $this->getUniqueKey() . '">';
    }

    public function tableEnd()
    {
        return '</table>';
    }


    public function thStart(BaseColumn $column)
    {
        return '<th style="width:' . $column->width . '" class="">';
    }

    public function thEnd()
    {
        return '</th>';
    }


    public function tdStart(Field $field)
    {
        return '<td class="' . $field->getClass() . '">';
    }

    public function tdEnd()
    {
        return '</td>';
    }

}