<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "resumes".
 *
 * @property integer $id
 * @property integer $candidate_file_id
 * @property string $start_time
 * @property string $hp_job_id
 * @property string $finished_time
 * @property string $extracted_text
 */
class Resumes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resumes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidate_file_id'], 'required'],
            [['candidate_file_id'], 'integer'],
            [['start_time', 'finished_time'], 'safe'],
            [['extracted_text'], 'string'],
            [['hp_job_id'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'candidate_file_id' => 'Candidate File ID',
            'start_time' => 'Start Time',
            'hp_job_id' => 'Hp Job ID',
            'finished_time' => 'Finished Time',
            'extracted_text' => 'Extracted Text',
        ];
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'start_time',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    public function getCandidatesFiles() {
        return $this->hasOne(CandidatesFiles::className(), ['id' => 'candidate_file_id']);
    }
    
    public function getResumesFaces() {
        return $this->hasOne(ResumesFaces::className(), ['resume_id' => 'id']);
    }
}
