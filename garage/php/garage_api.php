<?php

$filename = 'sample_data_for_prediction_garage.csv';
$fData=array();
if (($handle = fopen($filename, 'r')) !== false) {
    // Skip the first line (the header)
    fgetcsv($handle);
    // Loop through each line in the file
    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        
        $pData = [
            	"Location" => preg_replace(['/^,/', '/\?$/'], '', $data[0]),
                "Urgent" => preg_replace(['/^,/', '/\?$/'], '', $data[1]),
                "High" => preg_replace(['/^,/', '/\?$/'], '', $data[2]),
                "Verified" => preg_replace(['/^,/', '/\?$/'], '', $data[3]),
                "Frequent" => preg_replace(['/^,/', '/\?$/'], '', $data[5]),
            	"Additional" => preg_replace(['/^,/', '/\?$/'], '', $data[4]),
                "Where is the existing garage located?" => preg_replace(['/^,/', '/\?$/'], '', $data[6]),
                "How big is the garage to be converted?" =>preg_replace(['/^,/', '/\?$/'], '', $data[7]),
                "What are you planning to use the converted garage for?" => preg_replace(['/^,/', '/\?$/'], '', $data[8]),
                "Do you have plans drawn up?" => preg_replace(['/^,/', '/\?$/'], '', $data[9]),
                "What is the planning permission status for this job?" => preg_replace(['/^,/', '/\?$/'], '', $data[10]),
                "How likely are you to make a hiring decision?" => preg_replace(['/^,/', '/\?$/'], '', $data[11]),
                
            ];
            $pData['prediction'] = number_format(getPrediction($pData),5);
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
$outputFilename = 'predicted_data_garage.csv';
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
    
    $response = json_decode($result, true);
    if($response['success']){
        return $response['prediction'];
    }else{
        return 0;
    }
}
