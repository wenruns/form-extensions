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
        return count($this->columns) + ($this->showButton ? 1 : 0);
    }

    /**
     * @param $value
     * @return $this
     */
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
        $number = count($this->multiList->getOptions());
        return <<<HTML
<th >
    <span class="btn btn-sm btn-success table-list-btn-add-{$this->getUniqueKey()}" data-type="add" data-number="{$number}">
        <i class="fa fa-edit fa-fw"></i>新增
    </span>
    <table style="display: none">{$this->getOneRowHtml()}</table>
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
    <span class="btn btn-sm btn-danger table-list-btn-remove-{$this->getUniqueKey()}" data-type="remove">
        <i class="fa fa-remove fa-fw"></i>移除
    </span>
</td>
HTML;
    }

    /**
     * @param bool $addBtn
     * @return $this
     */
    protected function buttonEvent($addBtn = false)
    {
        $eventClosure = $this->buttonEventClosure ? $this->buttonEventClosure : 0;
        $addBtn ? $this->addEvent($eventClosure) : $this->removeEvent($eventClosure);
        return $this;
    }

    /**
     * @param $eventClosure
     * @return $this
     */
    protected function addEvent($eventClosure)
    {
        $btnClass = 'table-list-btn-add-' . $this->getUniqueKey();
        $replaceStr = MultiList::SYMBOL_BEGIN . $this->getKeyVariableName() . MultiList::SYMBOL_END;
        $reg = MultiList::SYMBOL_BEGIN . '.+' . MultiList::SYMBOL_END;
        $script = <<<SCRIPT
$(function(){
    $(document).on("click", ".{$btnClass}", function(e){
        function appendContent(oneRowHtml){
            oneRowHtml = oneRowHtml.replace(/{$replaceStr}/mg, e.currentTarget.dataset.number);
            e.currentTarget.dataset.number++;
            Array.from(e.currentTarget.parentElement.parentElement.parentElement.parentElement.children).forEach(function(item, k){
                if(item.tagName == 'TBODY'){
                    if(item.dataset.empty){
                        item.innerHTML = "";
                        item.removeAttribute("data-empty")
                    }
                    var table = document.createElement("table");
                    table.innerHTML = oneRowHtml;
                    
                    function addTd(tr){
                        item.append(tr);
                        var inputs = tr.querySelectorAll(".table.table-hover>tbody [name]");
                        if(inputs){
                            Array.from(inputs).forEach(function(v){
                                console.log(v.attributes.name.value);
                                var res = /{$reg}/.test(v.attributes.name.value);
                                if(!res){
                                    v.removeAttribute("disabled")                                
                                }
                            });
                        }
                        var script = tr.querySelectorAll(".script-content");
                        if(script){
                            Array.from(script).forEach(function(v){
                                console.log(v.dataset.class);
                                var res = /{$reg}/.test(v.dataset.class);
                                if(!res){
                                    try{
                                        eval(v.innerHTML);
                                    }catch(e){
                                        console.error(e);
                                    }
                                }
                            });
                        }
                    }
                    Array.from(table.children).forEach(function(child){
                        if(child.tagName == "TBODY"){
                            Array.from(child.children).forEach(function(vo){
                                addTd(vo);
                            });
                        }else if(child.tagName == "TR"){
                            addTd(child);
                        }
                    });
                }
            });
        }
        Array.from(e.currentTarget.nextElementSibling.children).forEach(function(item){
            if(item.tagName == "TBODY"){
                appendContent(item.innerHTML);
            }
        });
        if({$eventClosure}){
            var fn = {$eventClosure};
            fn.call(this, e.currentTarget.dataset.type, e);
        }
    });
});
SCRIPT;
        Admin::script($script);
        return $this;
    }

    /**
     * @param $eventClosure
     * @return $this
     */
    protected function removeEvent($eventClosure)
    {
        $emptyBody = str_replace('/', '\/', $this->compressHtml($this->multiList->emptyBody()));
        $btnClass = 'table-list-btn-remove-' . $this->getUniqueKey();
        $script = <<<SCRIPT
$(function(){
    $(document).on("click", ".{$btnClass}", function(e){
        function remove(ele){
            if(ele.nextElementSibling && ele.nextElementSibling.dataset && ele.nextElementSibling.dataset.sub == 'true'){
                remove(ele.nextElementSibling);
            }
            ele.remove();
        }
        var parent = e.currentTarget.parentElement.parentElement.parentElement;
        remove(e.currentTarget.parentElement.parentElement);
        if(parent && Array.from(parent.children).length == 0){
            parent.innerHTML = `{$emptyBody}`;
            parent.setAttribute("data-empty","true");
        }
        if({$eventClosure}){
            var fn = {$eventClosure};
            fn.call(this, e.currentTarget.dataset.type, e);
        }
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

    /**
     * @return int|string
     */
    public function getKeyVariableName()
    {
        return $this->getUniqueKey();
    }


    /**
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

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function trStart()
    {
        return '<tr>';
    }

    /**
     * @return string
     */
    public function trEnd()
    {
        return '</tr>';
    }

    /**
     * @param bool $empty
     * @return string
     */
    public function tbodyStart($empty = false)
    {
        if ($empty) {
            return '<tbody data-empty="true">';
        }
        return '<tbody>';
    }

    /**
     * @return string
     */
    public function tbodyEnd()
    {
        return '</tbody>';
    }

    /**
     * @return string
     */
    public function theadStart()
    {
        return '<thead>';
    }

    /**
     * @return string
     */
    public function theadEnd()
    {
        return '</thead>';
    }

    /**
     * @return string
     */
    public function tableStart()
    {
        $key = $this->multiList->getUniqueKey();
        return '<table class="table table-hover" id="grid-table-' . $key . $this->getUniqueKey() . '">';
    }

    /**
     * @return string
     */
    public function tableEnd()
    {
        return '</table>';
    }

    /**
     * @param BaseColumn $column
     * @return string
     */
    public function thStart(BaseColumn $column)
    {
        return '<th style="width:' . $column->width . '" class="">';
    }

    /**
     * @return string
     */
    public function thEnd()
    {
        return '</th>';
    }


    /**
     * @param Field $field
     * @return string
     */
    public function tdStart(Field $field)
    {
        return '<td class="' . $field->getClass() . '">';
    }

    /**
     * @return string
     */
    public function tdEnd()
    {
        return '</td>';
    }

}