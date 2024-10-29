<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Mail\CategoryNotification;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->paginate(10);

        return view('welcome', compact('categories'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::UpdateOrCreate(['id'=> $request->id],$request->validated());
        $mails = User::pluck('email')->toArray();
        Mail::to($mails)->send(new CategoryNotification($category, $request->id ? 'Updated' : 'Creaated'));

        return back()->with('success','Data Saved Successfully');
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('edit', compact('category'));

    }

    public function destroy(string $id)
    {
        try {            
            $category = Category::findOrFail($id);
            $category->delete();
            $mails = User::pluck('email')->toArray();
            Mail::to($mails)->send(new CategoryNotification($category, 'Deleted'));
            return back()->with('success', 'Data Deleted Successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete data: ' . $e->getMessage());
        }
    }   
}
