<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\BranchRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchRequestTest extends TestCase
{
    // This trait auto-resets the database after every single test.
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_branch_manager_can_only_view_their_own_requests()
    {
        // 1. Arrange
        $branchA = Branch::factory()->create();
        $branchB = Branch::factory()->create();

        $managerA = User::factory()->create(['role' => 'branch_manager', 'branch_id' => $branchA->id]);
        $managerB = User::factory()->create(['role' => 'branch_manager', 'branch_id' => $branchB->id]);

        // Creating dummy requests
        BranchRequest::factory()->count(3)->create(['branch_id' => $branchA->id, 'branch_manager_id' => $managerA->id]);
        BranchRequest::factory()->count(2)->create(['branch_id' => $branchB->id, 'branch_manager_id' => $managerB->id]);

        // 2. Act: Log in as Manager A and fetch requests
        $response = $this->actingAs($managerA)->getJson(route('branch-requests.index'));

        // 3. Assert
        $response->assertStatus(200);

        // Manager A should only see their 3 requests, not Manager B's 2 requests
        $this->assertCount(3, $response->json('data'));
    }

    /**
     * @test
     */
    public function a_branch_manager_can_create_a_request_when_stock_is_sufficient()
    {
        $branch = Branch::factory()->create();
        $manager = User::factory()->create(['role' => 'branch_manager', 'branch_id' => $branch->id]);
        $product = Product::factory()->create(['quantity_in_warehouse' => 100]);

        $payload = [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 20]
            ]
        ];

        $response = $this->actingAs($manager)->postJson(route('branch-requests.store'), $payload);

        $response->assertStatus(201);

        // Verify database state: the request and item were saved correctly
        $this->assertDatabaseHas('branch_requests', [
            'branch_id' => $branch->id,
            'branch_manager_id' => $manager->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('branch_request_items', [
            'product_id' => $product->id,
            'quantity' => 20,
        ]);

        // Stock shouldn't be deducted YET. It only gets deducted on dispatch.
        $this->assertEquals(100, $product->fresh()->quantity_in_warehouse);
    }

    /**
     * @test
     */
    public function it_blocks_creating_a_request_if_requested_quantity_exceeds_warehouse_stock()
    {
        $branch = Branch::factory()->create();
        $manager = User::factory()->create(['role' => 'branch_manager', 'branch_id' => $branch->id]);

        // Product only has 10 items
        $product = Product::factory()->create(['quantity_in_warehouse' => 10]);

        // Manager tries to request 50
        $payload = [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 50]
            ]
        ];

        $response = $this->actingAs($manager)->postJson(route('branch-requests.store'), $payload);

        // Validation error expected (422 Unprocessable Entity)
        $response->assertStatus(422);

        // Expect a validation error on the 'items' key
        $response->assertJsonValidationErrors(['items']);

        // Verify NOTHING was saved to the DB
        $this->assertDatabaseCount('branch_requests', 0);
    }

    /**
     * @test
     */
    public function a_warehouse_worker_can_dispatch_a_request_and_stock_is_deducted()
    {
        $branch = Branch::factory()->create();
        $manager = User::factory()->create(['role' => 'branch_manager', 'branch_id' => $branch->id]);
        $worker = User::factory()->create(['role' => 'warehouse_worker']);

        // Start with 100 items
        $product = Product::factory()->create(['quantity_in_warehouse' => 100]);

        $request = BranchRequest::create([
            'branch_id' => $branch->id,
            'branch_manager_id' => $manager->id,
            'status' => 'pending',
        ]);

        $request->items()->create([
            'product_id' => $product->id,
            'quantity' => 15,
        ]);

        // Act: Worker dispatches the request
        $response = $this->actingAs($worker)->patchJson(route('branch-requests.dispatch', $request));

        $response->assertStatus(200);

        // Assert 1: The request status is now dispatched
        $this->assertDatabaseHas('branch_requests', [
            'id' => $request->id,
            'status' => 'dispatched',
            'dispatched_by' => $worker->id,
        ]);

        // Assert 2: CRUCIAL: The warehouse stock was actually deducted!
        // Expected: 100 - 15 = 85
        $this->assertEquals(85, $product->fresh()->quantity_in_warehouse);
    }

    /**
     * @test
     */
    public function a_branch_manager_cannot_dispatch_a_request()
    {
        $branch = Branch::factory()->create();
        $manager = User::factory()->create(['role' => 'branch_manager', 'branch_id' => $branch->id]);

        $request = BranchRequest::factory()->create(['status' => 'pending']);

        // A manager attempts to dispatch their own request to bypass warehouse approval
        $response = $this->actingAs($manager)->patchJson(route('branch-requests.dispatch', $request));

        // Forbidden
        $response->assertStatus(403);
    }
}
