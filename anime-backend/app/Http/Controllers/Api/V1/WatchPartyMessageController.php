<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\Actions\WatchPartyMessages\CreateWatchPartyMessage;
use AnimeSite\DTOs\WatchParties\CreateWatchPartyMessageDTO;
use AnimeSite\Http\Controllers\Controller;
use AnimeSite\Http\Requests\WatchPartyMessages\CreateWatchPartyMessageRequest;
use AnimeSite\Http\Resources\WatchPartyMessageResource;
use AnimeSite\Models\WatchParty;

class WatchPartyMessageController extends Controller
{
    public function index(WatchParty $watchParty): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return WatchPartyMessageResource::collection(
            $watchParty->messages()->latest()->limit(100)->get()
        );
    }

    public function store(CreateWatchPartyMessageRequest $request, WatchParty $watchParty, CreateWatchPartyMessage $action): WatchPartyMessageResource
    {
        $dto = CreateWatchPartyMessageDTO::fromRequest($request);
        $message = $action->handle($watchParty, $dto);

        return new WatchPartyMessageResource($message);
    }
}

