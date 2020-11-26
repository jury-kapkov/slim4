<?php


namespace App\Middlewares;

use App\Models\SessionModel;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;

class MySessionMiddleware implements MiddlewareInterface
{
    protected $options = array(
        'name'          => 'CookieForTask',
        'lifetime'      => 600,
        'path'          => '/',
        'domain'        => null,
        'secure'        => false,
        'httponly'      => true,
        'cache_limiter' => 'nocache',
        'autorefresh'   => false
    );

    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public static function InitSessionMiddleware(App $app) : self {
        return new self($app);
    }

    private function validateSession($record) {
        //Если запись найдена
        if ($record != NULL) {
            $updated_at = strtotime($record->updated_at);
            $now = strtotime((date("Y-m-d H:i:s")));
            $dif = floor(($now - $updated_at));

            //Если еще не проошло 10 минут
            if ($dif > $this->options['lifetime']) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    private function createSession() {
        try {
            $options = $this->options;

            session_set_cookie_params($options['lifetime'], $options['path'], $options['domain'], $options['secure'], $options['httponly']);
            session_name($options['name']);
            session_cache_limiter(false);
            session_start();
            $session_id = session_id();

            setcookie($options['name'], $session_id, time() + $options['lifetime'], $options['path'], $options['domain'], $options['secure'], $options['httponly']);

            //Сохраняем сессию в бд
            $model = new SessionModel($this->app->getContainer());
            $model->save($session_id);

        } catch (Exception $exception) {
            die("Ошибка создания сессии: " . $exception->getMessage());
        }
        return $session_id;
    }

    private function updateDateSession($model, $session_id) {
        $model->update($session_id);
        //Обновляем время жизни куков
        setcookie($this->options['name'], $session_id, time() + $this->options['lifetime'], $this->options['path'], $this->options['domain'], $this->options['secure'], $this->options['httponly']);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!isset($_COOKIE['CookieForTask']))
        {
            $session_id = $this->createSession();
            $model = new SessionModel($this->app->getContainer());
            $session_record = $model->get($session_id);
            $request = $request->withAttribute('session', $session_record);

        } else {
            //Ключ из кукисов
            $session_id = $_COOKIE[$this->options['name']];
            $model = new SessionModel($this->app->getContainer());
            $session_record = $model->get($session_id);

            if ($this->validateSession($session_record)) {
                $this->updateDateSession($model, $session_record->id_session);
            } else {
                //Завершаем старую сессию
                if (isset($_SESSION)) {
                    session_unset();
                    session_destroy();
                }
                //Удаляем старые куки по ключу
                setcookie($this->options['name'], '', time() - 3600, '/');
                //Создаем новую сессию
                $this->createSession();
            }

            $session_record = $model->get($session_id);
            $request = $request->withAttribute('session', $session_record);
        }


        return $handler->handle($request);
    }

}