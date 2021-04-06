<?php
/**
 * Created by PhpStorm.
 * User: Administrator【wenruns】
 * Date: 2021/1/26
 * Time: 10:32
 */

namespace Wenruns\Form\Extensions\MultiList\Field;

use Wenruns\Form\Extensions\MultiList\Field;

class PowerSwitch extends Field
{

    public function build()
    {
        // TODO: Implement build() method.
        $changeEvent = $this->getEventListener('change');
        $value = $this->getValue();
        if ($value === 'off' || empty($value)) {
            $value = 0;
        }
        $ifChecked = $value ? 'checked' : '';
        return <<<HTML
<div class="input-group" style="width: 100%;{$this->style}">
    <input {$this->buildAttribute()} type="checkbox" class="{$this->getClass()} la_checkbox" {$ifChecked}>
    <input hidden class="{$this->getClass()}" id="{$this->getClass()}" name="{$this->getName()}" value="{$value}" {$this->buildAttribute()} >
</div>
<script>
    $(function(){
        $(".{$this->getClass()}.la_checkbox").bootstrapSwitch({
            size:"auto",
            onText: "ON",
            offText: "OFF",
            onColor: "primary",
            offColor: "default",
            onSwitchChange: function(event, state) {
                $(event.target).closest(".bootstrap-switch").next().val(state ? "on" : "off").change();
                let changeEvent = `{$changeEvent}`;
                if(changeEvent){
                    eval(`var fn = ${changeEvent}; fn.call(this, event, state);`)
                }
            },
        });
    });
</script>
HTML;
    }


    protected function buildEmpty(): string
    {
        // TODO: Implement buildEmpty() method.
        $changeEvent = $this->getEventListener('change');
        return <<<HTML
<div class="input-group" style="width: 100%;{$this->style}">
    <input {$this->buildAttribute()} type="checkbox" class="{$this->getClass()} la_checkbox" />
    <input hidden class="{$this->getClass()}" id="{$this->getClass()}" name="{$this->getName()}" value="" {$this->buildAttribute()} />
</div>
<script>
    $(function(){
        $(".{$this->getClass()}.la_checkbox").bootstrapSwitch({
            size:"auto",
            onText: "ON",
            offText: "OFF",
            onColor: "primary",
            offColor: "default",
            onSwitchChange: function(event, state) {
                $(event.target).closest(".bootstrap-switch").next().val(state ? "on" : "off").change();
                let changeEvent = `{$changeEvent}`;
                if(changeEvent){
                    eval(`var fn = ${changeEvent}; fn.call(this, event, state);`)
                }
            },
        });
    });
</script>
HTML;
    }


}