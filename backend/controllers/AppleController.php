<?php

namespace backend\controllers;

use common\models\Apple;
use DateTime;
use Faker\Factory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Apple controller
 */
class AppleController extends Controller
{
    const MAX_QUANTITY_GENERATE = 10;
    const FIVE_HOURS = 18000;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'create', 'generate', 'fall', 'eat', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Apple models.
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Apple::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Apple model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->createApples();

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Apple model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFall($id)
    {
        $model = $this->findModel($id);

        if ($model->status == Apple::APPLE_HANGING) {
            $model->status = Apple::APPLE_FALL;
            $model->fall_at = date_timestamp_get(date_create());
            $model->save();
        }
        return $this->redirect(['index']);
    }

    /**
     * Generate a new Apple models.
     * If generation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws Exception
     */
    public function actionGenerate()
    {
        $this->createApples(self::MAX_QUANTITY_GENERATE);

        return $this->redirect(['index']);
    }

    /**
     * Eat apple
     * @param $id
     * @return bool[]|string|Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionEat($id)
    {
        $model = $this->findModel($id);
        $date = new DateTime();

        if ($model->status == Apple::APPLE_HANGING) {
            Yii::$app->session->setFlash('danger', 'An apple cannot be eaten, it is on a tree.');
            return $this->redirect('index');
        }

        if ($model->status == Apple::APPLE_ROTTEN or $date->getTimestamp() >= $model->fall_at + self::FIVE_HOURS) {
            Yii::$app->session->setFlash('danger', 'The apple cannot be eaten, it has rotted.');
            return $this->redirect('index');
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->ate >= 100) {
                $model->delete();
                // JSON response is expected in case of successful save
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => true];
            } else {
                if ($model->save()) {
                    if (Yii::$app->request->isAjax) {
                        // JSON response is expected in case of successful save
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => true];
                    }
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->renderAjax('eat', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Apple model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Generating records and writing to the database
     * @param int $quantity
     */
    protected function createApples($quantity = 1)
    {
        $faker = Factory::create();
        for ($i = 0; $i < $quantity; $i++) {
            $models[$i] = new Apple();
            $models[$i]->created_at = $faker->unixTime;
            $models[$i]->color = $faker->safeColorName;
            $models[$i]->status = Apple::APPLE_HANGING;
        }
        foreach ($models as $model) $model->save();
    }
}