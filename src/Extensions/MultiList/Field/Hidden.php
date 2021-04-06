<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/3/12
 * Time: 9:01
 */

namespace Wenruns\Form\Extensions\MultiList\Field;


use Wenruns\Form\Extensions\MultiList\Field;

class Hidden extends Field
{

    public function build()
    {
        // TODO: Implement build() method.
        return <<<HTML
<input {$this->buildAttribute()} id="{$this->getClass()}" type="hidden" name="{$this->getName()}" value="{$this->getValue()}" placeholder="{$this->label}" style="display: none;" />
HTML;
    }

    protected function buildEmpty(): string
    {
        // TODO: Implement buildEmpty() method.
        return <<<HTML
<input {$this->buildAttribute()} id="{$this->getClass()}" type="hidden" name="{$this->getName()}" value="" placeholder="{$this->getPlaceholder()}" style="display: none;" />
HTML;
    }

}