<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidates".
 *
 * @property integer $id
 */
class Candidates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }
    
    public function getCandidatesFiles()
    {
        return $this->hasMany(CandidatesFiles::className(), ['candidate_id' => 'id']);
    }
}
