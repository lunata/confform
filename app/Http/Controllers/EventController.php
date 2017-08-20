<?php

namespace Confform\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use LaravelLocalization;
use Carbon\Carbon;

use Confform\City;
use Confform\Event;
use Confform\Confform;
use Confform\Country;
use Confform\Region;

class EventController extends Controller
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
        $this->middleware('perm:event:*,/', ['only'=>['index','view']]);
        $this->middleware('perm:event.create,/', ['only'=>['create','store']]);
        $this->middleware('perm:event.update,/', ['only'=>['edit','update']]);
        $this->middleware('perm:event.delete,/', ['only'=>['destroy']]);
        
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
        $events = Event::orderBy('id');
        
        if ($this->url_args['search_title']) {
            $search_title = '%'.$this->url_args['search_title'].'%';
            $events = $events->where(function ($query) use ($search_title) {
                $query->where('title_'.env('PRIM_LANG'), 'like', $search_title)
                      ->orWhere('title_'.env('ADD_LANG'), 'like', $search_title);
            });
        } 
        
        if ($this->url_args['search_city']) {
            $events = $events->whereIn('city_id',$this->url_args['search_city']);
        } 

        $numAll = $events->get()->count();
        $events = $events->paginate($this->url_args['limit_num']);   
        
        $country_values = Country::getList();
        $city_values = NULL;//City::getList($this->url_args['search_country']);
        $locale = LaravelLocalization::getCurrentLocale();
       
        return view('event.index')
                  ->with(['events'          => $events,
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
        $event = new Event;
        
        $locale = LaravelLocalization::getCurrentLocale();
        $country_values = [NULL => ''] + Country::getList();
        $region_values = [NULL => ''] + Region::getList();
        $city_values = [NULL => ''] + City::getList();
        
        $lang_values = [NULL => '', 
            env('PRIM_LANG')=> \Lang::get('messages.lang_'.env('PRIM_LANG')),
            env('ADD_LANG') => \Lang::get('messages.lang_'.env('ADD_LANG'))];
        
        return view('event.edit')
                  ->with(['event' => $event,
                          'city_values' => $city_values,
                          'country_values' => $country_values,
                          'region_values' => $region_values,
                          'locale' => $locale,
                          'lang_values' => $lang_values,
                          'action' => 'create',
                          'args_by_get'    => $this->args_by_get,
                          'url_args'       => $this->url_args,
                         ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = new Event;
        $this->validate($request, [
            'prim_lang' => 'required',
            'title_'.$request->prim_lang  => 'required|string|max:150',
//            'country_id' => 'required|integer',
            'city_id' => 'required|integer',
            'started_at' => 'required|date'
        ]);
        
        $input = $request->all();
        if ($input['city_id'] && !$input['country_id']) {
            $input['country_id'] = City::getCountryIDByID($input['city_id']);
        }
        
        $event -> fill($input);
        $event -> save();
         
        return Redirect::to('/event/'.($event->id).($this->args_by_get))
            ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id); 
        return view('event.show')
                  ->with(['event' => $event,
//                          'locale' => $locale,
                          'args_by_get'    => $this->args_by_get,
                          'url_args'       => $this->url_args,
                         ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id); 
//dd($event);        
        $locale = LaravelLocalization::getCurrentLocale();
        $country_values = [NULL => ''] + Country::getList();
        $region_values = [NULL => ''] + Region::getList($event->country_id);
        
        if ($event->city && $event->city->region_id) {
            $region_id = $event->city->region_id;
        } else {
            $region_id = null;
        }
        $city_values = [NULL => ''] + City::getList($event->country_id, $region_id);
        
        $lang_values = [NULL => '', 
            env('PRIM_LANG')=> \Lang::get('messages.lang_'.env('PRIM_LANG')),
            env('ADD_LANG') => \Lang::get('messages.lang_'.env('ADD_LANG'))];
        
        return view('event.edit')
                  ->with(['event' => $event,
                          'city_values' => $city_values,
                          'country_values' => $country_values,
                          'region_values' => $region_values,
                          'region_id' => $region_id,
                          'locale' => $locale,
                          'lang_values' => $lang_values,
                          'action' => 'edit',
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
        $event = Event::find($id);
        $this->validate($request, [
            'prim_lang' => 'required',
            'title_'.$request->prim_lang  => 'required|string|max:150',
//            'country_id' => 'required|integer',
            'city_id' => 'required|integer',
            'started_at' => 'required|date'
        ]);
        
        $input = $request->all();
        if ($input['city_id'] && !$input['country_id']) {
            $input['country_id'] = City::getCountryIDByID($input['city_id']);
        }
        
        $event -> fill($input);
        $event -> save();
         
        return Redirect::to('/event/'.($event->id).($this->args_by_get))
            ->withSuccess(\Lang::get('messages.created_success'));        
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
