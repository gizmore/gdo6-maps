<?php
use GDO\Maps\GDT_Position;
use GDO\UI\GDT_Icon;
$field instanceof GDT_Position;
?>
<md-input-container
 class="md-block md-float md-icon-left<?= $field->classError(); ?>"
 flex ng-controller="GDOPositionCtrl"
 ng-init='init(<?= json_encode($field->initJSON()); ?>)'>
  <label for="form[<?= $field->name; ?>]"><?= $field->label; ?></label>
  <?= GDT_Icon::iconS('gps_fixed'); ?>
  <input
   ng-click="onPick()"
   type="text"
   ng-model="data.display"
   <?= $field->htmlRequired(); ?>
   <?= $field->htmlDisabled(); ?>/>
  <div class="gdo-form-error"><?= $field->error; ?></div>
  
  <input id="gdo_pos_<?= $field->name; ?>" type="hidden" name="form[<?= $field->name ?>]" value="[{{data.lat}},{{data.lng}}]" />
  
</md-input-container>
