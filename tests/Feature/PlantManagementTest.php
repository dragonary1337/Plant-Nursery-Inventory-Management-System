<?php

use App\Models\Plant;
use App\Models\User;

function plantAttributes(array $overrides = []): array
{
    return array_merge([
        'plant_name' => 'Monstera',
        'category' => 'ornamental',
        'unit_price' => '24.50',
        'description' => 'A healthy indoor plant.',
        'status' => 'in-stock',
    ], $overrides);
}

it('requires authentication for plant inventory and reports', function () {
    $this->get(route('plants.index'))->assertRedirect(route('login'));
    $this->get(route('plants.reports.index'))->assertRedirect(route('login'));
});

it('creates and updates plants with nonnegative prices and creator relationship', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('plants.create'))
        ->assertOk()
        ->assertSee('Add a plant');

    $this->actingAs($user)
        ->post(route('plants.store'), plantAttributes([
            'unit_price' => '0',
            'user_id' => User::factory()->create()->id,
        ]))
        ->assertRedirect(route('plants.index'));

    $plant = Plant::query()->firstOrFail();
    expect($plant->user_id)->toBe($user->id)
        ->and($user->plants()->count())->toBe(1);

    $this->actingAs($user)
        ->get(route('plants.edit', $plant))
        ->assertOk()
        ->assertSee('Edit Monstera');

    $this->actingAs($user)
        ->put(route('plants.update', $plant), plantAttributes([
            'plant_name' => 'Fresh Monstera',
            'unit_price' => '19.99',
            'status' => 'out of stock',
        ]))
        ->assertRedirect(route('plants.show', $plant));

    expect($plant->fresh()->plant_name)->toBe('Fresh Monstera')
        ->and((float) $plant->fresh()->unit_price)->toBe(19.99)
        ->and($plant->fresh()->status)->toBe('out of stock');
});

it('rejects negative prices and supports viewing and deleting a plant', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('plants.create'))
        ->post(route('plants.store'), plantAttributes(['unit_price' => '-0.01']))
        ->assertRedirect(route('plants.create'))
        ->assertSessionHasErrors('unit_price');

    $plant = Plant::query()->create(plantAttributes(['user_id' => $user->id]));

    $this->actingAs($user)
        ->get(route('plants.show', $plant))
        ->assertOk()
        ->assertSee('Monstera');

    $this->actingAs($user)
        ->delete(route('plants.destroy', $plant))
        ->assertRedirect(route('plants.index'));

    $this->assertDatabaseMissing('plants', ['id' => $plant->id]);
});

it('applies the same filters to report pages and exports', function () {
    $user = User::factory()->create();

    Plant::query()->create(plantAttributes([
        'plant_name' => 'Available Herb',
        'category' => 'herb',
        'user_id' => $user->id,
    ]));
    Plant::query()->create(plantAttributes([
        'plant_name' => 'Unavailable Herb',
        'category' => 'herb',
        'status' => 'out of stock',
        'user_id' => $user->id,
    ]));
    Plant::query()->create(plantAttributes([
        'plant_name' => 'Available Succulent',
        'category' => 'succulent',
        'user_id' => $user->id,
    ]));

    $filters = [
        'category' => 'herb',
        'status' => 'in-stock',
        'report_type' => 'all',
    ];

    $this->actingAs($user)
        ->get(route('plants.reports.index', $filters))
        ->assertOk()
        ->assertSee('Available Herb')
        ->assertDontSee('Unavailable Herb')
        ->assertDontSee('Available Succulent');

    $this->actingAs($user)
        ->get(route('plants.reports.excel', $filters))
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8')
        ->assertSee('Available Herb')
        ->assertDontSee('Unavailable Herb')
        ->assertDontSee('Available Succulent');

    $this->actingAs($user)
        ->get(route('plants.reports.pdf', $filters))
        ->assertOk()
        ->assertDownload('plant-report.pdf');
});
