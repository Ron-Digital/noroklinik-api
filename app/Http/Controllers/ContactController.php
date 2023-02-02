<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();

        return response()->json([
            'contacts' => $contacts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'fullname' => 'required|string',
            'phone_number' => 'required|numeric|digits:10|unique:contacts,phone_number',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'validationMessages' => $validator->errors()
            ], 400);
        }

        $contact = Contact::create([
            "fullname"=>$request->fullname,
            "phone_number"=>$request->phone_number,
            "description"=> $request->description,
        ]);

        if(!$contact){
            return response()->json([
                'message' => 'Error: Try Again !'
            ]);
        }
        return response()->json([
            'message' => 'Succesful',
            'post' => new ContactResource($contact)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return response()->json([
            'contact' => $contact
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(),[
            'fullname' => 'required',
            'phone_number' => 'required|numeric|digits:10|unique:contacts,phone_number',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'validationMessages' => $validator->errors()
            ], 400);
        }

        $fullname=$request->fullname;
        $phone_number=$request->phone_number;
        $description=$request->description;

        $result = $contact->update([
            "fullname"=>$fullname,
            "phone_number"=>$phone_number,
            "description"=> $description,
        ]);

        if(!$result){
            return response()->json([
                'message' => 'Error: Try Again !'
            ]);
        }
        return response()->json([
            'message' => 'Succesful',
            'contact' => new ContactResource($contact)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json([
            'message' => 'Deleted'
        ]);
    }
}
