<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;
use api\models\LoginForm;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
    
        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
    
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];
    
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];
    
        return $behaviors;
    }
    
    public function actionIndex()
    {
        return 'api';
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($token = $model->auth()) {
            return $token;
        } else {
            return $model;
        }
    }

    protected function verbs()
    {
        return [
            'login' => ['post'],
        ];
    }
}
