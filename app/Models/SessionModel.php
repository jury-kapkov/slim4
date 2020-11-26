<?php


namespace App\Models;


use PDOException;
use Psr\Container\ContainerInterface;

class SessionModel extends BaseModel
{
    public function __construct(ContainerInterface $container, int $id = null)
    {
        parent::__construct($container, $id);
    }

//  Идентификатор сессии
    protected $id_session;

//  Время обновления сессии
    protected $updated_at;

    public function getIdSession() {
        return $this->id_session;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function setIdSession($id_session) {
        $this->id_session = $id_session;
    }

    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
    }

    public function get($id_session)
    {
        try {
            $model = $this->pdo->query('SELECT * FROM session WHERE id_session="' . $id_session . '"')->fetch();
            $id_session = $model->id_session;
            $updated_at = $model->updated_at;
            $id = $model->id;
        }catch (PDOException $exception) {
            die('Ошибка получения сессии: ' . $exception->getMessage());
        }

        return $model;
    }

    public function update($id_session)
    {
        try {
            $this->pdo->query('UPDATE session SET updated_at ="' . date('Y-m-d H:i:s') . '" WHERE id_session="' . $id_session . '"');
            return true;
        }catch (PDOException $exception) {
            die('Ошбка обновления сессии: ' . $exception->getMessage());
        }
    }

    public function save($id_session)
    {
        try {
            $this->pdo->query('INSERT INTO session SET updated_at ="' . date('Y-m-d H:i:s') . '", id_session ="' . $id_session . '"');
            return true;
        }catch (PDOException $exception) {
            die('Ошбка сохранения сессии: ' . $exception->getMessage());
        }
    }


}