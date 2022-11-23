<?php

namespace Ela\Http\Controllers;

use Ela\Interfaces\Middleware;
use Elastica\Index;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Contracts\EventDispatcher\Event;

abstract class Controller extends BaseController
{
    protected $middleware;

    public function __construct()
    {
        $this->loadMiddlewareKernelController();
    }

    /**
     * Собстрвенные прокладки для загрузки
     */
    private function loadMiddlewareKernelController()
    {
        $dispatcher = app('dispatcher');
        $controller = $this;
        if ($this->middleware) {
            $dispatcher->addListener('kernel.controller', function (Event $event) use ($controller) {
                $middlewares = $this->middleware;
                $reguest = $event->getRequest();
                foreach ($middlewares as $middleware) {

                    // проверяем чтобы небыло остановки
                    if (!$event->isPropagationStopped()) {
                        if (is_callable($middleware)) {
                            $middleware($reguest);
                        } else {
                            $Middleware = new $middleware();
                            if ($Middleware instanceof Middleware) {
                                $Middleware->handle($controller, $reguest, $event);
                            }
                        }
                    }
                }
            });
        }

        // KernelEvents::EXCEPTION - возвращаем исключение с ответом
        $dispatcher->addListener('kernel.exception', function (Event $event) use ($controller) {
            /* @var AuthenticationException $th */
            $th = $event->getThrowable();
            $event->setResponse(new JsonResponse([
                'message' => $th->getMessage(),
                'data' => method_exists($th, 'errors') ? $th->errors() : [],
            ], 422));

        });
    }


    /**
     * Выбросит исключение с ошибкой
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return bool|JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function validatorResponse(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        //try {
        $this->validator($request->all(), $rules, $messages, $customAttributes)->validate();
        /*  } catch (\Illuminate\Validation\ValidationException $e) {
              $arrError = $e->errors();
              foreach ($arrError as $key => $value) {
                  $arrImplode[$key] = implode(', ', $arrError[$key]);
              }
              $message = implode(', ', $arrImplode);
              return new JsonResponse([
                  'reason' => $message,
                  'data' => $arrImplode
              ], $e->status);
          }*/
        return true;
    }

    /**
     * @param array $data
     * @param array $rules
     * @return mixed|\Illuminate\Validation\Validator
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function validator(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        return app('Validator')->make($data, $rules, $messages, $customAttributes);
    }

    /**
     * @return Query
     */
    public function query()
    {
        return app('Query');
    }

    /**
     * @return Index
     */
    public function index()
    {
        return app('index');
    }

    /**
     * @return BoolQuery
     */
    public function BoolQuery()
    {
        return app('BoolQuery');
    }

    public function success($data = null, $status = 200)
    {
        if (!empty($data) && !is_array($data)) {
            $data = ['message' => $data];
        }
        if (is_null($data)) {
            $data = [];
        }
        return new JsonResponse($data, $status);
    }

    public function failure($data, $status = 422)
    {
        if (!is_array($data)) {
            $data = ['message' => $data];
        }
        if (is_null($data)) {
            $data = [];
        }
        return new JsonResponse($data, $status);
    }

    public function failureField($data, $status = 422)
    {
        $data = [
            'data' => $data
        ];
        return $this->failure($data, $status);
    }
}
