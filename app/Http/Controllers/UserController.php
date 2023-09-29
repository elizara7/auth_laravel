<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function registre(){
        if (session()->has('loggedInUser')) {
            return redirect('/profile');
        } else {
            return view('auth.registre');
        }
    }

    public function forgot(){
        return view('auth.forgot');
    }

    public function user(){
        return view('user');
    }
    public function fetchAll() {
		$emps = User::all();
		$output = '';
		if ($emps->count() > 0) {
			$output .= '<table class="table table-striped table-sm text-center align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Photo</th>
                <th>E-mail</th>
                <th>Genre</th>
                <th>Date de naissance</th>
                <th>Phone</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
			foreach ($emps as $emp) {
				$output .= '<tr>
                <td>' . $emp->id . '</td>
                <td>' . $emp->name . '</td>
                <td>' . $emp->picture . '</td>
                <td>' . $emp->email . '</td>
                <td>' . $emp->gender . '</td>
                <td>' . $emp->dob . '</td>
                <td>' . $emp->phone . '</td>
                <td>
                  <a href="#" id="' . $emp->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"><i class="bi-pencil-square h4"></i></a>

                  <a href="#" id="' . $emp->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
			}
			$output .= '</tbody></table>';
			echo $output;
		} else {
			echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
		}
	}

    // handle edit an employee ajax request
	public function edit(Request $request) {
		$id = $request->id;
		$emp = User::find($id);
		return response()->json($emp);
	}

	// handle update an employee ajax request
	public function update(Request $request) {
		$fileName = '';
		$emp = User::find($request->emp_id);
		if ($request->hasFile('picture')) {
			$file = $request->file('picture');
			$fileName = time() . '.' . $file->getClientOriginalExtension();
			$file->storeAs('public/images', $fileName);
			if ($emp->picture) {
				Storage::delete('public/images/' . $emp->picture);
			}
		} else {
			$fileName = $request->emp_picture;
		}

		$empData = ['name' => $request->name, 'email' => $request->email,'gender' => $request->gender, 'phone' => $request->phone, 'dob' => $request->dob, 'picture' => $fileName];

		$emp->update($empData);
		return response()->json([
			'status' => 200,
		]);
	}

    // handle delete an employee ajax request
	public function delete(Request $request) {
		$id = $request->id;
		$emp = User::find($id);
		if (Storage::delete('public/images/' . $emp->picture)) {
			User::destroy($id);
		}
	}

    // handle insert user ajax request
    public function userAjout(Request $request){
       $file = $request->file('picture');
       $fileName = time() . '.' . $file->getClientOriginalExtension();
       $file->StoreAS('public/images', $fileName);

       $empData = [
           'name' => $request->name,
           'email' => $request->email,
           'password' => $request->password,
           'picture' => $fileName,
           'gender' => $request->gender,
           'dob' => $request->dob,
           'phone' => $request->phone,
       ];

       User::create($empData);
       return response()->json([
           'status' => 200
       ]);
    }




    public function reset(){
        return view('auth.reset');
    }

    public function saveUser(Request $request){
        $validator = Validator::make($request->all(), [
             'name' => 'required|max:50',
             'email' => 'required|email|unique:users|max:100',
             'password' => 'required|min:6|max:50',
             'cpassword' => 'required|min:6|same:password'

        ],[
            'cpassword.same' => 'Password did not matched',
            'cpassword.required' => 'Confirm password is required!'
        ]);

        if($validator->fails()){
            return response()->json([
                  'status'=> 400,
                  'messages'=> $validator->getMessageBag()
            ]);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'status'=> 200,
                'messages'=> 'Registered Successfully!'
            ]);
        }
    }

    // Handle login user ajax request
    public function loginUser(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:50',
            'password' => 'required|min:6|max:100'

        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ]);
        }else  {
            $user = User::where('email', $request->email)->first();
            if($user){
                if(Hash::check($request->password, $user->password)){
                    $request->session()->put('loggedInUser', $user->id);
                    return response()->json([
                        'status' => 200,
                        'messages' => 'success'
                    ]);

                } else{
                    return response()->json([
                        'status' => 401,
                        'messages' => 'Username or password is incorrect!'
                    ]);
                }
            } else{
                return response()->json([
                    'status' => 401,
                    'messages' => 'Username not found!'
                ]);
            }
        }
    }

    //Profile page

    public function profile(){
        $data = ['userInfo' => DB::table('users')->where('id', session('loggedInUser'))->first()];
        return view('profile', $data);
    }

    //logout method
    public function logout() {
        if (session()->has('loggedInUser')) {
            session()->pull('loggedInUser');
            return redirect('/');
        }

    }

     //Modification image de profile
     public function profileImageUpdate(Request $request) {
        $user_id = $request->user_id;
        $user = User::find($user_id);

        if($request->hasFile('picture')) {
            $file = $request->file('picture');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images/', $fileName);

            if ($user->picture) {
                Storage::delete('public/images/' . $user->picture);
            }
        }
        User::where('id', $user_id)->update([
            'picture'=> $fileName
        ]);
        return response()->json([
             'status' => 200,
             'messages' => 'Profile image updated Successfully!'
        ]);
    }

    //Modification profile ajax
    public function profileUpdate(Request $request){
        User::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'phone' => $request->phone,
        ]);
        return response()->json([
            'status' => 200,
            'messages' => 'Modification profile succes!'
        ]);
    }

    //handle forgot password ajax request
    public function forgotPassword(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ]);
        } else {
            $token = Str::uuid();
            $user = DB::table('users')->where('email', $request->email)->first();
            $details = [
                'body' =>route('reset', ['email' => $request->email, 'token' =>
                 $token])
            ];

            if ($user) {
                User::where('email', $request->email)->update([
                    'token' => $token,
                    'token_expire' => Carbon::now()->addMinutes(10)->toDateTimeString()
                ]);


                Mail::to($request->email)->send(new ForgotPassword($details));
                return response()->json([
                    'status' => 200,
                    'messages' => 'Reset password link has been sent to your email!'
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'messages' => 'This email is not registered with us!'
                ]);
            }
        }
    }

    // handle resetpassword ajax request
    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'npass' => 'required|min:6|max:50',
            'cnpass' => 'required|min:6|max:50'
        ],[
            'cnpass.same' => 'Password did not matched!'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ]);
        } else {
            $user = DB::table('users')->where('email', $request->email)->whereNotNull
            ('token')->where('token', $request->token)->where('token_expire', '>',
            Carbon::now())->exists();

            if ($user) {
                User::where('email', $request->email)->update([
                    'password' => Hash::make($request->npass),
                    'token' => null,
                    'token_expire' => null
                ]);

                return response()->json([
                    'status' => 200,
                'messages' => 'New password updated§&nbsp;&nbsp;<a href="/">Login Now</a>'
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'messages' => 'Reset link expired! Request for a new reset password link!'
                ]);
            }


        }

    }

}

