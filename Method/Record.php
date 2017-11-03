<?php
namespace GDO\Maps\Method;

use GDO\Core\MethodAjax;
use GDO\User\GDO_UserSetting;
use GDO\Util\Common;

final class Record extends MethodAjax
{
	public function execute()
	{
		GDO_UserSetting::sesst('user_position', Common::getRequestString('position'));
		return $this->message('msg_location_recorded');
	}
	
}