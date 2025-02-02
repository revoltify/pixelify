<?php

namespace Revoltify\Pixelify\Traits;

use Revoltify\Pixelify\DTO\UserData;

trait HasPixelifyUser
{
    public function toPixelUser(): UserData
    {
        return UserData::fromModel($this);
    }

    public function getPixelEmail(): ?string
    {
        return $this->email ?? null;
    }

    public function getPixelPhone(): ?string
    {
        return $this->phone ?? null;
    }

    public function getPixelFirstName(): ?string
    {
        return $this->first_name ?? null;
    }

    public function getPixelLastName(): ?string
    {
        return $this->last_name ?? null;
    }

    public function getPixelDateOfBirth(): ?string
    {
        return $this->date_of_birth ?? null;
    }

    public function getPixelCity(): ?string
    {
        return $this->city ?? null;
    }

    public function getPixelState(): ?string
    {
        return $this->state ?? null;
    }

    public function getPixelCountryCode(): ?string
    {
        return $this->country_code ?? null;
    }

    public function getPixelZipCode(): ?string
    {
        return $this->zip_code ?? null;
    }

    public function getPixelGender(): ?string
    {
        return $this->gender ?? null;
    }
}
