<?php

declare(strict_types=1);

use App\Models\Chat;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

test('to array', function () {
    $room = Room::factory()->create()->fresh();
    expect(array_keys($room->toArray()))->toEqual([
        'id',
        'name',
        'description',
        'user_id',
        'created_at',
        'updated_at',
    ]);
});

test('relationships', function () {
    $room = Room::factory()
        ->has(User::factory()->count(3), 'members')
        ->create();

    Chat::factory()
        ->count(3)
        ->for($room)
        ->for($room->members->first(), 'user')
        ->create();

    expect($room->user)->toBeInstanceOf(User::class)
        ->and($room->members)->toBeInstanceOf(Collection::class)
        ->and($room->members)->each->toBeInstanceOf(User::class)
        ->and($room->chats)->toBeInstanceOf(Collection::class)
        ->and($room->chats)->each->toBeInstanceOf(Chat::class);
});
