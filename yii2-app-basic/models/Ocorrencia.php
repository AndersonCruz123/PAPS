<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\base\Model;
use app\controllers\FotoController;
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
    public $fotos;

    public function rules()
    {
        return [
            [['status', 'data', 'hora', 'detalheLocal', 'descricao', 'idCategoria', 'idLocal', 'idSubLocal', 'idNatureza'], 'required','message'=>'Este campo é obrigatório'],
            [['status', 'idCategoria', 'idNatureza', 'idLocal', 'idSubLocal', 'idSubLocalbkp'], 'integer'],
            [['data', 'dataConclusao'], 'safe'],
            [['descricao', 'procedimento'], 'string'],
            [['comentarioFoto'], 'string', 'max' => 500],            
            [['detalheLocal'], 'string', 'max' => 120],
            [['cpfUsuario'], 'string', 'max' => 12],
            [['imageFiles'], 'file', 'extensions'=>'jpg, png, jpeg', 'maxFiles' => 4,'wrongExtension'=>'Somente jpeg, jpg e png', 'tooMany'=>'Até 4 fotos podem ser anexadas'],
            [['hora'], 'validatehora'],
            [['periodo'], 'safe'],            
        ];
    }

    /**
     * @inheritdoc
     */
   public function validatehora($attribute, $params)
    {

            list ($hora, $minuto) = split('[:]', $this->hora);

            if ((int)$hora >=0 && (int)$hora <=23 && (int)$minuto >=0 && (int)$minuto <=59 ) {
                if ((int)$hora >=0 && (int)$hora <=5) {
                    $this->periodo=4;
                } else if ((int)$hora >=6 && (int)$hora <=11) {
                    $this->periodo=1;
                } else if ((int)$hora >=12 && (int)$hora <=17) {
                    $this->periodo=2;
                } else if ((int)$hora >=18 && (int)$hora <=23) {
                    $this->periodo=3;
                }         

            }
            
            else $this->addError($attribute, 'Insira uma hora válida. Ex: "14:45"');
    }

    public function attributeLabels()
    {
        return [
            'idOcorrencia' => 'Nº Registro',
            'status' => '*Status',
            'data' => '*Data do ocorrido',
            'hora' => '*Hora',
            'periodo' => '*Período',
            'detalheLocal' => '*Detalhamento do Local',
            'descricao' => '*Descrição',
            'procedimento' => 'Procedimento',
            'dataConclusao' => 'Data de Conclusão',
            'idCategoria' => '*Categoria',
            'idLocal' => '*Local',
            'idSubLocal' => '*Sublocal',
            'idNatureza' => '*Natureza da Ocorrência',
            'cpfUsuario' => 'Nome da última pessoa que atualizou a ocorrência',
            'comentarioFoto' => 'Comentário das Fotos',
            'imageFiles' => 'Clique abaixo e anexe até 4 fotos',
            'fotos' => 'fotos clique para fazer download'
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

        list ($ano, $mes, $dia) = split ('[-]', $this->data);
        $this->data = $dia.'/'.$mes.'/'.$ano;

        if ($this->dataConclusao!=null){
            list ($ano, $mes, $dia) = split ('[-]', $this->dataConclusao);
            $this->dataConclusao = $dia.'/'.$mes.'/'.$ano;            
        }
        
        if ($this->hora!=null){
            list ($hora, $minuto, $segundos) = split ('[:]', $this->hora);
            $this->hora = $hora.':'.$minuto;            
        }

        $foto = FotoController::getFotoOcorrencia($this->idOcorrencia);
        if ($foto != null) {
        $this->comentarioFoto = $foto[0]->comentario;
         }

        $this->idCategoriabkp = $this->idCategoria;
    //    echo "Categoria bkp".$this->idCategoriabkp;
        $this->idSubLocalbkp = $this->idSubLocal;
        $this->idNaturezabkp = $this->idNatureza;
        $sublocal = Sublocal::findOne($this->idSubLocal);
        $this->idLocal = $sublocal->idLocal;
        $this->cpfbkp = $this->cpfUsuario;
        $this->idSubLocal = Sublocal::findOne($this->idSubLocal)->Nome;

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
