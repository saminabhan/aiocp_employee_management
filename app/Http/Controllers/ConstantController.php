<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use Illuminate\Http\Request;

class ConstantController extends Controller
{
    public function index()
    {
        $constants = Constant::whereNull('parent')
            ->with('children')
            ->get();

        return view('constants.index', compact('constants'));
    }

   public function create(Request $request)
{
    $parentName = null;

    if ($request->has('parent')) {
        $parent = Constant::find($request->parent);
        $parentName = $parent ? $parent->name : null;
    }

        $governorates = Constant::childrenOfId(14)->get();

return view('constants.create', compact('parentName', 'governorates'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'parent' => 'nullable|integer|exists:constants,id',
        'governorate_id' => 'nullable|integer|exists:constants,id',
        'description' => 'nullable|string',
    ]);

    if (Constant::where('name', $request->name)->exists()) {
        return back()
            ->withErrors(['name' => 'اسم الثابت موجود بالفعل'])
            ->withInput();
    }

    Constant::create([
        'name' => $request->name,
        'parent' => $request->parent,
        'governorate_id' => $request->governorate_id,
        'description' => $request->description,
    ]);

    return redirect()->route('constants.index')
        ->with('success', 'تم إضافة الثابت "' . $request->name . '" بنجاح!');
}

    public function edit($id)
    {
        $constant = Constant::findOrFail($id);

        $parents = Constant::whereNull('parent')->get();

        $governorates = Constant::childrenOfId(14)->get();

        return view('constants.edit', compact('constant', 'parents', 'governorates'));
    }

public function update(Request $request, $id)
{
    $constant = Constant::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'parent' => 'nullable|integer|exists:constants,id',
        'governorate_id' => 'nullable|integer|exists:constants,id',
        'description' => 'nullable|string',
    ]);

    $exists = Constant::where('name', $request->name)
                      ->where('id', '!=', $id)
                      ->exists();

    if ($exists) {
        return back()
            ->withErrors(['name' => 'اسم الثابت موجود مسبقًا'])
            ->withInput();
    }

    $constant->update([
        'name' => $request->name,
        'parent' => $request->parent,
        'governorate_id' => $request->governorate_id,
        'description' => $request->description,
    ]);

    return redirect()->route('constants.index')
        ->with('success', 'تم التعديل بنجاح');
}

    public function destroy($id)
    {
        $constant = Constant::with('children')->findOrFail($id);

        $constant->deleteWithChildren();

        return redirect()->route('constants.index')
            ->with('success', 'تم حذف الثابت "' . $constant->name . '" بنجاح!');

    }

}
