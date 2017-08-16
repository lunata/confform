<?php

namespace Confform;

use Illuminate\Database\Eloquent\SoftDeletes;

use Cartalyst\Sentinel\Users\EloquentUser;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

use LaravelLocalization;

use Confform\Role;

class User extends EloquentUser
{
    protected $fillable = ['email','permissions','country_id','city_id'];
    protected $perm_list = ['all','user.view','user.update','user.delete',
        'role','conf.create','conf.update','conf.delete'];

    private $prim_lang;
    private $add_lang;
    private $translated_fields=['first_name','middle_name','last_name','degree',
                            'stitle','affil','position'];
    
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
    
    // User __belongs_to__ Country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }    

    // User __belongs_to__ City
    public function city()
    {
        return $this->belongsTo(City::class);
    }    

    /** Gets data primary language
     * 
     * @return String
     */
    public function getPrimLang()
    {
        return $this->prim_lang;
    }

    
    /** Gets data additional language
     * 
     * @return String
     */
    public function getAddLang()
    {
        return $this->add_lang;
    }

    /** Gets data additional language
     * 
     * @return String
     */
    public function getTranslatedFields()
    {
        return $this->translated_fields;
    }

    /** Gets name of this user
     * 
     * @return String
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' '. $this->middle_name. ' '. $this->last_name;
    }
         
    /** Gets priority of this user
     * 
     * @return String
     */
    public function getPriorAttribute()
    {
        $roles = $this->roles;
        if (!$roles) {
            return false;
        }
        
        $roles = $roles->sortByDesc('prior');
        if ($roles->last()->slug == 'banned') {
                return $roles->last()->prior;
        }
        
        return $roles->first()->prior;
    }
         
    /** Gets name of this user
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
         
    /** Gets first name of user, takes into account locale.
     * 
     * @return String or NULL
     */
    public function getFirstNameAttribute()
    {
        return $this->getLocaledField("first_name",true);
    }
    
    /** Gets last name of user, takes into account locale.
     * 
     * @return String or NULL
     */
    public function getLastNameAttribute()
    {
        return $this->getLocaledField("last_name",true);
    }
    
    /** Gets middle name of user, takes into account locale.
     * 
     * @return String or NULL
     */
    public function getMiddleNameAttribute()
    {
        return $this->getLocaledField("middle_name");
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
    
    // User __has_many__ Roles
    public function roles(){
        return $this->belongsToMany(Role::class, 'role_users');
    }
    
    public static function hasRole($role)
    {
        $role_id = Role::getIdByRole($role);
        if (!$role_id){
            return false;
        }

        $user=Sentinel::check();
        if (!$user) {
            return false;
        }
        
        return $user->roles()->wherePivot('role_id',$role_id)->count();
    }    
    
    /**
     * Get the fillable attributes for the model.
     *
     * @return array
     */
    public function getPermList()
    {
        $perms = $this->perm_list;
        $list = [];
        foreach ($perms as $p) {
            $list[$p] = \Lang::get("auth.perm.$p");
        }
        return $list;
    }

    /**
     * Gets a list of names of roles for the user.
     *
     * @return string
     */
    public function rolesNames()
    {
        $locale = LaravelLocalization::getCurrentLocale();

        $roles = $this->roles;
        $list = [];
        foreach ($roles as $role) {
            $list[] = $role->lname;
        }
        return join(', ', $list);
    }

    /**
     * Gets a list of permissions for the user.
     *
     * @return string
     */
    public function permissionString()
    {
        $permissions = $this->permissions;
        $list = [];
        
        foreach ($permissions as $key => $value) {
            $list[] = $key;
        }
        return join(', ', $list);
    }
    
    /**
     * Gets a list of names of roles for the user.
     *
     * @param  int  $user_id
     * @return string
     */
    public static function getRolesNames(int $user_id)
    {
        return self::where('id',$user_id)->first()->rolesNames();
    }
    
    /**
     * Checks access for a permission
     *
     * @param  string $permission, f.e. 'dict.edit'
     * @return boolean
     */
    public static function checkAccess(string $permission)
    {
        $user=Sentinel::check();
        if (!$user)
            return false;
//print "<pre>";
//var_dump($user);
        if ($user->hasAccess('all') || $user->hasAccess($permission))
            return true;
        return false;
    }
    
    public static function authUser() {
        $sentinelUser = Sentinel::check(); 
        if (!$sentinelUser) 
                return Redirect::to('/')
                               ->withErrors(\Lang::get('error.permission_denied'));
        
        $user = self::find($sentinelUser->id);
        return $user;
    }
}
