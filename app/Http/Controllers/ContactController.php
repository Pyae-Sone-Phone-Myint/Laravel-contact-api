<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactDetailResource;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate as FacadesGate;

class ContactController extends Controller
{
    // response properties
    // success [ true , false]
    // message
    // errors


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
            "user_id" => Auth::id(),
        ]);
        return new ContactDetailResource($contact);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = Contact::find($id);
        if (is_null($contact)) {
            return response()->json([
                // "success" => false,
                "message" => "Contact not found",

            ], 404);
        }

        // false APP_DEBUG from .env
        // $this->authorize('view',$contact);

        // show message manually
        if (FacadesGate::denies('view', $contact)) {
            return response()->json([
                // "success" => false,
                "message" => "You are not allowed",

            ], 403);
        }
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
            "name" => "nullable|min:1|max:20",
            "country_code" => "nullable|integer|min:1|max:193",
            "phone_number" => "nullable|min:7|max:15",
        ]);

        $contact = Contact::find($id);

        if (is_null($contact)) {
            return response()->json([
                // "success" => false,
                "message" => "Contact not found",

            ], 404);
        }

        if (FacadesGate::denies('update', $contact)) {
            return response()->json([
                // "success" => false,
                "message" => "You are not allowed",

            ], 403);
        }

        // $contact->update([
        //     "name" => $request->name,
        //     "country_code" => $request->country_code,
        //     "phone_number" => $request->phone_number,
        // ]);

        // $contact->update($request->all());

        if ($request->has('name')) {
            $contact->name = $request->name;
        };

        if ($request->has('country_code')) {
            $contact->country_code = $request->country_code;
        };

        if ($request->has('phone_number')) {
            $contact->phone_number = $request->phone_number;
        };

        $contact->update();
        return new ContactDetailResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::find($id);

        if (is_null($contact)) {
            return response()->json([
                // "success" => false,
                "message" => "Contact not found",

            ], 404);
        }

        if (FacadesGate::denies('delete', $contact)) {
            return response()->json([
                // "success" => false,
                "message" => "You are not allowed",

            ], 403);
        }
        $contact->delete();

        // return response()->json([], 204);
        return response()->json([
            "message" => "contact deleted"
        ]);
    }
}
