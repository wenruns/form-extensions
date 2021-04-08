<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/1/26
 * Time: 10:31
 */

namespace Wenruns\Form\Extensions\MultiList\Field;


use Wenruns\Form\Extensions\MultiList\Field;

class Select extends Field
{
    public function build()
    {
        // TODO: Implement build() method.
        $optionStr = '';
        foreach ($this->options as $value => $label) {
            $optionStr .= '<option value="' . $value . '" ' . ($this->getValue() == $value ? 'selected' : '') . '>' . $label . '</option>';
        }
        return <<<HTML
<div style="width: 100%;{$this->style}" class="input-group">
    <select class="form-control {$this->getClass()} select2-hidden-accessible" id="{$this->getClass()}" name="{$this->getName()}" data-value="{$this->getValue()}" tabindex="-1" aria-hidden="true" data-placeholder="{$this->getPlaceholder()}" style="width:100%;" {$this->buildAttribute()}>{$optionStr}</select>
</div>
<script>
    $(function(){
        $("select.{$this->getClass()}").select2({
            "allowClear":true,
            "placeholder":{
                "id":"{$this->getClass()}",
                "text":"选择{$this->getPlaceholder()}"
            },
        });
    })
</script>
HTML;
    }


    protected function buildEmpty(): string
    {
        // TODO: Implement buildEmpty() method.
        $optionStr = '';
        foreach ($this->options as $value => $label) {
            $optionStr .= '<option value="' . $value . '" >' . $label . '</option>';
        }
        return <<<HTML
<div style="width: 100%;{$this->style}" class="input-group">
    <select class="form-control {$this->getClass()} select2-hidden-accessible" id="{$this->getClass()}" name="{$this->getName()}" data-value="" tabindex="-1" aria-hidden="true" data-placeholder="{$this->getPlaceholder()}" style="width:100%;" disabled {$this->buildAttribute()}>{$optionStr}</select>
</div>
<span class="script-content" style="display: none" data-class="{$this->getClass()}">
    $(function(){
        $("select.{$this->getClass()}").select2({
            "allowClear":true,
            "placeholder":{
                "id":"{$this->getClass()}",
                "text":"选择{$this->getPlaceholder()}"
            },
        });
    })
</span>
HTML;
    }


}