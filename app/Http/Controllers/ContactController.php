<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contacts\StoreContactRequest;
use App\Http\Requests\Contacts\UpdateContactRequest;
use App\Models\Account;
use App\Models\Contact;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Contacts/Index', [
            'contacts' => Contact::with('account')->get()
        ]);
    }

    public function show(Contact $contact)
    {
        return Inertia::render('Contacts/Show', [
            'contact' => $contact,
            'account' => $contact->account
        ]);
    }

    public function create()
    {
        return Inertia::render('Contacts/Create', [
            'accounts' => Account::select('id', 'name')->get()
        ]);
    }

    public function store(StoreContactRequest $request)
    {
        $account = Account::findOrFail($request->get('account_id'));

        $contact = $account->contacts()
            ->create($request->only(['first_name', 'last_name', 'email', 'phone', 'position']));

        return Redirect::route('contacts.show', $contact);
    }

    public function edit(Contact $contact)
    {
        return Inertia::render('Contacts/Edit', [
            'accounts' => Account::select('id', 'name')->get(),
            'contact' => $contact
        ]);
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update([
            'account_id' => $request->get('account_id') ?? $contact->account_id,
            'first_name' => $request->get('first_name') ?? $contact->first_name,
            'last_name' => $request->get('last_name') ?? $contact->last_name,
            'email' => $request->get('email') ?? $contact->email,
            'phone' => $request->get('phone') ?? $contact->phone,
            'position' => $request->get('position') ?? $contact->position,
        ]);

        return Redirect::route('contacts.show', $contact);

    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return Redirect::route('contacts.index');
    }
}
