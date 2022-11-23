<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 06.11.2022
 * Time: 17:26
 */

namespace Ela;

use Ela\Helpers\UrlGenerator;
use Ela\Http\Kernel;
use Elastica\Client;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\Terms;
use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class Application
{

    /**
     * @return \Ela\Foundation\Application|mixed
     */
    public static function create($router)
    {
        $app = app();
        #$app = new \Ela\Foundation\Application();

        // events
        $app->singleton('dispatcher', EventDispatcher::class);

        // events
        $app->singleton('Validator', ValidatorFactory::class);

        ####################
        #####ELASTICA CLASSES
        ####################
        // Для поиска по ключевым словам
        $app->singleton('MultiMatch', MultiMatch::class);
        // Для фильтров
        $app->singleton('BoolQuery', BoolQuery::class);


        // Запрос
        $app->singleton('Query', Query::class);

        // Индекс
        $app->singleton('index', function () {
            $Client = new Client([
                'host' => getenv('ES_HOST'),
                'port' => getenv('ES_PORT'),
            ]);
            return $Client->getIndex(getenv('ES_INDEX_PRODUCT'));
        });


        ####################
        ####################
        ####################

        // routes
        if (is_callable($router)) {
            $app->singleton('router', Route::class);
            // Роутеры регистрируем после создания
            $app->resolving('router', $router);
        }


        // events
        $app->singleton('url', function () {
            return (new UrlGenerator(app('router'), Request::createFromGlobals()));
        });


        // Kernel
        $app->singleton(HttpKernel::class, function (Container $container) {

            // регистрация событи
            /* @var EventDispatcher $dispatcher */
            $dispatcher = $container->make('dispatcher');

            #$dispatcher = new EventDispatcher();

            // регистраиця роутеров
            $routes = $container->make('router');

            // какие то проблемы с роутерами решает
            $matcher = new UrlMatcher($routes, new RequestContext());
            #$dispatcher = new EventDispatcher();

            // add event Routers
            $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));


            # $subscriber = new StoreSubscriber();
            #$dispatcher->addSubscriber(new StoreSubscriber());

            // end
            $controllerResolver = new ControllerResolver();

            $argumentResolver = new ArgumentResolver();

            return new Kernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
            #return new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
        });


        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);


        return $app;
    }
}
