<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use App\Models\Engineer;
use App\Models\Issue;
use App\Models\IssueAttachment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IssueController extends Controller
{

public function index(Request $request)
{
    $user = auth()->user();

    $query = Issue::with(['engineer', 'user', 'problem']);

    switch ($user->role->name ?? '') {

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
                    });

                    $q->orWhereHas('user', function ($u) use ($user) {
                        $u->where('governorate_id', $user->governorate_id)
                        ->whereHas('role', function ($r) {
                            $r->where('name', 'survey_supervisor');
                        });
                    });
                    $q->orWhere('user_id', $user->id);

                });
                break;

            case 'north_support':
                $query->where(function ($q) {
                    $q->whereHas('engineer', function ($eng) {
                        $eng->whereIn('work_governorate_id', [17, 18]);
                    });
                })
                ->orWhere(function ($q) {
                    $q->whereHas('user.role', function ($role) {
                        $role->whereIn('name', [
                            'governorate_manager',
                            'survey_supervisor'
                        ]);
                    })
                    ->whereHas('user', function ($u) {
                        $u->whereIn('governorate_id', [17, 18]);
                    });
                })
                ->orWhereHas('user.role', function ($role) {
                    $role->whereIn('name', ['manager', 'admin']);
                });
                break;

            case 'south_support':
                $query->where(function ($q) {
                    $q->whereHas('engineer', function ($eng) {
                        $eng->whereIn('work_governorate_id', [15, 16]);
                    });
                })
                ->orWhere(function ($q) {
                    $q->whereHas('user.role', function ($role) {
                        $role->whereIn('name', [
                            'governorate_manager',
                            'survey_supervisor'
                        ]);
                    })
                    ->whereHas('user', function ($u) {
                        $u->whereIn('governorate_id', [15, 16]);
                    });
                })
                ->orWhereHas('user.role', function ($role) {
                    $role->whereIn('name', ['manager', 'admin']);
                });
                break;

                case 'field_engineer':
                    $query->where('engineer_id', $user->engineer_id);
                    break;

            case 'admin':
            case 'manager':
                break;

            default:
                if (!$user->hasPermission('issues.view')) {
                    abort(403, 'غير مصرح لك بعرض التذاكر');
                }
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

    switch ($user->role->name ?? '') {

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
                ->orWhereHas('user', function ($u) use ($user) {
                    $u->where('governorate_id', $user->governorate_id)
                    ->whereHas('role', function ($r) {
                        $r->where('name', 'survey_supervisor');
                    });
                })
                ->orWhere('user_id', $user->id);
            });
            break;

        case 'north_support':
            $statsQuery->where(function ($q) {
                $q->whereHas('engineer', function ($eng) {
                    $eng->whereIn('work_governorate_id', [17, 18]);
                });
            })
            ->orWhere(function ($q) {
                $q->whereHas('user', function ($u) {
                    $u->whereIn('main_work_area_code', [17, 18]);
                })
                ->whereHas('user.role', function ($r) {
                    $r->where('name', 'survey_supervisor');
                });
            })
            ->orWhere(function ($q) {
                $q->whereHas('user', function ($u) {
                    $u->whereIn('governorate_id', [17, 18]);
                })
                ->whereHas('user.role', function ($r) {
                    $r->where('name', 'governorate_manager');
                });
            })
            ->orWhereHas('user.role', function ($r) {
                $r->whereIn('name', ['manager', 'admin', 'system_admin']);
            });
            break;

        case 'south_support':
            $statsQuery->where(function ($q) {
                $q->whereHas('engineer', function ($eng) {
                    $eng->whereIn('work_governorate_id', [15, 16]);
                });
            })
            ->orWhere(function ($q) {
                $q->whereHas('user', function ($u) {
                    $u->whereIn('main_work_area_code', [15, 16]);
                })
                ->whereHas('user.role', function ($r) {
                    $r->where('name', 'survey_supervisor');
                });
            })
            ->orWhere(function ($q) {
                $q->whereHas('user', function ($u) {
                    $u->whereIn('governorate_id', [15, 16]);
                })
                ->whereHas('user.role', function ($r) {
                    $r->where('name', 'governorate_manager');
                });
            })
            ->orWhereHas('user.role', function ($r) {
                $r->whereIn('name', ['manager', 'admin', 'system_admin']);
            });
            break;

            case 'field_engineer':
                $statsQuery->where('engineer_id', $user->engineer_id);
                break;

            case 'admin':
            case 'manager':
                break;
            default:
                if (!$user->hasPermission('issues.view')) {
                    abort(403, 'غير مصرح لك بعرض التذاكر');
                }
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

switch ($user->role->name ?? '') {

    case 'admin':

        $engineers = Engineer::where('is_active', true)->get()
            ->map(function ($eng) {
                return (object)[
                    'id'   => 'eng_' . $eng->id,
                    'name' => $eng->full_name,
                    'type' => 'engineer'
                ];
            });

        $surveySupervisors = User::whereHas('role', fn($r) => 
                $r->where('name', 'survey_supervisor')
            )->get()
            ->map(function ($user) {
                return (object)[
                    'id'   => 'sup_' . $user->id,
                    'name' => $user->name,
                    'type' => 'supervisor'
                ];
            });

        $governorateManagers = User::whereHas('role', fn($r) => 
                $r->where('name', 'governorate_manager')
            )->get()
            ->map(function ($user) {
                return (object)[
                    'id'   => 'gm_' . $user->id,
                    'name' => $user->name,
                    'type' => 'gov_manager'
                ];
            });

        $eng = collect()
            ->merge($engineers)
            ->merge($surveySupervisors)
            ->merge($governorateManagers);

        break;



    case 'governorate_manager':

        $engineers = Engineer::where('is_active', true)
            ->where('work_governorate_id', $user->governorate_id)
            ->get()
            ->map(function ($eng) {
                return (object)[
                    'id'   => 'eng_' . $eng->id,
                    'name' => $eng->full_name,
                    'type' => 'engineer'
                ];
            });

        $surveySupervisors = User::whereHas('role', fn($r) =>
                $r->where('name', 'survey_supervisor')
            )
            ->where('governorate_id', $user->governorate_id)
            ->get()
            ->map(function ($u) {
                return (object)[
                    'id'   => 'sup_' . $u->id,
                    'name' => $u->name,
                    'type' => 'supervisor'
                ];
            });

        $eng = collect()->merge($engineers)->merge($surveySupervisors);
        break;



    case 'survey_supervisor':

        $eng = Engineer::where('is_active', true)
            ->where('main_work_area_code', $user->main_work_area_code)
            ->get()
            ->map(function ($eng) {
                return (object)[
                    'id'   => 'eng_' . $eng->id,
                    'name' => $eng->full_name,
                    'type' => 'engineer'
                ];
            });

        break;


    case 'field_engineer':
        $eng = collect();
        break;


    default:
        abort(403, 'غير مصرح لك بإنشاء تذكرة');
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
        'engineer_id' => 'nullable|string',
        'problem_type_id' => 'required|exists:constants,id',
        'description' => 'required|string|max:5000',
        'priority' => 'required|in:low,medium,high',

        'attachments.*.attachment_type_id' => 'nullable|exists:constants,id|required_with:attachments.*.file',

        'attachments.*.file' => [
            'nullable',
            'file',
            'max:20480',
            'required_with:attachments.*.attachment_type_id',
            'mimetypes:' .
                'image/jpeg,image/png,image/webp,image/gif,image/svg+xml,image/heic,image/heif,' .
                'application/pdf,' .
                'video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,' .
                'video/x-ms-wmv,video/webm,video/3gpp,video/3gpp2,' .
                'video/x-m4v,video/mpeg,video/x-flv,' .
                'application/octet-stream'
        ],
    ]);

    $assigneeRaw = $request->input('engineer_id');

    $engineerId = null;
    $assignedToUserId = null;

    if ($assigneeRaw) {

        $parts = explode('_', $assigneeRaw);

        if (count($parts) !== 3) {
            abort(400, "Invalid assignee format");
        }

        [$fromRole, $toRole, $id] = $parts;

        if ($toRole === 'eng') {
            $engineerId = intval($id);
        }

        if (in_array($toRole, ['sup', 'gm'])) {
            $assignedToUserId = intval($id);
        }
    }


    switch ($user->role->name ?? '') {

        case 'admin':
            break;

        case 'governorate_manager':

            if ($engineerId) {
                $eng = Engineer::find($engineerId);

                if ($eng->work_governorate_id != $user->governorate_id) {
                    abort(403, "لا تملك صلاحية تحويل تذكرة لهذا المهندس");
                }
            }

            break;

        case 'survey_supervisor':

            if ($engineerId) {
                $eng = Engineer::find($engineerId);

                if ($eng->main_work_area_code != $user->main_work_area_code) {
                    abort(403, "لا يمكنك تحويل تذكرة لمهندس خارج منطقة العمل");
                }
            }

            break;

        case 'field_engineer':
            $engineerId = $user->engineer_id;
            $assignedToUserId = null;
            break;

        default:
            if (!$user->hasPermission('issues.create')) {
                abort(403, 'غير مصرح لك بإنشاء تذكرة');
            }
            break;
    }

    $validated['user_id'] = $assignedToUserId ?? $user->id;

    $validated['engineer_id'] = $engineerId;

    $issue = Issue::create($validated);

    if ($request->has('attachments')) {
        foreach ($request->attachments as $att) {

            if (!isset($att['file'])) continue;

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

    $this->sendNotificationToSupport($issue, $user);

    return redirect()->route('issues.index')
        ->with('success', 'تم إنشاء التذكرة بنجاح');
}

private function sendNotificationToSupport($issue, $creatorUser)
{
    $engineer = $issue->engineer_id ? Engineer::find($issue->engineer_id) : null;

    $govId = null;

    if ($engineer && $engineer->work_governorate_id) {
        $govId = $engineer->work_governorate_id;
    } else {
        $govId = $creatorUser->governorate_id;
    }

    $supportRole = null;

    $northGovIds = [17, 18];
    $southGovIds = [15, 16];

    if (in_array($govId, $northGovIds)) {
        $supportRole = 'north_support';
    } elseif (in_array($govId, $southGovIds)) {
        $supportRole = 'south_support';
    }

    $receivers = collect();

    if ($supportRole) {
        $receivers = User::whereHas('role', function ($q) use ($supportRole) {
            $q->where('name', $supportRole);
        })->get();
    }

    if ($receivers->isEmpty()) {
        return;
    }

    $priorityLabels = [
        'low'    => 'منخفضة',
        'medium' => 'متوسطة',
        'high'   => 'عالية',
    ];

    $priorityLabel = $priorityLabels[$issue->priority] ?? $issue->priority;

    foreach ($receivers as $user) {
        Notification::create([
            'user_id'     => $user->id,
            'issue_id'    => $issue->id,
            'engineer_id' => $engineer?->id,
            'type'        => 'issue',
            'title'       => 'تذكرة دعم فني جديدة',
            'message'     => "تم إنشاء تذكرة جديدة بأولوية {$priorityLabel}"
                            . ($engineer ? " للمهندس {$engineer->full_name}" : ''),
        ]);
    }
}
public function show(Issue $issue)
{
    $user = auth()->user();

    if ($issue->user_id == $user->id) {
        $issue->load(['engineer', 'user', 'problem', 'attachments']);
        return view('issues.show', compact('issue'));
    }

    switch ($user->role->name   ?? '') {

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

            $engineer = $issue->engineer;
            $issueOwner = $issue->user;

            $belongsToEngineerGov = $engineer && $engineer->work_governorate_id == $user->governorate_id;

            $belongsToSurveySupervisorGov =
                $issueOwner &&
                $issueOwner->governorate_id == $user->governorate_id &&
                $issueOwner->role &&
                $issueOwner->role->name === 'survey_supervisor';

            if (!$belongsToEngineerGov && !$belongsToSurveySupervisorGov) {
                abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
            }

            break;

            case 'north_support':

                    if ($issue->user->role->name === 'admin') {
                            break;
                        }

                if (!$issue->engineer) {
                    $creatorGov = $issue->user->governorate_id;

                    if (!in_array($creatorGov, [17, 18])) {
                        abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
                    }

                    break;
                }

                if (!in_array($issue->engineer->work_governorate_id, [17, 18])) {
                    abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
                }
                break;

            case 'south_support':

                    if ($issue->user->role->name === 'admin') {
                            break;
                        }

                if (!$issue->engineer) {
                    $creatorGov = $issue->user->governorate_id;

                    if (!in_array($creatorGov, [15, 16])) {
                        abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
                    }

                    break;
                }

                if (!in_array($issue->engineer->work_governorate_id, [15, 16])) {
                    abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
                }
                break;

            case 'field_engineer':
                if ($issue->engineer_id != $user->engineer_id) {
                    abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
                }
                break;
        default:
            if (!$user->hasPermission('issues.view')) {
                abort(403, 'ليس لديك صلاحية لعرض هذه التذكرة');
            }
            break;
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

    $allowedRoles = ['admin', 'north_support', 'south_support'];

    $hasAllowedRole = $user->hasRole($allowedRoles);
    $hasPermissionButNoRole = ($user->role_id === null && $user->hasPermission('issues.edit'));

    if (!$hasAllowedRole && !$hasPermissionButNoRole) {
        abort(403);
    }

    $validated = $request->validate([
        'status' => 'required|in:open,in_progress,closed',
        'solution' => 'nullable|string|max:5000',
    ]);

    $oldStatus = $issue->status;
    
    $issue->update($validated);

    $this->notifyIssueOwner($issue, $oldStatus, $user);

    return redirect()->back()->with('success', 'تم تحديث حالة التذكرة بنجاح');
}

private function notifyIssueOwner($issue, $oldStatus, $updatedBy)
{
    Log::info('notifyIssueOwner called', [
        'issue_id' => $issue->id,
        'issue_owner_id' => $issue->user_id,
        'updatedBy_id' => $updatedBy->id,
        'old_status' => $oldStatus,
        'new_status' => $issue->status
    ]);

    $issueOwner = $issue->user;
    
    if (!$issueOwner) {
        Log::warning('Issue owner not found', ['issue_id' => $issue->id]);
        return;
    }

    if ($issueOwner->id === $updatedBy->id) {
        Log::info('Skipping notification - owner is updater');
        return;
    }

    $statusLabels = [
        'open' => 'مفتوحة',
        'in_progress' => 'قيد المعالجة',
        'closed' => 'مغلقة'
    ];

    $newStatusLabel = $statusLabels[$issue->status] ?? $issue->status;

    $title = 'تحديث حالة التذكرة';
    $message = "تم تحديث حالة تذكرتك إلى: {$newStatusLabel}";

    if ($issue->status === 'closed' && $issue->solution) {
        $title = 'تم إغلاق التذكرة';
        $solution = mb_strlen($issue->solution) > 100 
            ? mb_substr($issue->solution, 0, 100) . '...' 
            : $issue->solution;
        $message = "تم حل المشكلة وإغلاق التذكرة\n\nالحل: {$solution}";
    }

    try {
        $notification = Notification::create([
            'user_id' => $issueOwner->id,
            'issue_id' => $issue->id,
            'engineer_id' => $issue->engineer_id,
            'type' => 'issue_status_update',
            'title' => $title,
            'message' => $message,
        ]);
        
        Log::info('Notification created successfully', ['notification_id' => $notification->id]);
    } catch (\Exception $e) {
        Log::error('Failed to create notification', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
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
