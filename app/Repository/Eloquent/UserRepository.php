<?php

namespace App\Repository\Eloquent;

use App;
use App\Repository\Contracts;
use \Illuminate\Support\Facades\DB;
use App\Repository\Contracts\UserInterface as UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class UserRepository extends BaseRepository implements UserInterface
{

    protected function model() {
        return \App\User::class;
    }

    /**
     * search user
     *
     * @param $searchWord
     * @return mixed
     *
     * @author SD
     */
    public function searchUserAutoComplete($searchWord = '') {
        $query = $this->model->where([['type', '=', Config::get('constants.TYPE_USER.MEMBER')]]);
        if ($searchWord !== null && $searchWord != '') {
            $query = $this->model
                ->where([
                    ['type', '=', \Config::get('constants.TYPE_USER.MEMBER')],
                    [
                        function ($query) use ($searchWord) {
                            $query->where([
                                ['email', 'LIKE', '%' . $searchWord . '%']
                            ])->orWhere([
                                ['name', 'LIKE', '%' . $searchWord . '%']
                            ]);
                        }
                    ]
                ]);
        }
        $data = $query->skip(0)->take(100)->get();
        return $data;
    }

    /**
     * get admin
     *
     * @param $searchWord
     * @param $start
     * @param $limit
     * @param $order
     * @param $orderBy
     * @return array
     *
     * @author SD
     */
    public function findAllAdministrator($searchWord, $start, $limit, $order, $orderBy) {
        $query = $this->model->where([['type', '=', Config::get('constants.TYPE_USER.ADMIN')]]);
        if ($searchWord !== null && $searchWord != '') {
            $query = $this->model
                ->where([
                    ['type', '=', \Config::get('constants.TYPE_USER.ADMIN')],
                    [
                        function ($query) use ($searchWord) {
                            $query->where([
                                ['email', 'LIKE', '%' . $searchWord . '%']
                            ])->orWhere([
                                ['name', 'LIKE', '%' . $searchWord . '%']
                            ]);
                        }
                    ]
                ]);
        }
        $count = $query->count();
        $query = $this->buildOrderBy($query, $order, $orderBy);
        $data = $query->skip($start)->take($limit)->get();
        return [
            'data' => $data,
            'recordsTotal' => $count
        ];
    }

    /**
     * get users
     *
     * @param $searchWord
     * @param $start
     * @param $limit
     * @param $order
     * @param $orderBy
     * @param $filter
     * @return array
     *
     * @author SD
     */
    public function findAllMember($searchWord, $start, $limit, $order, $orderBy, $filter = []) {
        if ($filter['status'] != '*') {
            $status = $filter['status'];
        }
        $arrClause = [
            ['type', '=', \Config::get('constants.TYPE_USER.MEMBER')]
        ];
        if (isset($status)) {
            array_push($arrClause, ['users.status', '=', $status]);
        }
        $query = $this->model;
        $query = $query->where($arrClause);
        if ($searchWord !== null && $searchWord != '') {
            array_push($arrClause, [
                function ($query) use ($searchWord) {
                    $query->where([
                        ['users.email', 'LIKE', '%' . $searchWord . '%']
                    ])->orWhere([
                        ['users.name', 'LIKE', '%' . $searchWord . '%']
                    ]);
                }
            ]);
            $query = $this->model->where($arrClause);
        }
        $selectArray = [];
        array_push($selectArray, 'users.*');
        $query = $query->select($selectArray);
        $count = $query->count();
        $query = $this->buildOrderBy($query, $order, $orderBy);
        $data = $query->skip($start)->take($limit)->get();
        return [
            'data' => $data,
            'recordsTotal' => $count
        ];
    }
}
