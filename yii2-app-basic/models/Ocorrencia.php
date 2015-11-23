<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\base\Model;
/**
 * This is the model class for table "ocorrencia".
 *
 * @property integer $idOcorrencia
 * @property integer $status
 * @property string $data
 * @property string $hora
 * @property string $periodo
 * @property string $detalheLocal
 * @property string $descricao
 * @property string $procedimento
 * @property string $dataConclusao
 * @property integer $idCategoria
 * @property integer $idSubLocal
 * @property integer $idNatureza
 * @property string $cpfUsuario
 *
 * @property Foto[] $fotos
 * @property Categoria $idCategoria0
 * @property Naturezaocorrencia $idNatureza0
 * @property Sublocal $idSubLocal0
 * @property Usuario $cpfUsuario0
 */
class Ocorrencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ocorrencia';
    }

    /**
     * @inheritdoc
     */

    public $idLocal;
    public $idSubLocalbkp;
    public $idNaturezabkp;
    public $idCategoriabkp;
    public $cpfbkp;
    public $imageFiles;
    public $comentarioFoto;

    public function rules()
    {
        return [
            [['status', 'data', 'hora', 'periodo', 'detalheLocal', 'descricao', 'idCategoria', 'idLocal', 'idSubLocal', 'idNatureza'], 'required','message'=>'Este campo é obrigatório'],
            [['status', 'idCategoria', 'idNatureza', 'idLocal', 'idSubLocal'], 'integer'],
            [['data', 'hora', 'dataConclusao'], 'safe'],
            [['descricao', 'procedimento'], 'string'],
            [['comentarioFoto'], 'string', 'max' => 500],            
            [['periodo'], 'string', 'max' => 6],
            [['detalheLocal'], 'string', 'max' => 120],
            [['cpfUsuario'], 'string', 'max' => 12],
            [['imageFiles'], 'file', 'extensions'=>'jpg, png, jpeg', 'maxFiles' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idOcorrencia' => 'Número da Ocorrencia',
            'status' => 'Status',
            'data' => 'Data',
            'hora' => 'Hora',
            'periodo' => 'Período',
            'detalheLocal' => 'Detalhamento do Local',
            'descricao' => 'Descrição',
            'procedimento' => 'Procedimento',
            'dataConclusao' => 'Data de Conclusão',
            'idCategoria' => 'Categoria',
            'idLocal' => 'Local',
            'idSubLocal' => 'Sublocal',
            'idNatureza' => 'Natureza da Ocorrência',
            'cpfUsuario' => 'Nome da última pessoa que atualizou a ocorrência',
            'comentarioFoto' => 'Comentário das Fotos',
            'imageFiles' => 'Clique abaixo e anexe até 4 fotos',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFotos()
    {
        return $this->hasMany(Foto::className(), ['idOcorrencia' => 'idOcorrencia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCategoria0()
    {
        return $this->hasOne(Categoria::className(), ['idCategoria' => 'idCategoria']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdNatureza0()
    {
        return $this->hasOne(Naturezaocorrencia::className(), ['idNatureza' => 'idNatureza']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdLocal0()
    {
        return $this->hasOne(local::className(), ['idLocal' => 'idLocal']);
    }


    public function getIdSubLocal0()
    {
        return $this->hasOne(Sublocal::className(), ['idSubLocal' => 'idSubLocal']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCpfUsuario0()
    {
        return $this->hasOne(Usuario::className(), ['cpf' => 'cpfUsuario']);
    }
    public function afterFind(){
        $this->idCategoriabkp = $this->idCategoria;
    //    echo "Categoria bkp".$this->idCategoriabkp;
        $this->idSubLocalbkp = $this->idSubLocal;
        $this->idNaturezabkp = $this->idNatureza;
        $sublocal = Sublocal::findOne($this->idSubLocal);
        $this->idLocal = $sublocal->idLocal;
        $this->cpfbkp = $this->cpfUsuario;

        $this->cpfUsuario = User::findOne($this->cpfUsuario)->nome;
        $this->idNatureza = Naturezaocorrencia::findOne($this->idNatureza)->Nome;
        $this->idCategoria = Categoria::findOne($this->idCategoria)->Nome;

        if ($this->status == 1){
            $this->status = 'Aberto';
        } elseif ($this->status == 2) {
            $this->status = 'Solucionado';
        } elseif ($this->status == 3) {
            $this->status = 'Não Solucionado';
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
