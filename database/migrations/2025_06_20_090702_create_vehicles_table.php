<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('vehicleTitle');
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->string('stateOfRegistration');
            $table->enum('transmission', ['Manual', 'Auto']);
            $table->enum('fuelType', ['Petrol', 'Diesel', 'Hybrid', 'Electric']);
            $table->integer('odometer');
            $table->string('registrationNumber')->unique();
            $table->date('registrationExpiry');
            $table->date('ctpExpiry');
            $table->string('vin')->nullable();
            $table->string('bodyType')->nullable();
            $table->enum('driveType', ['2WD', '4WD', 'AWD'])->nullable();
            $table->integer('seats')->nullable();
            $table->integer('doors')->nullable();
            $table->string('color')->nullable();
            $table->integer('securityBond')->nullable();
            $table->enum('bookingFrequency', ['Daily', 'Weekly', 'Monthly']);
            $table->decimal('defaultPrice', 10, 2);
            $table->enum('cancellationPolicy', ['Flexible', 'Moderate', 'Strict']);
            $table->json('deliveryOptions');
            $table->json('extras')->nullable();
            $table->text('terms')->nullable();
            $table->string('pickupLocation');
            $table->boolean('insuranceIncluded');
            $table->decimal('insurancePrice', 10, 2)->nullable();
            $table->enum('insuranceFrequency', ['Daily', 'Per Booking', 'Weekly'])->nullable();
            $table->json('photos')->nullable();
            $table->string('videoUpload')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft', 'under_maintenance']);
            $table->string('thumbnail')->nullable();
            $table->string('type');
            $table->string('price');
            $table->string('location');
            $table->timestamps(); // for dateAdded & lastModified
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
