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
     * @param String $search_name
     * 
     * @return Array [1=>'Republic of Karelia',..]
     */
    public static function getList($country_id=null, $search_name=null)
    {     
        $locale = LaravelLocalization::getCurrentLocale();
        $field_name = 'name_'.$locale;
        
        $regions = self::orderBy($field_name);
        
        if ($search_name) {
            $regions = $regions->where($field_name,'like', $search_name);
        }
        
        if ($country_id) {
            $regions = $regions -> where('country_id',$country_id);
        }
        
        $regions = $regions -> get();
        
        $list = array();
        foreach ($regions as $row) {
            $list[$row->id] = $row->name;
        }
        asort($list);
              
        $first = env('FIRST_REGION');
        if ($first) {
            $first_obj = Region::
                    where('name_'.env('PRIM_LANG'),'like',$first)
                    ->first();
            if ($first_obj && isset($list[$first_obj->id])) {
                $first_name = $list[$first_obj->id];
                unset($list[$first_obj->id]);
                $list = [$first_obj->id => $first_name] + $list;
//dd($list);        
            }
        }
       
        return $list;         
    }
}
