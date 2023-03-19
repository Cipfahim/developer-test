<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Account $account;
    protected Contact $contact;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->account = Account::factory()->create([
            'owner_id' => $this->user->id
        ]);
        $this->contact = Contact::factory()->create([
            'account_id' => $this->account->id
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_visit_contact_get_routes()
    {
        $this->get(route('contacts.index'))
            ->assertRedirect(route('login'));

        $this->get(route('contacts.show', [
            'contact' => $this->contact
        ]))->assertRedirect(route('login'));

        $this->get(route('contacts.edit', [
            'contact' => $this->contact
        ]))->assertRedirect(route('login'));
    }

    /** @test */
    public function unauthenticated_users_cannot_submit_to_contact_post_put_routes()
    {
        $this->post(route('contacts.store'))
            ->assertRedirect('login');

        $this->put(route('contacts.update', [
            'contact' => $this->contact
        ]))->assertRedirect('login');
    }

    /** @test */
    public function index_view_can_be_rendered()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('contacts.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Contacts/Index')
            ->has('contacts')
        );
    }

    /** @test */
    public function create_view_can_be_rendered()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('contacts.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Contacts/Create')
            ->has('accounts')
        );
    }

    /** @test */
    public function show_view_can_be_rendered()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('contacts.show', [
            'contact' => $this->contact
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Contacts/Show')
            ->has('contact', fn (AssertableInertia $contact) => $contact
                ->where('email', $this->contact->email)
                ->etc()
            )
            ->has('account', fn (AssertableInertia $account) => $account
                ->where('name', $this->account->name)
                ->etc()
            )
        );
    }

    /** @test */
    public function edit_view_can_be_rendered()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('contacts.edit', [
            'contact' => $this->contact
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Contacts/Edit')
            ->has('contact', fn (AssertableInertia $contact) => $contact
                ->where('email', $this->contact->email)
                ->etc()
            )
            ->has('accounts')
        );
    }

    /** @test */
    public function a_contact_can_be_created()
    {
        $this->actingAs($this->user);

        $this->post(route('contacts.store'), [
            'account_id' => $this->account->id,
            'first_name' => 'First',
            'last_name' => 'Last',
            'email' => 'first@last.com',
            'phone' => '0123456789',
            'position' => 'Test position'
        ])
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('contacts', [
            'account_id' => $this->account->id,
            'first_name' => 'First',
            'last_name' => 'Last',
            'email' => 'first@last.com',
            'phone' => '0123456789',
            'position' => 'Test position'
        ]);
    }

    /** @test */
    public function a_contact_can_be_updated()
    {
        $this->actingAs($this->user);

        $this->put(route('contacts.update', $this->contact->id), [
            'email' => 'test@test.com'
        ])
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('contacts', [
            'id' => $this->contact->id,
            'email' => 'test@test.com'
        ]);
    }
}
