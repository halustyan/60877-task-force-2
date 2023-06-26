<?php


namespace app\controllers;

use yii\web\Controller;
use app\models\User;
use app\models\Register;
use app\models\City;
use yii\base\Exception;
use Yii;

class RegisterController extends Controller
{
    public function actionIndex()
    {
        $register = new Register();
        $cities = City::find()->all();
        $data = \Yii::$app->request->post();

        if ($register->load($data) && $register->validate()) { // если данные проходят валидацию то делаем дальше
            $users = new User();
            $users->name = htmlspecialchars($register->name, ENT_QUOTES);
            $users->email = htmlspecialchars($register->email, ENT_QUOTES);
            $users->dt_add = date('Y-m-d H:i:s', time());
            $users->city_id = $register->city;
            $users->answer_orders = $register->answer_orders;
            $users->user_status = $users->answer_orders ? User::CUSTOMER : User::EXECUTOR;
            $users->password_hash = Yii::$app->getSecurity()->generatePasswordHash($register->password); // а в поле пароля хэшированное значение

            if ($users->save()) {
                return Yii::$app->response->redirect(['tasks']); // если успех то на страницу с тасками
            }
        } else {
            return $this->render('index', ['register' => $register, 'cities' => $cities]); // а если нет то на ту же страницу попадаем
        }
    }
}