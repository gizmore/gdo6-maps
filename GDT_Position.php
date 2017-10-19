<?php
namespace GDO\Maps;
use GDO\Core\GDO;
use GDO\Core\GDT_Template;
use GDO\Core\GDT;
use GDO\DB\WithDatabase;
use GDO\Form\WithFormFields;
use GDO\UI\WithLabel;

final class GDT_Position extends GDT
{
    use WithLabel;
    use WithDatabase;
    use WithFormFields;
    
	##########
	### DB ###
	##########
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
	public function defaultCurrent($defaultCurrent=true)
	{
		$this->defaultCurrent = $defaultCurrent;
		return $this;
	}

	#############
	### Value ###
	#############
	public function toValue($var)
	{
	    $coords = $var ? json_decode($var) : [null, null];
	    return new Position($coords[0], $coords[1]);
	}
	
	public function toVar($value)
	{
	    return json_encode([$value->getLat(), $value->getLng()]);
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
	    return $lat && $lng ? $this->val("[$lat,$lng]") : $this->val(null);
	}
	
// 	public function getVar()
// 	{
// 		$lat = $this->getRequestVar('form', $this->gdo->getVar("{$this->name}_lat"), "{$this->name}_lat");
// 		$lng = $this->getRequestVar('form', $this->gdo->getVar("{$this->name}_lng"), "{$this->name}_lng");
// 		return sprintf('[%f,%f]', $lat, $lng);
// 	}
	
	##############
	### Render ###
	##############
	public function initJSON()
	{
		return array(
			'lat' => $this->getLat(),
			'lng' => $this->getLng(),
			'defaultCurrent' => $this->defaultCurrent,
		);
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
	    
	    return true;
	}
}
