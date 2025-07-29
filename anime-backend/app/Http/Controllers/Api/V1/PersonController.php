<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\Actions\People\CreatePerson;
use AnimeSite\Actions\People\GetAllPeople;
use AnimeSite\Actions\People\UpdatePerson;
use AnimeSite\DTOs\People\PersonIndexDTO;
use AnimeSite\DTOs\People\PersonStoreDTO;
use AnimeSite\DTOs\People\PersonUpdateDTO;
use AnimeSite\Http\Requests\People\PersonDeleteRequest;
use AnimeSite\Http\Requests\People\PersonIndexRequest;
use AnimeSite\Http\Requests\People\PersonStoreRequest;
use AnimeSite\Http\Requests\People\PersonUpdateRequest;
use AnimeSite\Http\Resources\AnimeResource;
use AnimeSite\Http\Resources\PersonResource;
use AnimeSite\Models\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class PersonController extends Controller
{
    /**
     * Get paginated list of persons with filtering, sorting and pagination
     *
     * @param  PersonIndexRequest  $request
     * @param  GetAllPeople  $action
     * @return AnonymousResourceCollection
     */
    public function index(PersonIndexRequest $request, GetAllPeople $action): AnonymousResourceCollection
    {
        $dto = PersonIndexDTO::fromRequest($request);
        $persons = $action->handle($dto);

        return PersonResource::collection($persons);
    }
    /**
     * Get paginated list of characters with filtering, sorting and pagination
     *
     * @param  PersonIndexRequest  $request
     * @param  GetAllPeople  $action
     * @return AnonymousResourceCollection
     */
    public function characters(PersonIndexRequest $request, GetAllPeople $action): AnonymousResourceCollection
    {
        $originalDto = PersonIndexDTO::fromRequest($request);
        $dto = new PersonIndexDTO(
            query: $originalDto->query,
            page: $originalDto->page,
            perPage: $originalDto->perPage,
            sort: $originalDto->sort,
            direction: $originalDto->direction,
            types: ['character'],
            genders: $originalDto->genders,
            animeIds: $originalDto->animeIds,
            minAge: $originalDto->minAge,
            maxAge: $originalDto->maxAge,
        );

        $persons = $action->handle($dto);

        return PersonResource::collection($persons);
    }
    /**
     * Get detailed information about a specific person
     *
     * @param  Person  $person
     * @return PersonResource
     */
    public function show(Person $person): PersonResource
    {
        $person->load(['animes' => function ($query) {
            $query->withPivot(['character_name', 'voice_person_id']);
        }]);

        // зберемо voice_person_id з pivot
        $voiceActorIds = $person->animes->pluck('pivot.voice_person_id')->filter()->unique();

        // дістаємо з таблиці `people` моделі Person
        $voiceActors = Person::whereIn('id', $voiceActorIds)->get()->keyBy('id');

        return new PersonResource($person, $voiceActors);
    }

    /**
     * Get animes associated with a specific person
     *
     * @param  Person  $person
     * @return AnonymousResourceCollection
     */
    public function animes(Person $person): AnonymousResourceCollection
    {
        $animes = $person->animes()->paginate();

        return AnimeResource::collection($animes);
    }

    /**
     * Store a newly created person
     *
     * @param  PersonStoreRequest  $request
     * @param  CreatePerson  $action
     * @return PersonResource
     * @authenticated
     */
    public function store(PersonStoreRequest $request, CreatePerson $action): PersonResource
    {
        $dto = PersonStoreDTO::fromRequest($request);
        $person = $action->handle($dto);

        return new PersonResource($person);
    }

    /**
     * Update the specified person
     *
     * @param  PersonUpdateRequest  $request
     * @param  Person  $person
     * @param  UpdatePerson  $action
     * @return PersonResource
     * @authenticated
     */
    public function update(PersonUpdateRequest $request, Person $person, UpdatePerson $action): PersonResource
    {
        $dto = PersonUpdateDTO::fromRequest($request);
        $person = $action->handle($person, $dto);

        return new PersonResource($person);
    }

    /**
     * Remove the specified person
     *
     * @param  PersonDeleteRequest  $request
     * @param  Person  $person
     * @return JsonResponse
     * @authenticated
     */
    public function destroy(PersonDeleteRequest $request, Person $person): JsonResponse
    {
        // Check if the person has related animes
        if ($person->animes()->exists()) {
            return response()->json([
                'message' => 'Cannot delete person with associated animes. Remove associations first.',
            ], 422);
        }

        $person->delete();

        return response()->json([
            'message' => 'Person deleted successfully',
        ]);
    }
}
