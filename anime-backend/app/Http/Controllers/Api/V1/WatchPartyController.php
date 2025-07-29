<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\Actions\WatchParties\CreateWatchParty;
use AnimeSite\Actions\WatchParties\JoinWatchParty;
use AnimeSite\DTOs\WatchParties\CreateWatchPartyDTO;
use AnimeSite\Http\Controllers\Controller;
use AnimeSite\Http\Requests\WatchParties\CreateWatchPartyRequest;
use AnimeSite\Http\Requests\WatchParties\JoinWatchPartyRequest;
use AnimeSite\Http\Resources\WatchPartyResource;
use AnimeSite\Models\WatchParty;
use Illuminate\Http\Response;

class WatchPartyController extends Controller
{
    public function create(CreateWatchPartyRequest $request, CreateWatchParty $action): WatchPartyResource
    {
        $dto = CreateWatchPartyDTO::fromRequest($request);
        $watchParty = $action->handle($dto);

        return (new WatchPartyResource($watchParty))->additional([
            'password' => $watchParty->plain_password ?? null,
        ]);
    }

    public function join(JoinWatchPartyRequest $request, JoinWatchParty $action): WatchPartyResource
    {
        $watchParty = $action->handle(
            slug: $request->input('slug'),
            name: $request->input('name'), // 🔹 Передаємо назву
            password: $request->input('password'),
        );

        return new WatchPartyResource($watchParty);
    }
}
