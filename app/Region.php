<?php

namespace Confform;

use Illuminate\Database\Eloquent\Model;
use LaravelLocalization;

class Region extends Model
{
    // City __belongs_to__ Country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }    

    /** Gets name of this region, takes into account locale.
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
    
    /** Gets list of regions
     * 
     * @param INT $country_id
     * 
     * @return Array [1=>'Republic of Karelia',..]
     */
    public static function getList($country_id=null)
    {     
        
        $regions = self::orderBy('id');
        
        if ($country_id) {
            $regions = $regions -> where('country_id',$country_id);
        }
        
        $regions = $regions -> get();
        
        $list = array();
        foreach ($regions as $row) {
            $list[$row->id] = $row->name;
        }
        asort($list);
              
        return $list;         
    }
}
