<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Profile;
use App\Projbid;
use App\Jobs;
use App\Projpost;
use App\User;
use App\Skills;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input as Input;
use Illuminate\Support\Facades\Redirect;

use GeoIP as GeoIP;


class DashboardController extends Controller
{

    public function index()
    {
        $posts = Projpost::latest()->get();
        return view('dashboard.index', compact('posts'));
    }
    public function faq()
    {
        //$posts = Projpost::latest()->get();
        return view('faq');
    }

    public function show($id)
    {
        $post = Projpost::findOrFail($id);
        $bids = Projbid::where('projpost_id', $id)->get();

        return view('dashboard.show', compact('post', 'bids'));
    }

    public function showpb($id)
    {
        $posts = Projpost::where('author', $id)->latest()->get();
        return view('dashboard.pindex', compact('posts', 'id'));
    }

    public function showbidpost($id)
    {
        $authuser = Auth::user()->name;
        if ($authuser == $id) {
            $posts = DB::table('projposts')->join('projbids', 'projposts.id', '=', 'projbids.projpost_id')->select('projposts.id', 'author', 'title', 'projposts.description as description', 'payment_type', 'startdate', 'deadline', 'tags', 'projposts.amount as amount')->where('projbids.username', $id)->groupBy('projposts.id');
            $posts = $posts->distinct()->get();

            return view('dashboard.bidindex', compact('posts', 'id'));
        } else {
            return "View your own bidded posts mate!";
        }
    }

    public function create()
    {
        if (Auth::check()) {
            $authuser = Auth::user()->name;
            return view('dashboard.create', compact('authuser'));
        } else {
            return Redirect::to('login');
        }
    }

    public function store()
    {
        $input = Input::all();
        $authuser = Auth::user()->name;

        $newpost = new Projpost;
        $newpost->author = $authuser;
        $newpost->title = $input['title'];
        $newpost->description = $input['description'];
        $newpost->tags = strtoupper($input['tags']);
        $newpost->payment_type = $input['pt'];
        $newpost->startdate = $input['startdate'];
        $newpost->deadline = $input['deadline'];
        $newpost->amount = $input['amount'];
        $newpost->status = 'waiting';

        $newpost->save();

        return redirect($authuser . '/dashboard');
    }

    public function showeb($id) // show edit blog for particular blog

    {
        $authuser = Auth::user()->name;
        $post = Projpost::findOrFail($id);
        if ((Auth::check() == true) && ($authuser == $post->author)) {
            if ($post->status == 'waiting') {
                return view('dashboard.edit', compact('post'));
            } else {
                return "Your posted project can not be edited, as it has already been awarded!";
            }
        } else {
            return 'Edit your own job posts';
        }
    }

    public function edit($id)
    {
        $authuser = Auth::user()->name;
        $input = Input::all();

        $post = Projpost::findOrFail($id);
        $post->title = $input['title'];
        $post->description = $input['description'];
        $post->tags = strtoupper($input['tags']);
        $post->payment_type = $input['pt'];
        $post->startdate = $input['startdate'];
        $post->deadline = $input['deadline'];
        $post->amount = $input['amount'];
        $post->save();

        return redirect($authuser . '/dashboard');

    }

    public function destroy($id)
    {
        $post = Projpost::findOrFail($id);
        if ($post->status == 'waiting') {
            $post = Projpost::findOrFail($id);
            $post->delete();
            $authuser = Auth::user()->name;
            return redirect($authuser . '/dashboard');
        } else {
            return "Your posted project can not be deleted, as it has already been awarded!";
        }
    }

    public function bidpost(Request $request)
    {
        $this->validate($request, [
            'postid' => 'required',
            'description' => 'required',
            'amount' => 'required',
        ]);
        $authuser = Auth::user()->name;

        $placebid = new Projbid;
        $placebid->projpost_id = $request['postid'];
        $placebid->username = $authuser;
        $placebid->description = $request['description'];
        $placebid->amount = $request['amount'];
        $placebid->acceptstatus = 0;
        $placebid->save();

        if ($request->ajax()) {
            return response()->json();
        }

    }

    public function bidaccepts(Request $request)
    {
        $this->validate($request, [
            'postid' => 'required',
            'bidid' => 'required',
        ]);

        $pid = $request['postid'];
        $bid = $request['bidid'];

        $post = Projpost::findOrFail($pid);
        $post->status = 'awarded';
        $post->accepted_bid_id = $bid;
        $post->save();

        $bid = Projbid::findOrFail($bid);
        $bid->acceptstatus = 1;
        $bid->save();

        if ($request->ajax()) {
            return response()->json();
        }

    }

    public function search(Request $request)
    {
        $location = GeoIP::getLocation();           // Getting location of user via IP
        $lat1 = $location['lat'];
        $lon1 = $location['lon'];

        $searchWords = explode(",", strtoupper($request['navsearch']));

        $posts = DB::table('projposts')->join('profiles', 'projposts.author', '=', 'profiles.username');
        foreach ($searchWords as $word) {
            $posts->orWhere('tags', 'LIKE', '%' . $word . '%');
        }
        $posts = $posts->distinct()->get();

        $arrays = array();
        foreach ($posts as $post) {             // src of this distance calculation formula = http://www.geodatasource.com/developers/php       
            $lat2 = $post->lati;
            $lon2 = $post->long;
            $theta = $lon1 - $lon2;

            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $km = $dist * 60 * 1.1515 * 1.609344;
            $post->dist = $km;
            $arrays[] = $post;
        }
        usort($arrays, array($this, "cmp"));

        if (Auth::check()) {
            return view('dashboard.search', compact('posts'));
        } else {
            return view('dashboard.offlineSearch', compact('posts'));
        }
        //    return $posts;
    }

    public function searchviews()
    {
        if (Auth::check()) {

            return view('dashboard.searchp');
        } else {
            return Redirect::to('login');
        }
    }
    public function searchview(Request $request)
    {
        if (Auth::check()) {
            //$authuser = Auth::user()->name;
            $this->validate($request,[
                'tosearch' => 'required',
            ]);

            //$search = $request['tosearch'];
            //echo $search;exit;
            $searchWords = explode(",", strtoupper($request['tosearch']));


            $posts = Projpost::query();
            foreach ($searchWords as $word) {
                $posts->orWhere('tags', 'LIKE', '%' . $word . '%');
            }

            $posts = $posts->distinct()->get();
            //var_dump($posts);


            return view('dashboard.searchp', compact('posts'));
        } else {
            return Redirect::to('login');
        }
    }



    public function jobsearch(Request $request)
    {
        $this->validate($request,[
         'tosearch' => 'required',
        ]);

        //$searchWords=$request->get('tosearch');
        $searchWords = explode(",", strtoupper($request['tosearch']));

        $skill=Array();
        foreach ($searchWords as $word) {

           $data= Skills::Where('title', 'LIKE', '%' . $word . '%')
               ->distinct()
               ->get();
            //$data=$data->distinct()->get();
            $skill=$data->toArray();
            //var_dump($skill);exit;


        }
        //var_dump($datas);
        //exit;
        $arrays = array();

        foreach ($skill as $dd) {

            $us= $dd['username'];

            $user = DB::table('profiles')
                ->leftjoin('skills', 'profiles.username', '=', 'skills.username')
                //->join('education', 'profiles.username', '=', 'education.username')
                ->where('skills.username',$us)
                ->get();

            $result_user=$user;


            $arrays=$result_user;
        }
        $skill=(object)$skill;
        //var_dump($skill);exit;
        return view('dashboard.search',compact('arrays','skill'));

    }


    //public function jobsearch(Request $request)
   // {
        //echo "sssssssssssss";exit;

        //$this->validate($request,[
        //  'tosearch' => 'required',
        //]);
     //   $searchWords=$request->get('tosearch');
       // echo $searchWords;exit;
       // $data = Skills::where('title', $searchWords)->get();



//        $datas=$data->toArray();
  //      foreach ($datas as $dd) {


    //        $us= $dd['username'];
      //      $user = Profile::where('username', $us)->get();

        //    $result_user=$user->toArray();


        //}
     //   return view('dashboard.search', compact($result_user));

   // }


    public function searchview_bk(Request $request)
    {
        if (Auth::check()) {
            $location = GeoIP::getLocation();           // Getting location of user via IP
            $lat1  = $location['lat'];               
            $lon1  = $location['lon']; 

            $authuser = Auth::user()->name;
            $usr      = Profile::where('username', $authuser)->first();
            
//            $lat1  = $usr->lati;               // for testing on localhost purposes
//            $lon1  = $usr->long;               // for testing on localhost purposes
            
            $searchWords = explode(",", strtoupper($usr->keywords));
            
            $posts =  DB::table('projposts')->join('profiles', 'projposts.author', '=', 'profiles.username');

            $arrays = array();

            foreach ($searchWords as $word) {
                $posts->orWhere('tags', 'LIKE', '%' . $word . '%');
            }

            $posts = $posts->distinct()->get();
            $arrays = array();
            foreach ($posts as $post) {             // src of this distance calculation formula = http://www.geodatasource.com/developers/php       
                $lat2  = $post->lati;
                $lon2  = $post->long;
                $theta = $lon1 - $lon2;

                $dist       = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist       = acos($dist);
                $dist       = rad2deg($dist);
                $km         = $dist * 60 * 1.1515 * 1.609344;
                $post->dist = $km;
                $arrays[] = $post;
            }

            usort($arrays, array($this, "cmp"));

            return view('dashboard.search', compact('posts'));
        } else {
            return Redirect::to('login');
        }
    }

    public function jobsearchhhh(Request $request)
    {
        $this->validate($request, [
            'tosearch' => 'required',
        ]);

        $location = GeoIP::getLocation();           // Getting location of user via IP
        $lat1  = $location['lat'];               
        $lon1  = $location['lon']; 

        $authuser = Auth::user()->name;
        $usr      = Profile::where('username', $authuser)->first();
            
//        $lat1  = $usr->lati;               // for testing on localhost purposes
//        $lon1  = $usr->long;               // for testing on localhost purposes


        $searchWords = explode(",", strtoupper($request['tosearch']));

        $posts =  DB::table('projposts')->join('profiles', 'projposts.author', '=', 'profiles.username');
        foreach ($searchWords as $word) {
            $posts->orWhere('tags', 'LIKE', '%' . $word . '%');
        }
        $posts = $posts->distinct()->get();
        $arrays = array();
        foreach ($posts as $post) {             // src of this distance calculation formula = http://www.geodatasource.com/developers/php       
            $lat2  = $post->lati;
            $lon2  = $post->long;
            $theta = $lon1 - $lon2;

            $dist       = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist       = acos($dist);
            $dist       = rad2deg($dist);
            $km         = $dist * 60 * 1.1515 * 1.609344;
            $post->dist = $km;
            $arrays[] = $post;
        }

        usort($arrays, array($this, "cmp"));
        

        if ($request->ajax()) {
            return response()->json(['success' => true, 'posts' => $posts]);
        }
        return $posts;
    }

    public function profsearch(Request $request) // profs == professionals

    {

        $this->validate($request, [
            'tosearch' => 'required',
        ]);

        $location = GeoIP::getLocation();           // Getting location of user via IP
        $lat1  = $location['lat'];               
        $lon1  = $location['lon']; 

        $authuser = Auth::user()->name;
        $usr      = Profile::where('username', $authuser)->first();
            
        $lat1  = $usr->lati;               // for testing on localhost purposes
        $lon1  = $usr->long;               // for testing on localhost purposes

        $searchWords = explode(",", strtoupper($request['tosearch']));

        $profs = Profile::query();
        foreach ($searchWords as $word) {
            $profs->orWhere('keywords', 'LIKE', '%' . $word . '%');
        }
        $profs = $profs->distinct()->get();
		$arrays = array();
        foreach ($profs as $prof) {             // src of this distance calculation formula = http://www.geodatasource.com/developers/php       
            $lat2  = $prof->lati;
            $lon2  = $prof->long;
            $theta = $lon1 - $lon2;

            $dist  		= sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist       = acos($dist);
            $dist       = rad2deg($dist);
            $km         = $dist * 60 * 1.1515 * 1.609344;
            $prof->dist = $km;
            $arrays[] = $prof;
        }
        usort($arrays, array($this, "cmp"));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'profs' => $arrays]);
        }
        return $profs;
    }


	public function cmp($a, $b)
	{
	    if ($a->dist == $b->dist) {
	        return 0;
	    }
	    return ($a->dist < $b->dist) ? -1 : 1;
	}

    public function searchAcInt(Request $request) // search according to interests saved by the user in his/her profile

    {
        $this->validate($request,[
            'searchjob' => 'required',
        ]);

        //$searchWords=$request->get('tosearch');
        $searchWords = explode(",", strtoupper($request['searchjob']));


        $arrays = array();

        foreach ($searchWords as $dd) {

            //$us= $dd['username'];

            $jobs= Jobs::Where('title', 'LIKE', '%' . $dd . '%')->get();




            $arrays=$jobs;
        }
       // var_dump($arrays);exit;
        //$arrays=(object)$arrays;
        return view('dashboard.jobs',compact('arrays'));
    }


}