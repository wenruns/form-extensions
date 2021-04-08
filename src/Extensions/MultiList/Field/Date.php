<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/1/26
 * Time: 10:31
 */

namespace Wenruns\Form\Extensions\MultiList\Field;


use Wenruns\Form\Extensions\MultiList\Field;

class Date extends Field
{
    protected $defaultFormat = 'YYYY-MM-DD';

    public function build()
    {
        // TODO: Implement build() method.
        $key = md5(mt_rand(1000, 9999));
        $format = $this->options['format'] ?? $this->defaultFormat;
        return <<<HTML
<div class="input-group" style="position: relative;width: 100%;{$this->style}">
    <span class="input-group-addon" ><i class="fa fa-calendar fa-fw"></i></span>
    <input type="text" id="{$this->getClass()}" name="{$this->getName()}" value="{$this->getValue()}" class="form-control {$this->getClass()}" placeholder="输入{$this->getPlaceholder()}" {$this->buildAttribute()}>
    <script>
        $(function(){
            let width_{$key} = 'auto';
            let height_{$key} = 'auto';
            $("input.{$this->getClass()}").parent().datetimepicker({"format":"{$format}","locale":"zh-CN","allowInputToggle":true});
        });
    </script>
</div>
HTML;
    }


    protected function buildEmpty(): string
    {
        // TODO: Implement buildEmpty() method.
        $key = md5(mt_rand(1000, 9999));
        $format = $this->options['format'] ?? $this->defaultFormat;
        return <<<HTML
<div class="input-group" style="position: relative;width: 100%;{$this->style}">
    <span class="input-group-addon" ><i class="fa fa-calendar fa-fw"></i></span>
    <input type="text" id="{$this->getClass()}" name="{$this->getName()}" disabled value="" class="form-control {$this->getClass()}" placeholder="输入{$this->getPlaceholder()}" {$this->buildAttribute()}>
</div>
<span style="display: none;" class="script-content" data-class="{$this->getClass()}">
    $(function(){
        let width_{$key} = 'auto';
        let height_{$key} = 'auto';
        $("input.{$this->getClass()}").parent().datetimepicker({"format":"{$format}","locale":"zh-CN","allowInputToggle":true});
    });
</span>
HTML;
    }
}