<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop the existing enum and recreate with new values
            $table->dropColumn('status');
        });
        
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->nullable()->after('resume_path');
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->nullable()->after('resume_path');
        });
    }
};