<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

/**
 * This is the model class for table "denuncia".
 *
 * @property integer $idDenuncia
 * @property string $descricao
 * @property string $local
 * @property string $data
 * @property string $hora
 * @property integer $status
 *
 * @property Foto[] $fotos
 */
class Denuncia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'denuncia';
    }

    /**
     * @inheritdoc
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['descricao', 'local', 'data', 'hora', 'status'], 'required','message'=>'Este campo é obrigatório'],
            [['descricao'], 'string'],
            [['data', 'hora'], 'safe'],
            [['status'], 'integer'],
            [['local'], 'string', 'max' => 254],
            [['imageFiles'], 'file', 'extensions'=>'jpg, png, jpeg', 'maxFiles' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idDenuncia' => 'Número da Denuncia',
            'descricao' => 'Descricao',
            'local' => 'Local',
            'data' => 'Data',
            'hora' => 'Hora',
            'status' => 'Status',
            'imageFiles' => 'Clique abaixo e anexe até 4 fotos',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFotos()
    {
        return $this->hasMany(Foto::className(), ['idDenuncia' => 'idDenuncia']);
    }

    public function afterFind(){

        if ($this->status == 1){
            $this->status = 'Não verificada';
        } elseif ($this->status == 2) {
            $this->status = 'Verdadeira';
        } elseif ($this->status == 3) {
            $this->status = 'Falsa';
        }


    }

    public function upload() {
        if ($this->validate()) { 
            foreach ($this->imageFiles as $file) {
                $file->saveAs('/opt/lampp/htdocs/uploads/' . $file->baseName . '.' . $file->extension);
                echo "error da foto em".$file->error;
            }
            return true;
        } else {
            return false;
        }
    }    

}
