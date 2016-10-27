<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VendorBankAccounts extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected $table = 'vendor_bank_accounts';
    public $timestamps = false;

    public function saveBankDetails($account_holder_name, $account_number, $bank_name, $branch, $ifsc, $user_id){
    	$vendor = $this;
    	$vendor->account_holder_name = $account_holder_name;
    	$vendor->account_number = $account_number;
    	$vendor->bank_name = $bank_name;
    	$vendor->branch = $branch;
    	$vendor->ifsc = $ifsc;
    	$vendor->user_id = $user_id;
    	if($vendor->save()){
    		return $vendor->id;
    	}
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id' , 'id');
    }
}
