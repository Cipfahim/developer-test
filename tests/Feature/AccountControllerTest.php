<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class AccountControllerTest extends TestCase
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
    public function unauthenticated_users_cannot_visit_account_get_routes()
    {
        $this->get(route('accounts.index'))
            ->assertRedirect(route('login'));

        $this->get(route('accounts.show', [
            'account' => $this->account
        ]))->assertRedirect(route('login'));

        $this->get(route('accounts.edit', [
            'account' => $this->account
        ]))->assertRedirect(route('login'));
    }

    /** @test */
    public function unauthenticated_users_cannot_submit_to_account_post_put_routes()
    {
        $this->post(route('accounts.store'))
            ->assertRedirect('login');

        $this->put(route('accounts.update', [
            'account' => $this->account
        ]))->assertRedirect('login');
    }

    /** @test */
    public function index_view_can_be_rendered()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('accounts.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Accounts/Index')
            ->has('accounts')
        );
    }

    /** @test */
    public function create_view_can_be_rendered()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('accounts.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Accounts/Create')
            ->has('users')
        );
    }

    /** @test */
    public function show_view_can_be_rendered()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('accounts.show', [
            'account' => $this->account
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Accounts/Show')
            ->has('account', fn (AssertableInertia $account) => $account
                ->where('name', $this->account->name)
                ->etc()
            )
            ->has('owner', fn (AssertableInertia $owner) => $owner
                ->where('id', $this->user->id)
                ->etc()
            )
            ->has('contacts', fn (AssertableInertia $page) => $page
                ->each(fn (AssertableInertia $contact) => $contact
                    ->where('account_id', $this->account->id)
                    ->etc()
                )
            )
        );
    }

    /** @test */
    public function edit_view_can_be_rendered()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('accounts.edit', [
            'account' => $this->account
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Accounts/Edit')
            ->has('account', fn (AssertableInertia $account) => $account
                ->where('name', $this->account->name)
                ->etc()
            )
            ->has('users')
        );
    }

    /** @test */
    public function an_account_can_be_created()
    {
        $this->actingAs($this->user);

        $this->post(route('accounts.store'), [
            'owner_id' => $this->user->id,
            'name' => 'Test account',
            'address' => 'Test address',
            'town_city' => 'Test town',
            'country' => 'Test country',
            'post_code' => 'AB12 3CD',
            'phone' => '0123456789'
        ])
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('accounts', [
            'owner_id' => $this->user->id,
            'name' => 'Test account',
            'address' => 'Test address',
            'town_city' => 'Test town',
            'country' => 'Test country',
            'post_code' => 'AB12 3CD',
            'phone' => '0123456789'
        ]);
    }

    /** @test */
    public function an_account_can_be_updated()
    {
        $this->actingAs($this->user);

        $this->put(route('accounts.update', $this->account->id), [
            'name' => 'Test account'
        ])
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('accounts', [
            'id' => $this->account->id,
            'name' => 'Test account'
        ]);
    }
}