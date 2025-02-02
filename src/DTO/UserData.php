<?php

namespace Revoltify\Pixelify\DTO;

use Illuminate\Support\Carbon;

class UserData
{
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $dateOfBirth = null,
        public ?string $gender = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $zipCode = null,
        public ?string $country = null
    ) {}

    public function toArray(): array
    {
        $data = $this->getHashedData();

        // Add non-hashed parameters
        $this->addNonHashedData($data);

        return array_filter($data);
    }

    private function getHashedData(): array
    {
        $data = [];

        if ($this->firstName) {
            $data['fn'] = $this->hashValue($this->normalize($this->firstName));
        }

        if ($this->lastName) {
            $data['ln'] = $this->hashValue($this->normalize($this->lastName));
        }

        if ($this->email) {
            $data['em'] = $this->hashValue($this->normalize($this->email));
        }

        if ($this->phone) {
            $data['ph'] = $this->hashValue($this->normalizePhone($this->phone));
        }

        if ($this->dateOfBirth) {
            $data['db'] = $this->hashValue($this->normalizeDateOfBirth($this->dateOfBirth));
        }

        if ($this->gender) {
            $data['ge'] = $this->hashValue($this->normalizeGender($this->gender));
        }

        if ($this->city) {
            $data['ct'] = $this->hashValue($this->normalizeCity($this->city));
        }

        if ($this->state) {
            $data['st'] = $this->hashValue($this->normalize($this->state));
        }

        if ($this->zipCode) {
            $data['zp'] = $this->hashValue($this->normalizeZipCode($this->zipCode));
        }

        if ($this->country) {
            $data['country'] = $this->hashValue($this->normalize($this->country));
        }

        return $data;
    }

    private function addNonHashedData(array &$data): void
    {
        $nonHashedFields = [
            'client_ip_address' => request()->ip() ?? null,
            'client_user_agent' => request()->userAgent() ?? null,
            'fbc' => request()->cookie('_fbc') ?? null,
            'fbp' => request()->cookie('_fbp') ?? null,
        ];

        foreach ($nonHashedFields as $key => $value) {
            if ($value !== null) {
                $data[$key] = $value;
            }
        }
    }

    private function hashValue(string $value): string
    {
        return hash('sha256', $value);
    }

    private function normalize(string $value): string
    {
        return mb_strtolower(trim($value), 'UTF-8');
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    private function normalizeDateOfBirth(string $dob): string
    {
        try {
            return Carbon::parse($dob)->format('Ymd');
        } catch (\Exception $e) {
            return $dob;
        }
    }

    private function normalizeGender(string $gender): string
    {
        $gender = strtolower(trim($gender));

        return match ($gender) {
            'female', 'f' => 'f',
            'male', 'm' => 'm',
            default => $gender,
        };
    }

    private function normalizeCity(string $city): string
    {
        return preg_replace('/[^a-z]/', '', mb_strtolower(trim($city), 'UTF-8'));
    }

    private function normalizeZipCode(string $zipCode): string
    {
        $normalized = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $zipCode));

        // For US zip codes, only use first 5 digits
        if (preg_match('/^\d{5,}$/', $normalized)) {
            return substr($normalized, 0, 5);
        }

        return $normalized;
    }

    public static function fromModel($model): self
    {
        if (! $model instanceof \Revoltify\Pixelify\Contracts\PixelifyUserInterface) {
            throw new \InvalidArgumentException('Model must implement PixelifyUserInterface');
        }

        return new self(
            firstName: $model->getPixelFirstName(),
            lastName: $model->getPixelLastName(),
            email: $model->getPixelEmail(),
            phone: $model->getPixelPhone(),
            dateOfBirth: $model->getPixelDateOfBirth(),
            gender: $model->getPixelGender(),
            city: $model->getPixelCity(),
            state: $model->getPixelState(),
            zipCode: $model->getPixelZipCode(),
            country: $model->getPixelCountryCode()
        );
    }
}
