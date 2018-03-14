<?php

namespace backend\filters;

use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilters extends ActionFilter
{
    public function beforeAction($action)
    {
        if (!\Yii::$app->user->can($action->uniqueId)) {
            throw new HttpException(403, '您没有该操作的权限');
        } else {
            return true;
        }

    }
}