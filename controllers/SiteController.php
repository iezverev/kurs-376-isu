<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Message;
use app\models\SignupForm;
use app\models\User;
use app\models\Theme;
use app\models\ThemeCreation;
use app\models\UploadForm;
use yii\web\UploadedFile;



class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionForum($idredact = null)
    {
    	$themes = Theme::find()->all();
        $model = new ThemeCreation();
        $buttonname = 'Создать';

        if ($idredact) 
        {
            $buttonname = 'Редактировать';
            $model = ThemeCreation::findOne($idredact);
            if ($model->load(Yii::$app->request->post()) && $model->save() && Yii::$app->user->identity->role == 'admin') 
            {  
                return $this->redirect('forum');
            }   
            return $this->render('forum', compact('model', 'buttonname','themes'));
        }

        if ($model->load(Yii::$app->request->post())) { 
            $model->save();
            $this->redirect('forum');
        }

        return $this->render('forum', compact('themes', 'buttonname', 'model'));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {    
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        $user = new User();
        if ($model->load(Yii::$app->request->post()) && $model->login()) { 
            if((Yii::$app->user->identity->role)=='admin'){
            $this->redirect(['/site/index']);
        }
            else if((Yii::$app->user->identity->role)=='user'){
                $this->redirect(['/site/cabinet']);
            }
        }
        //$model->password = '';
           return $this->render('login', [
               'model' => $model,
           ]);
     //   return $this->render('index');
        }   


   

    public function actionRemovetheme($id)
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $onetheme = Theme::findOne($id);
        $messages = UploadForm::find()->where('id_theme='.$id)->all();
        for ($i=0; $i < count($messages); $i++) { 
            $messages[$i]->delete();
        }
        $onetheme->delete();



        return $this->redirect('forum');
    }


    




    public function actionThemepage($id)
    {    
    	

        $themeinfo = Theme::findOne($id);
        $messages = UploadForm::find()->where('id_theme='.$themeinfo->id)->all();
        $model = new UploadForm();
                
        if(!Yii::$app->user->isGuest) {
            if ($model->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isPost) {
                    $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                    if ($model->imageFile !== null) {
                        $model->imageFile->saveAs('uploads/'.$model->imageFile->basename.'.'.$model->imageFile->extension);
                        $model->imageFile = 'uploads/'.$model->imageFile->basename.'.'.$model->imageFile->extension;
                    } else {
                        $model->imageFile = 'none';
                    }
                    $model->save();
                    return $this->redirect('themepage?id='.$id);
                }
                
            }
        }

          



        return $this->render('themepage', compact('themeinfo','model', 'id' , 'messages'));
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */


    public function actionSignup()
    {

        $user = new User();
        
        $model = new SignupForm();
        if($model->load(Yii::$app->request->post()) && $model->validate()   ){



            $user->password = Yii::$app->security->generatePasswordHash($model->password);
            $user->username = $model->username;
            $user->fio = $model->fio;
            $user->role = 'user';
            
         if($user->save()){
            $this->redirect(['/site/login']);
         }
        }

     return $this->render('signup', compact('model'));
    }


}
