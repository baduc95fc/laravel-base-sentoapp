<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CommonFunctions;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repository\Contracts\UserInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $user;

    public function __construct(
        UserInterface $user
    ) {
        $this->user = $user;
    }

    /**
     * index page
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function index() {
        return view('admin.administrator.index');
    }

    /**
     * Get datatable list user
     *
     * @param $request
     *
     * @return mixed
     *
     * @author SD
     */
    public function getDataUserAdministrator(Request $request) {
        $columns = [
            0 => 'id',
            1 => 'email',
            2 => 'name',
            3 => 'status',
            4 => 'options'
        ];
        $searchWord = $request->input('search.value');
        $start = $request->input('start');
        $limit = $request->input('length');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $users = $this->user->findAllAdministrator($searchWord, $start, $limit, $order, $dir);
        $data = [];
        if(!empty($users['data'])) {
            foreach ($users['data'] as $index => $item) {
                $id = $item->id;
                $urlEdit = '/admin/getDataAdministratorById/' . $id;
                $urlDelete = '/admin/deleteAdministrator';
                $editMethod = 'GET';
                $deleteMethod = 'POST';
                $urlStatus = "/admin/updateStatusAdministrator";
                $tableName = "#table-users";
                $deleteTitle = isset($item->email) ? $item->email : '';
                $nestedData['index'] = ++$index + $start;
                $nestedData['name'] = isset($item->name) ? $item->name : '---';
                $nestedData['email'] = isset($item->email) ? $item->email : '---';
                if ($item->type == Config::get('constants.TYPE_USER.ADMIN')) {
                    $nestedData['status'] = '---';
                } else {
                    $nestedData['status'] = $item->status ? '<span class="label label-active btnChangeStatus" data-status="'.$item->status.'" data-id="'.$item->id.'" data-url="'.$urlStatus.'" data-template="'.$tableName.'">Active</span>' : '<span class="label label-inverse btnChangeStatus" data-status="'.$item->status.'" data-id="'.$item->id.'" data-url="'.$urlStatus.'" data-template="'.$tableName.'">Disabled</span>';
                }
                if ($item->type == Config::get('constants.TYPE_USER.ADMIN')) {
                    $nestedData['options'] = '---';
                } else {
                    $nestedData['options'] = CommonFunctions::getHtmlEditAndDelete($id, $urlEdit, $urlDelete, $editMethod, $deleteMethod, $deleteTitle);
                }
                $data[] = $nestedData;
            }
        }
        $result = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($users['recordsTotal']),
            "recordsFiltered" => intval($users['recordsTotal']),
            "data"            => $data
        );
        return response()->json($result);
    }

    /**
     * Save data user form
     *
     * @param  mixed
     *
     * @return mixed response json
     *
     * @author SD
     */
    public function store(Request $request) {
        $apiFormat = array();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'gender' => 'required',
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'email.email' => 'Định dạng email chưa chính xác',
            'email.unique' => 'Email này đã tồn tại trong hệ thống',
            'password.min' => 'Mật khẩu có độ dài ít nhất 8 ký tự',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        try {
            DB::beginTransaction();
            $result = $this->user->create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'gender' => $request->input('gender'),
                'type' => Config::get('constants.TYPE_USER.ADMIN'),
                'status' => $request->input('status')
            ]);
            if ($result) {
                $apiFormat['message'] = 'Thêm thành công';
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $apiFormat['message'] = $e->getMessage();
        }
        return response()->json($apiFormat);
    }

    /**
     * GEt data user by Id
     *
     * @param  mixed
     *
     * @return mixed response json
     *
     * @author
     */
    public function edit($id) {
        $apiFormat = array();
        $data = $this->user->firstWhere(['id' => $id], ['*']);
        if (!$data) {
            return response()->json(['status' => 0, 'message' => 'The new is not exists']);
        } else {
            $apiFormat['data'] = $data;
        }
        return response()->json($apiFormat);

    }

    /**
     * Update data user form
     *
     * @param  mixed
     *
     * @return mixed response json
     *
     * @author SD
     */
    public function update(Request $request) {
        $data = [];
        $id = $request->input('id');
        if (!isset($id)) {
            return response()->json(['status' => 0, 'message' => 'ID không tồn tại']);
        }
        $apiFormat = [];
        $validator = Validator::make($request->all(), [
            'name_edit' => 'required',
            'email_edit' => 'required|email|unique:users,email,' . $id,
            'gender_edit' => 'required',
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'email_edit.email' => 'Định dạng email chưa chính xác',
            'email_edit.unique' => 'Email này đã tồn tại trong hệ thống',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors_edit'=>$validator->errors()->all()]);
        }
        if ($request->input('password_edit') !='') {
            $data['password'] = bcrypt($request->input('password_edit'));
        }
        try {
            $data['name'] = $request->input('name_edit');
            $data['email'] = $request->input('email_edit');
            $data['gender'] = $request->input('gender_edit');
            $data['type'] = Config::get('constants.TYPE_USER.ADMIN');
            $data['status'] = $request->input('status_edit');
            DB::beginTransaction();
            $this->user->update($data, $id);
            $apiFormat['message'] = 'Cập nhật thành công';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $apiFormat['message'] = $e->getMessage();
        }
        return response()->json($apiFormat);
    }

    /**
     * delete administrator
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function delete($id) {
        $apiFormat = array();
        $apiFormat['status'] = Config::get('constants.STATUS.INACTIVE');
        try {
            DB::beginTransaction();
            $this->user->delete($id);
            DB::commit();
            $apiFormat['status'] = Config::get('constants.STATUS.ACTIVE');
            $apiFormat['message'] = 'Xóa thành công';
        } catch (\Exception $e) {
            DB::rollBack();
            $apiFormat['message'] = 'Có lỗi xảy ra';
        }
        return response()->json($apiFormat);
    }

    /**
     * Update status level
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function updateStatus(Request $request) {
        $status = $request->input('status');
        if ($status == Config::get('constants.STATUS.ACTIVE')) {
            $statusAfter = Config::get('constants.STATUS.INACTIVE');
        } else {
            $statusAfter = Config::get('constants.STATUS.ACTIVE');
        }
        try {
            DB::beginTransaction();
            $result = $this->user->update([
                "status" => $statusAfter
            ], $request->input('id'));
            if ($result->status == Config::get('constants.STATUS.INACTIVE') && $result->access_token != '') {
                try {
                    JWTAuth::setToken($result->access_token)->invalidate();
                } catch (\Exception/*\Tymon\JWTAuth\Exceptions\TokenBlacklistedException*/ $e) {

                }
            }
            DB::commit();
            return response()->json(['data' => $result, 'error' => false]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => ""]);
        }
    }

    /**
     * index page member
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function member() {
        return view('admin.member.index');
    }

    /**
     * Get datatable list member
     *
     * @param $request
     *
     * @return mixed
     *
     * @author SD
     */
    public function getDataUserMember(Request $request) {
        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'date_of_birth',
            4 => 'gender',
            5 => 'status'
        ];
        $searchStatus = $request->has('searchStatus') ? $request->input('searchStatus') : '*';
        $searchWord = $request->input('search.value');
        $start = $request->input('start');
        $limit = $request->input('length');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $users = $this->user->findAllMember($searchWord, $start, $limit, $order, $dir, [
            'status' => $searchStatus,
        ]);
        $data = [];
        if(!empty($users['data'])) {
            foreach ($users['data'] as $index => $item) {
                $urlStatus = "/admin/updateStatusAdministrator";
                $id = $item->id;
                $urlEdit = '/admin/getDataMemberById/' . $id;
                $editMethod = 'GET';
                $deleteMethod = 'POST';
                $tableName = "#table-users";
                $deleteTitle = $item->email ? $item->email : '---';
                $nestedData['index'] = ++$index + $start;
                $nestedData['name'] = isset($item->name) ? $item->name : '---';
                $nestedData['email'] = isset($item->email) ? $item->email : '---';
                $nestedData['gender'] = isset($item->gender) ? $item->gender == 1 ? "Nam" : "Nữ" : '---';
                $nestedData['date_of_birth'] = isset($item->date_of_birth) ?  Carbon::parse($item->date_of_birth)->format('d/m/Y') : '---';
                $nestedData['status'] = $item->status == 1 ? '<span class="label label-active btnChangeStatus" data-status="'.$item->status.'" data-id="'.$item->id.'" data-url="'.$urlStatus.'" data-template="'.$tableName.'">Đã kích hoạt</span>' : ( $item->status == 2 ? '<span class="label label-warning btnChangeStatus" data-status="'.$item->status.'" data-id="'.$item->id.'" data-url="'.$urlStatus.'" data-template="'.$tableName.'">Chưa kích hoạt</span>' :'<span class="label label-inverse btnChangeStatus" data-status="'.$item->status.'" data-id="'.$item->id.'" data-url="'.$urlStatus.'" data-template="'.$tableName.'">Khóa</span>');
                $nestedData['options'] = CommonFunctions::getHtmlEditAndDelete($id, 'NONE', 'NONE', $editMethod, $deleteMethod, $deleteTitle, true);
                $data[] = $nestedData;
            }
        }
        $result = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($users['recordsTotal']),
            "recordsFiltered" => intval($users['recordsTotal']),
            "data"            => $data
        );
        return response()->json($result);
    }

    /**
     * View detail member
     *
     * @param $id
     *
     * @return mixed
     *
     * @author SD
     */
    public function viewDetailMember($id) {
        $apiFormat = array();
        $apiFormat['status'] = Config::get('constants.STATUS.INACTIVE');
        if ($this->user->countWhere(['id' => $id], ['*']) == 0) {
            return response()->json(['status' => Config::get('constants.STATUS.INACTIVE'), 'message' => 'Id not found']);
        }
        try {
            $data = User::findOrFail($id);
            $apiFormat['status'] = Config::get('constants.STATUS.ACTIVE');
            $apiFormat['data'] = $data;
        } catch (\Exception $e) {
            $apiFormat['message'] = $e->getMessage();
        }
        return response()->json($apiFormat, 200);
    }

    /**
     * Update status
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function updateStatusMember(Request $request) {
        $status = $request->has('status') ? 1 : 0;
        try {
            DB::beginTransaction();
            $result = $this->user->update([
                "status" => $status
            ], $request->input('id'));
            if ($result->status == Config::get('constants.STATUS.INACTIVE') && $result->access_token != '') {
                try {
                    JWTAuth::setToken($result->access_token)->invalidate();
                } catch (\Exception/*\Tymon\JWTAuth\Exceptions\TokenBlacklistedException*/ $e) {

                }
            }
            DB::commit();
            return response()->json(['error' => false, 'message' => 'Cập nhật thành công']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Change password admin
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return mixed
     *
     * @author SD
     */
    public function changePasswordAdmin(Request $request) {
        $validator = Validator::make($request->all(), [
            'current-password' => 'required',
            'new-password' => 'required|min:8',
            'confirm-password' => 'required|min:8|same:new-password',
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'new-password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
            'confirm-password.min' => 'Xác nhận mật khẩu phải có ít nhất 8 ký tự',
            'confirm-password.same' => 'Xác nhận mật khẩu không giống nhau'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            if (!(Hash::check($request->get('current-password'), Auth::guard('admin')->user()->password))) {
                // The passwords matches
                return response()->json(['errors_old_pass' => "Mật khẩu cũ không đúng. Vui lòng nhập lại !"]);
            }
            if(strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
                //Current password and new password are same
                return response()->json(['errors_confirm_pass' => "Mật khẩu mới không thể giống mật khẩu cũ. Vui lòng chọn lại !"]);
            }
            //Change Password
            $user = Auth::guard('admin')->user();
            $user->password = bcrypt($request->get('new-password'));
            $user->save();
            DB::commit();
            Auth::guard('admin')->logout();
            return redirect('/admin');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/');
        }
    }

    /**
     * profile
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function profile() {
        $info = Auth::guard('admin')->user();
        return view('admin.profile.index', compact('info'));
    }

    /**
     * Update data user form
     *
     * @param  mixed
     *
     * @return mixed response json
     *
     * @author SD
     */
    public function updateProfile(Request $request) {
        $data = [];
        $user = Auth::guard('admin')->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'gender' => 'required',
        ], [
            'required' => 'Các trường có dấu (*) là bắt buộc nhập',
            'email.email' => 'Định dạng email chưa chính xác',
            'email.unique' => 'Email này đã tồn tại trong hệ thống',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return redirect()->back()->with('error', $message);
        }
        if ($request->input('password_edit') !='') {
            $data['password'] = bcrypt($request->input('password_edit'));
        }
        try {
            $data['name'] = $request->input('name');
            $data['email'] = $request->input('email');
            $data['gender'] = $request->input('gender');
            DB::beginTransaction();
            $this->user->update($data, $user->id);
            DB::commit();
            return redirect()->back()->with('message', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại');
        }
    }

    /**
     * search user
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function searchUserAutoComplete(Request $request) {
        $jsonFormat['status'] = Config::get('constants.STATUS.ACTIVE');
        return response()->json($this->user->searchUserAutoComplete($request->input('q')));
    }
}
