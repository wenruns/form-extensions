<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/3/18
 * Time: 18:12
 */

namespace Wenruns\Form\Extensions\MultiList\Field;


use Wenruns\Form\Extensions\MultiList\Field;

class Color extends Field
{
    public function build()
    {
        // TODO: Implement build() method.
        return <<<HTML
<div class="input-group colorpicker-element" style="width: 100%;">
    <span class="input-group-addon"><i style="background-color: {$this->getValue()};"></i></span> 
    <input style="{$this->style}" type="text" id="{$this->getClass()}" name="{$this->getName()}" value="{$this->getValue()}" class="form-control {$this->getClass()}" placeholder="输入{$this->getPlaceholder()}" {$this->buildAttribute()}>            
</div>
<script>
    $(function(){
        $("input.{$this->getClass()}").parent().colorpicker([]);
        $(".colorpicker.dropdown-menu").css("z-index", 9999999);
    })
</script>
HTML;
    }

    protected function buildEmpty(): string
    {
        // TODO: Implement buildEmpty() method.
        return <<<HTML
<div class="input-group colorpicker-element" style="width: 100%;">
    <span class="input-group-addon"><i style="background-color: {$this->getValue()};"></i></span> 
    <input style="{$this->style}" type="text" id="{$this->getClass()}" name="{$this->getName()}" disabled value="" class="form-control {$this->getClass()}" placeholder="输入{$this->getPlaceholder()}" {$this->buildAttribute()}>            
</div>
<span style="display: none;" class="script-content" data-class="{$this->getClass()}">
    $(function(){
        $("input.{$this->getClass()}").parent().colorpicker([]);
        $(".colorpicker.dropdown-menu").css("z-index", 9999999);
    })
</span>
HTML;

    }


}