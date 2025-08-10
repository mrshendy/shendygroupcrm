<?php
namespace App\Traits\api;
trait ApiresponseTrait
{


    public function apiresponse($Data=null,$message=null,$status=null)
    {

      if($Data)
      {
        $array =[
            'Data'=>$Data,
            'message'=>$message,
            'status'=>$status
      ];
        return response($array,$status);
      }else
      {
        $array =[
            'Data'=>'Null',
            'message'=>'The Data Not Found',
            'status'=>401
      ];
        return response($array,$status);
      }


    }
    public function apiresettings($Types_of_Zakat=null,$types_of_charity=null,$types_of_campaigine=null,$type_of_sacrifice=null,$distribution_method=null,$currency=null,$message=null,$status=null)
    {

      if( $Types_of_Zakat!=''or $currency!='' or $types_of_charity!='' or $types_of_campaigine!=''  or $type_of_sacrifice!=''or $distribution_method!='')
      {
        $array =[

            'types_of_Zakat'=>$Types_of_Zakat,
            'types_of_charity'=>$types_of_charity,
            'types_of_campaigine'=>$types_of_campaigine,
            'type_of_sacrifice'=>$type_of_sacrifice,
            'distribution_method'=>$distribution_method,
            'currency'=>$currency,
            'message'=>$message,
            'status'=>$status
      ];
        return response($array,$status);
      }else
      {
        $array =[
            'Data'=>'Null',
            'message'=>'The Data Not Found',
            'status'=>401
      ];
        return response($array,$status);
      }


    }
   public function Dashbord_ApiresponseTrait($count_request=null,$count_Offers=null,$count_Requests_resved=null,$count_Request_end=null,$count_Request_in_progress=null,$count_Request_canceled=null,$count_profits=null,$related=null,$message=null,$status=null)
    {

      if($count_request!='' or $count_Offers!='' or $count_Requests_resved!=''
      or $count_Request_in_progress!='' or $count_Request_canceled!=''or $count_Request_end!=''or $count_profits!='' or $related!='')
      {
        $array =[
            'count_request'=>$count_request,
            'count_Offers'=>$count_Offers,
            'count_Requests_received'=>$count_Requests_resved,
            'count_Request_in_progress'=>$count_Request_in_progress,
            'count_Request_canceled'=>$count_Request_canceled,
            'count_Request_end'=>$count_Request_end,
            'count_profits'=>$count_profits,
            'related'=>$related,
            'message'=>$message,
            'status'=>$status
      ];
        return response($array,$status);
      }else
      {
        $array =[
            'Data'=>'Null',
            'message'=>'The Data Not Found',
            'status'=>401
      ];
        return response($array,$status);
      }


    }
    public function apirerequest($Requests_select_all=null,$Requests_select=null,$message=null,$status=null)
    {

      if($Requests_select_all!='' or $Requests_select!='' )
      {
        $array =[
            'All_orders'=>$Requests_select_all,
            'Special_Requests'=>$Requests_select,
            'message'=>$message,
            'status'=>$status
      ];
        return response($array,$status);
      }else
      {
        $array =[
            'Data'=>'Null',
            'message'=>'The Data Not Found',
            'status'=>401
      ];
        return response($array,$status);
      }


    }
    public function apireRequests_get_details($patient_data=null,$Transfer_data=null,$Offers=null,$files=null,$message=null,$status=null)
    {

      if($patient_data!='' or $Transfer_data!='' or $files!=''or $Offers!='' )
      {
        $array =[
            'patient_data'=>$patient_data,
            'Transfer_data'=>$Transfer_data,
            'Offers'=>$Offers,
            'files'=>$files,
            'message'=>$message,
            'status'=>$status
      ];
        return response($array,$status);
      }else
      {
        $array =[
            'Data'=>'Null',
            'message'=>'The Data Not Found',
            'status'=>401
      ];
        return response($array,$status);
      }


    }
    public function apiresponse_Saved_successfully($message=null,$status=null)
    {

      if($status)
      {
        $array =[
          'message'=>$message,
          'status'=>$status
        ];
         return response($array,$status);
      }else
      {
        $array =[
          'message'=>'Error No Status',
          'status'=>404
        ];
         return response($array,$status);
      }

    }
    public function apiresponse_error($message=null,$status=null)
    {

      if($status)
      {
        $array =[
          'message'=>$message,
          'status'=>$status
        ];
         return response($array,$status);
      }else
      {
        $array =[
          'message'=>'Error No Status',
          'status'=>404
        ];
         return response($array,$status);
      }

    }
}
?>