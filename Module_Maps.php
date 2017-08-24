<?php
namespace GDO\Maps;

use GDO\Core\Module;
use GDO\Net\GDO_Url;
use GDO\Template\GDO_Bar;
use GDO\Template\GDO_Template;
use GDO\Type\GDO_Checkbox;
use GDO\Type\GDO_Secret;
use GDO\Util\Javascript;
/**
 * Maps API helper.
 * 
 * @see GDO_Postion - A geolocation GDO_Base.
 * @author gizmore
 * @since 4.0
 * @version 5.0
 */
final class Module_Maps extends Module
{
	public $module_priority = 45;
	
	public function onLoadLanguage() { return $this->loadLanguage('lang/maps'); }
	
	public function getConfig()
	{
		return array(
			GDO_Secret::make('maps_api_key')->max(64)->initial('AIzaSyBrEK28--B1PaUlvpHXB-4MzQlUjNPBez0'),
			GDO_Checkbox::make('maps_sensors')->initial('1'),
		);
	}
	public function cfgApiKey() { return $this->getConfigValue('maps_api_key'); }
	public function cfgSensors() { return $this->getConfigValue('maps_sensors'); }

	public function onIncludeScripts()
	{
		Javascript::addJavascript($this->googleMapsScriptURL());
		$this->addJavascript('js/gwf-location-bar-ctrl.js');
		$this->addJavascript('js/gwf-location-picker.js');
		$this->addJavascript('js/gwf-map-util.js');
		$this->addJavascript('js/gwf-position-ctrl.js');
		$this->addJavascript('js/gwf-position-srvc.js');
		$this->addCSS('css/gwf-maps.css');
	}
	
	public function googleMapsScriptURL()
	{
		$protocol = GDO_Url::protocol();
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
	public function hookRightBar(GDO_Bar $navbar)
	{
    	$navbar->addField(GDO_Template::make()->template('Maps', 'maps-navbar.php'));
	}
	
}
