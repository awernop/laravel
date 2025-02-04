<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        $reports = Report::where('user_id', Auth::user()->id)->get();
        $userId = Auth::id();
        return view('reports.index', compact('reports', 'userId'));
    }

    public function create() {
        $reports = Report::all();
        return view('reports.create', compact('reports'));
    }

    public function store(Request $request): RedirectResponse {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'path_img' => 'image|mimes:png,jpg,jpeg,gif|max:800',
        ]);

        $imageName = time() . '.' . $request['path_img']->extension();
        $request['path_img']->move(public_path('images'), $imageName);

        Report::create([
            'title' => $request->title,
            'description' => $request->description,
            'path_img' => $imageName,
            "user_id" => Auth::user()->id,
            "status" => "Новая",
        ]);

        return redirect()->route('dashboard');
    }

    public function update(Request $request) {
        $request->validate([
            'status' => ['required'],
            'id' => ['required']
        ]);

        Report::where('id', $request->id)->update([
            'status' => $request->status,
        ]);
        return redirect()->back();
    }
}
