<?php

use App\Models\AccesoGoogle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleAccesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_accesos', function (Blueprint $table) {
            $table->string('correo');
            $table->string('nombre', 75);            
            $table->string('cargo', 30)->nullable();
            $table->primary('correo');
            $table->timestamps();
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
        Schema::dropIfExists('google_accesos');
    }
}
