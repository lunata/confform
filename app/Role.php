<?php

namespace Confform;

use Cartalyst\Sentinel\Roles\EloquentRole;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use LaravelLocalization;

use Confform\User;

class Role extends EloquentRole
{
    protected $fillable = ['slug','name','permissions','prior'];
    
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500; //Stop tracking revisions after 500 changes have been made.
    protected $revisionCreationsEnabled = true; // By default the creation of a new model is not stored as a revision. Only subsequent changes to a model is stored.

    public static function boot()
    {
        parent::boot();
    }

    /** Gets name of this role, takes into account locale.
     * 
     * @return String
     */
    public function getLnameAttribute() : String
    {
        $locale = LaravelLocalization::getCurrentLocale();
        if ($locale == 'ru') {
            $name = $this->name;
        } else {
            $name = $this->slug;
        }
        
        return $name;
    }
    
    /** Gets id of this role by slug.
     * 
     * @return String or NULL
     */
    public static function getIdByRole($slug)
    {
        $role = self::where('slug',$slug)->first();
        
        if (!$role) {
            return NULL;
        }

        return $role->id;
    }
    
    // Role __has_many__ Users
    public function users(){
        return $this->belongsToMany(User::class, 'role_users');
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
//dd($permissions);       
        if ($permissions) {
            foreach ($permissions as $key => $value) {
                $list[] = $key;
            }
        }
        return join(', ', $list);
    }

    /** Gets list of roles
     * 
     * @param $min_prior - get roles with priority more $min_prior
     * 
     * @return Array [1=>'admin',..]
     */
    public static function getList($min_prior=null)
    {     
        $roles = self::orderBy('prior')->get();
        
        if ($min_prior) {
            $roles = $roles->where('prior','>=',$min_prior);
        }
        
        $list = array();
        foreach ($roles as $row) {
            $list[$row->id] = $row->name;
        }
//        asort($list);
        return $list;         
    }
}
