<?php

namespace Confform\Http\Controllers;

use Illuminate\Http\Request;

use Confform\Http\Requests;
use Confform\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use DB;
use LaravelLocalization;
use Sentinel;
use Response;

use Confform\City;
use Confform\Confform;
use Confform\Country;
use Confform\Region;
use Confform\Role;
use Confform\User;

class UserController extends Controller
{
    public $url_args=[];
    public $args_by_get='';
    
//    protected $dbd = null;
        
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
//        $this->middleware('role:admin,/', ['all']);
//        $this->middleware('auth');
        $this->middleware('perm:user.view,/', ['only'=>['index','view']]);
        $this->middleware('role:admin,/', ['only'=>['create','store']]);
        $this->middleware('perm:user.update,/', ['only'=>['edit','update']]);
        $this->middleware('perm:user.delete,/', ['only'=>['destroy']]);
        
        // $this->dbd = getenv('DB_DATABASE');
        // dd('hello world from __construct()' . $this->dbd);
        $this->url_args = [
                    'limit_num'       => (int)$request->input('limit_num'),
                    'page'            => (int)$request->input('page'),
                    'search_email'    => $request->input('search_email'),
                    'search_name'     => $request->input('search_name'),
                    'search_id'       => (int)$request->input('search_id'),
                    'search_country'  => (array)$request->input('search_country'),
                    'search_city'     => (array)$request->input('search_city'),
                    'search_perm'    => (array)$request->input('search_perm'),
                    'search_role'    => (array)$request->input('search_role'),
                ];
        
        if (!$this->url_args['page']) {
            $this->url_args['page'] = 1;
        }
        
        if (!$this->url_args['search_id']) {
            $this->url_args['search_id'] = NULL;
        }
        
        if ($this->url_args['limit_num']<=0) {
            $this->url_args['limit_num'] = 10;
        } elseif ($this->url_args['limit_num']>1000) {
            $this->url_args['limit_num'] = 1000;
        }   
        
        $this->args_by_get = Confform::searchValuesByURL($this->url_args);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy('prior');
        
        $admin = User::authUser();
        if (!$admin->hasAccess('all')) {
            $users = $users->where('prior','>=',$admin->prior);
        }
        
        if ($this->url_args['search_email']) {
            $users = $users->where('email','like', '%'.$this->url_args['search_email'].'%');
        } 

        if ($this->url_args['search_name']) {
            $search_name = '%'.$this->url_args['search_name'].'%';
            $users = $users->where(function ($query) use ($search_name) {
                $query->where('first_name_'.env('PRIM_LANG'), 'like', $search_name)
                      ->orWhere('last_name_'.env('PRIM_LANG'), 'like', $search_name)
                      ->orWhere('middle_name_'.env('PRIM_LANG'), 'like', $search_name)
                      ->orWhere('first_name_'.env('ADD_LANG'), 'like', $search_name)
                      ->orWhere('last_name_'.env('ADD_LANG'), 'like', $search_name)
                      ->orWhere('middle_name_'.env('ADD_LANG'), 'like', $search_name);
            });
        } 

        if ($this->url_args['search_country']) {
            $users = $users->whereIn('country_id',$this->url_args['search_country']);
        } 

        if ($this->url_args['search_city']) {
            $users = $users->whereIn('city_id',$this->url_args['search_city']);
        } 

        if ($this->url_args['search_role']) {
            $search_role = $this->url_args['search_role'];
            $users = $users->whereIn('id',function ($query) use ($search_role) {
                $query->select('user_id')
                      ->from('role_users')
                      ->whereIn('role_id', $search_role);
            });
        } 

        if ($this->url_args['search_perm'] && sizeof($this->url_args['search_perm'])) {
            $search_perm = $this->url_args['search_perm'];
            $users = $users->where(function ($query) use ($search_perm) {
                $query->where('permissions', 'like', '%"'.$search_perm[0].'":true%');
                for ($i=1; $i<sizeof($search_perm); $i++) {
                    $query->orWhere('permissions', 'like', '%"'.$search_perm[$i].'":true%');
                }
            });
        } 

        $numAll = $users->get()->count();
        $users = $users->paginate($this->url_args['limit_num']);         

        $country_values = Country::getList();
        $city_values = City::getList($this->url_args['search_country']);
        $role_values = Role::getList();
        
        $user = new User;
        $perm_values = $user->getPermList();
        
        return view('user.index')
                    ->with(['users' => $users,
                            'city_values'    => $city_values,
                            'country_values' => $country_values,
                            'role_values'    => $role_values,
                            'perm_values' => $perm_values,
                            'numAll'         => $numAll,
                            'args_by_get'    => $this->args_by_get,
                            'url_args'       => $this->url_args,
                    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Redirect::to('/user/');
//        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Redirect::to('/user/');
/*        $this->validate($request, [
            'first_name'  => 'required|max:255',
            'last_name'  => 'max:255',
            'email'  => 'required|email|max:150',
        ]);
        
        $user = User::create($request->all());
        
        return Redirect::to('/user/?search_id='.$user->id)
            ->withSuccess(\Lang::get('messages.created_success'));  
 * 
 */      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Redirect::to('/user/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id); 

        $admin = User::authUser();
        if (!$admin->hasAccess('all') && $admin->prior > $user->prior) {
            return Redirect::to('/')
                           ->withErrors(\Lang::get('error.permission_denied'));
        }
                
        $role_values = Role::getList($admin->prior);
        
        $role_value = [];
        foreach ($user->roles as $role) {
            $role_value[] = $role->id;
        }
        
        $perm_values = $user->getPermList();
      
        if (!$admin->hasAccess('all')) {
            $access_perm_values = [];
            foreach ($admin->permissions as $perm_n=>$perm_v) {
                if ($perm_v) {
                    $access_perm_values[$perm_n] = $perm_values[$perm_n];
                }
            }
            $perm_values = $access_perm_values;
        }
        
        $user_perms = $user->permissions;

        $perm_value = [];
        foreach ($perm_values as $perm=>$perm_t) {
            if (isset($user_perms[$perm]) && $user_perms[$perm]) {
                $perm_value[] = $perm;
            }
        }
        
        $locale = LaravelLocalization::getCurrentLocale();
        $country_values = [NULL => ''] + Country::getList();
        $region_values = [NULL => ''] + Region::getList();
        $city_values = [NULL => ''] + City::getList();
        
        if ($user->city && $user->city->region_id) {
            $region_id = $user->city->region_id;
        } else {
            $region_id = null;
        }
              
        return view('user.edit')
                  ->with(['user' => $user,
                          'city_values' => $city_values,
                          'country_values' => $country_values,
                          'region_values' => $region_values,
                          'region_id' => $region_id,
                          'role_values' => $role_values,
                          'role_value' => $role_value,
                          'perm_values' => $perm_values,
                          'perm_value' => $perm_value,
                          'locale' => $locale,
                          'args_by_get'    => $this->args_by_get,
                          'url_args'       => $this->url_args,
                         ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $admin = User::authUser();
        if (!$admin->hasAccess('all') && $admin->prior > $user->prior) {
            return Redirect::to('/')
                           ->withErrors(\Lang::get('error.permission_denied'));
        }

        $prlang = $user->getPrimLang();
        $adlang = $user->getAddLang();
        
        $this->validate($request, [
            'first_name_'.$prlang  => 'required|string|max:191',
            'last_name_'.$prlang  => 'required|string|max:191',
            'affil_'.$prlang  => 'required|string|max:255',
            'email'  => 'required|email|max:191',
            'country_id' => 'required|integer',
            'city_id' => 'required|integer'
        ]);
        $user->fill($request->all());
        
        $user->prior=$user->getRolesPrior();

        $user_perms = [];
        if ($admin->hasAccess('all')) {
            if ($request->permissions) {
                foreach ($request->permissions as $p) {
                    $user_perms[$p] = true;
                }
            } 
            $user->permissions = $user_perms;      
        } else {
            foreach ($admin->permissions as $perm_n=>$perm_v) {
                if ($request->permissions && in_array($perm_n,$request->permissions)) {
                    $user -> updatePermission($perm_n, true, true);
                } else {
                    $user -> removePermission($perm_n);
                }
            }
        }

        $user->save();
        
        $user->roles()->detach();
        $user->roles()->attach($request->roles);

        return Redirect::to('/user/?'.($this->args_by_get))
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $error = false;
        $status_code = 200;
        $result =[];
        if($id != "" && $id > 0) {
            try{
                $user = User::find($id);
                if($user){
                    $admin = User::authUser();
                    if (!$admin->hasAccess('all') && $admin->prior > $user->prior) {
                        $error = true;
                        $result['error_message'] = \Lang::get('error.permission_denied');
                    } else {
                        $user_name = $user->email;
                        $user->roles()->detach();
                        $user->delete();
                        $result['message'] = \Lang::get('auth.user_removed', ['name'=>$user_name]);
                    }
                }
                else{
                    $error = true;
                    $result['error_message'] = \Lang::get('messages.record_not_exists');
                }
          }catch(\Exception $ex){
                    $error = true;
                    $status_code = $ex->getCode();
                    $result['error_code'] = $ex->getCode();
                    $result['error_message'] = $ex->getMessage();
                }
        }else{
            $error =true;
            $status_code = 400;
            $result['message']='Request data is empty';
        }
        
        if ($error) {
                return Redirect::to('/user/')
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to('/user/')
                  ->withSuccess($result['message']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = User::authUser();
        
        $locale = LaravelLocalization::getCurrentLocale();
        $country_values = [NULL => ''] + Country::getList();
        $region_values = [NULL => ''] + Region::getList();
        $city_values = [NULL => ''] + City::getList();
        
        if ($user->city->region_id) {
            $region_id = $user->city->region_id;
        } else {
            $region_id = null;
        }
       
        $role_values = Role::getList();
        
        $role_value = [];
        foreach ($user->roles as $role) {
            $role_value[] = $role_values[$role->id];
        }
        
        $perm_values = $user->getPermList();
        $user_perms = $user->permissions;

        $perm_value = [];
        foreach ($perm_values as $perm=>$perm_t) {
            if (isset($user_perms[$perm]) && $user_perms[$perm]) {
                $perm_value[] = $perm_t;
            }
        }
        
        return view('user.edit_profile')
                  ->with(['user' => $user,
                          'city_values' => $city_values,
                          'country_values' => $country_values,
                          'region_values' => $region_values,
                          'region_id' => $region_id,
                          'role_value' => $role_value,
                          'perm_value' => $perm_value,
                          'locale' => $locale
                         ]);
    }
    
    public function profileUpdate(Request $request)
    {
        $user = User::authUser();
        
        $prlang = $user->getPrimLang();
        $adlang = $user->getAddLang();
        
        $this->validate($request, [
            'first_name_'.$prlang  => 'required|string|max:191',
            'last_name_'.$prlang  => 'required|string|max:191',
            'affil_'.$prlang  => 'required|string|max:255',
//            'email'  => 'required|email|max:191',
            'country_id' => 'required|integer',
            'city_id' => 'required|integer'
        ]);
        $user->fill($request->all());

        $user->save();
//dd($request->all());        
        
        return Redirect::to('/')
            ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Gets list of cities for drop down list in JSON format
     * Test url: /user/city_list?country_id=159&region_id=
     * 
     * @return JSON response
     */
    public function citiesList(Request $request)
    {
//        $locale = LaravelLocalization::getCurrentLocale();
        
        $search_name = '%'.$request->input('q').'%';
        $country_id = (int)$request->input('country_id');
        $region_id = (int)$request->input('region_id');
//        $field_name = 'name_'.$locale;
        
        $all_cities = [];
        $cities = City::getList($country_id, $region_id, $search_name);
                /*City::where('country_id',$country_id)
                       ->where($field_name,'like', $search_name);
        
        if ($region_id) {
            $cities = $cities -> where('region_id',$region_id);
        }
        
        $cities = $cities ->orderBy($field_name)->get();*/
        
        foreach ($cities as $city_id => $city_name) {
            $all_cities[]=['id'  => $city_id, 
                           'text'=> $city_name];
        }  

        return Response::json($all_cities);
    }

    /**
     * Gets list of regions for drop down list in JSON format
     * Test url: /user/region_list?country_id=159
     * 
     * @return JSON response
     */
    public function regionsList(Request $request)
    {
//        $locale = LaravelLocalization::getCurrentLocale();
        
        $search_name = '%'.$request->input('q').'%';
        $country_id = (int)$request->input('country_id');
//        $field_name = 'name_'.$locale;
        
        $all_regions = [];
        $regions = Region::getList($country_id, $search_name);
//        dd($regions);
                /*Region::where('country_id',$country_id)
                       ->where($field_name,'like', $search_name)
                       ->orderBy($field_name)->get();*/
        
        foreach ($regions as $region_id => $region_name) {
            $all_regions[]=['id'  => $region_id, 
                            'text'=> $region_name];
        }  

        return Response::json($all_regions);
    }

}
