<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use Illuminate\Http\Request;

class WorkCodeController extends Controller
{
    public function index()
    {
        // الجذر (الكود الرئيسي)
        $root = Constant::where('id', 55)->first();

        // المحافظات (items التي لها governorate_id فريد)
        $governorateIds = Constant::whereNotNull('governorate_id')
            ->groupBy('governorate_id')
            ->pluck('governorate_id');

        $governorates = [];

        foreach ($governorateIds as $govId) {
            $governorates[] = [
                'id'    => $govId,
                'name'  => Constant::find($govId)?->name ?? "محافظة رقم $govId",
                'codes' => Constant::where('governorate_id', $govId)->get()
            ];
        }

        return view('work_codes.index', compact('root', 'governorates'));
    }
}
