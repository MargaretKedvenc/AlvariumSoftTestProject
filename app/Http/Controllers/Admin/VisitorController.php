<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Visitor;

class VisitorController extends Controller
{
    public function index() {
        $visitors = Visitor::all();
        return view('admin.static-table', compact('visitors'));
    }

    public function visitorsWithLimit($limit) {
        return [
            'data' => $this->getData($limit)
        ];
    }
    public function visitors() {
        $count = Visitor::count();
        if ($count > 50000) {
            ini_set('memory_limit', '512M');
        }
        return [
            'data' => $this->getData($count)
        ];
    }

    private function getData($limit)
    {
        return Visitor::limit($limit)->get();
    }
}
