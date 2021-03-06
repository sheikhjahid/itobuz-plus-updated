<?php

namespace App\Repositories;
use App\Contracts\LeaveInterface;
use App\Leave;
use App\User;
use App\Policy;
use DB;

class LeaveRepository implements LeaveInterface
{
	protected $leave;
	protected $policy;
	protected $user;
	public function __construct(Leave $leave, Policy $policy, User $user)
	{
		$this->leave = $leave;
		$this->policy = $policy;
		$this->user = $user;
	}

	public function getTypeData()
	{
		try
		{
			return $this->policy->all();
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}

	public function getTypeDataPagination()
	{
		try
		{
			return $this->policy->paginate(5);
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}

	public function getPolicyById($id)
	{
		try
		{
			return $this->policy->with('user')->find($id);
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}

	public function searchUserData($id, $request)
	{
		try
		{
			$leaveData = $this->policy->with('user')->find($id);
			foreach($leaveData->user as $user)
			{
				$userdata = $this->user->where('name','LIKE',"%{$request}%")->get();
			}
			return $userdata;
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}

	public function updatePolicyData($id, $request)
	{
		DB::beginTransaction();
		try
		{
			$policyUpdate = $this->policy->find($id)->update($request);
			DB::commit();
			return $policyUpdate; 
		}
		catch(\Exception $e)
		{
			DB::rollback();
			return $e->getMessage();
		}
	}

	public function createPolicyData($request)
	{
		DB::beginTransaction();
		try
		{
			$policyCreate = $this->policy->create($request);
			DB::commit();
			return $policyCreate;
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	} 

	public function deletePolicyData($id)
	{
		try
		{
			$policyDelete = $this->policy->where('id',$id)->update(['status'=>0]);
			return $policyDelete;
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}

	public function recoverPolicyData($id)
	{
		try
		{
			$policyRecover = $this->policy->where('id',$id)->update(['status'=>1]);
			return $policyRecover;
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}

	public function getPendingLeaveData()
	{
		try
		{
			return $this->leave->where('status',1)->with('user','policy')->paginate(5);
		}
		catch(\Excpetion $e)
		{
			return $e->getMessage();
		}
	}

	public function getApprovedLeaveData()
	{
		try
		{
			return $this->leave->where('status',2)->with('user','policy')->paginate(5);
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}

	public function getRejectedLeaveData()
	{
		try
		{
			return $this->leave->where('status',0)->with('user','policy')->paginate(5);
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}

	public function createLeaveData($request)
	{
		DB::beginTransaction();
		try
		{
			$createLeaveData = $this->leave->create($request);
			DB::commit();
			return $createLeaveData;
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}

	public function getLeaveDataById($id)
	{
		try
		{
			$result = $this->leave->with('user','policy')->find($id);
			return $result;
		}
		catch(\Exception $e)
		{
			return $e->getMessage();
		}
	}
}