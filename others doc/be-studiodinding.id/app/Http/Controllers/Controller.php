<?php

namespace App\Http\Controllers;

use App\Models\CustomerInventoryModel;
use App\Models\CustomerModel;
use Hashids;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    

    public function encode($id) {
        try{
            if($id) return Hashids::encode($id);

        } catch (\Exception $e) {
            return null;
        }
    }

    public function decode($id) {
        try{
            if($id) return Hashids::decode($id)[0];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function generateCodeCustomer($customerGroupCode) {
        $prefix = $customerGroupCode . date('ym');
        $customer = CustomerModel::where('code_customer', 'like', '%' . $prefix . '%')
                ->orderBy('code_customer', 'desc')
                ->first();
        if(!$customer) {
            return $prefix . '001';
        }
        
        do {
            $last_number = substr($customer->code_customer, -3) + 1;
            if($last_number < 100) $last_number = '0' . (int)$last_number;
            if($last_number < 10) $last_number = '00' . (int)$last_number;
            $kode_exists = CustomerModel::where('code_customer', $last_number)->exists();
        } while ($kode_exists);

        return $prefix . $last_number;
    }

    public function generateCodeBarcode() {
        $prefix = 'INV' . date('ym');
        $inventory = CustomerInventoryModel::where('kode_barcode', 'like', '%' . $prefix . '%')
                ->orderBy('kode_barcode', 'desc')
                ->first();
        if(!$inventory) {
            return $prefix . '001';
        }
        
        do {
            $last_number = substr($inventory->kode_barcode, -3) + 1;
            if($last_number < 100) $last_number = '0' . (int)$last_number;
            if($last_number < 10) $last_number = '00' . (int)$last_number;
            $kode_invoice_exists = CustomerInventoryModel::where('kode_barcode', $last_number)->exists();
        } while ($kode_invoice_exists);

        return $prefix . $last_number;
    }
}
