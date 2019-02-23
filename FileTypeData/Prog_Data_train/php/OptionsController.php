<?php

namespace App\Controllers\Admin;

use App\Helpers\SessionManager as Session;
use App\Source\Factory\ModelsFactory;
use App\Source\ModelFieldBuilder\BuildFields;
use \Psr\Http\Message\ServerRequestInterface as request;

class OptionsController extends UniversalController
{
    /**
     * @param request $req
     * @param $res
     */
    public function index(request $req, $res)
    {
        $this->initRoute($req, $res);

        $model = ModelsFactory::getModelWithRequest($req);

        if (!$this->containerSlim->systemOptions->isHideFunctionality() ||
            $this->containerSlim->systemOptions->isDevMode()) {
            $this->data['items'] = $model->paginate($this->pagecount);
        } elseif ($this->containerSlim->systemOptions->isHideFunctionality()) {
            $this->data['items'] = $model->where('frozen', '!=', 1)->orWhere('code', 'develop_mode')->paginate($this->pagecount);
        }

        $this->data['items']->setPath($this->router->pathFor($this->data['all_e_link']));
        $t = $model->getColumnsNames(['GroupName']);
        $this->data['fields'] = $this->getFields($t, ['id'], ['values', 'type', 'options_group_id', 'frozen']);

        $userField = ModelsFactory::getModel('UserViewsSettings');
        $userField = $userField->where('user_id', Session::get('user')['id'])->where('group', $this->data['all_e_link'])->where('code', 'show_fields_in_table')->first();

        $this->data['showFields'] = array();
        if ($userField) {
            $this->data['showFields'] = (array) json_decode($userField->toArray()['value']);
            $this->data['fields'] = $this->data['showFields'];
        }

        $this->data['allFields'] = array_diff($model->getColumnsNames(), $this->data['showFields']);

        $this->data['developMode'] = $this->containerSlim->systemOptions->isDevMode();

        $this->render('admin\optionsTable.twig');
    }

    /**
     * @param request $req
     * @param $res
     */
    public function add(request $req, $res)
    {
        $this->initRoute($req, $res);

        $model = ModelsFactory::getModelWithRequest($req);
        $builder = new BuildFields();
        $builder->setFields($model->getColumnsNames())->addJsonShema($model->getAnnotations())->build();
        $builder->setType('options_group_id', 'select');
        $model = ModelsFactory::getModel('GroupOptions');
        foreach ($model->where('active', 1)->get() as $item) {
            $builder->getField('options_group_id')->values[$item->id] = $item->name;
        }
        $builder->getField('value')->noVisible();

        $this->data['ttt'] = $builder->getAll();

        $this->render('admin\addTables.twig');
    }

    /**
     * @param request $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function edit(request $req, $res, $args)
    {
        $this->initRoute($req, $res);

        $model = ModelsFactory::getModelWithRequest($req);
        $this->data['fields'] = $this->getFields($model->getColumnsNames(), ['id']);
        $this->data['fieldsValues'] = $model->find($args['id']);
        $this->data['type_link'] = $this->data['save_link'];

        if ($this->data['fieldsValues']['frozen'] &&
            (!$this->containerSlim->systemOptions->isDevMode() &&
                $this->data['fieldsValues']['code'] != 'develop_mode')) {
            $this->flash->addMessage('errors', $this->controllerName . ' this value not editable, set developers mode.');
            return $res->withStatus(302)->withHeader('Location', $this->router->pathFor('list.' . $this->controllerName));
        }

        $builder = new BuildFields();
        $builder->setFields($model->getColumnsNames())->addJsonShema($model->getAnnotations());
        $builder->build();
        $builder->setType('id', 'hidden');
        $builder->setType('options_group_id', 'select');
        $builder->setType('value', $this->data['fieldsValues']->type);
        if (in_array($this->data['fieldsValues']->type, ['select', 'multiselect', 'checkbox', 'radio']) && $this->data['fieldsValues']->values) {
            $builder->getField('value')->values = json_decode($this->data['fieldsValues']->values);
        }

        $model = ModelsFactory::getModel('GroupOptions');
        foreach ($model->where('active', 1)->get() as $item) {
            $builder->getField('options_group_id')->values[$item->id] = $item->name;
        }
        foreach ($this->data['fields'] as $name) {
            $builder->getField($name)->setValue($this->data['fieldsValues']->$name);
        }

        if ($this->containerSlim->systemOptions->isHideFunctionality()) {
            $builder->getField('values')->noVisible();
            $builder->getField('type')->noVisible();
            $builder->getField('frozen')->noVisible();
            $builder->getField('code')->noVisible();
        }
        if ($this->containerSlim->systemOptions->isDevMode() &&
            !$this->data['fieldsValues']->frozen) {
            $builder->getField('values')->noVisible(false);
            $builder->getField('type')->noVisible(false);
        }

        $this->data['ttt'] = $builder->getAll();

        $this->render('admin\addTables.twig');
    }
}
