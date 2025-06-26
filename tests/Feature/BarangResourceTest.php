<?php

use App\Filament\Resources\BarangResource;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

// hook
beforeEach(function () {
    // login user untuk setiap test yang di bawah
    $this->actingAs(User::factory()->create());
});

describe('authenticated user using barang resource', function () {
    it('can show index page', function() {
        $this->get(BarangResource::getUrl('index'))->assertSuccessful();
    });

    it('can access create page', function() {
        $this->get(BarangResource::getUrl('create'))->assertSuccessful();
    });

    it('can create barang', function () {
        $newData = Barang::factory()->make();

        livewire(BarangResource\Pages\CreateBarang::class)
            ->fillForm([
                'nama' => $newData->nama,
                'barcode' => $newData->barcode,
                'satuan' => $newData->satuan,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(
            Barang::class,
            [
                'nama' => $newData->nama,
                'barcode' => $newData->barcode,
                'satuan' => $newData->satuan,
                'version' => $newData->version,
            ]
        );
    });

    it('can edit barang', function() {
        $barang = Barang::factory()->create();
        $newData = Barang::factory()->make();

        livewire(BarangResource\Pages\EditBarang::class, 
            ['record' => $barang->getRouteKey()])
            ->fillForm([
                'nama' => $newData->nama,
                'barcode' => $newData->barcode,
                'satuan' => $newData->satuan,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($barang->refresh())
                ->nama->toBe($newData->nama)
                ->barcode->toBe($newData->barcode)
                ->satuan->toBe($newData->satuan);
    });
});