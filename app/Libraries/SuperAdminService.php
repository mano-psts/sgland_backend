<?php

namespace App\Libraries;

use Modules\Tenat\Entities\FaultReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Modules\Tenat\Entities\Tenat;
use App\Models\TenatContact;
use Mail;
use App\Models\EventLog;

class SuperAdminService {
    
    public function addTenat($item) {
        
        $result = Tenat::where(['company_email_address' => $item->company_email_address])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            // $registerationNumberInc = 1;
            // $registerNumber = Tenat::max('registration_number');
            // if(!empty($registerNumber)) {
            //     $registerationNumberInc = $registerNumber + 1;
            // }
            // else {
            //     $registerationNumberInc = $registerationNumberInc;
            // }

            $company_full_name = $item->company_full_name;
            $company_email_address = $item->company_email_address;
            $office_phone_number = $item->office_phone_number;
            $reception_unit_number = $item->reception_unit_number;
            $levels=$item->levels;
            $unit=$item->unit;
            $first_name=$item->first_name;
            $last_name=$item->last_name;
            $work_email_address=$item->work_email_address;
            $job_postion=$item->job_postion;
            $mobile_number=$item->mobile_number;
            $office_phone_number1=$item->office_phone_number;
            $assess_start_date=$item->assess_start_date;
            $office_unit_number=$item->office_unit_number;

            // $password = Str::random(12);
            // $hashed_random_password = Hash::make($password);

            $tenat = new Tenat;
            $tenat->company_full_name = $company_full_name; 
            // $tenat->password = $hashed_random_password;
            // $tenat->reference_number = $reference_number; 
            $tenat->company_email_address = $company_email_address; 
            $tenat->office_phone_number =$office_phone_number;
            $tenat->reception_unit_number = $reception_unit_number;
            $tenat->levels = $levels;
            $tenat->unit = $unit;
            $tenat->save();
            $lastInsert = $tenat->id;

            $tenatcontact = new TenatContact;
            $tenatcontact->tenat_id = $lastInsert;
            $tenatcontact->first_name = $first_name;
            $tenatcontact->last_name = $last_name;
            $tenatcontact->work_email_address = $work_email_address;
            $tenatcontact->job_postion = $job_postion;
            $tenatcontact->mobile_number = $mobile_number;
            $tenatcontact->office_phone_number = $office_phone_number1;
            $tenatcontact->assess_start_date = $assess_start_date;
            $tenatcontact->office_unit_number = $office_unit_number;
            $tenatcontact->save();
            $lastInserttenat = $tenatcontact->id;
            if($lastInsert) {
                $event = new EventLog;
                $event->action = "Add"; 
                $event->role = "Super Admin"; 
                $event->message = "Add tenat Account"; 
                $event->status = 200;
                $event->save();
            }

            // $email = $item->email;
            // $mail_details['name'] = $item->company_name;
            // $mail_details['email'] = $item->email;
            // $mail_details['password'] = $password;
        
            // Mail::send('mail.tenatAccount', $mail_details, function($message) use ($email) {
            //     $message->to($email)->subject
            //        ('Tenat Account Created Successfully!');
            // });

            $result = Tenat::where(['id' => $lastInsert])->get();
            $resulttenatcontact = TenatContact::where(['id' => $lastInserttenat])->get();
            $resultItem = [
                'company_full_name' => $result[0]->company_full_name,
                'company_email_address' => $result[0]->company_email_address,
                'office_phone_number' => $result[0]->office_phone_number,
                'reception_unit_number' => $result[0]->reception_unit_number,
                'levels' => $result[0]->levels,
                'unit' => $result[0]->unit,
                'tenat_id'=>$result[0]->tenat_id,
                'first_name'=>$resulttenatcontact[0]->first_name,
                'last_name'=>$resulttenatcontact[0]->last_name,
                'work_email_address'=>$resulttenatcontact[0]->work_email_address,
                'job_postion'=>$resulttenatcontact[0]->job_postion,
                'mobile_number'=>$resulttenatcontact[0]->mobile_number,
                'office_phone_number'=>$resulttenatcontact[0]->office_phone_number,
                'assess_start_date'=>$resulttenatcontact[0]->assess_start_date,
                'office_unit_number'=>$resulttenatcontact[0]->office_unit_number,


            ];

            return $resultItem;
        }
        else {
            return false;
        }
    }
}