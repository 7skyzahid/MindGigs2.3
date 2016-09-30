<?php

namespace App\Http\Controllers;

use App\Educatn;
use App\Experience;
use File;
use Auth;
use App\User;
use App\Profile;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Input as Input;
use Cookie;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;



class ProfilesController extends Controller
{
   protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required','briefdescription' => 'required','address' => 'required','languages' => 'required','about' => 'required','interests' => 'required',  
        ]);
    }

    protected function create($id)
    {
		$frame_str="http://localhost:8000/auth/empty";
		$input = Input::all();
        //dd($input);
			
//		$id = Auth::id();
//		$usr = User::find($id);
//		$logprof = new Profile;

		$authuser = Auth::user()->name;
		//dd(public_path('images'));
		$logprof = Profile::where('username',$authuser)->first(); 
		$logprof->briefdescription = $input['proftitle'];
		$logprof->address = $input['address'];
        $logprof->city = $input['city'];
        //$logprof->profilepic = $input['profileimage'];
        $logprof->country = $input['country'];
		$logprof->languages = $input['langs'];
		$logprof->about = $input['about'];
		$logprof->interests = $input['interests'];
        $logprof->keywords = $input['keywords'];
        $logprof->gitlink = $input['gitlink'];
        $logprof->fblink = $input['fblink'];
        $logprof->twitlink = $input['twitlink'];
        $logprof->lilink = $input['lilink'];
        if(isset($input['prfileimage'])){
            $file = Input::file('prfileimage');
            $file->move(public_path('images'),$file->getClientOriginalName('prfileimage'));
            $logprof->profilepic = $file->getClientOriginalName();
        }


        if(isset($input['prfileimage'])){
          $file = Input::file('prfileimage');
            $file->move(public_path('images'),$file->getClientOriginalName('prfileimage'));
            $logprof->profilepic = $file->getClientOriginalName();
        }

		$logprof->save();

        $logusr = User::where('name',$authuser)->first();
        $logusr->email = $input['email'];
        $logusr->save();


		return Redirect::to(''.$authuser);	
    }

    protected function show($id,Request $request){
    	$uvar = User::where('name',$id)->first();

    	$uprof = Profile::where('username',$uvar->name)->first();
    	if (($uprof != null) && (!Auth::check())){
            $cookieval =  $request->cookie('affiliate');
            if ($cookieval == null) {
                    $response = Response(view('p.show',compact('uvar','uprof')));
                    $response->withCookie(Cookie::forever('affiliate', $id));
                    return $response;
            }
            else{   // for updating the cookie... to store new profile's username
                $response = Response(view('p.show',compact('uvar','uprof')));
                $response->withCookie(Cookie::forever('affiliate', $id));
                return $response;
            }
        }
        return view('p.show',compact('uvar','uprof'));
    }

    protected function showep($id){
    	$authuser = Auth::user()->name;
    	if($authuser == $id){
    		$uvar = User::where('name',$id)->first();
    		$uprof = Profile::where('username',$uvar->name)->first();
            $user = User::where('name',$uvar->name)->first();
    		return view('p.showep',compact('uprof','user'));
    	}
    	else {
    		return 'Sorry mate... edit your own profile!';
    	}
    }

    protected function info(){
        $sb = User::count();
        $arr = Profile::selectRaw('country, count(country) as peoplecount')->groupBy('country')->orderBy('country', 'asc')->get();
        
        return view('score',compact('sb','arr'));
    }
    //Adding Experience
    public function addExperience(){
        $postInput = Input::all();
        //dd($postInput);
        $userName = Auth::user()->name;
        $whereUser = new Experience();
       //insert data
        $whereUser->username = $userName;
        $whereUser->position = $postInput['position'];
        $whereUser->company = $postInput['company'];
        $whereUser->from = $postInput['datefrom'];
        $whereUser->to = $postInput['dateto'];
        $whereUser->description = $postInput['description'];
        $whereUser->save();
        $userExperience = Experience::where('username',$userName)->get();
        return redirect('profile')->with('experienceData',$userExperience);


    }
    public function showExperience(){
        $userName = Auth::user()->name;

        $userExperience = Experience::where('username',$userName)->get();
        $education = Educatn::where('username',$userName)->get();
        return view('profile')->with('experienceData',$userExperience)
                              ->with('educationData',$education);
    }
    public function deleteExperience($id){

            $experience = Experience::find($id);
        if($experience){
            $experience->delete();
        }
        $userName = Auth::user()->name;
        $userExperience = Experience::where('username',$userName)->get();
        return redirect('profile')->with('experienceData',$userExperience);
    }
    protected function getExperience(){
        $userName = Auth::user()->name;
        $userExperience = Experience::where('username',$userName)->get();
        return view('profile')->with('experienceData',$userExperience);
    }
    public function getUpdate(Request $request){
        if($request->ajax()){

            $experience = Experience::where('id',$request->id)->first();
        
            return Response($experience);
        }
    }
    public function updateExperience(Request $request){
        if(isset($request) && !empty($request)){

       $updateExperience = Experience::where('id',$request->id)
           ->update([
               'position'=> $request->position,
                'company'=> $request->company,
                'from'=> $request->datefrom,
                'to'=> $request->dateto,
                'description'=> $request->description,
           ]);
        return redirect('profile')->with('Message','Experience Has Been Update Successfully');
    }

    }
                                                /*
                                                 * EDUCATION ACTION AND THEIR RELATED LOGIC
                                                 * */
    public function addeducation(Request $request){
        if(isset($request) && !empty($request)){
            $education = new Educatn();
            $userName = Auth::user()->name;
            $education->username = $userName;
            $education->degree = $request->degree;
            $education->studylevel = $request->studylevel;
            $education->institute = $request->institute;
            $education->from = $request->datefrom;
            $education->to = $request->dateto;
            $education->description = $request->description;
            $education->save();
            return redirect('profile')->with('Message','Education has been Created');
        }
    }
    public function geteduaction(Request $request){
        if($request->ajax()){

            $education = Educatn::where('id',$request->id)->first();

            return Response($education);
        }
    }
    public function updateeducation(Request $request){
        if(isset($request) && !empty($request)){

            $updateExperience = Educatn::where('id',$request->id)
                ->update([
                    'degree'=> $request->degree,
                    'studylevel'=> $request->studylevel,
                    'institute'=> $request->institute,
                    'from'=> $request->datefrom,
                    'to'=> $request->dateto,
                    'description'=> $request->description,
                ]);
            return redirect('profile')->with('Message','Education Has Been Updated Successfully');
        }

    }
    public function deleteeducation($id){

        $education = Educatn::find($id);
        if($education){
            $education->delete();
        }
        $userName = Auth::user()->name;
        $userExperience = Experience::where('username',$userName)->get();
        $userExperience = Experience::where('username',$userName)->get();
        $educationdata = Educatn::where('username',$userName)->get();
        return redirect('profile')->with('experienceData',$userExperience)
            ->with('educationData',$educationdata);
    }

}