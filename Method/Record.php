<?php
namespace GDO\Maps\Method;

use GDO\Core\MethodAjax;
use GDO\Util\Common;
use GDO\Maps\Module_Maps;

final class Record extends MethodAjax
{
	public function execute()
	{
	    Module_Maps::instance()->saveSetting('user_position', Common::getRequestString('position'));
		return $this->message('msg_location_recorded');
	}
	
}