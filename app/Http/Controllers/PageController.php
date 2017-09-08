<?php

namespace Confform\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use LaravelLocalization;
use Carbon\Carbon;

use Confform\City;
use Confform\Page;
use Confform\Confform;
use Confform\Country;
use Confform\Region;

class PageController extends Controller
{
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('perm:page:*,/', ['only'=>['index','view']]);
        $this->middleware('perm:page.create,/', ['only'=>['create','store']]);
        $this->middleware('perm:page.update,/', ['only'=>['edit','update']]);
        $this->middleware('perm:page.delete,/', ['only'=>['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::orderBy('id');
        
        return view('page.index')
                  ->with(['pages'          => $pages,
                         ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($event_id=NULL)
    {
        $page = new Page;
        
        $event_title=null;
        $prim_lang = env('PRIM_LANG');
        $add_lang = env('ADD_LANG');
        
        if ($event_id) {
            $event = Event::find($event_id); 
            if ($event) {
                $event_title = $event->title;
                $prim_lang = $event->prim_lang;
                $add_lang = $event->add_lang;
            }
        }
        
        $page_list = null;
        return view('page.edit')
                  ->with(['page' => $page,
                          'prim_lang' => $prim_lang,
                          'add_lang' => $add_lang,
                          'event_id' => $event_id,
                          'event_title' => $event_title,
                          'page_list' => $page_list,
                          'action' => 'create',
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
        $page = new Page;
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
        
        $page -> fill($input);
        $page -> save();
         
        return Redirect::to('/admin/page/'.($page->id).($this->args_by_get))
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
        $page = Page::find($id); 
        return view('page.show')
                  ->with(['page' => $page,
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
        $page = Page::find($id); 
//dd($page);        
        $locale = LaravelLocalization::getCurrentLocale();
        $country_values = [NULL => ''] + Country::getList();
        $region_values = [NULL => ''] + Region::getList($page->country_id);
        
        if ($page->city && $page->city->region_id) {
            $region_id = $page->city->region_id;
        } else {
            $region_id = null;
        }
        $city_values = [NULL => ''] + City::getList($page->country_id, $region_id);
        
        $lang_values = [NULL => '', 
            env('PRIM_LANG')=> \Lang::get('messages.lang_'.env('PRIM_LANG')),
            env('ADD_LANG') => \Lang::get('messages.lang_'.env('ADD_LANG'))];
        
        return view('page.edit')
                  ->with(['page' => $page,
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
        $page = Page::find($id);
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
        
        $page -> fill($input);
        $page -> save();
         
        return Redirect::to('/admin/page/'.($page->id).($this->args_by_get))
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
    
    /**
     * Shows history of page.
     *
     * @param  int  $id - ID of page
     * @return \Illuminate\Http\Response
     */
    public function history($id)
    {
        $page = Page::find($id);
        if (!$page) {
            return Redirect::to('/admin/page/'.($this->args_by_get))
                           ->withErrors(\Lang::get('messages.record_not_exists'));
        }
//dd($page->revisionHistory);        
        return view('page.history')
                  ->with(['page'       => $page,
                          'args_by_get' => $this->args_by_get,
                          'url_args'    => $this->url_args,
                         ]);
    }
    
}
