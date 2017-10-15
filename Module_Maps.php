<?php
namespace GDO\Maps;

use GDO\Core\GDO_Module;
use GDO\Net\GDT_Url;
use GDO\UI\GDT_Bar;
use GDO\Core\GDT_Template;
use GDO\DB\GDT_Checkbox;
use GDO\Core\GDT_Secret;
use GDO\Util\Javascript;
/**
 * Maps API helper.
 * 
 * @see GDT_Postion - A geolocation GDT.
 * @author gizmore
 * @since 4.0
 * @version 5.0
 */
final class Module_Maps extends GDO_Module
{
	public $module_priority = 45;
	
	public function onLoadLanguage() { return $this->loadLanguage('lang/maps'); }
	
	public function getConfig()
	{
		return array(
			GDT_Secret::make('maps_api_key')->max(64)->initial('AIzaSyBrEK28--B1PaUlvpHXB-4MzQlUjNPBez0'),
			GDT_Checkbox::make('maps_sensors')->initial('1'),
		);
	}
	public function cfgApiKey() { return $this->getConfigValue('maps_api_key'); }
	public function cfgSensors() { return $this->getConfigValue('maps_sensors'); }

	public function onIncludeScripts()
	{
		Javascript::addJavascript($this->googleMapsScriptURL());
		if (module_enabled('Angular'))
		{
		    $this->addJavascript('js/gwf-location-bar-ctrl.js');
    		$this->addJavascript('js/gwf-location-picker.js');
    		$this->addJavascript('js/gwf-map-util.js');
    		$this->addJavascript('js/gwf-position-ctrl.js');
    		$this->addJavascript('js/gwf-position-srvc.js');
		}
		$this->addCSS('css/gwf-maps.css');
	}
	
	public function googleMapsScriptURL()
	{
		$protocol = GDT_Url::protocol();
		$sensors = $this->cfgSensors() ? 'true' : 'false';
		$apikey = $this->cfgApiKey();
		if (!empty($apikey))
		{
			$apikey = '&key='.$apikey;
		}
		return sprintf('%s://maps.google.com/maps/api/js?sensors=%s%s', $protocol, $sensors, $apikey);
	}
	
	###############
	### Sidebar ###
	###############
	public function hookRightBar(GDT_Bar $navbar)
	{
    	$navbar->addField(GDT_Template::make()->template('Maps', 'maps-navbar.php'));
	}
	
}
