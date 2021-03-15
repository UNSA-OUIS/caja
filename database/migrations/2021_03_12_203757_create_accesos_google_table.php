<?php

use App\Models\AccesoGoogle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccesosGoogleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesos_google', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 75);
            $table->string('correo')->unique();
            $table->string('cargo', 30)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        AccesoGoogle::create(['correo' => 'rsiza@unsa.edu.pe', 'nombre' => 'Renzo Siza Tejada']);
        AccesoGoogle::create(['correo' => 'jortiz@unsa.edu.pe', 'nombre' => 'Jesus Ortiz']);
        AccesoGoogle::create(['correo' => 'gnunezc@unsa.edu.pe', 'nombre' => 'Gary Nuñez']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accesos_google');
    }
}
