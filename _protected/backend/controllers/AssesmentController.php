<?php

namespace backend\controllers;

use Yii;
use common\models\Assesment;
use backend\models\AssesmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\PengadilanNegeri;
use common\models\Pegawai;
use common\models\Audit;
use common\models\AuditUpload;
use common\models\Tujuan;
use common\models\Kriteria;
use common\models\Pertanyaan;
use backend\models\AuditSearch;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

/**
 * AssesmentController implements the CRUD actions for Assesment model.
 */
class AssesmentController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Assesment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $uug = Yii::$app->user->identity->ug_id;
        $pkey = Yii::$app->user->identity->pkey;

        $searchModel = new AssesmentSearch();
        
        if($uug=='01'){
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }else{
            $model = PengadilanNegeri::findOne($pkey);
            $dataProvider = $searchModel->search2(Yii::$app->request->queryParams,$pkey);
        }

        //data PN
            $pn = new PengadilanNegeri();
            $datapn = $pn->find()->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'datapn' => $datapn,
        ]);
    }

    /**
     * Displays a single Assesment model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionAudit($id)
    {
        $model2 = $this->findModel($id);
        $model = PengadilanNegeri::find()->where(['pn_id'=>$model2->pn_id])->one();

        //$total = Audit::find()->where(['assesment_id'=>$model2->assesment_id])->all()->sum('bobot');

        // $command = Yii::$app->db->createCommand("SELECT sum(bobot), sum(audit_nilai_angka) FROM tb_audit where assesment_id=".$id);
        // $bobotTotal = $command->queryScalar();

        $query = (new \yii\db\Query())->from('tb_audit')->where(['assesment_id'=>$id]);
        $bobotTotal = $query->sum('bobot');
        $bobotNilai = $query->sum('audit_nilai_angka');

        $searchModel = new AuditSearch();
        $dataProvider = $searchModel->search2(Yii::$app->request->queryParams,$id);

        //data Tujuan
            $tujuan = new Tujuan();
            $datatj = $tujuan->find()->all();

        //data Kriteria
            $kriteria = new Kriteria();
            $datakrit = $kriteria->find()->all();


        return $this->render('audit', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'datatj' => $datatj,
            'datakrit' => $datakrit,
            'bobotTotal' => $bobotTotal,
            'bobotNilai' => $bobotNilai,
        ]);
    }

    
    
    public function actionNilai($id)
    {
        $model2 = new Audit();
        $model = $model2->findOne($id);
        
        if($post = Yii::$app->request->post()){

            $data=$model2->findOne($post['audit_id']);
            $data->audit_nilai=$post['audit_nilai'];
            $data->audit_nilai_angka=$post['audit_nilai_angka'];
            $data->audit_temuan=$post['audit_temuan'];
            $data->audit_keterangan=$post['audit_keterangan'];
            
            if($data->save()){
                return 'success';
            }else{
                return $this->renderAjax('nilai', [
                    'model' => $model,
                ]);
            }
        }
        
        return $this->renderAjax('nilai', [
            'model' => $model,
        ]);
    }

    public function actionNilaiuser($id)
    {
        $model = Audit::findOne($id);
        
        if($model->load(Yii::$app->request->post())){
            $audit_upload=UploadedFile::getInstances($model, 'audit_upload');

            foreach ($audit_upload as $file) {

                $contact = new AuditUpload();
                $contact->audit_id=$model->audit_id;
                $contact->audit_upload=$model->audit_id .'-'. $file->baseName . '.' . $file->extension;
                $contact->save();

                 Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/file/';
                  $path = Yii::$app->params['uploadPath'] .$model->audit_id .'-'. $file->baseName . '.' . $file->extension;
                  $file->saveAs($path);
            }

            // $data = new Audit();
            // $data->upload($audit_upload);
            $model->save();
            return $this->redirect(['audit', 'id' => $model->assesment_id]);
        }
        
        return $this->render('nilaiuser', [
            'model' => $model,
        ]);
    }

    public function actionTambah($id)
    {
        $model = new Audit();

        //data Tujuan
            $tujuan = new Tujuan();
            $datatjn = $tujuan->find()->all();

        //data Tujuan
            $kriteria = new Kriteria();
            $datakrit = $kriteria->find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // $contact = new Audit();
            // $contact->assesment_id=$id;
            // $contact->pertanyaan_id=$model->tanya_id;
            // $contact->save();
            return $this->redirect(['audit', 'id' => $id]);
            //return $this->redirect(['index']);
        }

        return $this->render('tambah', [
            'model' => $model,
            'datatjn'=>$datatjn,
            'datakrit'=>$datakrit,
        ]);
    }

    public function actionFileuser($id)
    {
        $model = new AuditUpload();
        $tanya = Audit::findOne($id);
        return $this->render('fileuser', [
            'model' => $model->find()->where(['audit_id'=>$id])->all(),
            'tanya' => $tanya,
        ]);
    }

    public function actionDeletefile($id,$audit_id)
    {

        $data = AuditUpload::findOne($id);
        unlink(Yii::$app->basePath . '/web/uploads/file/' . $data->audit_upload);
        $delete= AuditUpload::findOne($id)->delete();

        return $this->redirect(['fileuser', 'id' => $audit_id]);
    }

    /**
     * Creates a new Assesment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Assesment();

        //data PN
            $pn = new PengadilanNegeri();
            $dataPN = $pn->find()->all();

        //data Pegawai
            $pg = new Pegawai();
            $dataPG = $pg->find()->all();
            
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->assesment_id]);

            //$detPertanyaan;
            // if ($model->kelas_id=='KL001'){
            //     $detPertanyaan=Pertanyaan1::find()->all();
            // }else{
            //     $detPertanyaan=Pertanyaan2::find()->all();
            // }

            $kelas = PengadilanNegeri::find()->where(['pn_id'=>$model->pn_id])->one();
            $detPertanyaan=Pertanyaan::find()->where(['kelas_id'=>$kelas->pn_kelas])->all();

            foreach ($detPertanyaan as $key => $value) {
                $audit = new Audit();
                $audit->assesment_id=$model->assesment_id;
                $audit->tujuan_id=$value['tujuan_id'];
                $audit->kriteria_id=$value['kriteria_id'];
                $audit->pertanyaan=$value['pertanyaan'];
                $audit->nilai_a=$value['tanya_ket_a'];
                $audit->nilai_b=$value['tanya_ket_b'];
                $audit->nilai_c=$value['tanya_ket_c'];
                $audit->bobot=$value['tanya_bobot'];
                $audit->save();
            }

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'dataPN' => $dataPN,
            'dataPG' => $dataPG,
        ]);
    }

    /**
     * Updates an existing Assesment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //data PN
            $pn = new PengadilanNegeri();
            $dataPN = $pn->find()->all();

        //data Pegawai
            $pg = new Pegawai();
            $dataPG = $pg->find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->assesment_id]);

        }

        return $this->render('update', [
            'model' => $model,
            'dataPN' => $dataPN,
            'dataKLS' => $dataKLS,
            'dataPG' => $dataPG,
        ]);
    }

    /**
     * Deletes an existing Assesment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Assesment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Assesment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionLists($id)
     {
         $countPosts = PengadilanNegeri::find()
             ->where(['pn_kelas' => $id])
             ->count();
         
         $posts = PengadilanNegeri::find()
             ->where(['pn_kelas' => $id])
             ->all();
     
        if($countPosts>0)
         {
            foreach($posts as $post){
                echo "<option value='".$post->pn_id."'>".$post->pn_nama."</option>";
            }
         }
         else{
            echo "<option>-</option>";
         }
     
     }

    protected function findModel($id)
    {
        if (($model = Assesment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReport() {
    // get your HTML raw content without any layouts or scripts
    //$content = $this->renderPartial('_reportView');
     $aa = 'asasa';
     $ab = 'aba';
    // setup kartik\mpdf\Pdf component
    $pdf = new Pdf([
        // set to use core fonts only
        'mode' => Pdf::MODE_CORE, 
        // A4 paper format
        'format' => Pdf::FORMAT_A4, 
        // portrait orientation
        'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
        'destination' => Pdf::DEST_BROWSER, 
        // your html content input
        'content' => 
        ($aa.'<br> aaaa'),
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
         'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
        // // any css to be embedded if required
         'cssInline' => '.kv-heading-1{font-size:18px}', 
         // set mPDF properties on the fly
        'options' => ['title' => 'Krajee Report Title'],
         // call mPDF methods on the fly
        'methods' => [ 
            'SetHeader'=>['asdas'.'aaaaaaaaaaaaa'], 
            'SetFooter'=>['{PAGENO}'],
        ]
    ]);

        echo 'aa';
    
    // return the pdf output as per the destination setting
    return $pdf->render(); 
    }

    public function actionUnduh($id) 
    { 
        $download =  AuditUpload::findOne($id);
        //$download = AuditUpload::findOne()->where(['audit_id'=>$id]);
        // foreach ($download as $value) {
            $path = Yii::$app->basePath.'/web/uploads/file/'.$download['audit_upload'];

            if (file_exists($path)) {
                return Yii::$app->response->sendFile($path);
            }
        // }
        //$download = PstkIdentifikasi::findOne($id); 
        
    }
}
