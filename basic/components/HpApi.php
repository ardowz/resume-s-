<?php
namespace app\components;
 
use Yii;
use yii\base\Object;
 
class HpApi extends Object
{
    public function storeObject($url)
    {
        $urlEncoded = urlencode($url);
        $apiKey = Yii::$app->params['HpIdolOnDemand']['ApiKey'];
        $apiEndpoint = 'https://api.idolondemand.com/1/api/sync/storeobject/v1';
        
        $finalUrl = $apiEndpoint . "?url=" . $urlEncoded . "&apiKey=" . $apiKey;
        
        //  Initiate curl
        $ch = curl_init();
        
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        
        // Execute
        $result = curl_exec($ch);
        
        // Closing
        curl_close($ch);
        
        // Transform the result to array
        $result = json_decode($result, true);
        
        return $result['reference'];
    }
    
    public function ocrDocument($reference_id)
    {
        $apiKey = Yii::$app->params['HpIdolOnDemand']['ApiKey'];
        $apiEndpoint = 'https://api.idolondemand.com/1/api/async/ocrdocument/v1';
        
        $finalUrl = $apiEndpoint . "?reference=" . $reference_id . "&mode=scene_photo&apiKey=" . $apiKey;
        
        //  Initiate curl
        $ch = curl_init();
        
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        
        // Execute
        $result = curl_exec($ch);
        
        // Closing
        curl_close($ch);
        
        // Transform the result to array
        $result = json_decode($result, true);
        
        return $result['jobID'];
    }
    
    public function faceDetection($reference_id)
    {
        $apiKey = Yii::$app->params['HpIdolOnDemand']['ApiKey'];
        $apiEndpoint = 'https://api.idolondemand.com/1/api/async/detectfaces/v1';
        
        $finalUrl = $apiEndpoint . "?reference=" . $reference_id . "&additional=false&apiKey=" . $apiKey;
        
        //  Initiate curl
        $ch = curl_init();
        
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        
        // Execute
        $result = curl_exec($ch);
        
        // Closing
        curl_close($ch);
        
        // Transform the result to array
        $result = json_decode($result, true);
        
        return $result['jobID'];
    }
    
    public function getStatus($jobID)
    {
        $apiKey = Yii::$app->params['HpIdolOnDemand']['ApiKey'];
        $apiEndpoint = 'https://api.idolondemand.com/1/job/status/';
        
        $finalUrl = $apiEndpoint . $jobID ."?apiKey=" . $apiKey;
        
        //  Initiate curl
        $ch = curl_init();
        
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        
        // Execute
        $result = curl_exec($ch);
        
        // Closing
        curl_close($ch);
        
        // Transform the result to array
        $result = json_decode($result, true);
        
        return $result;
    }
    
    
    public function addToIndex($text, $candidateID)
    {
        $apiKey = Yii::$app->params['HpIdolOnDemand']['ApiKey'];
        $apiEndpoint = 'https://api.idolondemand.com/1/api/sync/addtotextindex/v1';
        
        
        $jsonInput = array(
            'document' => array(
                 (object) array('title' => 'candidateFileID_'.$candidateID, 'content' => $text)
                )
            );
            
        $jsonString = urlencode(json_encode($jsonInput));
        
        $finalUrl = $apiEndpoint ."?json=". $jsonString ."&index=resumes&apiKey=" . $apiKey;
        
        //  Initiate curl
        $ch = curl_init();
        
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        
        // Execute
        $result = curl_exec($ch);
        
        // Closing
        curl_close($ch);
        
        // Transform the result to array
        $result = json_decode($result, true);
        
        return $result;
    }
    
    public function queryTextIndex($text)
    {
        $apiKey = Yii::$app->params['HpIdolOnDemand']['ApiKey'];
        $apiEndpoint = 'https://api.idolondemand.com/1/api/sync/querytextindex/v1';
        
        // Wildcard to the text
        $text = "*" . $text . "*";
        $finalUrl = $apiEndpoint ."?text=". json_encode($text) ."&indexes=resumes&print=all&apiKey=" . $apiKey;
        
        //  Initiate curl
        $ch = curl_init();
        
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        
        // Execute
        $result = curl_exec($ch);
        
        // Closing
        curl_close($ch);
        
        // Transform the result to array
        $result = json_decode($result, true);
        
        return $result;
    }
}