<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/3/23
 * Time: 9:37
 */

namespace Wenruns\Form\Extensions\MultiList\Field;


use Wenruns\Form\Extensions\MultiList\Field;

class TextArea extends Field
{

    public function build()
    {
        // TODO: Implement build() method.
        return <<<HTML
<textarea rows="5" id="{$this->getClass()}" class="form-control {$this->getClass()}"  name="{$this->getName()}" placeholder="输入{$this->getPlaceholder()}" {$this->buildAttribute()}>{$this->getValue()}</textarea>
HTML;
    }


    protected function buildEmpty(): string
    {
        // TODO: Implement buildEmpty() method.
        return <<<HTML
<textarea rows="5" id="{$this->getClass()}" class="form-control {$this->getClass()}" name="{$this->getName()}" placeholder="输入{$this->getPlaceholder()}" {$this->buildAttribute()}></textarea>
HTML;
    }
}