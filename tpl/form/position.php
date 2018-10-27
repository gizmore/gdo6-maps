<?php
use GDO\Maps\GDT_Position;
use GDO\UI\GDT_Icon;
$field instanceof GDT_Position;
?>
<?php /** @var $field \GDO\DB\GDT_String **/ ?>
<div class="gdo-container<?= $field->classError(); ?>">
  <label for="form[<?= $field->name; ?>]"><?= $field->displayLabel(); ?></label>
  <?= $field->htmlIcon(); ?>
  <input
   type="text"
   <?=$field->htmlID()?>
   <?=$field->htmlRequired()?>
   <?=$field->htmlDisabled()?>
   size="12"
   name="form[<?=$field->name?>]"
   value="<?= $field->displayVar(); ?>" />
  <?= $field->htmlError(); ?>
</div>
