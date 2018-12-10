<?php


// config
$template_id = 'd-155a815710dd438e9cbe3fbdfa57e2c3';
$authorization = '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';
$emailFrom = 'kurskiy.ifc@gmail.com';
$emailTo = 'Albina.kurskaya@gmail.com';


$curl = curl_init();


curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => '{
    "personalizations":[
       {
          "to":[
             {
                "email":"' . $emailTo . '",
                "name":"Albina Kurskaya"
             }
          ],
          "dynamic_template_data":{
             "date":"' . $date . '",
             "time":"' . $time . '",
             "user":"' . $user . '",
             "comment":"' . $comment . '",
             "repeatable":"' . $repeatable . '"
          }
       }
    ],
    "from":{
       "email":"' . $emailFrom . '",
       "name":"Albi Yoga"
    },
    "template_id":"' . $template_id . '"
 }',
    CURLOPT_HTTPHEADER => array(
        "authorization: Bearer " . $authorization . "",
        "content-type: application/json"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

//if ($err) {
//    echo "cURL Error #:" . $err;
//} else {
//    echo $response;
//}


/*
personalizations":[
       {
          "to":[
             {
                "email":"'.$email.'",
                "subject":"hello from php"
             }
          ],
          "dynamic_template_data":{
             "firstName":"'.$name.'"
          }
       }
    ]
*/