<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "resumes_faces".
 *
 * @property integer $id
 * @property integer $resume_id
 * @property string $start_time
 * @property string $hp_job_id
 * @property string $finished_time
 * @property integer $top_position
 * @property integer $left_position
 * @property integer $width
 * @property integer $height
 */
class ResumesFaces extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resumes_faces';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resume_id', 'top_position', 'left_position', 'width', 'height'], 'integer'],
            [['start_time', 'finished_time'], 'safe'],
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
            'resume_id' => 'Resume ID',
            'start_time' => 'Start Time',
            'hp_job_id' => 'Hp Job ID',
            'finished_time' => 'Finished Time',
            'top_position' => 'Top Position',
            'left_position' => 'Left Position',
            'width' => 'Width',
            'height' => 'Height',
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
    
    public function getResumes() {
        return $this->hasOne(Resumes::className(), ['id' => 'resume_id']);
    }
}
