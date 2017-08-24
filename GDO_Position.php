<?php
namespace GDO\Maps;
use GDO\DB\GDO;
use GDO\Form\GDO_Form;
use GDO\Template\GDO_Template;
use GDO\Type\GDO_Base;
use GDO\Util\Common;
use GDO\DB\WithDatabase;

final class GDO_Position extends GDO_Base
{
    use WithDatabase;
    
	public function __construct()
	{
		$this->initial(null);
	}
	
	public function blankData()
	{
		return array(
			"{$this->name}_lat" => $this->initial[0],
			"{$this->name}_lng" => $this->initial[1],
		);
	}
		
	public function gdoColumnDefine()
	{
		$defaultLat = $this->initial[0] ? (" DEFAULT ".GDO::quoteS($this->initial[0])) : '';
		$defaultLng = $this->initial[1] ? (" DEFAULT ".GDO::quoteS($this->initial[1])) : '';
		return
			"{$this->name}_lat DECIMAL(9,6){$this->gdoNullDefine()}{$defaultLat},\n".
			"{$this->name}_lng DECIMAL(9,6){$this->gdoNullDefine()}{$defaultLng}";
	}
	
	########################
	### Current Position ###
	########################
	public $defaultCurrent = false;
	public function defaultCurrent(bool $defaultCurrent=true)
	{
		$this->defaultCurrent = $defaultCurrent;
		return $this;
	}

	#############
	### Value ###
	#############
// 	public function value($value)
// 	{
// 		$this->value = $value === null ? [null, null] : $value;
// 	}
	
// 	public function initial($initial)
// 	{
// 		$this->initial = $initial === null ? [null, null] : $initial;
// 		return $this;
// 	}

	public function getLat()
	{
		return $this->gdo->getVar($this->name.'_lat');
	}

	public function getLng()
	{
		return $this->gdo->getVar($this->name.'_lng');
	}
	
	public function getValue()
	{
		return $this->gdo ?
			new Position($this->getLat(), $this->getLng()) :
			new Position($this->formLat(), $this->formLng());
	}
	
	public function getGDOData()
	{
		return array(
			"{$this->name}_lat" => $this->getLat(),
			"{$this->name}_lng" => $this->getLng(),
		);
	}
	
	##############
	### Render ###
	##############
	public function initJSON()
	{
		return array(
			'lat' => $this->formLat(),
			'lng' => $this->formLng(),
			'defaultCurrent' => $this->defaultCurrent,
		);
	}
	public function renderForm()
	{
		return GDO_Template::php('Maps', 'form/position.php', ['field' => $this]);
	}
	public function renderCell()
	{
		return GDO_Template::php('Maps', 'cell/position.php', ['field' => $this]);
	}
	
	##################
	### Validation ###
	##################
	public function formLat()
	{
		$vars = Common::getRequestArray('form', []);
		if (isset($vars["{$this->name}_lat"]))
		{
			$var = trim($vars["{$this->name}_lat"]);
			return empty($var) ? null : $var;
		}
		return $this->value[0] ? $this->value[0] : $this->initial[0];
	}
	
	public function formLng()
	{
		$vars = Common::getRequestArray('form', []);
		if (isset($vars["{$this->name}_lng"]))
		{
			$var = trim($vars["{$this->name}_lng"]);
			return empty($var) ? null : $var;
		}
		return $this->value[1] ? $this->value[1] : $this->initial[1];
	}
	
	public function formValidate(GDO_Form $form)
	{
		if ($this->validatorsValidate($form))
		{
			$lat = $this->formLat();
			$lng = $this->formLng();
			if ( (($lat === null)||($lng === null)) && (!$this->null) )
			{
				return $this->error('err_is_null', [$this->name]);
			}
			if ( ($lat < -90) || ($lat > 90) )
			{
				return $this->error('err_lat', [$this->name]);
			}
			if ( ($lng < -180) || ($lng > 180) )
			{
				return $this->error('err_lng', [$this->name]);
			}
			
			# Add to form
			$this->oldValue = $this->value;
			$this->value = [$lat, $lng];
			$form->addValue($this->name.'_lat', $lat);
			$form->addValue($this->name.'_lng', $lng);
			return true;
		}
	}
}
