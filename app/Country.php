<?php

namespace Confform;

use Illuminate\Database\Eloquent\Model;

use LaravelLocalization;

class Country extends Model
{
    /** Gets name of this country, takes into account locale.
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
    
    /** Gets list of countries
     * 
     * @return Array [1=>'Russia',..]
     */
    public static function getList()
    {     
        
        $countries = self::all();
        
        $list = array();
        foreach ($countries as $row) {
            $list[$row->id] = $row->name;
        }
        asort($list);
        
        $first_country = env('FIRST_COUNTRY');
//dd($first_country);       
        if ($first_country) {
            $first_country_obj = Country::
                    where('name_'.env('PRIM_LANG'),'like',$first_country)
                    ->first();
            if ($first_country_obj) {
                $first_country_name = $list[$first_country_obj->id];
                unset($list[$first_country_obj->id]);
                $list = [$first_country_obj->id => $first_country_name] + $list;
            }
        }
        
        return $list;         
    }
}
