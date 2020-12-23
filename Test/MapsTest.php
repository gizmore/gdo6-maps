<?php
namespace GDO\Maps\Test;

use GDO\Tests\TestCase;
use GDO\Core\GDO;
use GDO\DB\GDT_AutoInc;
use GDO\Maps\GDT_Position;

final class MapsTestGDO extends GDO
{
    public function gdoColumns()
    {
        return [
            GDT_AutoInc::make('mt_id'),
            GDT_Position::make('mt_pos'),
        ];
    }

    
}

final class MapsTest extends TestCase
{
    public function testPosition()
    {
        
        
    }
}
