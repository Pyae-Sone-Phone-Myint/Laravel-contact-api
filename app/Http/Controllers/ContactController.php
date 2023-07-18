<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactDetailResource;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::latest('id')->paginate(5)->withQueryString();
        // return response()->json($contacts);

        // Controlling data that u want to show using resource collection
        return ContactResource::collection($contacts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "country_code" => "required|min:1|max:193",
            "phone_number" => "required",
        ]);

        $contact = Contact::create([
            "name" => $request->name,
            "country_code" => $request->country_code,
            "phone_number" => $request->phone_number,
        ]);
        return new ContactDetailResource($contact);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = Contact::find($id);

        // SHOW DATA USING REGULAR WAY
        // return response()->json([
        //     "data" => $contact
        // ]);

        // SHOW ALL DATA USING RESOURCE
        return new ContactDetailResource($contact);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required",
            "country_code" => "required|min:1|max:193",
            "phone_number" => "required",
        ]);

        $contact = Contact::find($id);

        $contact->update([
            "name" => $request->name,
            "country_code" => $request->country_code,
            "phone_number" => $request->phone_number,
        ]);
        return new ContactDetailResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::find($id);
        $contact->delete();

        return response()->json([],204);
    }
}
