<?php
namespace GDO\Maps;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_Template;
use GDO\DB\GDT_Checkbox;
use GDO\Core\GDT_Secret;
use GDO\Javascript\Javascript;
use GDO\UI\GDT_Page;
use GDO\Core\Application;

/**
 * Maps API helper.
 * 
 * @see GDT_Postion - A geolocation GDT.
 * 
 * @author gizmore
 * @version 6.11.0
 * @since 4.0.0
 */
final class Module_Maps extends GDO_Module
{
	public $module_priority = 45;
	
	public function onLoadLanguage() { return $this->loadLanguage('lang/maps'); }
	
	public function getConfig()
	{
		return [
		    GDT_Checkbox::make('maps_api_google')->initial('1'),
			GDT_Secret::make('maps_api_key')->max(64)->initial(),
			GDT_Checkbox::make('maps_sensors')->initial('0'),
			GDT_Checkbox::make('maps_record')->initial('0'),
			GDT_Checkbox::make('maps_sidebar')->initial('0'),
		];
	}
	public function cfgGoogle() { return $this->getConfigValue('maps_api_google'); }
	public function cfgApiKey() { return $this->getConfigVar('maps_api_key'); }
	public function cfgSensors() { return $this->getConfigValue('maps_sensors'); }
	public function cfgRecord() { return $this->getConfigValue('maps_record'); }
	public function cfgSidebar() { return $this->getConfigValue('maps_sidebar'); }
	
	public function onIncludeScripts()
	{
		Javascript::addJS($this->googleMapsScriptURL());
		if (module_enabled('Angular') && Application::instance()->hasTheme('material'))
		{
			$this->addJS('js/material/gwf-location-bar-ctrl.js');
			$this->addJS('js/material/gwf-location-picker.js');
			$this->addJS('js/material/gwf-map-util.js');
			$this->addJS('js/material/gwf-position-ctrl.js');
			$this->addJS('js/material/gwf-position-srvc.js');
			if ($this->cfgRecord())
			{
				$this->addJS('js/gdo-record-location.js');
			}
		}
		$this->addJS('js/gdo6-maps.js');
		$this->addCSS('css/gwf-maps.css');
	}
	
	private function googleMapsScriptURL()
	{
		$sensors = $this->cfgSensors() ? 'true' : 'false';
		$apikey = $this->cfgApiKey();
		if (!empty($apikey))
		{
			$apikey = '&key='.$apikey;
		}
		return sprintf('https://maps.google.com/maps/api/js?sensors=%s%s',
		    $sensors, $apikey);
	}
	
	#################
	### Recording ###
	#################
	public function getUserConfig()
	{
		if ($this->cfgRecord())
		{
			return [
				GDT_Position::make('user_position'),
			];
		}
	}
	
	############
	### Init ###
	############
	public function onInitSidebar()
	{
	    if ($this->cfgSidebar())
	    {
	        if (module_enabled('Angular'))
	        {
	            $navbar = GDT_Page::$INSTANCE->rightNav;
	            $navbar->addField(GDT_Template::make()->template('Maps', 'maps-navbar.php'));
	        }
	    }
	}
	
}
