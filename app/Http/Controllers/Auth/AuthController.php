<?php
  
namespace App\Http\Controllers\Auth;
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PasswordRequest;
use App\Mail\sendForgotPasswordToUserMailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterToAgencyAdminMailable;
use App\Mail\sendRegisterToUserMailable;
use Illuminate\Support\Str;
use App\Models\Page;
use Session;
use App\Models\User;
use App\Models\Customer;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\Activity;
use App\Models\Agent;
use App\Models\Voucher;
use App\Models\Notification;
use Hash;
use DB;
use SiteHelpers;
use Carbon\Carbon;


class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
		if (auth()->check()) {
			$user = Auth::user();
			if($user->role_id == '3'){
					return redirect('/');
				}else {
			return redirect()->intended('dashboard');
			}
		}
        $query = Notification::where('id','!=', null);
					
					$query->where(function ($q) {
					$q->where('status', '=', 1)
					->Where('type', '=', 1);
					});
            $notifications = $query->orderBy('updated_at', 'DESC')->paginate(10);

            $query = Notification::where('id','!=', null);
					
            $query->where(function ($q) {
            $q->where('status', '=', 1)
            ->Where('type', '=', 2);
            });
    $announcments = $query->orderBy('updated_at', 'DESC')->paginate(10);

    $query = Notification::where('id','!=', null);
					
    $query->where(function ($q) {
    $q->where('status', '=', 1);
    });
$allrecords = $query->orderBy('updated_at', 'DESC')->paginate(10);
        return view('auth.login', compact('allrecords','notifications','announcments'));
    }  
	
	public function thankyou()
    {
        return view('auth.thankyou');
    } 
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
		if (auth()->check()) {
			if(Auth::user()->role_id == '3'){
			return redirect('/');
			}else {
			return redirect()->intended('dashboard');
			}
		}
		
		$countries = Country::where('status', 1)->orderBy('name', 'ASC')->get();
        $countriesCode = Country::where('status', 1)->where('code','!=', null)->orderBy('id', 'ASC')->get();
        return view('auth.registration', compact('countries','countriesCode'));
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        
            $credentials = $request->only('email', 'password');
            $credentials['is_active'] = 1;
           
            if (Auth::attempt($credentials)) {
				//pr($credentials); die;
				$user = Auth::user();
				if($user->role_id == '3'){
					return redirect('/agent-vouchers/create');
				}else {
					 return redirect()->intended('dashboard')
                            ->withSuccess('You have Successfully loggedin.');
				}
            }else{
                $credentials = $request->only('email', 'password');
                if (Auth::attempt($credentials)) {
					$user = Auth::user();
					if($user->is_active==0){
						Auth::logout();
                    return redirect()->route('login')->with('error', 'Your account is inactive. We request you to kindly contact your account manager or customer support.');
					}
                }
            }

		return redirect()->route('login')->with('error', 'Oops! You have entered invalid credentials.');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {  
      $sameEmailPhone = $request->input('same_email_phone');
        $options['allow_img_size'] = 10;
        $request->validate([
			'company_name' => 'required|max:255|sanitizeScripts',
           'first_name' => 'required|max:255|sanitizeScripts|alpha',
            'last_name' => 'required|max:255|sanitizeScripts|alpha',
			'agency_mobile' => [
			'nullable',
			'numeric',
				function ($attribute, $value, $fail) use ($request) {
					if ($request->country_id == 94 && strlen($value) != 10) {
					$fail("Agency Mobile should be 10 digits.");
					}
				},
			],
			'mobile' => [
			'required',
			'numeric',
				function ($attribute, $value, $fail) use ($request) {
					if ($request->country_id == 94 && strlen($value) != 10) {
					$fail("The $attribute must be exactly 10 digits");
					}
				},
			],
            'address' => 'required',
			/* 'same_email_phone' => 'nullable',
			'agency_mobile' => 'required_if:same_email_phone,null',
			'agency_email' => 'required_if:same_email_phone,null', */
			'email' => 'required|max:255|sanitizeScripts|email|unique:users|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
			//'password' => 'required|min:8|max:255|sanitizeScripts',
			//'image' => 'nullable|mimes:jpeg,jpg,png|max:' . ($options['allow_img_size'] * 1024),  
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'postcode' => 'required',
			'agent_currency_id' => 'required',
			'trade_license_no_file' => 'nullable|mimes:jpeg,jpg,png,pdf|max:' . ($options['allow_img_size'] * 1024), 
			'pan_no_file' => 'nullable|mimes:jpeg,jpg,png,pdf|max:' . ($options['allow_img_size'] * 1024), 
			
        ], [
            'name.sanitize_scripts' => 'Invalid value entered for Name field.',
            'country_id.required' => 'The country field is required.',
            'state_id.required' => 'The state field is required.',
			'agent_currency_id.required' => 'The preferred currency field is required.',
        ]);


		$input = $request->all();
		
        $record = new User();
if(($request->input('country_id') == '94') && (strlen($request->input('mobile')) != '10'))
        return redirect('/')->with('success', 'Please Enter Valid Mobile No.');
		
		$destinationPath = public_path('/uploads/users/');
		if ($request->hasFile('pan_no_file')) {

           $destinationPath = public_path('/uploads/users/');
			$fileName = $input['pan_no_file']->getClientOriginalName();
			$file = request()->file('pan_no_file');
			$fileNameArr = explode('.', $fileName);
			$fileNameExt = end($fileNameArr);
			$newName = date('His').rand() . time() . '.' . $fileNameExt;
			$file->move($destinationPath, $newName);
            $record->pan_no_file = $newName;
		} 
		if ($request->hasFile('trade_license_no_file')) {

           $destinationPath2 = public_path('/uploads/users/');
			$fileName2 = $input['trade_license_no_file']->getClientOriginalName();
			$file2 = request()->file('trade_license_no_file');
			$fileNameArr2 = explode('.', $fileName2);
			$fileNameExt2 = end($fileNameArr2);
			$newName2 = date('His').rand() . time() . '.' . $fileNameExt2;
			$file2->move($destinationPath2, $newName2);
            $record->trade_license_no_file = $newName2;
		} 
		
		
		
        $record->name = $request->input('first_name');
        $record->lname = $request->input('last_name');
		if($sameEmailPhone == 1){
			$record->agency_mobile = $request->input('mobile');
			$record->agency_email = $request->input('email');
		} else {
			$record->agency_mobile = $request->input('agency_mobile');
			$record->agency_email = $request->input('agency_email');
		}
		$zone = SiteHelpers::getStateCityZone($request->input('state_id'),$request->input('city_id'));
        $record->mobile = $request->input('mobile');
		$record->email = $request->input('email');
        $record->country_code = $request->input('country_code');
        $record->agency_country_code = $request->input('agency_country_code');
		$record->company_name = $request->input('company_name');
		$record->department = '';
		$record->phone = '';
        $record->address = $request->input('address');
		$record->address_two = $request->input('address_two');
        $record->postcode = $request->input('postcode');
        $record->country_id = $request->input('country_id');
        $record->state_id = $request->input('state_id');
        $record->city_id = $request->input('city_id');
        $record->zone = $zone;
		$record->currency_id = $request->input('agent_currency_id');
		$record->pan_no = $request->input('pan_no');
		$record->trade_license_no = $request->input('trade_license_no');
		$record->trn_no = $request->input('trn_no');
        $record->is_active = '0';
		$record->agent_credit_limit = 0;
		$record->agent_amount_balance = 0;
        $record->created_by = '';
		$record->role_id = 3; 
        $record->password = bcrypt('123456');
		$record->ticket_only = 0;
		$record->sic_transfer = 0;
		$record->pvt_transfer = 0;
		$record->vat = 0;
        $record->save();
		
        $record->attachRole('3');
		
		$userCount = User::where("role_id",3)->count();
		$codeNumber  = $userCount + 1;
		$code = 'TA-700'.$codeNumber;
		$recordUser = User::find($record->id);
		$recordUser->code = $code;
		$recordUser->save();
		$admin = User::where("role_id",1)->first();
		$agentData['name'] =  $recordUser->name;
        $agentData['company'] =  $recordUser->company_name;
		$agentData['email'] =  $recordUser->email;
		$agentData['address'] =  $recordUser->address;
		$agentData['address_two'] =  $recordUser->address_two;
		$agentData['agency_mobile'] =  $recordUser->agency_mobile;
		$agentData['agency_email'] =  $recordUser->agency_email;
		$agentData['city'] =  @$recordUser->city->name;
		$agentData['state'] =  @$recordUser->state->name;
		$agentData['country'] =  @$recordUser->country->name;
		$userEmail =  $recordUser->email;
        $emails = [$admin->email];
		Mail::to($userEmail,'Registration Confirmation')->send(new sendRegisterToUserMailable($agentData));
		
        Mail::to($emails,'New Agency Registered.')->send(new RegisterToAgencyAdminMailable($agentData));
		
        return redirect('/registration')->with('success', 'Your Account has been Created Successfully.');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        $ppePer = 0;
        $userPer = 0;
        $ppePerUnAssi = 0;
        $expiringCPer = 0;
        $expiredCPer = 0;
        $validCPer = 0;

       $currentDate = Carbon::now()->format('Y-m-d');
       $expiringDate = Carbon::now()->addMonths(1)->format('Y-m-d');

        if(Auth::check()){
            
				$todayDate = date("Y-m-d");
				$userId = Auth::user()->id;
				$totalUserRecords = User::select('count(*) as allcount')->where('role_id',2)->count();
				$totalAgentRecords = User::select('count(*) as allcount')->where('role_id',3)->count();
				$totalSupplierRecords = User::select('count(*) as allcount')->where('role_id',9)->count();
				$totalCustomerRecords = Customer::select('count(*) as allcount')->count();
				$totalActivityRecords = Activity::select('count(*) as allcount')->count();
                $totalHotelRecords = Hotel::select('count(*) as allcount')->count();
				$currentDate = Carbon::now();
				$currentMonthStartDate = Carbon::now()->startOfMonth();
				$currentYear = Carbon::now()->year;

				$startDateOfApril = Carbon::createFromDate($currentYear, 4, 1);
				$endDateOfMarchNextYear = Carbon::createFromDate($currentYear + 1, 4, 1)->subDay();

				//$dateOneMonth = $currentDate->copy()->subMonth();
				$dateOneYearAgo = $currentDate->copy()->subYear();

				$vouchersCurrentDate = Voucher::select(
        DB::raw('COUNT(*) as totalVouchers'),
        DB::raw('SUM((SELECT SUM(totalprice) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2))) as totalVoucherActivityAmount'),
		DB::raw('SUM((SELECT SUM(adult) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2)))  as totalAdult'),
		DB::raw('SUM((SELECT SUM(child) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2))) as totalChild'),
		DB::raw('SUM((SELECT COUNT(id) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2))) as totalActivity'),
        DB::raw('SUM((SELECT SUM(discount_tkt) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2)))  as totalTktDis'),
        DB::raw('SUM((SELECT SUM(discount_sic_pvt_price) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2)))  as totalTrfDis')
    )
    ->where('status_main', '5')
    ->whereDate('booking_date',  $currentDate)
    ->first();
			
			$vouchersMonth =Voucher::select(
        DB::raw('COUNT(*) as totalVouchers'),
        DB::raw('SUM((SELECT SUM(totalprice) FROM  voucher_activity WHERE voucher_id = vouchers.id)) as totalVoucherActivityAmount'),
		DB::raw('SUM((SELECT SUM(adult) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2))) as totalAdult'),
		DB::raw('SUM((SELECT SUM(child) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2))) as totalChild'),
		DB::raw('SUM((SELECT COUNT(id) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2))) as totalActivity'),
        DB::raw('SUM((SELECT SUM(discount_tkt) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2)))  as totalTktDis'),
        DB::raw('SUM((SELECT SUM(discount_sic_pvt_price) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2)))  as totalTrfDis')
    )
    ->where('status_main', '5')
    ->whereDate('booking_date', '>=', $currentMonthStartDate)
    ->whereDate('booking_date', '<=', $currentDate)
    ->first();
			
			 $vouchersYear = Voucher::select(
        DB::raw('COUNT(*) as totalVouchers'),
        DB::raw('SUM((SELECT SUM(totalprice) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2))) as totalVoucherActivityAmount'),
		DB::raw('SUM((SELECT SUM(adult) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2))) as totalAdult'),
		DB::raw('SUM((SELECT SUM(child) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2))) as totalChild'),
		DB::raw('SUM((SELECT COUNT(id) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2))) as totalActivity'),
        DB::raw('SUM((SELECT SUM(discount_tkt) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2)))  as totalTktDis'),
        DB::raw('SUM((SELECT SUM(discount_sic_pvt_price) FROM  voucher_activity WHERE voucher_id = vouchers.id AND status NOT IN (1,2)))  as totalTrfDis')
    )
    ->where('status_main', '5')
    ->whereDate('booking_date', '>=', $startDateOfApril)
    ->whereDate('booking_date', '<=', $endDateOfMarchNextYear)
    ->first();
			
			  //print_r($vouchersCurrentDate);
			  //exit;
				
				if(Auth::user()->role_id == '3'){
					 return view('dashboard-agent', compact('totalUserRecords','totalAgentRecords','totalSupplierRecords','totalCustomerRecords','totalActivityRecords','totalHotelRecords'));
				}else{
					$query = Voucher::where('id','!=', null);
					$query->whereDate('booking_date', $todayDate);
					$query->where(function ($q) {
					$q->where('status', '=', 1)
					->orWhere('status', '=', 4)->orWhere('status', '=', 5);
					});
                    if(!empty(Auth::user()->zone)){
			
                        $query->where('zone', '=', Auth::user()->zone);
                    
        }
          
					$vouchers = $query->orderBy('booking_date', 'DESC')->paginate(50);
                return view('dashboard', compact('totalUserRecords','totalAgentRecords','totalSupplierRecords','totalCustomerRecords','totalActivityRecords','totalHotelRecords','vouchers','vouchersCurrentDate','vouchersMonth','vouchersYear'));
				}
           
        }
  
        //return redirect("login")->withSuccess('Opps! You do not have access');
		return redirect()->route('login')->with('error', 'Opps! You do not have access');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'is_active' => 1
      ]);
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
       // return Redirect('login');
		return redirect()->route('login');
    }
	
	public function changepassword(Request $request){
		$user = User::where('id',\Auth::user()->id)->first();
		if(Auth::user()->role_id == '3'){
		return view('changepassword-agent',compact('user'));
		} else {
			return view('changepassword',compact('user'));
		}
	}

    public function saveProfile(Request $request){
		$data = $request->all();
		$request->validate([
            'OldPassword'=>['required',function ($attribute, $value, $fail) use ($data) {
					if (!\Hash::check($value, \Auth::user()->password)) {
						return $fail(__('The old password is incorrect.'));
					}
					}],
            'password'=>'required|min:6',
			'confirmPassword' => 'required_with:password|same:password|min:6'
        ],
        [
            'password.regex' => "Password contains At least one uppercase, At least one digit and At least it should have 6 characters long"
        ]
        );
				
		$id = \Auth::user()->id;
		$user = User::where('id',$id)->first();
		$redirect = '/change-password';
							
		// Check password change conditions //
		$user->password = bcrypt($data['password']);
		$success_msg = 'Password has been Updated Successfully, Please login again.';
		$error_msg = 'Password not changed successfully, Please try again.';
		
		if($user->save()){
			\Session::flash('success', $success_msg);
            Auth::logout();
            return redirect()->route('login');
			//return redirect($redirect);
		}
		else{
			\Session::flash('error', $error_msg);
			return redirect($redirect);
		}
	}


	/**
	* admin reset password
	*/
	public function showResetForm()
    { 
        return view('auth.passwords.email');
    }
	
	/**
	* admin reset password email
	*/
	public function resetPassword(PasswordRequest $request)
    { 	
        
		$data = $request->all();
        
		$admin_details = DB::table('users')->where('email', $data['email'])->get();
		//print_r($admin_details); die('test');
		if(isset($admin_details[0]->id) && !empty($admin_details[0]->id)) {

            $token = Str::random(60);

            //$update_pass = DB::table('users')->where('id', $admin_details[0]->id)->update(['remember_token' => $token, 'is_active' => 0]);
            $update_pass = DB::table('users')->where('id', $admin_details[0]->id)->update(['remember_token' => $token]);

			/*$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+?";
		    $new_password = substr( str_shuffle( $chars ), 0, 7 ); 
			$h_newspassword = Hash::make($new_password);
			$update_pass = DB::table('users')->where('id', $admin_details[0]->id)->update(['password' => $h_newspassword]); */
			//Mail::to($data['email'],'Password details')
					 //->send(new sendForgotPasswordToUserMailable($admin_details[0], $new_password));
            //die;
			$admin = User::where("role_id",1)->first();
            Mail::to($data['email'],'Password Reset Link')->cc($admin->email)->send(new sendForgotPasswordToUserMailable($admin_details[0], $token));         
			
            return redirect()->route('resetpassword')->with('success', 'Success! password reset link has been sent to your email.');
		} else {
			$request->session()->flash('error', 'Email does not exists.');
			return redirect()->route('resetpassword');
		}
		
		
    }

    /**
     * Validate token for forgot password
     * @param token
     * @return view
     */
    public function forgotPasswordValidate($token)
    {
		//$email = $token;
		//return view('auth.passwords.change-password', compact('email'));
        //$user = User::where('remember_token', $token)->where('is_active', 0)->first();
        $user = User::where('remember_token', $token)->first();
        if ($user) {
            $email = $user->email;
            return view('auth.passwords.change-password', compact('email'));
        }
        \Session::flash('error', 'Password reset link is expired');
        return redirect()->route('resetpassword');
    }

    /**
     * Change password
     * @param request
     * @return response
     */
    public function updatePassword(Request $request) {
		
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password|min:6'
        ],
        [
            'password.regex' => "Password contains At least one uppercase, At least one digit and At least it should have 8 characters long"
        ]
    );

        $user = User::with('roles')->where('email', $request->email)->first();
        if ($user) {
           //$user['is_active'] = 0;
            $user['remember_token'] = '';
            $user['password'] = Hash::make($request->password);
            $user->save();
            //if(Auth::user()->role_id == '3'){
                return redirect()->route('login')->with('success', 'Success! password has been changed.');
                //return redirect()->route('thankyou')->with('success', 'Success! password has been changed. Please login from the app.');
           // }else{
                //return redirect()->route('login')->with('success', 'Success! password has been changed.');
            //}
            
        }
        return redirect()->route('forgot-password')->with('failed', 'Failed! something went wrong');
    }


    public function privacyPolicy()
    { 
        $page = Page::find(4);
        return view('auth.privacyPolicy',compact('page'));
    }

    public function updatePage()
    { 
        
        $query = Notification::where('id','!=', null);
					
        $query->where(function ($q) {
        $q->where('status', '=', 1)
        ->Where('type', '=', 1);
        });
$notifications = $query->orderBy('updated_at', 'DESC')->paginate(10);

$query = Notification::where('id','!=', null);
        
$query->where(function ($q) {
$q->where('status', '=', 1)
->Where('type', '=', 2);
});
$announcments = $query->orderBy('updated_at', 'DESC')->paginate(10);

$query = Notification::where('id','!=', null);
        
$query->where(function ($q) {
$q->where('status', '=', 1);
});
$allrecords = $query->orderBy('updated_at', 'DESC')->paginate(10);
return view('auth.updatePage', compact('allrecords','notifications','announcments'));
        
    }

    public function termsAndConditions()
    { 
        $page = Page::find(5);
        return view('auth.termsAndConditions',compact('page'));
    }
    public function paymentMethods()
    { 
       
        return view('auth.paymentMethods');
    }
}
