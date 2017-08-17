<?php

namespace Confform\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use LaravelLocalization;
use Carbon\Carbon;

use Confform\City;
use Confform\Conference;
use Confform\Confform;
use Confform\Country;

class ConferenceController extends Controller
{
    public $url_args=[];
    public $args_by_get='';
    
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('perm:conf:*,/', ['only'=>['index','view']]);
        $this->middleware('perm:conf.create,/', ['only'=>['create','store']]);
        $this->middleware('perm:conf.update,/', ['only'=>['edit','update']]);
        $this->middleware('perm:conf.delete,/', ['only'=>['destroy']]);
        
        $this->url_args = [
                    'limit_num'            => (int)$request->input('limit_num'),
                    'page'                 => (int)$request->input('page'),
                    'search_id'            => (int)$request->input('search_id'),
                    'search_title'         => $request->input('search_title'),
                    'search_started_after' => $request->input('search_started_after'),
                    'search_finished_before' => $request->input('search_finished_before'),
                    'search_country'       => (array)$request->input('search_country'),
                    'search_city'          => (array)$request->input('search_city'),
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
    public function index()
    {
        $confs = Conference::orderBy('id');
        
        if ($this->url_args['search_title']) {
            $search_title = '%'.$this->url_args['search_title'].'%';
            $confs = $confs->where(function ($query) use ($search_title) {
                $query->where('title_'.env('PRIM_LANG'), 'like', $search_title)
                      ->orWhere('title_'.env('ADD_LANG'), 'like', $search_title);
            });
        } 
        
        if ($this->url_args['search_city']) {
            $confs = $confs->whereIn('city_id',$this->url_args['search_city']);
        } 

        $numAll = $confs->get()->count();
        $confs = $confs->paginate($this->url_args['limit_num']);   
        
        $country_values = NULL;//Country::getList();
        $city_values = NULL;//City::getList($this->url_args['search_country']);
        $locale = LaravelLocalization::getCurrentLocale();
       
        return view('conference.index')
                  ->with(['confs'          => $confs,
                          'city_values'    => $city_values,
                          'country_values' => $country_values,
                          'locale'         => $locale,
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
