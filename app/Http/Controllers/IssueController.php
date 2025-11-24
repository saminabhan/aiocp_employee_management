<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use App\Models\Engineer;
use App\Models\Issue;
use App\Models\IssueAttachment;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * عرض قائمة التذاكر
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Issue::with(['engineer', 'user', 'problem']);

        if ($user->engineer_id != null) {
            $query->where('engineer_id', $user->engineer_id);
        } elseif (!$user->hasRole(['admin', 'support'])) {
            $query->where('user_id', $user->id);
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

        $problemTypes = Constant::childrenOfId(41)->get();

        $statsQuery = Issue::query();

        if ($user->engineer_id != null) {
            $statsQuery->where('engineer_id', $user->engineer_id);
        } elseif (!$user->hasRole(['admin', 'support'])) {
            $statsQuery->where('user_id', $user->id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'open' => (clone $statsQuery)->open()->count(),
            'in_progress' => (clone $statsQuery)->inProgress()->count(),
            'closed' => (clone $statsQuery)->closed()->count(),
        ];

        return view('issues.index', compact('issues', 'problemTypes', 'stats'));
    }


    /**
     * عرض صفحة إضافة تذكرة جديدة
     */
    public function create()
    {
        $problemTypes = Constant::childrenOfId(41)->get();

        // أنواع المرفقات
        $attachmentTypes = Constant::childrenOfId(51)->get();

        $eng = [];
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $eng = Engineer::where('is_active', true)->get();
        } elseif ($user->hasRole('governorate_manager')) {
            $eng = Engineer::where('is_active', true)
                           ->where('home_governorate_id', $user->governorate_id)
                           ->get();
        }

        return view('issues.create', [
            'problemTypes' => $problemTypes,
            'engineers' => $eng,
            'attachmentTypes' => $attachmentTypes
        ]);
    }


    /**
     * حفظ تذكرة جديدة مع المرفقات
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'engineer_id' => 'nullable|exists:engineers,id',
            'problem_type_id' => 'required|exists:constants,id',
            'description' => 'required|string|max:5000',
            'priority' => 'required|in:low,medium,high',

            // المرفقات
            'attachments.*.attachment_type_id' => 'required|exists:constants,id',
            'attachments.*.file' => 'required|file|mimes:jpg,jpeg,png,pdf,mp4|max:20480',
        ]);

        if ($user->hasRole('admin') || $user->hasRole('governorate_manager')) {
            $validated['user_id'] = $user->id;
        } elseif ($user->engineer_id != null) {
            $validated['engineer_id'] = $user->engineer_id;
            $validated['user_id'] = null;
        } else {
            $validated['user_id'] = $user->id;
            $validated['engineer_id'] = null;
        }

        $issue = Issue::create($validated);

        // حفظ المرفقات
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


    /**
     * عرض تفاصيل التذكرة
     */
public function show(Issue $issue)
{
    $user = auth()->user();

    // إذا كان أدمن أو دعم فني → مسموح يدخل
    if ($user->hasRole(['admin', 'support'])) {
        // allow
    }
    // إذا كان مهندس
    elseif ($user->engineer_id != null) {
        if ($issue->engineer_id != $user->engineer_id) {
            abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
        }
    }
    // مستخدم عادي
    else {
        if ($issue->user_id != $user->id) {
            abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
        }
    }

    $issue->load(['engineer', 'user', 'problem', 'attachments']);

    return view('issues.show', compact('issue'));
}

    /**
     * تحديث الحالة
     */
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


    /**
     * حذف التذكرة
     */
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
