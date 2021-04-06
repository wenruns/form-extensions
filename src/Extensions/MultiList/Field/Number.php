<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/3/12
 * Time: 15:12
 */

namespace Wenruns\Form\Extensions\MultiList\Field;


use Wenruns\Form\Extensions\MultiList\Field;

class Number extends Field
{

    public function build()
    {
        // TODO: Implement build() method.
        return <<<HTML
<div style="width: 100%;{$this->style}">
    <input style="text-align: center;" type="text" id="{$this->getClass()}" name="{$this->getName()}" value="{$this->getValue()}" class="form-control {$this->getClass()}" placeholder="输入{$this->getPlaceholder()}" {$this->buildAttribute()}>
    <script>
        $(function(){
            $('input.{$this->getClass()}:not(.initialized)').addClass('initialized').bootstrapNumber({
                upClass: 'success',
                downClass: 'primary',
                center: true
            });
        })
    </script>
</div>
HTML;
    }

    protected function buildEmpty(): string
    {
        // TODO: Implement buildEmpty() method.
        return <<<HTML
<div style="width: 100%;{$this->style}">
    <input style="text-align: center;" type="text" id="{$this->getClass()}" name="{$this->getName()}" value="" class="form-control {$this->getClass()}" placeholder="输入{$this->getPlaceholder()}" {$this->buildAttribute()}>
    <script>
        $(function(){
            $('input.{$this->getClass()}:not(.initialized)').addClass('initialized').bootstrapNumber({
                upClass: 'success',
                downClass: 'primary',
                center: true
            });
        })
    </script>
</div>
HTML;
    }


}