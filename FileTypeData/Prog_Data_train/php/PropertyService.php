<?php

namespace me\adamcameron\general\cleancode\service;

use me\adamcameron\general\cleancode\repository\MediaRepository;
use me\adamcameron\general\cleancode\repository\MetadataRepository;
use me\adamcameron\general\cleancode\repository\PersonnelRepository;
use me\adamcameron\general\cleancode\repository\PropertyRepository;
use me\adamcameron\general\cleancode\repository\SocialRepository;
use me\adamcameron\general\cleancode\repository\AvailabilityRepository;

class PropertyService
{
    private $propertyRepository;
    private $mediaRepository;
    private $metadataRepository;
    private $socialRepository;
    private $personnelRepository;

    public function __construct(
        PropertyRepository $propertyRepository,
        MediaRepository $mediaRepository,
        MetadataRepository $metadataRepository,
        SocialRepository $socialRepository,
        PersonnelRepository $personnelRepository,
        AvailabilityRepository $availabilityRepo
    ) {
        $this->propertyRepository = $propertyRepository;
        $this->mediaRepository = $mediaRepository;
        $this->metadataRepository = $metadataRepository;
        $this->socialRepository = $socialRepository;
        $this->personnelRepository = $personnelRepository;
        $this->availabilityRepo = $availabilityRepo;
    }

public function update(int $id, array $parameters): void
{
    $this->propertyRepository->update($id, $parameters);

    if (array_key_exists('media', $parameters) && is_array($parameters['media'])) {
        $this->mediaRepository->update($id, $parameters['media']);
    }
    if (array_key_exists('metadata', $parameters) && is_array($parameters['metadata'])) {
        $this->metadataRepository->update($id, $parameters['metadata']);
    }
    if (array_key_exists('social', $parameters) && is_array($parameters['social'])) {
        $this->socialRepository->update($id, $parameters['social']);
    }
    if (array_key_exists('personnel', $parameters) && is_array($parameters['personnel'])) {
        $this->personnelRepository->update($id, $parameters['personnel']);
    }
    if (array_key_exists('availability', $parameters) && is_array($parameters['availability'])) {
        $this->availabilityRepo->update($id, $parameters['availability']);
    }
}

    public function update2(int $id, array $parameters): void
    {
        $this->propertyRepository->update($id, $parameters);

        if ($this->keyExistsAndIsArray('media', $parameters)) {
            $this->mediaRepository->update($id, $parameters['media']);
        }
        if ($this->keyExistsAndIsArray('metadata', $parameters)) {
            $this->metadataRepository->update($id, $parameters['metadata']);
        }
        if ($this->keyExistsAndIsArray('social', $parameters)) {
            $this->socialRepository->update($id, $parameters['social']);
        }
        if ($this->keyExistsAndIsArray('personnel', $parameters)) {
            $this->personnelRepository->update($id, $parameters['personnel']);
        }
        if ($this->keyExistsAndIsArray('availability', $parameters)) {
            $this->availabilityRepo->update($id, $parameters['availability']);
        }
    }

    public function update3(int $id, array $parameters): void
    {
        $this->propertyRepository->update($id, $parameters);

        $this->updateOptional($id, $parameters, 'media', $this->mediaRepository);
        $this->updateOptional($id, $parameters, 'metadata', $this->metadataRepository);
        $this->updateOptional($id, $parameters, 'social', $this->socialRepository);
        $this->updateOptional($id, $parameters, 'personnel', $this->personnelRepository);
        $this->updateOptional($id, $parameters, 'availability', $this->availabilityRepo);
    }

    private function keyExistsAndIsArray(string $key, array $array) : bool
    {
        return array_key_exists($key, $array) && is_array($array[$key]);
    }

    private function updateOptional($id, $parameters, $optional, $repository)
    {
        if (array_key_exists($optional, $parameters) && is_array($parameters[$optional])) {
            $repository->update($id, $parameters[$optional]);
        }
    }
}
