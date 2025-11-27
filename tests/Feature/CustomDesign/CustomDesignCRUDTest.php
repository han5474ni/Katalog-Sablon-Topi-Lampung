<?php

namespace Tests\Feature\CustomDesign;

use App\Models\CustomDesignOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomDesignCRUDTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function custom_design_can_be_created()
    {
        $this->actingAs($this->user);

        $response = $this->post(route("custom-design.store"), [
            "design_name" => "Custom Design",
            "description" => "Design",
            "quantity" => 10,
        ]);

        $this->assertDatabaseHas("custom_design_orders", [
            "design_name" => "Custom Design",
            "user_id" => $this->user->id,
        ]);
    }

    /** @test */
    public function custom_design_can_be_retrieved()
    {
        $design = CustomDesignOrder::factory()->create(["user_id" => $this->user->id]);

        $this->actingAs($this->user);
        $response = $this->get(route("custom-design.show", $design));

        $response->assertStatus(200);
        $response->assertSee($design->design_name);
    }

    /** @test */
    public function custom_design_status_can_be_updated()
    {
        $design = CustomDesignOrder::factory()->create([
            "user_id" => $this->user->id,
            "status" => "pending",
        ]);

        $this->actingAs($this->user);
        $response = $this->patch(route("custom-design.update", $design), [
            "status" => "approved",
        ]);

        $this->assertDatabaseHas("custom_design_orders", [
            "id" => $design->id,
            "status" => "approved",
        ]);
    }

    /** @test */
    public function custom_design_can_be_deleted()
    {
        $design = CustomDesignOrder::factory()->create(["user_id" => $this->user->id]);

        $this->actingAs($this->user);
        $response = $this->delete(route("custom-design.destroy", $design));

        $this->assertDatabaseMissing("custom_design_orders", [
            "id" => $design->id,
        ]);
    }

    /** @test */
    public function custom_design_quantity_can_be_updated()
    {
        $design = CustomDesignOrder::factory()->create([
            "user_id" => $this->user->id,
            "quantity" => 5,
        ]);

        $this->actingAs($this->user);
        $response = $this->patch(route("custom-design.update-quantity", $design), [
            "quantity" => 20,
        ]);

        $this->assertDatabaseHas("custom_design_orders", [
            "id" => $design->id,
            "quantity" => 20,
        ]);
    }
}
