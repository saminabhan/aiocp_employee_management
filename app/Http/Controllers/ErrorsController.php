<?php

namespace App\Http\Controllers;

use App\Models\SystemError;
use Illuminate\Http\Request;

class ErrorsController extends Controller
{
    public function index()
    {
        $errors = SystemError::with('user')->latest()->paginate(20);
        return view('admin.errors.index', compact('errors'));
    }

    public function show($id)
    {
        $error = SystemError::findOrFail($id);
        return view('admin.errors.show', compact('error'));
    }
}
