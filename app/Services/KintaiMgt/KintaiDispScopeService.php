<?php

namespace App\Services\KintaiMgt;

use Illuminate\Support\Facades\Auth;
use App\Models\Kintai;
use App\Models\Base;
use App\Enums\RoleEnum;

class KintaiDispScopeService
{
    // 勤怠表示範囲の条件をクエリに追加（employeesテーブルを結合している前提）
    public function applyScope($query)
    {
        $user = Auth::user();
        // 自分のみ
        if($user->role->kintai_disp_scope == RoleEnum::KINTAI_DISP_SCOPE_SELF){
            // 従業員番号が未設定の場合は何も表示しない
            if(is_null($user->employee_no)){
                return $query->whereRaw('0 = 1');
            }
            return $query->where('employees.employee_no', $user->employee_no);
        }
        // 自拠点
        if($user->role->kintai_disp_scope == RoleEnum::KINTAI_DISP_SCOPE_BASE){
            return $query->where('employees.base_id', $user->base_id);
        }
        // 全拠点
        if($user->role->kintai_disp_scope == RoleEnum::KINTAI_DISP_SCOPE_ALL){
            return $query;
        }
        // 想定外の値の場合は何も表示しない
        return $query->whereRaw('0 = 1');
    }

    // 勤怠表示範囲で選択可能な拠点を取得（全拠点でない場合は自拠点のみ）
    public function getDispBases()
    {
        if(Auth::user()->role->kintai_disp_scope == RoleEnum::KINTAI_DISP_SCOPE_ALL){
            return Base::getAll()->get();
        }
        return Base::getSpecify(Auth::user()->base_id)->get();
    }

    // 指定された勤怠IDの中から表示可能な勤怠IDのみを取得
    public function getDispKintaiIds($kintai_ids)
    {
        $query = Kintai::whereIn('kintai_id', $kintai_ids)
                    ->join('employees', 'employees.employee_id', 'kintais.employee_id');
        return $this->applyScope($query)->pluck('kintais.kintai_id')->toArray();
    }

    // 指定された勤怠が表示可能か判定
    public function isDispKintai($kintai_id)
    {
        return !empty($this->getDispKintaiIds([$kintai_id]));
    }
}
