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
     * @param String $search_name
     * 
     * @return Array [1=>'Petrozavodsk',..]
     */
    public static function getList($country_id=null, 
                                   $region_id=null, 
                                   $search_name=null)
    {     
        $locale = LaravelLocalization::getCurrentLocale();
        $field_name = 'name_'.$locale;
        
        $cities = self::orderBy($field_name);
        
        if ($search_name) {
            $cities = $cities->where($field_name,'like', $search_name);
        }
        
        if ($country_id) {
            $cities = $cities -> whereIn('country_id',(array)$country_id);
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
              
        $first_city = env('FIRST_CITY');
        if ($first_city) {
            $first_city_obj = City::
                    where('name_'.env('PRIM_LANG'),'like',$first_city)
                    ->first();
            if ($first_city_obj && isset($list[$first_city_obj->id])) {
                $first_city_name = $list[$first_city_obj->id];
                unset($list[$first_city_obj->id]);
                $list = [$first_city_obj->id => $first_city_name] + $list;
            }
        }
        
        return $list;         
    }
    
    /** Gets country_id by city ID
     * 
     * @param INT $id
     * 
     * @return INT
     */
    public static function getCountryIDByID($id) {
        $city = self::find($id);
        if (!$city) {
            return NULL;
        }
        return $city->country_id;
    }
}
