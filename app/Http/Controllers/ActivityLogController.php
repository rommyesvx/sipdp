<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');

        $logs = Activity::with('causer')
                        ->orderBy($sort, $direction)
                        ->paginate(20); // Menampilkan 20 log per halaman

        return view('admin.logs.index', [ // Sesuaikan nama view jika perlu
            'logs' => $logs,
            'sort' => $sort,
            'direction' => $direction
        ]);
    }
}
