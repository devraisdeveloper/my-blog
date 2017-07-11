<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\Comments;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\helpers\PictureHelper;
use app\helpers\PostBuilder;
use app\models\Likes;
use yii\filters\AccessControl;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
             'access'=>[
                'class' => AccessControl::className(),
                'only'=>['index','create','update','delete','view','profile','comment','list'],
                'rules'=>[
                     [
                        'allow' => true,
                        'actions' => ['index','view','comment','list'],
                        'roles' => ['?','@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete','profile'],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex() {   
        $model = new Post();
        $posts = $model::find()->all();
          foreach ($posts as $key => $post) {
            if ($post->isGroupPost) {
                unset($posts[$key]);
            }
        }
        return $this->render('index', ['posts' => $posts]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $comment = new Comments();
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'comment' => $comment
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Post();
        $builder = new PostBuilder();
        $postData = Yii::$app->request->post();
        if (isset($postData['Post'])) {
            $id = $builder->createNewPost($postData);
            return $id != false ? $this->redirect(['view', 'id' => $id]) : $this->redirect(['create']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            if (!empty(UploadedFile::getInstance($model, 'imageFile'))) {
                
                $helper = new PictureHelper();
                $imageName = strtolower($model->title);
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                $model->picture = $helper->setPicture($imageName, $model);
            }
            if ($model->validate() && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Failed to Update Post!!!');
                return $this->refresh();
            }
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixedyii2 call action
     */
    public function actionDelete($id) {  
        try {
            $this->findModel($id)->delete();
            return $this->redirect(['profile','id'=> Yii::$app->user->id]);
        } catch (Exception $ex) {

         return $this->redirect(['profile', 'id' => $this->findModel($id)->user->id]);
        }
    }

    /** 
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $idalisia
     * @return Post the loaded model
     * @throws NotFoundHttpExc$thYii::$app->request->post()is->findModel($id);eption if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionProfile($id) {
        $model = new Post();
        $posts = $model::findAll(['user_id' => $id]);

        foreach ($posts as $key => $post) {
            if ($post->isGroupPost) {
                unset($posts[$key]);
            }
        }
        return $this->render('profile', ['posts' => $posts, 'id' => $id]);
    }

    public function actionLike($id) {
       // $user_id = Yii::$app->user->id;
    
        if(!Yii::$app->user->isGuest){
             $user_id = Yii::$app->user->id;
            if (!Likes::find()->where(['user_id' => $user_id, 'post_id' => $id])->exists()) {
                $dbTransaction = Yii::$app->db->beginTransaction();
                try {
                    $post = Post::findOne(['id' => $id]);
                    $post->total_likes += 1;
                    $post->update();
                    $like = new Likes();
                    $like->user_id = $user_id;
                    $like->post_id = $id;

                    if ($like->validate() && $like->save()) {
                        $dbTransaction->commit();
                        return $post->total_likes;
                    }
                } catch (\Exception $e) {
                    $dbTransaction->rollBack();
                    return false;
                }
            } else {
                return 'liked';
            }
        }else{
            return 'guest';
        }
    }

    public function actionComment($id) {
        try {
            $postData = Yii::$app->request->post();
            $user_id = Yii::$app->user->id;
            $comment = new Comments();
            $post = new Post();
           
            if (isset($postData['Comments'])) {
                $dbTransaction = Yii::$app->db->beginTransaction();
                $comment->load($postData);
                $comment->post_id = $id;
                $comment->user = (Yii::$app->user->isGuest ? NULL : $user_id);
                if ($comment->validate() && $comment->save()) {
                    $post = Post::findOne(['id'=> $id]);
                    $post->total_comments += 1;
                    $post->update();
                    $dbTransaction->commit();
                    return $this->redirect(['view', 'id' => $id]);
                } else {
                     $dbTransaction->rollBack();
                    return $this->redirect(['profile','id' => Yii::$app->user->id]);
                }
            } else {
                return $this->renderAjax('comment', [
                            'comment' => $comment,
                            'post' => $post
                ]);
            }
        } catch (\Exception $ex) {
            Yii::$app->getSession()->setFlash('error', 'Failed to Add Comment !!!');
        }
    }
    
    public function actionIsGroupPost($id){
        $post = Post::findOne(['id'=>$id]);
        if($post->isGroupPost){
            return 'true';
        }else{
            return 'false';
        }
    }
}
