<?php

$filename = 'sample_data_for_prediction_business_consulting.csv';
$fData=array();
if (($handle = fopen($filename, 'r')) !== false) {
    // Skip the first line (the header)
    fgetcsv($handle);
    // Loop through each line in the file
    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        
        $pData = [
            "Location" => !empty($data[1]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[0])) : null,
            "Urgent" => !empty($data[2]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[1])) : null,
            "High" => !empty($data[3]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[2])) : null,
            "Verified" => !empty($data[4]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[3])) : null,
            "Frequent" => !empty($data[5]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[5])) : null,
            "Have you used business consulting services before?" => !empty($data[6]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[6])) : null,
            "How long has the business been running?" =>!empty($data[7]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[7])) : null,
            "What is the business annual turnover/sales?" => !empty($data[8]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[8])) : null,
            "How many employees do you have?" => !empty($data[9]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[9])) : null,
            "What industry is your business in?" => !empty($data[10]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[10])) : null,
            "Which type(s) of consulting are you interested in?" => !empty($data[11]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[11])) : null,
            "What are your goals for this service?" => !empty($data[12]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[12])) : null,
            "How long do you need a consultant?" => !empty($data[13]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[13])) : null,
            "How would you like to work with the consultant?" => !empty($data[14]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[14])) : null,
            
        ];
        // $output = getPrediction($pData);
        // $pData['prediction'] = number_format($output['prediction'],5);
        // $pData['rounded_prediction'] = ceil($pData['prediction']);
        
        echo "<pre>";
        print_r($pData);
        exit;
    }
    fclose($handle);
} else {
    echo "Unable to open file.";
}



//save the data to csv file
// $outputFilename = 'predicted_data_business_culsulting_3.csv';
// $fp = fopen($outputFilename, 'w');
// if ($fp === false) {
//     die("Error: Unable to open file for writing.");
// }
// if (!empty($fData) && is_array($fData)) {
//     // Write headers (columns)
//     fputcsv($fp, array_keys($fData[0]));
// 	foreach ($fData as $row) {
//         fputcsv($fp, $row);
//     }
//     echo "Data successfully written to $outputFilename";
// } else {
//     echo "Error: No data to write.";
// }
// fclose($fp);


// Sample data to send to the API
// 


function getPrediction($data){
    $url = 'http://127.0.0.1:5000/predict';
    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        ]
    ];
    
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    
    if ($result === FALSE) {
        die('Error calling API');
    }
    
    return $response = $result;//json_decode($result, true);
    if($response['success']){
        return $response;
    }else{
        return 0;
    }
}
