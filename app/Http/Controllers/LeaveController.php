<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LeaveTypeRequest;
use App\Contracts\LeaveInterface;

class LeaveController extends Controller
{
    protected $leaveInterface;
    public function __construct(LeaveInterface $leaveInterface)
    {
    	$this->leaveInterface = $leaveInterface;
    }

    public function getAllTypes()
    {
    	$leaveData = $this->leaveInterface->getTypeData();
    	return view('Leave.allTypes')->with('leavedata',$leaveData);
    }

    public function getLeaveById($id)
    {
        $leaveData = $this->leaveInterface->getLeaveDataById($id);
        return view('Leave.singleLeave')->with('leavedata',$leaveData);
    }

    public function searchLeaveUser(Request $request)
    {
        try
        {
            if($request->ajax())
            {
                $id = $request->get('id');
                $name = $request->get('name');
                $searchUser = $this->leaveInterface->searchUserData($id,$name);
                if(count($searchUser)!=0)
                {    
                  return $searchUser;
                }
                else
                {
                    return "No data found!!";
                }
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function editPolicy($id)
    {
        $leaveData = $this->leaveInterface->getLeaveDataById($id);
        return view('Leave.editLeaveType')->with('leavedata',$leaveData);
    }

    public function updatePolicy($id, LeaveTypeRequest $request)
    {
        $requestData['name'] = $request->name;
        $checkUpdatedData = $this->leaveInterface->updatePolicyData($id, $requestData);
        if($checkUpdatedData==1)
        {
            return redirect('policies')->with('update_success','Policy data updated successfully!!');
        }
        else
        {
            return redirect('policies')->with('update_failure','Unable to update policy data');
        }
    }

    public function createForm()
    {
        return view('Leave.createPolicy');
    }
    public function createPolicy(LeaveTypeRequest $request)
    {
        $requestData = $request->all();
        $checkCreatedData = $this->leaveInterface->createPolicyData($requestData);
        if($checkCreatedData)
        {
            return redirect('policies')->with('create_success','Policy data created!!');
        }
        else
        {
            return redirect('policies')->with('create_failure','Unable to create policy data!!');
        }
    }

    public function deletePolicy($id)
    {
        $checkDeletedData = $this->leaveInterface->deletePolicyData($id);
        if($checkDeletedData==1)
        {
            return redirect('policies')->with('delete_success','Policy data deleted successfully!!');
        }
        else
        {
            return redirect('policies')->with('delete_failure','Unable to delete policy data!!');
        }
    }

    public function recoverPolicy($id)
    {
        $checkRecoveredData = $this->leaveInterface->recoverPolicyData($id);
        if($checkRecoveredData==1)
        {
            return redirect('policies')->with('recover_success','Policy data recovered successfully!!');
        }
        else
        {
            return redirect('policies')->with('recover_failure','Unable to recover policy data');
        }
    }

}