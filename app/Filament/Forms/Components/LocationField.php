<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class LocationField extends Field
{
    protected string $view = 'filament.forms.components.location-field';

    protected string $latitudeField = 'latitude';
    protected string $longitudeField = 'longitude';
    protected string $addressField = 'address';
    protected string $streetField = 'street';
    protected string $cityField = 'city';

    public function latitude(string $field): static
    {
        $this->latitudeField = $field;
        return $this;
    }

    public function longitude(string $field): static
    {
        $this->longitudeField = $field;
        return $this;
    }

    public function address(string $field): static
    {
        $this->addressField = $field;
        return $this;
    }

    public function street(string $field): static
    {
        $this->streetField = $field;
        return $this;
    }

    public function city(string $field): static
    {
        $this->cityField = $field;
        return $this;
    }

    public function getLatitudeField(): string
    {
        return $this->latitudeField;
    }

    public function getLongitudeField(): string
    {
        return $this->longitudeField;
    }

    public function getAddressField(): string
    {
        return $this->addressField;
    }

    public function getStreetField(): string
    {
        return $this->streetField;
    }

    public function getCityField(): string
    {
        return $this->cityField;
    }
}
