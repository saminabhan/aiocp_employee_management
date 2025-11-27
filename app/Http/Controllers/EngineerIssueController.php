<?php

namespace App\Http\Controllers;

use App\Models\AppIssue;
use App\Models\Engineer;
use App\Models\Issue;
use Illuminate\Http\Request;

class EngineerIssueController extends Controller
{
    public function store(Request $request, Engineer $engineer)
    {
        $request->validate([
            'problem_type_id' => 'required|integer|exists:constants,id',
            'description'     => 'required|min:5',
        ]);

        Issue::create([
            'engineer_id'      => $engineer->id,
            'problem_type_id'  => $request->problem_type_id,
            'description'      => $request->description,
            'status'           => 'open',
            'priority'         => 'medium',
        ]);

        return redirect()
            ->back()
            ->with('active_tab', $request->active_tab)
            ->with('success', 'تم إضافة المشكلة بنجاح');}

    public function updateStatus(Request $request, Issue $issue)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
            'solution' => 'nullable|required_if:status,closed|min:5',
        ]);

        $issue->update([
            'status'   => $request->status,
            'solution' => $request->status == 'closed' ? $request->solution : null,
        ]);


return redirect()
    ->back()
    ->with('active_tab', $request->active_tab)
    ->with('success', 'تم تحديث حالة المشكلة بنجاح');
    }
}
