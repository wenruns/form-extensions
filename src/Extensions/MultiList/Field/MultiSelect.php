<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/1/26
 * Time: 10:31
 */

namespace Wenruns\Form\Extensions\MultiList\Field;


use Wenruns\Form\Extensions\MultiList\Field;

class MultiSelect extends Field
{

    public function build()
    {
        // TODO: Implement build() method.
        $values = $this->getValue();
        if (!is_array($values)) {
            $values = explode(',', $values);
        }
        $optionStr = '';
        foreach ($this->options as $value => $label) {
            $optionStr .= '<option value="' . $value . '" ' . (in_array($value, $values) ? 'selected' : '') . '>' . $label . '</option>';
        }
        $values = implode(',', $values);
        return <<<HTML
<div style="width: 100%;{$this->style}" class="input-group">
    <select id="{$this->getClass()}" class="form-control {$this->getClass()} select2-hidden-accessible" style="width: 100%;" name="{$this->getName()}[]" multiple data-placeholder="{$this->getPlaceholder()}" data-value="{$values}" tabindex="-1" aria-hidden="true" {$this->buildAttribute()}>{$optionStr}</select>
</div>
<script>
    $(function(){
        $("select.{$this->getClass()}").select2({
            "allowClear":true,
            "placeholder":{
                "id":"{$this->getClass()}",
                "text":"选择{$this->getPlaceholder()}"
            }
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
            $optionStr .= '<option value="' . $value . '">' . $label . '</option>';
        }
        return <<<HTML
<div style="width: 100%;{$this->style}" class="input-group">
    <select id="{$this->getClass()}" class="form-control {$this->getClass()} select2-hidden-accessible" style="width: 100%;" name="{$this->getName()}[]" disabled multiple data-placeholder="{$this->getPlaceholder()}" data-value="" tabindex="-1" aria-hidden="true" {$this->buildAttribute()}>{$optionStr}</select>
</div>
<span style="display: none;" class="script-content" data-class="{$this->getClass()}">
    $(function(){
        $("select.{$this->getClass()}").select2({
            "allowClear":true,
            "placeholder":{
                "id":"{$this->getClass()}",
                "text":"选择{$this->getPlaceholder()}"
            }
        });
    })
</span>
HTML;
    }
}