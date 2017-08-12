<?php

namespace Confform;

use Illuminate\Database\Eloquent\Model;
use LaravelLocalization;

class City extends Model
{
    // City __belongs_to__ Country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }    

    // City __belongs_to__ Region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }    

    /** Gets name of this city, takes into account locale.
     * 
     * @return String
     */
    public function getnameAttribute() : String
    {
        $locale = LaravelLocalization::getCurrentLocale();
        $fieldName = 'name';
        $column = $fieldName."_" . $locale;
        $value = $this->{$column};
        return $value;
    }
    
    /** Gets list of cities
     * 
     * @param INT $country_id
     * @param INT $region_id
     * 
     * @return Array [1=>'Petrozavodsk',..]
     */
    public static function getList($country_id=null, $region_id=null)
    {     
        
        $cities = self::orderBy('id');
        
        if ($country_id) {
            $cities = $cities -> where('country_id',$country_id);
        }
        
        if ($region_id) {
            $cities = $cities -> where('region_id',$region_id);
        }
        
        $cities = $cities -> get();
        
        $list = array();
        foreach ($cities as $row) {
            $list[$row->id] = $row->name;
        }
        asort($list);
              
        return $list;         
    }
}
