<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'employment_status')) {
                $table->enum('employment_status', ['applicant', 'pending', 'hired', 'rejected', 'not_employed'])->default('applicant')->after('role');
            }
            if (!Schema::hasColumn('users', 'hired_position_id')) {
                $table->foreignId('hired_position_id')->nullable()->constrained('job_listings')->onDelete('set null')->after('employment_status');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['hired_position_id']);
            $table->dropColumn(['employment_status', 'hired_position_id']);
        });
    }
};