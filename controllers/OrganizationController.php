<?php

namespace app\controllers;

use Yii;
use app\models\Organization;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\models\UserOrganization;

class OrganizationController extends Controller
{
    /**
     * Checks if the current user is an admin.
     *
     * @throws ForbiddenHttpException if the user is not an admin.
     */
    protected function checkAdmin()
    {
        if (!Yii::$app->user->identity || Yii::$app->user->identity->status !== 'admin')
        {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
    }

    /**
     * Lists all organizations.
     *
     * @return string
     */
    public function actionIndex()
    {
        $organizations = Organization::find()->all();
        return $this->render('index', ['organizations' => $organizations]);
    }

    /**
     * Creates a new organization.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $this->checkAdmin(); // Ensure only admin users can access this action

        $model = new Organization();
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing organization.
     *
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the organization is not found.
     */
    public function actionUpdate($id)
    {
        $this->checkAdmin(); // Ensure only admin users can access this action

        $model = Organization::findOne($id);
        if (!$model)
        {
            throw new NotFoundHttpException('Organization not found.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing organization.
     *
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the organization is not found.
     */
    public function actionDelete($id)
    {
        // todo action delete allowed if the Organization has no documents
        // Check if the organization has any associated documents before deletion

        $this->checkAdmin(); // Ensure only admin users can access this action

        $model = Organization::findOne($id);
        if (!$model)
        {
            throw new NotFoundHttpException('Organization not found.');
        }

        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Displays the details of a single organization.
     *
     * @param int $id The ID of the organization to view.
     * @return string
     * @throws NotFoundHttpException if the organization is not found.
     */
    public function actionView($id)
    {
        $model = Organization::findOne($id);
        if (!$model)
        {
            throw new NotFoundHttpException('Organization not found.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Unlinks a user from an organization.
     *
     * @param int $organization_id The ID of the organization.
     * @param int $user_id The ID of the user.
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the link is not found.
     */
    public function actionUnlinkUser($organization_id, $user_id)
    {
        $link = UserOrganization::findOne(['organization_id' => $organization_id, 'user_id' => $user_id]);
        if ($link)
        {
            $link->delete();
            Yii::$app->session->setFlash('success', 'Корисник је успешно уклоњен из организације.');
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Повезивање није пронађено.');
        }

        return $this->redirect(['view', 'id' => $organization_id]);
    }

    /**
     * Links a user to an organization.
     *
     * @return \yii\web\Response
     */
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

        // Redirect back to the organization's view page
        if (isset($model->organization_id))
        {
            return $this->redirect(['view', 'id' => $model->organization_id]);
        }

        return $this->goHome();
    }

    /**
     * Searches for organizations by name.
     *
     * @param string $term The search term.
     * @return \yii\web\Response
     */
    public function actionOrganizationList($term)
    {
        $results = \app\models\Organization::find()
            ->select(['id', 'name'])
            ->where(['like', 'name', $term])
            ->asArray()
            ->all();

        return $this->asJson(array_map(function ($organization)
        {
            return [
                'label' => $organization['name'],
                'value' => $organization['id'],
            ];
        }, $results));
    }
}
