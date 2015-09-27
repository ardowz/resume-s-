<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "candidates_files".
 *
 * @property integer $id
 * @property integer $candidate_id
 * @property string $uploaded_time
 * @property string $hp_reference_id
 * @property string $original_filename
 * @property string $server_filename
 */
class CandidatesFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidates_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidate_id'], 'required'],
            [['candidate_id'], 'integer'],
            [['uploaded_time'], 'safe'],
            [['hp_reference_id'], 'string', 'max' => 50],
            [['original_filename', 'server_filename'], 'string', 'max' => 70]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'candidate_id' => 'Candidate ID',
            'uploaded_time' => 'Uploaded Time',
            'hp_reference_id' => 'Hp Reference ID',
            'original_filename' => 'Original Filename',
            'server_filename' => 'Server Filename',
        ];
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'uploaded_time',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    public function getCandidates() {
        return $this->hasOne(Candidates::className(), ['id' => 'candidate_id']);
    }
    
    public function getResumes() {
        return $this->hasOne(Resumes::className(), ['candidate_file_id' => 'id']);
    }
}
