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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('refund_reason')->nullable()->after('notes');
            $table->timestamp('refund_requested_at')->nullable()->after('refund_reason');
            $table->text('refund_admin_note')->nullable()->after('refund_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['refund_reason', 'refund_requested_at', 'refund_admin_note']);
        });
    }
};
