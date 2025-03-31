<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\LoginForm; // Ensure LoginForm is imported
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\models\UserOrganization;

class UserController extends Controller
{
    /**
     * Creates a new user.
     *
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException if the current user is not an admin.
     */
    public function actionCreate()
    {
        if (!User::isCurrentUserAdmin())
        {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }

        $model = new User(['scenario' => 'create']); // Set the scenario to 'create'
        if ($model->load(Yii::$app->request->post()))
        {
            $model->auth_key = Yii::$app->security->generateRandomString();
            $model->access_token = Yii::$app->security->generateRandomString();
            $model->password = Yii::$app->security->generatePasswordHash($model->password); // Hash the password
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
     * Updates an existing user.
     *
     * @param int $id The ID of the user to update.
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException if the current user is not an admin.
     * @throws NotFoundHttpException if the user is not found.
     */
    public function actionUpdate($id)
    {
        if (!User::isCurrentUserAdmin())
        {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }

        $model = User::findOne($id);
        if (!$model)
        {
            throw new NotFoundHttpException('User not found.');
        }

        $model->scenario = 'update'; // Set the scenario to 'update'
        $model->password = null; // Ensure the password field is blank

        if ($model->load(Yii::$app->request->post()))
        {
            if (!empty($model->password))
            {
                $model->password = Yii::$app->security->generatePasswordHash($model->password); // Hash the password if updated
            }
            else
            {
                unset($model->password); // Do not overwrite the password if it's not being updated
            }
            if ($model->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Edits an existing user.
     *
     * @param int $id The ID of the user to edit.
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException if the current user is not an admin.
     * @throws NotFoundHttpException if the user is not found.
     */
    public function actionEditUser($id)
    {
        if (!User::isCurrentUserAdmin())
        {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }

        $model = User::findOne($id);
        if (!$model)
        {
            throw new NotFoundHttpException('User not found.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single user.
     *
     * @param int $id The ID of the user to view.
     * @return string
     * @throws NotFoundHttpException if the user is not found.
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // Initialize the data provider for related data (e.g., documents or other users)
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => \app\models\Documents::find()->where(['user_id' => $id]),
            'pagination' => [
                'pageSize' => 10, // Adjust the page size as needed
            ],
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Handles user login.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
        {
            return $this->goHome(); // Redirect to home if already logged in
        }

        $model = new LoginForm(); // Assuming you have a LoginForm model
        if ($model->load(Yii::$app->request->post()) && $model->login())
        {
            return $this->goHome(); // Redirect to the homepage after successful login
        }

        // Log validation errors for debugging
        if ($model->hasErrors())
        {
            Yii::error('Login failed: ' . json_encode($model->errors), __METHOD__);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLinkOrganization()
    {
        $model = new UserOrganization();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            Yii::$app->session->setFlash('success', 'Корисник је успешно повезан са организацијом.');
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Дошло је до грешке приликом повезивања корисника и организације.');
        }

        // Redirect back to the user or organization view page
        if (isset($model->user_id))
        {
            return $this->redirect(['user/view', 'id' => $model->user_id]);
        }
        elseif (isset($model->organization_id))
        {
            return $this->redirect(['organization/view', 'id' => $model->organization_id]);
        }

        return $this->goHome();
    }

    public function actionUnlinkOrganization($user_id, $organization_id)
    {
        $link = UserOrganization::findOne(['user_id' => $user_id, 'organization_id' => $organization_id]);
        if ($link)
        {
            $link->delete();
            Yii::$app->session->setFlash('success', 'Организација је успешно уклоњена.');
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Повезивање није пронађено.');
        }

        return $this->redirect(['view', 'id' => $user_id]);
    }

    /**
     * Deletes an existing user.
     *
     * @param int $id The ID of the user to delete.
     * @return \yii\web\Response
     * @throws ForbiddenHttpException if the user tries to delete themselves.
     */
    public function actionDelete($id)
    {
        // Check if the user is trying to delete themselves
        if (Yii::$app->user->id == $id)
        {
            Yii::$app->session->setFlash('error', 'Не можете обрисати сопствени налог.');
            return $this->redirect(['index']); // Redirect to the user index page
        }

        $model = User::findOne($id);
        if ($model)
        {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Корисник је успешно обрисан.');
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Корисник није пронађен.');
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = \app\models\User::findOne($id)) !== null)
        {
            return $model;
        }

        throw new \yii\web\NotFoundHttpException('The requested user does not exist.');
    }
}
