<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the existing foreign key constraint
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });

        // Re-add the foreign key constraint referencing the correct table
        Schema::table('applications', function (Blueprint $table) {
            $table->foreign('job_id')
                  ->references('id')
                  ->on('jobs_table')  // Changed from 'jobs' to 'jobs_table'
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            $table->foreign('job_id')
                  ->references('id')
                  ->on('jobs')
                  ->onDelete('cascade');
        });
    }
};