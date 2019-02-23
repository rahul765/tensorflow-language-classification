<?php
namespace App\Middleware;

use App\Helpers\SessionManager as Session;
use App\Source\Factory\ModelsFactory;
use App\Helpers\RequestParams;
use App\Source\Events\BaseContainerEvent;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LastPagePaginatorMiddleware extends ABaseMiddleware
{
    protected $variableName = 'page';
    protected $groupName = 'last.page.';

    protected $req;
    protected $res;

    public function __invoke(Request $request, Response $response, $next)
    {
        $this->req = $request;
        $this->res = $response;

        $allParams = new RequestParams($this->req);

        if (Session::has('auth') &&
            Session::get('auth') &&
            $allParams->all($this->variableName)
        ) {
            $this->setOption($allParams->all($this->variableName), $allParams);
        }

        return $next($request, $response);
    }

    protected function storeParams($value)
    {
        $u_id = Session::get('user')['id'];
        $model = ModelsFactory::getModel('UserViewsSettings');
        $result = $model->where('user_id', $u_id)->where('group', $this->groupName)->where('code', $this->variableName)->first();

        if (!$result) {
            $result = ModelsFactory::getModel('UserViewsSettings', ['user_id' => $u_id, 'group' => $this->groupName, 'code' => $this->variableName]);
            $result->user_id = $u_id;
        }

        $result->value = $value;
        $result->save();

        return $result;
    }

    public function setOption($value, RequestParams $allParams)
    {
        $this->groupName = $this->groupName . basename($allParams->getRequest()->getUri()->getPath());

        $arParams = [
            'value' => $value, 
            'codeName'  => $this->variableName,
            'groupName'  => $this->groupName
        ];

        $event = new BaseContainerEvent($this->c, $arParams);
        $event = $this->c->dispatcher->dispatch('middleware.lastpagepaginator.before', $event);

        $value = ($event->getParams()['value'])?$event->getParams()['value']:$allParams->all($this->variableName);

        $result = $this->storeParams($value);

        $arParams = [
            'result' => $result,
            'allParams' => $allParams
        ];

        $event = new BaseContainerEvent($this->c, $arParams);
        $this->c->dispatcher->dispatch('middleware.lastpagepaginator.after', $event);
    }
}
