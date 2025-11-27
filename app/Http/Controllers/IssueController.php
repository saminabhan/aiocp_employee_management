<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use App\Models\Engineer;
use App\Models\Issue;
use App\Models\IssueAttachment;
use Illuminate\Http\Request;

class IssueController extends Controller
{

public function index(Request $request)
{
    $user = auth()->user();

    $query = Issue::with(['engineer', 'user', 'problem']);

    switch ($user->role->name) {

        case 'engineer':
            $query->where(function ($q) use ($user) {
                $q->where('engineer_id', $user->engineer_id)
                  ->orWhere('user_id', $user->id);
            });
            break;

        case 'survey_supervisor':
            $query->where(function ($q) use ($user) {
                $q->whereHas('engineer', function ($eng) use ($user) {
                    $eng->where('main_work_area_code', $user->main_work_area_code);
                })
                ->orWhere('user_id', $user->id);
            });
            break;

        case 'governorate_manager':
            $query->where(function ($q) use ($user) {
                $q->whereHas('engineer', function ($eng) use ($user) {
                    $eng->where('work_governorate_id', $user->governorate_id);
                })
                ->orWhere('user_id', $user->id);
            });
            break;

        case 'system_admin':
        case 'support':
        default:
            break;
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
              ->orWhereHas('problem', function($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    $issues = $query->latest()->paginate(15);

    $statsQuery = Issue::query();

    switch ($user->role->name) {

        case 'engineer':
            $statsQuery->where(function ($q) use ($user) {
                $q->where('engineer_id', $user->engineer_id)
                  ->orWhere('user_id', $user->id);
            });
            break;

        case 'survey_supervisor':
            $statsQuery->where(function ($q) use ($user) {
                $q->whereHas('engineer', function ($eng) use ($user) {
                    $eng->where('main_work_area_code', $user->main_work_area_code);
                })
                ->orWhere('user_id', $user->id);
            });
            break;

        case 'governorate_manager':
            $statsQuery->where(function ($q) use ($user) {
                $q->whereHas('engineer', function ($eng) use ($user) {
                    $eng->where('work_governorate_id', $user->governorate_id);
                })
                ->orWhere('user_id', $user->id);
            });
            break;

        case 'system_admin':
        case 'support':
        default:
            break;
    }

    $stats = [
        'total'        => (clone $statsQuery)->count(),
        'open'         => (clone $statsQuery)->open()->count(),
        'in_progress'  => (clone $statsQuery)->inProgress()->count(),
        'closed'       => (clone $statsQuery)->closed()->count(),
    ];

    $problemTypes = Constant::childrenOfId(41)->get();

    return view('issues.index', compact('issues', 'problemTypes', 'stats'));
}

public function create()
{
    $problemTypes = Constant::childrenOfId(41)->get();
    $attachmentTypes = Constant::childrenOfId(51)->get();

    $user = auth()->user();
    $eng = [];

    switch ($user->role->name) {

        case 'system_admin':
            $eng = Engineer::where('is_active', true)->get();
            break;

        case 'governorate_manager':
            $eng = Engineer::where('is_active', true)
                           ->where('work_governorate_id', $user->governorate_id)
                           ->get();
            break;

        case 'survey_supervisor':
            $eng = Engineer::where('is_active', true)
                           ->where('main_work_area_code', $user->main_work_area_code)
                           ->get();
            break;

        case 'engineer':
            $eng = [];
            break;

        default:
            $eng = [];
            break;
    }

    return view('issues.create', [
        'problemTypes' => $problemTypes,
        'engineers' => $eng,
        'attachmentTypes' => $attachmentTypes
    ]);
}

public function store(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'engineer_id' => 'nullable|exists:engineers,id',
        'problem_type_id' => 'required|exists:constants,id',
        'description' => 'required|string|max:5000',
        'priority' => 'required|in:low,medium,high',

        'attachments.*.attachment_type_id' => 'required|exists:constants,id',
        'attachments.*.file' => 'required|file|mimes:jpg,jpeg,png,pdf,mp4|max:20480',
    ]);


    switch ($user->role->name) {

        case 'system_admin':
            $validated['user_id'] = $user->id;
            break;

        case 'governorate_manager':
            if ($validated['engineer_id']) {
                $eng = Engineer::find($validated['engineer_id']);

                if ($eng->work_governorate_id != $user->governorate_id) {
                    abort(403, "لا تملك صلاحية تحويل تذكرة لهذا المهندس");
                }
            }
            $validated['user_id'] = $user->id;
            break;

        case 'survey_supervisor':
            if ($validated['engineer_id']) {
                $eng = Engineer::find($validated['engineer_id']);

                if ($eng->main_work_area_code != $user->main_work_area_code) {
                    abort(403, "لا يمكنك تحويل تذكرة لمهندس خارج منطقة العمل");
                }
            }
            $validated['user_id'] = $user->id;
            break;

        case 'engineer':
            $validated['engineer_id'] = $user->engineer_id;
            $validated['user_id'] = null;
            break;

        default:
            $validated['engineer_id'] = null;
            $validated['user_id'] = $user->id;
            break;
    }


    $issue = Issue::create($validated);

    if ($request->has('attachments')) {
        foreach ($request->attachments as $att) {
            $file = $att['file'];
            $path = $file->store('issue_attachments', 'public');

            IssueAttachment::create([
                'issue_id' => $issue->id,
                'attachment_type_id' => $att['attachment_type_id'],
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
    }

    return redirect()->route('issues.index')
        ->with('success', 'تم إنشاء التذكرة بنجاح');
}

public function show(Issue $issue)
{
    $user = auth()->user();

    if ($issue->user_id == $user->id) {
        $issue->load(['engineer', 'user', 'problem', 'attachments']);
        return view('issues.show', compact('issue'));
    }

    switch ($user->role->name) {

        case 'admin':
        case 'support':
            break;

        case 'engineer':
            if ($user->engineer_id != $issue->engineer_id) {
                abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
            }
            break;

        case 'survey_supervisor':
            if (!$issue->engineer || $issue->engineer->main_work_area_code != $user->main_work_area_code) {
                abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
            }
            break;

        case 'governorate_manager':
            if (!$issue->engineer || $issue->engineer->work_governorate_id != $user->governorate_id) {
                abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
            }
            break;

        default:
            abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
    }

    $issue->load(['engineer', 'user', 'problem', 'attachments']);

    return view('issues.show', compact('issue'));
}

private function authorizeEngineer(Engineer $engineer)
{
    $user = auth()->user();

    if ($user->role->name === 'admin') {
        return true;
    }

    if ($user->role->name === 'governorate_manager') {

        if ($engineer->work_governorate_id != $user->governorate_id) {
            abort(403, 'غير مصرح لك بالوصول إلى هذا المهندس.');
        }

        return true;
    }

    abort(403, 'ليس لديك صلاحية لعرض هذا المهندس.');
}

    public function updateStatus(Request $request, Issue $issue)
    {
        $user = auth()->user();

        if (!$user->hasRole(['admin', 'support'])) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,closed',
            'solution' => 'nullable|string|max:5000',
        ]);

        $issue->update($validated);

        return redirect()->back()->with('success', 'تم تحديث حالة التذكرة بنجاح');
    }

    public function destroy(Issue $issue)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin')) {
            abort(403);
        }

        $issue->delete();

        return redirect()->route('issues.index')
                         ->with('success', 'تم حذف التذكرة بنجاح');
    }
}
