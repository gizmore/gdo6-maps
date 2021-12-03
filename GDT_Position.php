<?php
namespace GDO\Maps;

use GDO\Core\GDO;
use GDO\Core\GDT_Template;
use GDO\DB\WithDatabase;
use GDO\Form\WithFormFields;
use GDO\UI\WithLabel;
use GDO\UI\WithIcon;
use GDO\DB\GDT_String;

/**
 * Lat/Lng position GDT.
 * Adds two columns to a database table.
 * 
 * @see Position
 * 
 * @author gizmore
 * @version 6.11.0
 * @since 6.2.0
 */
final class GDT_Position extends GDT_String
{
	use WithLabel;
	use WithDatabase;
	use WithFormFields;
	use WithIcon;
	
	public function defaultLabel() { return $this->label('position'); }
	
	##########
	### DB ###
	##########
	public function blankData()
	{
	    if ($this->initial === null)
	    {
	        return [
    			"{$this->name}_lat" => null,
    			"{$this->name}_lng" => null,
	        ];
	    }
	    return [
	        "{$this->name}_lat" => $this->initial[0],
	        "{$this->name}_lng" => $this->initial[1],
        ];
	}
		
	public function gdoColumnNames()
	{
		return [
			"{$this->name}_lat",
			"{$this->name}_lng",
		];
	}
	
	public function gdoColumnDefine()
	{
		$defaultLat = isset($this->initial[0]) ? (" DEFAULT ".GDO::quoteS($this->initial[0])) : '';
		$defaultLng = isset($this->initial[1]) ? (" DEFAULT ".GDO::quoteS($this->initial[1])) : '';
		return
			"{$this->name}_lat DECIMAL(9,6){$this->gdoNullDefine()}{$defaultLat},\n".
			"{$this->name}_lng DECIMAL(9,6){$this->gdoNullDefine()}{$defaultLng}";
	}
	
	########################
	### Current Position ###
	########################
	public $defaultCurrent = false;
	public function defaultCurrent($defaultCurrent=true)
	{
		$this->defaultCurrent = $defaultCurrent;
		return $this;
	}

	#############
	### Value ###
	#############
	public function inputToVar($input)
	{
		$input = trim($input, "\r\n\t []");
		return $input ? "[{$input}]" : null;
	}
	
	public function toValue($var)
	{
		$coords = $var ? json_decode($var, true) : [null, null];
		return new Position($coords[0], $coords[1]);
	}
	
	public function toVar($value)
	{
	    return $value === null ? null : json_encode(
	        [$value->getLat(), $value->getLng()]);
	}
	
	public function initialLatLng($lat, $lng)
	{
		return parent::initial("[$lat,$lng]");
	}
	
	public function getLat()
	{
		return $this->getValue()->getLat();
	}

	public function getLng()
	{
		return $this->getValue()->getLng();
	}
	
	public function getGDOData()
	{
		return array(
			"{$this->name}_lat" => $this->getLat(),
			"{$this->name}_lng" => $this->getLng(),
		);
	}
	
	public function setGDOData(GDO $gdo=null)
	{
		$lat = $gdo->getVar("{$this->name}_lat");
		$lng = $gdo->getVar("{$this->name}_lng");
		return ($lat && $lng) ? $this->var("[$lat,$lng]") : $this->var(null);
	}
	
	##############
	### Render ###
	##############
	public function initJSON()
	{
		return [
			'lat' => $this->getLat(),
			'lng' => $this->getLng(),
			'defaultCurrent' => $this->defaultCurrent,
		];
	}
	public function renderForm()
	{
		return GDT_Template::php('Maps', 'form/position.php', ['field' => $this]);
	}
	public function renderCell()
	{
		return GDT_Template::php('Maps', 'cell/position.php', ['field' => $this]);
	}
	
	##################
	### Validation ###
	##################
	public function validate($value)
	{
		if ($value === null)
		{
			return $this->notNull ? $this->errorNotNull() : true;
		}
		return $this->validatePosition($value);
	}
	
	private function validatePosition(Position $pos)
	{
		if (!$pos->hasValidLat())
		{
			return $this->error('err_latitude');
		}
		elseif (!$pos->hasValidLng())
		{
			return $this->error('err_longitude');
		}
		else
		{
			return true;
		}
	}

}
