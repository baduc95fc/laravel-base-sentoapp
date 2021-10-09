<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Config;

class CommonFunctions {

    /**
     * Check time expire
     *
     * @param $time
     * @return bool
     */
    public static function checkExpireTime($time) {
        return isset($time) ? \Carbon\Carbon::parse($time)->addHours(Config::get('constants.PASSWORD_USER_EXPIRE'))->isPast() : true;
    }

    /**
     * Get html button edit and delete
     *
     * @param $id
     * @param $urlEdit
     * @param $urlDelete
     * @param $editMethod
     * @param $deleteMethod
     * @param $deleteTitle
     * @param $isView
     * @return string
     *
     * @author SD
     */
    public static function getHtmlEditAndDelete($id, $urlEdit, $urlDelete, $editMethod = 'PUT', $deleteMethod = 'DELETE', $deleteTitle = '', $isView = false) {
        $html = '<div class="btn-group btn-group-xs">';
        if ($isView == true) {
            $html .= '<a href="#" title="Xem chi tiết" class="btn btn-warning viewRecordDetail" data-toggle="modal" data-target="#viewRecordDetail" data-id="'.$id.'"><i class="fa fa-eye icon-only"></i></a>';
        }
        if ($urlEdit != 'NONE') {
            $html .= '<a href="#" title="Sửa" class="btn btn-inverse editRecord" data-toggle="modal" data-target="#edit-Record" data-method="'.$editMethod.'" data-url="'.$urlEdit.'" data-id="'.$id.'"><i class="fa fa-pencil icon-only"></i></a>';
        }
        if ($urlDelete != 'NONE') {
            $html .= '<a href="javascript:void(0)" class="btn btn-danger removeRecord" title="Xóa" data-method="'.$deleteMethod.'" data-url="'.$urlDelete.'" data-id="'.$id.'" data-delete-title="'.$deleteTitle.'"><i class="fa fa-times icon-only"></i></a>';
        }
        $html .= '</div>';
        return $html;
    }
    /**
     * Get html button edit and delete
     *
     * @param $id
     * @param $urlEdit
     * @param $editMethod
     * @param $deleteTitle
     * @return string
     *
     * @author SD
     */
    public static function getHtmlEdit($id, $urlEdit, $editMethod = 'PUT', $deleteTitle = '') {
        $html = '<div class="btn-group btn-group-xs"><a href="#" title="Sửa" class="btn btn-inverse editRecord" data-toggle="modal" data-target="#edit-Record" data-method="'.$editMethod.'" data-url="'.$urlEdit.'" data-id="'.$id.'"><i class="fa fa-pencil icon-only"></i></a></div>';
        return $html;
    }
    /**
     * Get html button edit and delete
     *
     * @param $id
     * @param $urlDelete
     * @param $deleteMethod
     * @param $deleteTitle
     * @return string
     *
     * @author SD
     */
    public static function getHtmlDelete($id, $urlDelete, $deleteMethod = 'DELETE', $deleteTitle = '') {
        $html = '<div class="btn-group btn-group-xs"><a href="javascript:void(0)" class="btn btn-danger removeRecord" title="Xóa" data-method="'.$deleteMethod.'" data-url="'.$urlDelete.'" data-id="'.$id.'" data-delete-title="'.$deleteTitle.'"><i class="fa fa-times icon-only"></i></a></div>';
        return $html;
    }

    /**
     * Create key random
     *
     * @param $length
     * @return string
     *
     * @author SD
     */
    public static function rand_string($length) {
        $chars = "0123456789";
        $size = strlen($chars);
        $str = '';
        for($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }
}
