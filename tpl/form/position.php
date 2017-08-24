<?php
use GDO\Maps\GDO_Position;
use GDO\UI\GDO_Icon;
$field instanceof GDO_Position;
?>
<md-input-container
 class="md-block md-float md-icon-left<?= $field->classError(); ?>"
 flex ng-controller="GWFPositionCtrl"
 ng-init='init(<?= json_encode($field->initJSON()); ?>)'>
  <label for="form[<?= $field->name; ?>]"><?= $field->label; ?></label>
  <?= GDO_Icon::iconS('gps_fixed'); ?>
  <input
   ng-click="onPick()"
   type="text"
   ng-model="data.display"
   <?= $field->htmlRequired(); ?>
   <?= $field->htmlDisabled(); ?>/>
  <div class="gdo-form-error"><?= $field->error; ?></div>
  
  <input type="hidden" name="form[<?= $field->name ?>_lat]" value="{{data.lat}}" />
  <input type="hidden" name="form[<?= $field->name ?>_lng]" value="{{data.lng}}" />
  
</md-input-container>
