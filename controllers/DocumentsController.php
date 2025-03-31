<?php

namespace app\controllers;

use Yii;
use app\models\Documents;
use app\models\DocumentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DocumentsController implements the CRUD actions for Documents model.
 */
class DocumentsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Documents models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DocumentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Documents model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Documents model.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Documents();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->user_id = Yii::$app->user->id; // Set the user_id to the logged-in user's ID
            if ($model->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Documents model.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Documents model.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Documents model based on its primary key value.
     * @param int $id
     * @return Documents
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Documents::findOne($id)) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException('The requested document does not exist.');
    }

    /**
     * Uploads a file.
     * @return \yii\web\Response
     */
    public function actionUploadFile()
    {
        $uploadedFile = \yii\web\UploadedFile::getInstanceByName('file');
        if ($uploadedFile)
        {
            Yii::info('File received: ' . $uploadedFile->name, __METHOD__);
            $uploadDir = Yii::getAlias('@webroot/files/' . date('Y-m-d'));
            if (!is_dir($uploadDir))
            {
                if (!mkdir($uploadDir, 0777, true))
                {
                    Yii::error('Failed to create upload directory: ' . $uploadDir, __METHOD__);
                    return $this->asJson(['success' => false, 'error' => 'Failed to create upload directory.']);
                }
            }

            $uniqueFileName = uniqid() . '.' . $uploadedFile->extension;
            $filePath = $uploadDir . '/' . $uniqueFileName;

            if ($uploadedFile->saveAs($filePath))
            {
                $fullUrl = Yii::$app->request->hostInfo . '/files/' . date('Y-m-d') . '/' . $uniqueFileName;
                return $this->asJson(['success' => true, 'fileUrl' => $fullUrl]);
            }
            else
            {
                Yii::error('Failed to save uploaded file: ' . $filePath, __METHOD__);
                return $this->asJson(['success' => false, 'error' => 'Failed to save uploaded file.']);
            }
        }

        Yii::error('No file uploaded.', __METHOD__);
        return $this->asJson(['success' => false, 'error' => 'No file uploaded.']);
    }
}
