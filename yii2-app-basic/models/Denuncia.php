<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;
use app\controllers\FotoController;
/**
 * This is the model class for table "denuncia".
 *
 * @property integer $idDenuncia
 * @property string $descricao
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
    public $fotos;
    public $idLocal;
    public $idSubLocalbkp;
    public $idNaturezabkp;
    public $idCategoriabkp;    
    public $comentarioFoto;    

    public function rules()
    {
        return [
            [['descricao', 'idCategoria', 'idNatureza', 'periodo', 'detalheLocal', 'idLocal', 'idSubLocal','data', 'hora', 'status'], 'required','message'=>'Este campo é obrigatório'],
            [['descricao'], 'string'],
            [['comentarioFoto'], 'string', 'max' => 500],                        
            [['data'], 'safe'],            
            [['periodo'], 'string', 'max' => 6],            
            [['status','idSubLocal', 'idLocal', 'idCategoria', 'idNatureza'], 'integer'],
            [['imageFiles'], 'file', 'extensions'=>'jpg, png, jpeg', 'maxFiles' => 4],
            [['hora'], 'validatehora'],
            ];
    }

    /**
     * @inheritdoc
     */

    public function validatehora($attribute, $params)
    {

            list ($hora, $minuto) = split('[:]', $this->hora);

            if ((int)$hora >=0 && (int)$hora <=23 && (int)$minuto >=0 && (int)$minuto <=59 );
            else $this->addError($attribute, 'Insira uma hora válida. Ex: "14:45"');
    }

    public function attributeLabels()
    {
        return [
            'idDenuncia' => 'Número da Denúncia',
            'descricao' => '*Descrição',
            'idCategoria' => '*Categoria',
            'idNatureza' => '*Natureza da Ocorrência',            
            'idLocal' => '*Local',
            'idSubLocal' => '*Sublocal',
            'periodo' => '*Período',
            'detalheLocal' => '*Detalhamento do Local',            
            'status' => '*Status',            
            'data' => '*Data',
            'comentarioFoto' => 'Comentário das Fotos',            
            'hora' => '*Hora',
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

        list ($ano, $mes, $dia) = split ('[-]', $this->data);
        $this->data = $dia.'/'.$mes.'/'.$ano;

        $this->idCategoriabkp = $this->idCategoria;
        $this->idNaturezabkp = $this->idNatureza;        
        $this->idSubLocalbkp = $this->idSubLocal;
        $sublocal = Sublocal::findOne($this->idSubLocal);
        $this->idLocal = $sublocal->idLocal;
        $this->idSubLocal = Sublocal::findOne($this->idSubLocal)->Nome;
        $this->idNatureza = Naturezaocorrencia::findOne($this->idNatureza)->Nome;
        $this->idCategoria = Categoria::findOne($this->idCategoria)->Nome;

        $foto = FotoController::getFotoDenuncia($this->idDenuncia);
        if ($foto != null) {
        $this->comentarioFoto = $foto[0]->comentario;
         }

        if ($this->hora!=null){
            list ($hora, $minuto, $segundos) = split ('[:]', $this->hora);
            $this->hora = $hora.':'.$minuto;            
        }

        if ($this->status == 1){
            $this->status = 'Não verificada';
        } elseif ($this->status == 2) {
            $this->status = 'Verdadeira';
        } elseif ($this->status == 3) {
            $this->status = 'Falsa';
        }
        
        if ($this->periodo == 1){
            $this->periodo = 'Manhã';
        } elseif ($this->periodo == 2) {
            $this->periodo = 'Tarde';
        } elseif ($this->periodo == 3) {
            $this->periodo = 'Noite';
        } elseif ($this->periodo == 4) {
            $this->periodo = 'Madrugada';
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
