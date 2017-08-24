<?php
use GDO\Maps\GDO_Position;
use GDO\Maps\Position;

$field instanceof GDO_Position;
$pos = $field->getValue(); $pos instanceof Position;
if ($pos->empty())
{
	l('unknown');
}
else
{
	printf('<span>%s<br/>%s</span>', $pos->displayLat(), $pos->displayLng());
}
