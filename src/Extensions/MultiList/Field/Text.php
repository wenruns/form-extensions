<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/1/26
 * Time: 10:30
 */

namespace Wenruns\Form\Extensions\MultiList\Field;


use Wenruns\Form\Extensions\MultiList\Field;

class Text extends Field
{

    public function build()
    {
        // TODO: Implement build() method.
        return <<<HTML
<div class="input-group" style="width: 100%;{$this->style}">
    <span class="input-group-addon" style="display: {$this->hideIconBool};"><i class="fa fa-pencil fa-fw"></i></span>
    <input type="text" id="{$this->getClass()}" name="{$this->getName()}" value="{$this->getValue()}" class="form-control text" placeholder="输入{$this->getPlaceholder()}" {$this->buildAttribute()}>        
</div>
HTML;
    }


    protected function buildEmpty(): string
    {
        // TODO: Implement buildEmpty() method.
        return <<<HTML
<div class="input-group" style="width: 100%;{$this->style}">
    <span class="input-group-addon" style="display: {$this->hideIconBool};"><i class="fa fa-pencil fa-fw"></i></span>
    <input type="text" id="{$this->getClass()}" name="{$this->getName()}" value="" class="form-control text" placeholder="输入{$this->getPlaceholder()}" {$this->buildAttribute()}>        
</div>
HTML;
    }

}