<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Url;
use yii\db\Expression;

use app\models\Candidates;
use app\models\CandidatesFiles;
use app\models\Resumes;
use app\models\ResumesFaces;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CronController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
    
    public function actionGetupdates()
    {
        //gets one status of a face and document at a time
        $this->getFaceStatus();
        $this->getResumeStatus();
        
        /*$jobID = 'usw3p_a9d55b08-f0eb-44ee-80d3-ccb482ed4753';
        $hpStatus = Yii::$app->HpApi->getStatus($jobID);
        // var_dump($hpStatus['actions'][0]['result']['face']);
        $faceArray = $hpStatus['actions'][0]['result']['face'];
        if (count($faceArray) > 1) {
            $faceSizeArray = array();
            for ($ctr = 0; count($faceArray) > $ctr; $ctr++) {
                $faceSizeArray[$ctr] = $faceArray[$ctr]['width'] * $faceArray[$ctr]['height'];
            }
            
            $faceIndex = array_keys($faceSizeArray, max($faceSizeArray));
            $face = $faceArray[$faceIndex[0]];
        } else {
            $face = $faceArray[0];
        }
        
        var_dump($face);*/

        // $hpStatus = Yii::$app->HpApi->getStatus($jobID);
        // $addIndex = Yii::$app->HpApi->addToIndex($hpStatus['actions'][0]['result']['text_block'][0]['text']);
        // echo var_dump($hpStatus);
        // var_dump($addIndex);
    }
    
    public function getFaceStatus()
    {
        $resumeFacesModel = ResumesFaces::find()->where(array('finished_time' => null))->orderBy('id asc')->one();

        if ($resumeFacesModel) {
            $hpStatus = Yii::$app->HpApi->getStatus($resumeFacesModel->hp_job_id);
  
            if ($hpStatus['status'] == 'finished') {
                $faceArray = $hpStatus['actions'][0]['result']['face'];
                
                
                if (count($faceArray) > 1) {
                    $faceSizeArray = array();
                    for ($ctr = 0; count($faceArray) > $ctr; $ctr++) {
                        $faceSizeArray[$ctr] = $faceArray[$ctr]['width'] * $faceArray[$ctr]['height'];
                    }
                    
                    $faceIndex = array_keys($faceSizeArray, max($faceSizeArray));
                    $face = $faceArray[$faceIndex[0]];
                } else {
                    $face = $faceArray[0];
                }
                
                $resumeFacesModel->top_position = $face['top'];
                $resumeFacesModel->left_position = $face['left'];
                $resumeFacesModel->width = $face['width'];
                $resumeFacesModel->height = $face['height'];

                $resumeFacesModel->finished_time = new Expression('NOW()');
                
                // Crop the image
                $imageFilename = $resumeFacesModel->resumes->candidatesFiles->server_filename;
                $imageFullPath = '/home/ubuntu/workspace/basic/uploads/' . $resumeFacesModel->resumes->candidatesFiles->candidate_id;
                
                // Create image instances
                $src = imagecreatefromjpeg($imageFullPath . '/' . $imageFilename);
                $dest = imagecreatetruecolor($face['width'], $face['height']);
                
                // Copy
                imagecopy($dest, $src, 0, 0, $face['left'], $face['top'], $face['width'], $face['height']);
                
                // Output and free from memory
                header('Content-Type: image/gif');
                imagejpeg($dest, $imageFullPath . '/crop_' . $imageFilename);
                
                imagedestroy($dest);
                imagedestroy($src);
                
                $resumeFacesModel->save();
                

            }   
        }
    }
    
    public function getResumeStatus()
    {
        $resumeModel = Resumes::find()->where(array('finished_time' => null))->orderBy('id asc')->one();
        if ($resumeModel) {
            $hpStatus = Yii::$app->HpApi->getStatus($resumeModel->hp_job_id);
        
            if ($hpStatus['status'] == 'finished') {
                //['actions'][0]['result']['text_block'][0]['text']
                $resumeModel->finished_time = new Expression('NOW()');
                $resumeModel->save();
                
                
                $addIndex = Yii::$app->HpApi->addToIndex($hpStatus['actions'][0]['result']['text_block'][0]['text'], $resumeModel->candidate_file_id);
            }
        }
    }
}
