<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_tujuan".
 *
 * @property string $tujuan_id
 * @property string $tujuan_nama
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 */
class Tujuan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors(){
      return[
        [
          'class' => 'mdm\autonumber\Behavior',
          'attribute' => 'tujuan_id', // required
          'group' => 'tujuan', // required, unique
          'value' => 'TJ'.'?', // format auto number. '?' will be replaced with generated number
          'digit' => 3 // optional, default to null.
        ],
        \yii\behaviors\TimestampBehavior::className(),
        \yii\behaviors\BlameableBehavior::className(),

      ];
    }

    public static function tableName()
    {
        return 'tb_tujuan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tujuan_nama'], 'required'],
            [['created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['tujuan_id'], 'string', 'max' => 5],
            [['tujuan_nama'], 'string', 'max' => 255],
            [['tujuan_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tujuan_id' => 'ID',
            'tujuan_nama' => 'Tujuan',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    public function getlistTujuan($id){
            $model = Tujuan::find()->where(['tujuan_id'=>$id])->one();
            if(!empty($model)){
                    return $model->tujuan_nama;
            }
            return 'Root';
    }
}
