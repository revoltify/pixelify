<?php

namespace Revoltify\Pixelify\Contracts;

interface PixelifyUserInterface
{
    public function getPixelEmail(): ?string;

    public function getPixelPhone(): ?string;

    public function getPixelFirstName(): ?string;

    public function getPixelLastName(): ?string;

    public function getPixelDateOfBirth(): ?string;

    public function getPixelGender(): ?string;

    public function getPixelCity(): ?string;

    public function getPixelState(): ?string;

    public function getPixelCountryCode(): ?string;

    public function getPixelZipCode(): ?string;
}
