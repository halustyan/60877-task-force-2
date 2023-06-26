<?php

namespace app\models;

use app\models\Task;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\base\BaseObject;
use yii\base\Model;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use app\models\Files;
use GuzzleHttp\Client;


/**
 * This is the model class for table "users".
 *
 * @property string $about_job;
 * @property string $describe_task;
 * @property int $categories;
 * @property int $budget;
 * @property string $expire_date;
 * @property string $files;
 * @property int $location;
 */
class AddTask extends Model
{
    /**
     * {@inheritdoc}
     */
    public $about_job;
    public $describe_task;
    public $categories;
    public $budget;
    public $expire_date;
    public $files;
    public $location;
    public $task_id;

    public $task;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['about_job', 'describe_task', 'categories'], 'required'],
            ['about_job', 'string', 'min' => 10],
            ['describe_task', 'string', 'min' => 30, 'max' => 255],
            ['location', 'string', 'max' => 255],
            //['location', 'exist', 'targetClass' => City::class, 'targetAttribute' => 'name'],
            [['files'], 'file', 'maxSize' => 1024 * 1024, 'maxFiles' => 4],
            ['categories', 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
            ['budget', 'integer', 'min' => 1],
            [['expire_date'], 'date', 'format' => 'php:Y-m-d'],
            ['expire_date', 'validateDate'],

        ];
    }

    public function validateDate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $date = \DateTime::createFromFormat('Y-m-d', $this->$attribute);
            $now = new \DateTime();

            if ($date < $now) {
                $this->addError($attribute, 'The date cannot be in the past.');
            }
        }
    }

    public function createNewTask()
    {
        //добавление геокодера
//        $api_key = 'e666f398-c983-4bde-8f14-e3fec900592a';
        $api_key = '9191a52b-ad1d-4ad1-8098-4f12761fe6a5';
//        $api_key = '286a0cc9-43d5-49e1-8e7a-3b0d4b1631f2';
        $client = new Client();
        $response = $client->request(
            'GET',
            'https://geocode-maps.yandex.ru/1.x',
            [
                'query' => [
                    'apikey' => $api_key,
                    'geocode' => $this->location,
                    'format' => 'json'
                ]
            ]
        );

        $content = $response->getBody()->getContents(); // что тут происходит?
        $response_data = json_decode($content, true);

        if (is_array($response_data)) {
            //  $coordinates = $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
            if (isset($response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'])) {
                $coordinates = $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
            } else {
                $coordinates = '';
            }
            $coordinates = explode(' ', $coordinates);
        }


        $newTask = new Task();
        $newTask->name = $this->about_job;
        $newTask->description = $this->describe_task;
        $newTask->category_id = $this->categories;
        //добавление геокодера
        $newTask->address = $this->location;
        //  $newTask->longitude = $coordinates[0];
        //$newTask->latitude = $coordinates[1];

        if (isset($coordinates[0])) {
            $newTask->longitude = $coordinates[0];
        } else {
            $newTask->longitude = '';
        }

        if (isset($coordinates[1])) {
            $newTask->latitude = $coordinates[1];
        } else {
            $newTask->latitude = '';
        }

        if (isset($coordinates)) {
            $city = City::find()->where(['name' => $this->location])->one();
            if ($city !== null) {
                $newTask->city_id = $city->id;
            } else {
                $newTask->city_id  = null;
            }
        }

        $newTask->budget = $this->budget;
        $newTask->expire = $this->expire_date;
        $newTask->create_at = date('Y-m-d h-m-s');
        $newTask->status = "new";
        $newTask->user_id = \Yii::$app->user->identity->id;
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            if (!$newTask->save()) {
                throw new Exception("Can not save new task");
            }
            $this->saveFile($newTask);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        return $newTask;
    }

    public function saveFile($task_after_save)
    {
        $instances = UploadedFile::getInstances($this, 'files');
        foreach ($instances as $instance) {
            $files = new Files();
            $instance->saveAs("uploads/" . $instance->name);
            $files->tasks_id = $task_after_save->id;
            $files->files_name = $instance->name;
            if (!$files->save()) {
                throw new ServerErrorHttpException("Файл(ы) не был(и) сохранен(ы)!");
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'about_job' => 'Опишите суть работы',
            'describe_task' => 'Подробности задания',
            'categories' => 'Категории',
            'location' => 'Локация',
            'budget' => 'Бюджет',
            'expire_date' => 'Срок исполнения',
            'files' => 'Файлы',
        ];
    }


}
