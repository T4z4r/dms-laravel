# Database Schema

This document outlines the complete database structure for the DMS (Document Management System) Laravel application, including migration code for each table.

## Migrations Overview

The database is built through the following migrations:

1. [01_01_01_0000000_create_departments_table.php](#departments-migration)
2. [0001_01_01_000000_create_users_table.php](#users-migration)
3. [0001_01_01_000001_create_cache_table.php](#cache-migration)
4. [0001_01_01_000002_create_jobs_table.php](#jobs-migration)
5. [01_01_01_000003_create_file_categories_table.php](#file-categories-migration)
6. [2025_10_22_125611_create_permission_tables.php](#permissions-migration)
7. [2025_10_22_130312_create_files_table.php](#files-migration)
8. [2025_10_22_133725_create_signatures_table.php](#signatures-migration)
9. [2025_10_22_134438_create_file_shares_table.php](#file-shares-migration)
10. [2025_10_23_111518_add_department_id_to_users_table.php](#add-department-to-users-migration)
11. [2025_10_24_091136_add_signature_to_users_table.php](#add-signature-to-users-migration)
12. [2025_11_05_201343_add_comment_to_file_shares_access_enum.php](#add-comment-to-file-shares-migration)
13. [2025_11_05_210908_create_file_comments_table.php](#file-comments-migration)
14. [2025_11_05_212238_create_file_access_requests_table.php](#file-access-requests-migration)
15. [2025_11_10_124600_add_access_control_fields_to_files_table.php](#add-access-control-to-files-migration)

## Tables Overview

The database consists of the following tables:

1. [departments](#departments)
2. [users](#users)
3. [password_reset_tokens](#password_reset_tokens)
4. [sessions](#sessions)
5. [cache](#cache)
6. [cache_locks](#cache_locks)
7. [jobs](#jobs)
8. [job_batches](#job_batches)
9. [failed_jobs](#failed_jobs)
10. [file_categories](#file_categories)
11. [permissions](#permissions)
12. [roles](#roles)
13. [model_has_permissions](#model_has_permissions)
14. [model_has_roles](#model_has_roles)
15. [role_has_permissions](#role_has_permissions)
16. [files](#files)
17. [signatures](#signatures)
18. [file_shares](#file_shares)
19. [file_comments](#file_comments)
20. [file_access_requests](#file_access_requests)

---

## departments-migration

**Migration File:** `01_01_01_0000000_create_departments_table.php`

```php
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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
```

## departments

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| name | varchar(255) | NOT NULL | Department name |
| description | varchar(255) | NULL | Department description |
| created_at | timestamp | NULL | Creation timestamp |
| updated_at | timestamp | NULL | Update timestamp |

---

## users-migration

**Migration File:** `0001_01_01_000000_create_users_table.php`

```php
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
         Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('position')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('status')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
```

**Additional Migration:** `2025_10_23_111518_add_department_id_to_users_table.php`

```php
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
         Schema::table('users', function (Blueprint $table) {
             $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('users', function (Blueprint $table) {
             $table->dropForeign(['department_id']);
         });
    }
};
```

**Additional Migration:** `2025_10_24_091136_add_signature_to_users_table.php`

```php
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
        Schema::table('users', function (Blueprint $table) {
            $table->longText('signature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('signature');
        });
    }
};
```

## users

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| name | varchar(255) | NOT NULL | User full name |
| email | varchar(255) | UNIQUE, NOT NULL | User email address |
| email_verified_at | timestamp | NULL | Email verification timestamp |
| password | varchar(255) | NOT NULL | Hashed password |
| department_id | bigint unsigned | NULL, FOREIGN KEY → departments(id) ON DELETE SET NULL | User's department |
| position | varchar(255) | NULL | User's position/job title |
| phone | varchar(255) | NULL | User's phone number |
| status | tinyint(1) | NOT NULL, DEFAULT 1 | User active status (1=active, 0=inactive) |
| signature | longtext | NULL | User's digital signature (base64 or path) |
| remember_token | varchar(100) | NULL | Remember token for authentication |
| created_at | timestamp | NULL | Creation timestamp |
| updated_at | timestamp | NULL | Update timestamp |

---

## password_reset_tokens

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| email | varchar(255) | PRIMARY KEY | User email (references users.email) |
| token | varchar(255) | NOT NULL | Reset token |
| created_at | timestamp | NULL | Token creation timestamp |

---

## sessions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | varchar(255) | PRIMARY KEY | Session ID |
| user_id | bigint unsigned | NULL, INDEX, FOREIGN KEY → users(id) | Associated user |
| ip_address | varchar(45) | NULL | Client IP address |
| user_agent | text | NULL | Client user agent |
| payload | longtext | NOT NULL | Session data |
| last_activity | int | INDEX, NOT NULL | Last activity timestamp |

---

## cache-migration

**Migration File:** `0001_01_01_000001_create_cache_table.php`

```php
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
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
```

## cache

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| key | varchar(255) | PRIMARY KEY | Cache key |
| value | mediumtext | NOT NULL | Cached value |
| expiration | int | NOT NULL | Expiration timestamp |

---

## cache_locks

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| key | varchar(255) | PRIMARY KEY | Lock key |
| owner | varchar(255) | NOT NULL | Lock owner |
| expiration | int | NOT NULL | Expiration timestamp |

---

## jobs-migration

**Migration File:** `0001_01_01_000002_create_jobs_table.php`

```php
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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
```

## jobs

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| queue | varchar(255) | INDEX, NOT NULL | Queue name |
| payload | longtext | NOT NULL | Job payload |
| attempts | tinyint unsigned | NOT NULL | Number of attempts |
| reserved_at | int unsigned | NULL | Reservation timestamp |
| available_at | int unsigned | NOT NULL | Available timestamp |
| created_at | int unsigned | NOT NULL | Creation timestamp |

---

## job_batches

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | varchar(255) | PRIMARY KEY | Batch ID |
| name | varchar(255) | NOT NULL | Batch name |
| total_jobs | int | NOT NULL | Total jobs in batch |
| pending_jobs | int | NOT NULL | Pending jobs count |
| failed_jobs | int | NOT NULL | Failed jobs count |
| failed_job_ids | longtext | NOT NULL | Failed job IDs |
| options | mediumtext | NULL | Batch options |
| cancelled_at | int | NULL | Cancellation timestamp |
| created_at | int | NOT NULL | Creation timestamp |
| finished_at | int | NULL | Completion timestamp |

---

## failed_jobs

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| uuid | varchar(255) | UNIQUE, NOT NULL | Job UUID |
| connection | text | NOT NULL | Connection name |
| queue | text | NOT NULL | Queue name |
| payload | longtext | NOT NULL | Job payload |
| exception | longtext | NOT NULL | Exception details |
| failed_at | timestamp | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Failure timestamp |

---

## file-categories-migration

**Migration File:** `01_01_01_000003_create_file_categories_table.php`

```php
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
        Schema::create('file_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_categories');
    }
};
```

## file_categories

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| name | varchar(255) | NOT NULL | Category name |
| created_at | timestamp | NULL | Creation timestamp |
| updated_at | timestamp | NULL | Update timestamp |

---

## permissions-migration

**Migration File:** `2025_10_22_125611_create_permission_tables.php`

```php
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
        $teams = config('permission.teams');
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        throw_if(empty($tableNames), new Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.'));
        throw_if($teams && empty($columnNames['team_foreign_key'] ?? null), new Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.'));

        Schema::create($tableNames['permissions'], static function (Blueprint $table) {
            // $table->engine('InnoDB');
            $table->bigIncrements('id'); // permission id
            $table->string('name');       // For MyISAM use string('name', 225); // (or 166 for InnoDB with Redundant/Compact row format)
            $table->string('guard_name'); // For MyISAM use string('guard_name', 25);
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['roles'], static function (Blueprint $table) use ($teams, $columnNames) {
            // $table->engine('InnoDB');
            $table->bigIncrements('id'); // role id
            if ($teams || config('permission.testing')) { // permission.testing is a fix for sqlite testing
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
            }
            $table->string('name');       // For MyISAM use string('name', 225); // (or 166 for InnoDB with Redundant/Compact row format)
            $table->string('guard_name'); // For MyISAM use string('guard_name', 25);
            $table->timestamps();
            if ($teams || config('permission.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });

        Schema::create($tableNames['model_has_permissions'], static function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission, $teams) {
            $table->unsignedBigInteger($pivotPermission);

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                $table->primary([$columnNames['team_foreign_key'], $pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            } else {
                $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            }

        });

        Schema::create($tableNames['model_has_roles'], static function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $teams) {
            $table->unsignedBigInteger($pivotRole);

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign($pivotRole)
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                $table->primary([$columnNames['team_foreign_key'], $pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
            } else {
                $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
            }
        });

        Schema::create($tableNames['role_has_permissions'], static function (Blueprint $table) use ($tableNames, $pivotRole, $pivotPermission) {
            $table->unsignedBigInteger($pivotPermission);
            $table->unsignedBigInteger($pivotRole);

            $table->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign($pivotRole)
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
};
```

## permissions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| name | varchar(255) | NOT NULL | Permission name |
| guard_name | varchar(255) | NOT NULL | Guard name |
| created_at | timestamp | NULL | Creation timestamp |
| updated_at | timestamp | NULL | Update timestamp |

**Unique Constraint:** (name, guard_name)

---

## roles

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| team_foreign_key | bigint unsigned | NULL, INDEX (if teams enabled) | Team foreign key |
| name | varchar(255) | NOT NULL | Role name |
| guard_name | varchar(255) | NOT NULL | Guard name |
| created_at | timestamp | NULL | Creation timestamp |
| updated_at | timestamp | NULL | Update timestamp |

**Unique Constraint:** (team_foreign_key, name, guard_name) or (name, guard_name)

---

## model_has_permissions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| permission_id | bigint unsigned | PRIMARY KEY (part), FOREIGN KEY → permissions(id) ON DELETE CASCADE | Permission ID |
| model_type | varchar(255) | PRIMARY KEY (part), NOT NULL | Model type |
| model_morph_key | bigint unsigned | PRIMARY KEY (part), INDEX, NOT NULL | Model ID |
| team_foreign_key | bigint unsigned | PRIMARY KEY (part), INDEX (if teams enabled) | Team foreign key |

---

## model_has_roles

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| role_id | bigint unsigned | PRIMARY KEY (part), FOREIGN KEY → roles(id) ON DELETE CASCADE | Role ID |
| model_type | varchar(255) | PRIMARY KEY (part), NOT NULL | Model type |
| model_morph_key | bigint unsigned | PRIMARY KEY (part), INDEX, NOT NULL | Model ID |
| team_foreign_key | bigint unsigned | PRIMARY KEY (part), INDEX (if teams enabled) | Team foreign key |

---

## role_has_permissions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| permission_id | bigint unsigned | PRIMARY KEY (part), FOREIGN KEY → permissions(id) ON DELETE CASCADE | Permission ID |
| role_id | bigint unsigned | PRIMARY KEY (part), FOREIGN KEY → roles(id) ON DELETE CASCADE | Role ID |

---

## files-migration

**Migration File:** `2025_10_22_130312_create_files_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');
            $table->string('name'); // internal file name
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('file_categories')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_signed')->default(false);
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('signed_at')->nullable();
            $table->string('share_token')->nullable()->index();
            $table->timestamp('shared_until')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
}
```

**Additional Migration:** `2025_11_10_124600_add_access_control_fields_to_files_table.php`

```php
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
        Schema::table('files', function (Blueprint $table) {
            $table->json('allowed_users')->nullable(); // Array of user IDs who can access
            $table->json('restricted_departments')->nullable(); // Array of department IDs that cannot access
            $table->enum('access_type', ['view_only', 'downloadable'])->default('downloadable'); // View only or downloadable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn(['allowed_users', 'restricted_departments', 'access_type']);
        });
    }
};
```

## files

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| original_name | varchar(255) | NOT NULL | Original file name |
| name | varchar(255) | NOT NULL | Internal file name |
| path | varchar(255) | NOT NULL | File storage path |
| mime_type | varchar(255) | NULL | MIME type |
| category_id | bigint unsigned | NULL, FOREIGN KEY → file_categories(id) ON DELETE SET NULL | File category |
| department_id | bigint unsigned | NULL, FOREIGN KEY → departments(id) ON DELETE SET NULL | Associated department |
| uploaded_by | bigint unsigned | NULL, FOREIGN KEY → users(id) ON DELETE SET NULL | Uploader user |
| is_signed | tinyint(1) | NOT NULL, DEFAULT 0 | Whether file is signed |
| signed_by | bigint unsigned | NULL, FOREIGN KEY → users(id) ON DELETE SET NULL | User who signed |
| signed_at | timestamp | NULL | Signature timestamp |
| share_token | varchar(255) | NULL, INDEX | Public share token |
| shared_until | timestamp | NULL | Share expiration |
| allowed_users | json | NULL | Array of user IDs with access |
| restricted_departments | json | NULL | Array of restricted department IDs |
| access_type | enum('view_only', 'downloadable') | NOT NULL, DEFAULT 'downloadable' | Access type |
| deleted_at | timestamp | NULL | Soft delete timestamp |
| created_at | timestamp | NULL | Creation timestamp |
| updated_at | timestamp | NULL | Update timestamp |

---

## signatures-migration

**Migration File:** `2025_10_22_133725_create_signatures_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignaturesTable extends Migration
{
    public function up()
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('signature_type', ['drawn','typed','uploaded'])->default('drawn');
            $table->longText('signature_image'); // base64 data or storage path
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('signatures');
    }
}
```

## signatures

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| file_id | bigint unsigned | FOREIGN KEY → files(id) ON DELETE CASCADE | Associated file |
| user_id | bigint unsigned | FOREIGN KEY → users(id) ON DELETE CASCADE | Signing user |
| signature_type | enum('drawn','typed','uploaded') | NOT NULL, DEFAULT 'drawn' | Signature type |
| signature_image | longtext | NOT NULL | Signature data (base64 or path) |
| notes | text | NULL | Additional notes |
| created_at | timestamp | NULL | Creation timestamp |
| updated_at | timestamp | NULL | Update timestamp |

---

## file-shares-migration

**Migration File:** `2025_10_22_134438_create_file_shares_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileSharesTable extends Migration
{
    public function up()
    {
        Schema::create('file_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete();
            $table->string('email')->index();
            $table->enum('access', ['view','edit','sign'])->default('view');
            $table->foreignId('shared_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_shares');
    }
}
```

**Additional Migration:** `2025_11_05_201343_add_comment_to_file_shares_access_enum.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE file_shares MODIFY COLUMN access ENUM('view','edit','sign','comment') DEFAULT 'view'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE file_shares MODIFY COLUMN access ENUM('view','edit','sign') DEFAULT 'view'");
    }
};
```

## file_shares

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| file_id | bigint unsigned | FOREIGN KEY → files(id) ON DELETE CASCADE | Shared file |
| email | varchar(255) | INDEX, NOT NULL | Recipient email |
| access | enum('view','edit','sign','comment') | NOT NULL, DEFAULT 'view' | Access level |
| shared_by | bigint unsigned | FOREIGN KEY → users(id) ON DELETE CASCADE | User who shared |
| created_at | timestamp | NULL | Creation timestamp |
| updated_at | timestamp | NULL | Update timestamp |

---

## file-comments-migration

**Migration File:** `2025_11_05_210908_create_file_comments_table.php`

```php
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
        Schema::create('file_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_comments');
    }
};
```

## file_comments

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| file_id | bigint unsigned | FOREIGN KEY → files(id) ON DELETE CASCADE | Commented file |
| user_id | bigint unsigned | FOREIGN KEY → users(id) ON DELETE CASCADE | Commenting user |
| comment | text | NOT NULL | Comment content |
| created_at | timestamp | NULL | Creation timestamp |
| updated_at | timestamp | NULL | Update timestamp |

---

## file-access-requests-migration

**Migration File:** `2025_11_05_212238_create_file_access_requests_table.php`

```php
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
        Schema::create('file_access_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('requested_access', ['view','comment','edit','sign'])->default('view');
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->text('request_message')->nullable();
            $table->text('response_message')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_access_requests');
    }
};
```

## file_access_requests

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Primary key |
| file_id | bigint unsigned | FOREIGN KEY → files(id) ON DELETE CASCADE | Requested file |
| requester_id | bigint unsigned | FOREIGN KEY → users(id) ON DELETE CASCADE | Requesting user |
| approver_id | bigint unsigned | NULL, FOREIGN KEY → users(id) ON DELETE SET NULL | Approving user |
| requested_access | enum('view','comment','edit','sign') | NOT NULL, DEFAULT 'view' | Requested access level |
| status | enum('pending','approved','rejected') | NOT NULL, DEFAULT 'pending' | Request status |
| request_message | text | NULL | Request message |
| response_message | text | NULL | Response message |
| responded_at | timestamp | NULL | Response timestamp |
| created_at | timestamp | NULL | Creation timestamp |
| updated_at | timestamp | NULL | Update timestamp |

---

## Relationships

### Core Relationships
- **users** belongs to **departments** (department_id)
- **files** belongs to **file_categories** (category_id)
- **files** belongs to **departments** (department_id)
- **files** belongs to **users** (uploaded_by, signed_by)

### Permission System (Spatie Laravel Permission)
- **roles** have many **permissions** through **role_has_permissions**
- **users** have many **permissions** through **model_has_permissions**
- **users** have many **roles** through **model_has_roles**

### File Management
- **files** has many **signatures**
- **files** has many **file_shares**
- **files** has many **file_comments**
- **files** has many **file_access_requests**
- **signatures** belongs to **files** and **users**
- **file_shares** belongs to **files** and **users** (shared_by)
- **file_comments** belongs to **files** and **users**
- **file_access_requests** belongs to **files**, **users** (requester_id, approver_id)

### Access Control
- Files can have granular access control through:
  - **allowed_users**: JSON array of specific user IDs
  - **restricted_departments**: JSON array of department IDs that cannot access
  - **access_type**: 'view_only' or 'downloadable'
  - **file_access_requests**: For requesting additional access permissions