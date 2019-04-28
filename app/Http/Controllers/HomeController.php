<?php

namespace App\Http\Controllers;

use App\User;
use App\Contact;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class HomeController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        return view('home');
    }

    public function getuser()
    {
        return Datatables::of(User::query())->make(true);
    }

    public function apiContact()
    {
        $contact = Contact::all();
 
        return Datatables::of($contact)
            ->addColumn('action', function($contact){
                return '<a onclick="editForm('. $contact->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                       '<a onclick="deleteData('. $contact->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])->make(true);
    }

    public function Create(Request $request){

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->save();

        return response()->json([
            'success' => true,
            'message' => 'Contact Created'
        ]);
    }

    public function Edit(Request $request){
        $contact = Contact::find($request->id);
        return $contact;
    }

    
    public function EditSave(Request $request){
        $contact = Contact::find($request->id);
        $contact->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        return $contact;
    }

    public function Delete(Request $request){
        $contact = Contact::find($request->id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Contact Deleted'
        ]);
    }
}
