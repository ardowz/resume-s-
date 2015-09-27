<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

use app\models\Candidates;
use app\models\CandidatesFiles;
use app\models\Resumes;
use app\models\ResumesFaces;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    
    public function actionNewcandidateid()
    {
        return $this->render('newcandidateid');
    }

    public function actionUpload()
    {
        $candidateID = intval($_POST['candidateID']);
        
        $Candidate = Candidates::findOne($candidateID);
        if (!$Candidate) {
            echo 0; die;
        }
        
        $finalDir = '/home/ubuntu/workspace/basic/uploads/' . $Candidate->id;
        
        if (!is_dir($finalDir)) {
            mkdir($finalDir);
        }
    
        $CandidatesFiles = new CandidatesFiles;
        $CandidatesFiles->candidate_id = $Candidate->id;
        $CandidatesFiles->original_filename = basename($_FILES['uploadedFile']['name']);
        $CandidatesFiles->server_filename = time() . "_" . $CandidatesFiles->original_filename;
        
        if(move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $finalDir . "/" . $CandidatesFiles->server_filename)) {
            // HP Store object
            $fileUrl = Url::to('@web/uploads/' . $Candidate->id . '/' . $CandidatesFiles->server_filename, true);
            $CandidatesFiles->hp_reference_id = Yii::$app->HpApi->storeObject($fileUrl);
            $CandidatesFiles->save();
            
            // Resumes object
            $Resumes = new Resumes;
            $Resumes->candidate_file_id = $CandidatesFiles->id;
            
            // HP OCR
            $Resumes->hp_job_id = Yii::$app->HpApi->ocrDocument($CandidatesFiles->hp_reference_id);
            $Resumes->save();
            
            // Resumes faces object
            $ResumesFaces = new ResumesFaces;
            $ResumesFaces->resume_id = $Resumes->id;
            
            // HP Face Detection
            $ResumesFaces->hp_job_id = Yii::$app->HpApi->faceDetection($CandidatesFiles->hp_reference_id);
            $ResumesFaces->save();
            
            echo 1;
        } else{
            echo 0;
        }
    }
    
    public function actionSearch()
    {
        $return = array();
        $results = Yii::$app->HpApi->queryTextIndex($_POST['query']);
        
        if ($results) {
            foreach ($results['documents'] as $document) {
                $candidate_file_id = str_replace('candidateFileID_', '', $document['title']);
                $Resumes = Resumes::find()->where(array('candidate_file_id' => $candidate_file_id))->one();

                if ($Resumes) {
                    $CandidatesFiles = $Resumes->candidatesFiles;
                    $ResumesFaces = $Resumes->resumesFaces;
                    
                    if ($CandidatesFiles && $ResumesFaces) {
                        if ($ResumesFaces->width) {
                            $finalImage = '/home/ubuntu/workspace/basic/uploads/' . $CandidatesFiles->candidate_id . '/crop_' . $CandidatesFiles->server_filename;
                            
                            if (file_exists($finalImage)) {
                                $return[$CandidatesFiles->candidate_id][] = array(
                                    'content' => $document['content'],
                                    'url_image' => Url::to('@web/uploads/' . $CandidatesFiles->candidate_id . '/crop_' . $CandidatesFiles->server_filename, true),
                                    'face_top_position' => $ResumesFaces->top_position,
                                    'face_left_position' => $ResumesFaces->left_position,
                                    'face_width' => $ResumesFaces->width,
                                    'face_height' => $ResumesFaces->height,
                                    'resumes_id' => $ResumesFaces->resume_id
                                );
                            }
                        }
                    }
                }
            }
        }
        
        $return = json_encode($return);
        echo $return;
    }
    
    public function actionIndex()
    {
        return $this->render('apiindex');
    }
    

}
