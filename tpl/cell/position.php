<?php
use GDO\Maps\GDT_Position;
use GDO\Maps\Position;
$field instanceof GDT_Position;
$pos = $field->getValue(); $pos instanceof Position;
if ($pos->isEmpty())
{
	echo t('unknown');
}
else
{
	printf('<span>%s<br/>%s</span>', $pos->displayLat(), $pos->displayLng());
}
