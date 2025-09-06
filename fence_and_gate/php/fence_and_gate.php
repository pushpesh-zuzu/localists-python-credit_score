<?php

$filename = 'sample_data_for_fence_and_gate.csv';
$fData=array();
if (($handle = fopen($filename, 'r')) !== false) {
    // Skip the first line (the header)
    fgetcsv($handle);
    // Loop through each line in the file
    
    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        
        $pData = [
            // "Location" => !empty($data[0]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[0])) : 'None',
            "Urgent" => !empty($data[2]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[2])) : '0',
            "High" => !empty($data[3]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[3])) : '0',
            "Verified" => !empty($data[4]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[4])) : '0',
            "Frequent" => !empty($data[5]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[5])) : '0',
            "Which category does the property fall under?" => !empty($data[6]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[6])) : 'Unknown',
            "What will the project consist of?" =>!empty($data[7]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[7])) : 'Unknown',
            "What is the intended material for the fence or gate?" => !empty($data[8]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[8])) : 'Unknown',
            "What is the desired height of the fence?" => !empty($data[9]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[9])) : 'Unknown',
            "What is the estimated length of the fence?" => !empty($data[10]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[10])) : 'Unknown',
            "How many access gates are to be included in the fence design?" => !empty($data[11]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[11])) : 'Unknown',
            "Should the professional be responsible for supplying the materials?" => !empty($data[12]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[12])) : 'Unknown',
            "Please indicate any supplementary fence-related services you may need?" => !empty($data[13]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[13])) : 'Unknown',
            "When would you like the work to start?" => !empty($data[14]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[14])) : 'Unknown',
            "How likely are you to hire a professional?" => !empty($data[15]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[15])) : 'Unknown',
            
        ];
        $output = getPrediction($pData);
        $pData['Location'] = !empty($data[1]) ? preg_replace(['/^,/', '/\?$/'], '', trim($data[1])) : 'None';
        $pData['LeadId'] = $data[0];
        $pData['prediction'] = number_format($output['prediction'],5);
        $pData['rounded_prediction'] = ceil($pData['prediction']);
        
        array_push($fData, $pData);
        echo "<pre>";
        print_r($pData);
    }
    fclose($handle);
} else {
    echo "Unable to open file.";
}



//save the data to csv file
$outputFilename = 'new_ques_ans_predicted_data_fence_and_gate.csv';
$fp = fopen($outputFilename, 'w');
if ($fp === false) {
    die("Error: Unable to open file for writing.");
}
if (!empty($fData) && is_array($fData)) {
    // Write headers (columns)
    fputcsv($fp, array_keys($fData[0]));
	foreach ($fData as $row) {
        fputcsv($fp, $row);
    }
    echo "Data successfully written to $outputFilename";
} else {
    echo "Error: No data to write.";
}
fclose($fp);


// Sample data to send to the API
// 


function getPrediction($data){
    $url = 'http://127.0.0.1:5000/predict/fence_and_gate';
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
    
    return $response = json_decode($result, true);
    if($response['success']){
        return $response;
    }else{
        return 0;
    }
}
