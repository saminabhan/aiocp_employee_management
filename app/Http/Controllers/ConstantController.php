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

    return view('constants.create', compact('parentName'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'parent' => 'nullable|integer|exists:constants,id',
        ]);

        Constant::create([
            'name' => $request->name,
            'parent' => $request->parent,
        ]);

    return redirect()->route('constants.index')
        ->with('success', 'تم إضافة الثابت "' . $request->name . '" بنجاح!');
        }

    public function edit($id)
    {
        $constant = Constant::findOrFail($id);

        $parents = Constant::whereNull('parent')->get();

        return view('constants.edit', compact('constant', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $constant = Constant::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'parent' => 'nullable|integer|exists:constants,id',
        ]);

        $constant->update([
            'name' => $request->name,
            'parent' => $request->parent,
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
