<?php

namespace Confform;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelLocalization;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = ['prim_lang','add_lang','started_at','finished_at',
        'country_id','city_id','registr_start','registr_finish',
        'material_start','material_finish'];
    private $translated_fields=['title'];
    
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
  
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500; //Stop tracking revisions after 500 changes have been made.
    protected $revisionCreationsEnabled = true; // By default the creation of a new model is not stored as a revision. Only subsequent changes to a model is stored.

    public static function boot()
    {
        parent::boot();
    }
    
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
        $this->prim_lang = env('PRIM_LANG');
        $this->add_lang = env('ADD_LANG');
        
        foreach(array($this->prim_lang,$this->add_lang) as $lang) {
            foreach ($this->translated_fields as $f) {
                $this->fillable[] = $f.'_'.$lang;
            }
        }
    }
    
    // Event __belongs_to__ Country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }    

    // Event __belongs_to__ City
    public function city()
    {
        return $this->belongsTo(City::class);
    }    

    /** Gets data additional language
     * 
     * @return String
     */
    public function getTranslatedFields()
    {
        return $this->translated_fields;
    }

    /** Get value of attribute, takes into account locale.
     * 
     * @param Bool $otherLangIfEmpty - get value in English, 
     *                                 if value in current locale is empty 
     * @param String $fieldName - name of attribute
     * 
     * @return mixed
     */
    public function getLocaledField(String $fieldName, Bool $otherLangIfEmpty=false)
    {
        $locale = LaravelLocalization::getCurrentLocale();
        $column = $fieldName."_" . $locale;
        $value = $this->{$column};
        
        if (!$value && $otherLangIfEmpty && $locale!=$this->prim_lang) {
            $value = $this->{$fieldName."_".$this->prim_lang};
        } 
        
        return $value;
    }
    
    /** Gets name of this user
     * 
     * @return String
     */
    public function getTitleAttribute()
    {
        return $this->getLocaledField("title",true);
    }
         
    /** Gets name of this event
     * 
     * @return String
     */
    public function getPlaceAttribute()
    {
        $place=[];
        
        if ($this->city) {
            $place[] = $this->city->name;
            if ($this->city->region) {
                $place[] = $this->city->region->name;
            }
        }
        
        if ($this->country) {
            $place[] = $this->country->name;
        }
        
        return join(', ', $place);
    }
         
    /** Gets date range
     * 
     * @param Date $started_at
     * @param Date $finished_at
     * 
     * @return String
     * 
     * 8 сeнтября 2017 г.
     * 3 - 8 сeнтября 2017 г.
     * 30 сeнтября - 1 октября 2017 г.
     * 30 дeкабря 2016 г. - 1 января 2017 г.
     * September 3, 2017
     * September 3 - 8, 2017
     * September 30 - October 1, 2017
     * December 30, 2016 - January 1, 2017
     * 
     */
    public static function getDateRange($started_at, $finished_at)
    {
        $locale = LaravelLocalization::getCurrentLocale();

        $start = new Carbon($started_at);
        $finish = new Carbon($finished_at);
        
        $d1 = $start->day;
        $m1 = $start->month;
        $y1 = $start->year;
        $d2 = $finish->day;
        $m2 = $finish->month;
        $y2 = $finish->year;
        
        $div = '- ';
                    
        if ($y2 == $y1) {
            $y1 = '';
            if ($m2 == $m1) {
                $m1 = '';
                if ($d2 == $d1) {
                    $d1 = '';
                    $div = '';
                }
            }
        }

        if ($d1 && ($locale == 'ru' || $y1)) {
            $d1 .= ' '; 
        }
        
        if ($locale == 'ru') {
            $d2 .= ' '; 
        }
        
        if ($m1) {
            $m1 = \Lang::get('date.of_mon.'.$m1).' ';
        }
        
        if ($m2) {
            $m2 = \Lang::get('date.of_mon.'.$m2).' ';
        }
        
        if ($y1) {
            if ($locale == 'ru') {
                $y1 .= ' г.';
            }
            $y1 .= ' ';
        }
        
        if ($y2 && $locale == 'ru') {
            $y2 .= ' г.';
        }
        
        if ($locale == 'ru') {
            return $d1.$m1.$y1.$div.$d2.$m2.$y2;
        }
        return $m1.$d1.$y1.$div.$d2.$m2.$y2;
    }
         
    /** Gets dates of this event
     * 
     * @return String
     */
    public function getDatesAttribute()
    {
        return self::getDateRange($this->started_at, $this->finished_at);
    }
         
    /** Gets registration dates of this event
     * 
     * @return String
     */
    public function getRegistrDatesAttribute()
    {
        return self::getDateRange($this->registr_start, $this->registr_finish);
    }
         
    /** Gets material accepting dates of this event
     * 
     * @return String
     */
    public function getMaterialDatesAttribute()
    {
        return self::getDateRange($this->material_start, $this->material_finish);
    }
         
    /**
     * Get the list of available statuses.
     *
     * @return array
     */
    public function getStatusList()
    {
        $list = [];
        for ($i=0; $i<=2; $i++) {
            $list[$i] = \Lang::get("event.status$i");
        }

        return $list;
    }

}
