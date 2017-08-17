<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group( [ 'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]
    ],
    function()
    {
        Route::get('/', function () {
            return view('welcome');
        });
        
        Route::get('captcha-form-validation',array('as'=>'google.get-recaptcha-validation-form','uses'=>'FileController@getCaptchaForm')) ;
        Route::post('captcha-form-validation',array('as'=>'google.post-recaptcha-validation','uses'=>'FileController@postCaptchaForm')) ;

        // Вызов страницы регистрации пользователя
        Route::get('register', 'AuthController@register');   
        // Пользователь заполнил форму регистрации и отправил
        Route::post('register', 'AuthController@registerProcess'); 
        // Пользователь получил письмо для активации аккаунта со ссылкой сюда
        Route::get('activate/{id}/{code}', 'AuthController@activate');
        // Вызов страницы авторизации
        Route::get('login', ['uses'=>'AuthController@login', 'as'=>'login']);
        // Пользователь заполнил форму авторизации и отправил
        Route::post('login', 'AuthController@loginProcess');
        // Выход пользователя из системы
        Route::get('logout', 'AuthController@logoutuser');
        // Пользователь забыл пароль и запросил сброс пароля. Это начало процесса - 
        // Страница с запросом E-Mail пользователя
        Route::get('reset', 'AuthController@resetOrder');
        // Пользователь заполнил и отправил форму с E-Mail в запросе на сброс пароля
        Route::post('reset', 'AuthController@resetOrderProcess');
        // Пользователю пришло письмо со ссылкой на эту страницу для ввода нового пароля
        Route::get('reset/{id}/{code}', 'AuthController@resetComplete');
        // Пользователь ввел новый пароль и отправил.
        Route::post('reset/{id}/{code}', 'AuthController@resetCompleteProcess');
        // Сервисная страничка, показываем после заполнения рег формы, формы сброса и т.
        // о том, что письмо отправлено и надо заглянуть в почтовый ящик.
        Route::get('wait', 'AuthController@wait');
        
        Route::get('profile', ['uses'=> 'UserController@profile',
                                     'as' => 'profile']);
        Route::post('profile', ['uses'=> 'UserController@profileUpdate',
                                     'as' => 'profile.update']);

        Route::get('user/city_list', 'UserController@citiesList');
        Route::get('user/region_list', 'UserController@regionsList');

        Route::resource('conf', 'ConferenceController',
                       ['names' => ['update' => 'Conference.update',
                                    'store' => 'Conference.store',
                                    'destroy' => 'Conference.destroy']]);
                
        Route::resource('role', 'RoleController',
                       ['names' => ['update' => 'role.update',
                                    'store' => 'role.store',
                                    'destroy' => 'role.destroy']]);
                
        Route::resource('user', 'UserController',
                       ['names' => ['update' => 'user.update',
                                    'destroy' => 'user.destroy']]);       
    }
);

