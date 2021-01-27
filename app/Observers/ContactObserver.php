<?php

namespace App\Observer;

use App\Contact;

class ContactObserver
{
    public function creating(Contact $contact)
    {
        $country=$contact->country;
        $city=$contact->city;
        $state=$contact->state;
        $pincode=$contact->pincode;
        $address=$contact->address_line_1;
        $countryName="";
        $statename="";
        $cityname="";
        if($country){
            $countryName=\App\Countries::find($country)->sortname;

        }
        if($state){
            $statename=\App\States::find($state)->name;
        }
        if($city){
            $cityname=\App\Cities::find($city)->count()>0?\App\Cities::find($city)->name:$city;
        }
        $address.=",".$cityname.",".$statename.",".$countryName.",".$pincode;
        $query=htmlspecialchars("");
        $client = new \GuzzleHttp\Client();
        $res = $client->get("https://maps.google.com/maps/api/geocode/json?key=".env('GOOGLE_API_KEY')."&address=".$address);
        $data = (string) $res->getBody();

        $data = json_decode($data);
        $add_array  = $data->results;
        if(isset($add_array[0])){
             $geo=$add_array[0];
             $lat=$geo->geometry->location->lat;
             $lng=$geo->geometry->location->lng;
             if($lat && $lng){
                 $contact->latitude=$lat;
                 $contact->longitude=$lng;
                 //$contact->save();
             }
         }

    }
    public function updating(Contact $contact)
    {
        $country=$contact->country;
        $city=$contact->city;
        $state=$contact->state;
        $pincode=$contact->pincode;
        $address=$contact->address_line_1;
        $countryName="";
        $statename="";
        $cityname="";
        if($country){
            $countryName=\App\Countries::find($country)->sortname;

        }
        if($state){
            $statename=\App\States::find($state)->name;
        }
        if($city){
            $cityname=\App\Cities::find($city)->count()>0?\App\Cities::find($city)->name:$city;
        }
        $address.=",".$cityname.",".$statename.",".$countryName.",".$pincode;

        $client = new \GuzzleHttp\Client();
        $res = $client->get("https://maps.google.com/maps/api/geocode/json?key=".env('GOOGLE_API_KEY')."&address=".$address);
        if($res){
        $data = (string) $res->getBody();

        $data = json_decode($data);
        $add_array  = $data->results;
        if(isset($add_array[0])){
             $geo=$add_array[0];
             $lat=$geo->geometry->location->lat;
             $lng=$geo->geometry->location->lng;
             if($lat && $lng){
                 $contact->latitude=$lat;
                 $contact->longitude=$lng;
                // $contact->save();
             }
         }
        }
    }

}
