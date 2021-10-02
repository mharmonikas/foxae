<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Admin\AdminModel;
use Illuminate\Support\Facades\DB;
use Hash;
use File;
use Session;
use App\Admin; 
class AdmindemoController extends Controller
{
	
	public function __construct(AdminModel $AdminModel) {
        $this->AdminModel = $AdminModel;
		
    }
	
	public function checklogin(){
		$intAdminID = Session::get('intAdminID');	
		 if(empty($intAdminID)){		 
		
			 return $this->logout();
		 }
	}

	public function editmastertag($id){
		echo 'Rahul';
	// $users = DB::select('select * from tbl_MasterTag where IntId ="'.$id.'"');
 //    return view('stud_update',['users'=>$users]);

		 
	}


	

}