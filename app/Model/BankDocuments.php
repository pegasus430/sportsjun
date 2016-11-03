<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BankDocuments extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected $table = 'bank_documents';
    public $timestamps = false;
    protected $fillable = ['vendor_bank_account_id', 'name', 'location'];

    public function saveBankDocs($vendor_bank_account_id, $name, $location){
        $bankDocs = $this;
        $bankDocs->vendor_bank_account_id = $vendor_bank_account_id;
        $bankDocs->name = $name;
        $bankDocs->location = $location;
        if($bankDocs->save()){
            return $bankDocs->id;
        }
    }
    
}
