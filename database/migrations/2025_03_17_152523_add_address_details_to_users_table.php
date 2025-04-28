<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressDetailsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Adding new structured address fields
            $table->string('address_line1')->after('phone');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('town')->after('address_line2');
            $table->string('county')->after('town');
            $table->string('postcode')->after('county');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['address_line1', 'address_line2', 'town', 'county', 'postcode']);
        });
    }
}
