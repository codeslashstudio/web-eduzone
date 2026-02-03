<?php

/**
 * DEBUG SCRIPT - Check Session & Database
 * Place this file in: app/Controllers/DebugKeuangan.php
 */

namespace App\Controllers;

class DebugKeuangan extends BaseController
{
    public function checkSession()
    {
        echo "<h1>DEBUG: Session Information</h1>";
        echo "<pre>";

        echo "=== SESSION DATA ===\n";
        print_r($_SESSION);

        echo "\n\n=== CodeIgniter Session ===\n";
        echo "isLoggedIn: " . (session()->get('isLoggedIn') ? 'YES' : 'NO') . "\n";
        echo "User ID: " . (session()->get('id') ?? 'NULL') . "\n";
        echo "Username: " . (session()->get('username') ?? 'NULL') . "\n";
        echo "Full Name: " . (session()->get('fullname') ?? 'NULL') . "\n";
        echo "Role: " . (session()->get('role') ?? 'NULL') . "\n";
        echo "Selected Role: " . (session()->get('selectedRole') ?? 'NULL') . "\n";

        echo "\n\n=== FALLBACK TEST ===\n";
        $user_id = session()->get('id') ?? 1;
        echo "user_id will be: $user_id\n";

        $created_by = session()->get('id') ?? 1;
        echo "created_by will be: $created_by\n";

        echo "</pre>";
    }

    public function checkDatabase()
    {
        $db = \Config\Database::connect();

        echo "<h1>DEBUG: Database Structure</h1>";
        echo "<pre>";

        // Check transaksi_pemasukan structure
        echo "=== TABLE: transaksi_pemasukan ===\n";
        $query = $db->query("DESCRIBE transaksi_pemasukan");
        $fields = $query->getResultArray();

        $hasUserId = false;
        foreach ($fields as $field) {
            echo $field['Field'] . " | " . $field['Type'] . " | " .
                ($field['Null'] == 'YES' ? 'NULL' : 'NOT NULL') . " | " .
                "Default: " . ($field['Default'] ?? 'NONE') . "\n";

            if ($field['Field'] === 'user_id') {
                $hasUserId = true;
            }
        }

        if (!$hasUserId) {
            echo "\n⚠️ WARNING: Column 'user_id' NOT FOUND!\n";
            echo "You need to run the SQL patch!\n";
        } else {
            echo "\n✅ Column 'user_id' EXISTS\n";
        }

        // Check if there are any NULL values
        echo "\n=== CHECKING NULL VALUES ===\n";
        $nullCheck = $db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN user_id IS NULL THEN 1 ELSE 0 END) as null_user_id,
                SUM(CASE WHEN created_by IS NULL THEN 1 ELSE 0 END) as null_created_by
            FROM transaksi_pemasukan
        ")->getRowArray();

        echo "Total rows: " . $nullCheck['total'] . "\n";
        echo "NULL user_id: " . $nullCheck['null_user_id'] . "\n";
        echo "NULL created_by: " . $nullCheck['null_created_by'] . "\n";

        if ($nullCheck['null_user_id'] > 0 || $nullCheck['null_created_by'] > 0) {
            echo "\n⚠️ WARNING: Found NULL values! Run UPDATE query.\n";
        } else {
            echo "\n✅ No NULL values found\n";
        }

        echo "</pre>";
    }

    public function testInsert()
    {
        $db = \Config\Database::connect();

        echo "<h1>DEBUG: Test Insert</h1>";
        echo "<pre>";

        $testData = [
            'no_transaksi' => 'TEST-' . time(),
            'tanggal_transaksi' => date('Y-m-d'),
            'id_kategori' => 1,
            'keterangan' => 'Test Insert Debug',
            'jumlah' => 100000,
            'metode_pembayaran' => 'Tunai',
            'status' => 'Verified',
            'user_id' => session()->get('id') ?? 1,
            'created_by' => session()->get('id') ?? 1,
            'tahun_ajaran' => '2025/2026',
            'semester' => 'Ganjil'
        ];

        echo "Attempting to insert:\n";
        print_r($testData);

        try {
            $result = $db->table('transaksi_pemasukan')->insert($testData);

            if ($result) {
                echo "\n✅ INSERT SUCCESS!\n";
                echo "Insert ID: " . $db->insertID() . "\n";
            } else {
                echo "\n❌ INSERT FAILED!\n";
                echo "Error: " . $db->error() . "\n";
            }
        } catch (\Exception $e) {
            echo "\n❌ EXCEPTION: " . $e->getMessage() . "\n";
        }

        echo "</pre>";
    }

    public function runFix()
    {
        $db = \Config\Database::connect();

        echo "<h1>AUTO FIX: Adding user_id column</h1>";
        echo "<pre>";

        try {
            // Check if column exists
            $query = $db->query("
                SELECT COUNT(*) as count
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'transaksi_pemasukan'
                  AND COLUMN_NAME = 'user_id'
            ");

            $exists = $query->getRowArray()['count'];

            if ($exists == 0) {
                echo "Adding user_id column to transaksi_pemasukan...\n";
                $db->query("
                    ALTER TABLE `transaksi_pemasukan` 
                    ADD COLUMN `user_id` int(11) DEFAULT 1 
                    COMMENT 'User yang input' 
                    AFTER `semester`
                ");
                echo "✅ Column added!\n";
            } else {
                echo "✅ Column already exists\n";
            }

            // Update NULL values
            echo "\nUpdating NULL values...\n";
            $db->query("
                UPDATE `transaksi_pemasukan` 
                SET `user_id` = COALESCE(`created_by`, 1) 
                WHERE `user_id` IS NULL
            ");
            echo "✅ NULL values updated!\n";

            // Repeat for pengeluaran
            $query2 = $db->query("
                SELECT COUNT(*) as count
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'transaksi_pengeluaran'
                  AND COLUMN_NAME = 'user_id'
            ");

            $exists2 = $query2->getRowArray()['count'];

            if ($exists2 == 0) {
                echo "\nAdding user_id column to transaksi_pengeluaran...\n";
                $db->query("
                    ALTER TABLE `transaksi_pengeluaran` 
                    ADD COLUMN `user_id` int(11) DEFAULT 1 
                    COMMENT 'User yang input' 
                    AFTER `is_from_bos`
                ");
                echo "✅ Column added!\n";
            } else {
                echo "✅ Column already exists\n";
            }

            $db->query("
                UPDATE `transaksi_pengeluaran` 
                SET `user_id` = COALESCE(`created_by`, 1) 
                WHERE `user_id` IS NULL
            ");
            echo "✅ NULL values updated!\n";

            echo "\n\n🎉 FIX COMPLETED!\n";
            echo "You can now try to add pemasukan again.\n";
        } catch (\Exception $e) {
            echo "❌ ERROR: " . $e->getMessage() . "\n";
        }

        echo "</pre>";
    }
}
