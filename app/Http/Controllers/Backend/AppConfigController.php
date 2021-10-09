<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Repository\Contracts\AppConfigInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CommonFunctions;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AppConfigController extends Controller
{
    protected $appConfig;

    public function __construct(
        AppConfigInterface $appConfig
    ) {
        $this->appConfig = $appConfig;
    }

    /**
     * index, edit app config
     *
     * @return mixed
     *
     * @author SD
     */
    public function index() {
        $use_of_terms = isset($this->appConfig->firstWhere(['key' => 'use_of_terms'])->value) ? $this->appConfig->firstWhere(['key' => 'use_of_terms'])->value : null;
        return view('admin.appConfig.index', compact(
                    'use_of_terms'

        ));
    }
    /**
     * update config
     *
     * @param  Request $request
     *
     * @return mixed
     *
     * @author SD
     */
    public function updateAppConfig(Request $request) {
        $status = Config::get('constants.STATUS.INACTIVE');
        $validator = Validator::make($request->all(), [
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc',
        ]);
        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return redirect()->back()->with('error', $message);
        }
        try {
            DB::beginTransaction();
            $use_of_terms = isset($request->use_of_terms) ? $request->use_of_terms : '';
            $data = [
                'use_of_terms' => $use_of_terms
            ];
            $this->appConfig->configSave($data);
            DB::commit();
            return redirect()->back()->with('message', 'Cập nhật thành công!');
        } catch(\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại');
        }
    }
}
