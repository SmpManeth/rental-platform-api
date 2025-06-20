<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'vehicleTitle',
        'make',
        'model',
        'year',
        'stateOfRegistration',
        'transmission',
        'fuelType',
        'odometer',
        'registrationNumber',
        'registrationExpiry',
        'ctpExpiry',
        'vin',
        'bodyType',
        'driveType',
        'seats',
        'doors',
        'color',
        'securityBond',
        'bookingFrequency',
        'defaultPrice',
        'cancellationPolicy',
        'deliveryOptions',
        'extras',
        'terms',
        'pickupLocation',
        'insuranceIncluded',
        'insurancePrice',
        'insuranceFrequency',
        'photos',
        'videoUpload',
        'status',
        'thumbnail',
        'type',
        'price',
        'location'
    ];

    protected $casts = [
        'deliveryOptions' => 'array',
        'extras' => 'array',
        'photos' => 'array',
        'insuranceIncluded' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
