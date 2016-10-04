<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/*
Route::bind('profile',function($uname){
	return App/Profile::where('username',$uname)->first();
})
*/


Route::get('/home', function () {
		if (Auth::check()) {
			return view('home');
		} else {
			return Redirect::to('login');
		}
	});

/*read notes on dashboard.search.blade.php ... then you'll understand following 3 routes*/
Route::get('searchjobs','DashboardController@searchviews');		// page for searching jobs ... like a search engine
Route::get('searchjobss','DashboardController@searchview');		// page for searching jobs ... like a search engine
Route::get('searchjobsint','DashboardController@searchAcInt');	// working... but still have to be integrated with search.blade.php with ajax
Route::post('searchjobs','DashboardController@jobsearch');

Route::post('searchprofs','DashboardController@profsearch');

//Route::get('temproute','DashboardController@searchAcInt');		// page for searching jobs ... like a search engine


Route::get('dashboard','DashboardController@index');
Route::get('dashboard/create','DashboardController@create');	
Route::get('dashboard/{id}','DashboardController@show');			// viewing particular job post
Route::get('dashboard/{id}/editpost','DashboardController@showeb');	// view for editing logged in user's job post
Route::get('{id}/dashboard','DashboardController@showpb');	// show particular profile's all job posts
Route::post('dashboard','DashboardController@store');		// for saving new job post
Route::post('dashboard/{id}','DashboardController@edit');	// for editing logged in user's job post
Route::delete('dashboard/{id}','DashboardController@destroy');	// for deleting logged in user's job post
Route::post('jobpost','DashboardController@bidpost');	// for 
Route::post('jobbid','DashboardController@bidaccept');	

//Route::get('/p','ProfilesController@index');
//Route::post('/p', 'ProfilesController@create');		// for posting data of edited profile

// made indirect links by making showep.blade.php and show.blade.php to make it more secure

Route::get('/', function () {
			return view('welcome');
		
});
//Route::get('/searchjobs','DashboardController@searchj');

Route::get('/scoreboard','ProfilesController@info');			// for profile page .--------- make profile page here
Route::get('getUpdate','ProfilesController@getUpdate');			// for profile edit experience modal page .---------
Route::post('updaterecord/{id}','ProfilesController@updateExperience');			// for profile edit experience modal page .---------

Route::get('/profile',[
    'uses' => 'ProfilesController@showExperience',
    'as' => 'profile',
    'middleware' => 'auth'
]);
/*
 * education Section Routes and action
 * **************************************************************************************************/
Route::post('addeducation/', 'ProfilesController@addeducation');
Route::get('geteduaction','ProfilesController@geteduaction');
Route::post('updateeducation/{id}','ProfilesController@updateeducation');
Route::get('deleteeducation/{id}','ProfilesController@deleteeducation');


Route::get('blogs','BlogsController@index');
Route::get('blogs/create','BlogsController@create');	
Route::get('blogs/{id}','BlogsController@show');			// viewing particular blog
Route::get('blogs/{id}/editblog','BlogsController@showeb');	// view for editing logged in user's old blog
Route::get('{id}/blogs','BlogsController@showpb');	// show particular profile's all blogs

Route::post('blogs','BlogsController@store');		// for saving new blog
Route::post('blogs/{id}','BlogsController@edit');	// for editing logged in user's  old blog
Route::delete('blogs/{id}','BlogsController@destroy');	// for deleting logged in user's  old blog

/* Note that: localhost:8000/editprofile and localhost:8000/profile are not to be official pages acc to me, 
coz they arent generic, that's why i've made /p/{$id} -- for profile ; and /p/{$id}/editprofile 

*/

/*
Route::get('/editprofile', function () {
		if (Auth::check()) {
			return view('editprofile');
		} else {
			return Redirect::to('login');
		}
	});
*/
Route::auth();

Route::get('{id}','ProfilesController@show');			// for profile page .--------- make profile page here
Route::post('addexperience','ProfilesController@addExperience');			// for profile page .--------- make profile page here
Route::get('{id}/editprofile','ProfilesController@showep');		// for editing profile page ------ make edit profile here
Route::get('deleteexperience/{id}','ProfilesController@deleteExperience');		// for deleting experience profile page ------ make edit profile here

Route::post('{id}', 'ProfilesController@create');		// for posting data of edited profile

Route::get('{id}/{slug}','BlogsController@showslg');	// show particular profile's particular song 


/*Zahid routes*/

