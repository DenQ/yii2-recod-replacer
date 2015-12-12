<?php
/**
 * Создает запись, если ее еще нет,
 * а если такая запись уже есть, то обновляет ее
 * @todo: создать пакет в composer
 *
 * User: denq
 * Date: 31.10.15
 * Time: 20:43
 */

namespace RecordReplacer;

class RecordReplacer {

    /**
     * @var \yii\db\ActiveRecord null
     */
    public $model = null;
    public $params = [];
    public $primary = [];
    public $exclusion = [];

    /**
     * @var \yii\db\ActiveRecord null
     */
    public $resultModel = null;

    /**
     * Возвращает название модели
     * @param $model \yii\db\ActiveRecord
     * @return string
     */
    private function GetClassName() {
        $matches = explode('\\', $this->model->className());
        return $matches[count($matches)-1];
    }

    /**
     * @param $model \yii\db\ActiveRecord
     * @param $params mixed
     * @param $primary mixed
     * @param $primary mixed
     * @return string
     */
    public function Run($model, $params, $primary = [], $exclusion = []) {
        $this->SetVariables($model, $params, $primary, $exclusion);
        if ( $this->Get() === null ) {
            $result = $this->Post();
        } else {
            $result = $this->Put();
        }
        $this->CleanVariables();
        return $result;
    }

    private function Post() {
        $params = $this->params;
        $params = [
            $this->GetClassName() => $params,
        ];
        if ($this->model->load($params) && $this->model->save()) {
            return $this->model;
        } return null;
    }

    private function Put() {
        $params = [
            $this->GetClassName() => $this->ExclusionFilter(),
        ];
        if ($this->resultModel->load($params) && $this->resultModel->save()) {
            return $this->resultModel;
        } return null;
    }

    private function ExclusionFilter() {
        $results = [];
        foreach($this->params as $key => $val) {
            if (!in_array($key, $this->exclusion)) {
                $results[$key] = $val;
            }
        } return $results;
    }

    private function Get() {
        $model = $this->model;
        $model = $model::find()
            ->where( $this->GetCriteria() )
            ->one();
        $this->resultModel = $model;
        return $model;
    }

    private function GetCriteria() {
        $criteria = [];
        foreach($this->primary as $item) {
            if (array_key_exists($item, $this->params)) {
                $criteria[$item] = $this->params[$item];
            }
        } return $criteria;
    }

    private function SetVariables($model, $params, $primary = [], $exclusion = []) {
        $this->model = $model;
        $this->params = $params;
        $this->primary = $primary;
        $this->exclusion = $exclusion;
    }

    private function CleanVariables() {
        $this->model = null;
        $this->params = [];
        $this->primary = [];
        $this->exclusion = [];
        $this->resultModel = null;
    }

}