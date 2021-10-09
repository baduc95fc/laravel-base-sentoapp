<?php

namespace App\Repository\Eloquent;

use App;
use App\Repository\Contracts;
use DB;
use App\Repository\Contracts\AppConfigInterface as AppConfigInterface;
use Illuminate\Support\Facades\Auth;

class AppConfigRepository extends BaseRepository implements AppConfigInterface
{

    protected function model() {
        return \App\AppConfig::class;
    }
    
    public function exists($key) {
        $query = $this->model->where('key', $key);
        return $query->count() == 1;
    }

    public function save($key, $value) {
        $config_data = array(
            'key' => $key,
            'value' => $value
        );
        if (!$this->exists($key)) {
            return $this->create($config_data);
        }
        return $this->model->where('key', $key)->update($config_data);
    }

    public function configSave($data) {
        $success = true;
        foreach($data as $key => $value) {
            if(!$this->save($key, $value)) {
                $success = false;
                break;
            }
        }
        return $success;
    }
}