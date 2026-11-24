<?php

namespace App\Http\Controllers;

use App\Http\Requests\BackupRequest;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use Throwable;
use ZipArchive;

class BackupController extends Controller
{
    public function index()
    {
        $connectionName = (string) config('database.default');
        $driver = (string) config("database.connections.{$connectionName}.driver", $connectionName);
        $databaseName = (string) config("database.connections.{$connectionName}.database", '-');
        $tableCount = count($this->getTableNames($driver));

        $storagePath = storage_path('app/public');
        [$storageFileCount, $storageTotalBytes] = $this->inspectDirectory($storagePath);
        $storageSizeFormatted = $this->formatBytes($storageTotalBytes);

        return view('layouts.pages.pengaturan.backup.index', compact(
            'driver',
            'databaseName',
            'tableCount',
            'storageFileCount',
            'storageSizeFormatted',
        ));
    }

    public function exportDatabase()
    {
        $connectionName = (string) config('database.default');
        $driver = (string) config("database.connections.{$connectionName}.driver", $connectionName);
        $databaseName = basename((string) config("database.connections.{$connectionName}.database", 'database'));
        $filename = 'backup-db-'.preg_replace('/[^a-zA-Z0-9_-]/', '_', $databaseName).'-'.now()->format('Ymd-His').'.sql';

        $sql = $this->buildSqlDump($driver, $databaseName);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Mengunduh backup database',
            "Ekspor database ({$databaseName}) ke berkas {$filename}.",
            'info',
        );

        return response()->streamDownload(function () use ($sql): void {
            echo $sql;
        }, $filename, [
            'Content-Type' => 'application/sql; charset=UTF-8',
        ]);
    }

    public function importDatabase(BackupRequest $request)
    {
        $file = $request->file('sql_file');
        $sqlContent = (string) file_get_contents($file->getRealPath());

        if (trim($sqlContent) === '') {
            return redirect()
                ->route('pengaturan.backup.index')
                ->with('error', 'Berkas SQL kosong dan tidak dapat dipulihkan.');
        }

        $connectionName = (string) config('database.default');
        $driver = (string) config("database.connections.{$connectionName}.driver", $connectionName);

        try {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF;');
            }

            DB::unprepared($sqlContent);

            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON;');
            }
        } catch (Throwable $e) {
            return redirect()
                ->route('pengaturan.backup.index')
                ->with('error', 'Gagal memulihkan database: '.$e->getMessage());
        }

        ActivityLog::record(
            Auth::user(),
            'security',
            'Memulihkan database',
            "Impor database dari berkas {$file->getClientOriginalName()}.",
            'warning',
        );

        return redirect()
            ->route('pengaturan.backup.index')
            ->with('success', 'Database berhasil dipulihkan dari berkas SQL.');
    }

    public function exportStorage()
    {
        if (! class_exists(ZipArchive::class)) {
            return redirect()
                ->route('pengaturan.backup.index')
                ->with('error', 'Ekstensi PHP ZipArchive tidak tersedia pada server.');
        }

        $storagePath = storage_path('app/public');
        File::ensureDirectoryExists($storagePath);

        $tempDir = storage_path('app/private/backups');
        File::ensureDirectoryExists($tempDir);

        $filename = 'backup-storage-public-'.now()->format('Ymd-His').'.zip';
        $zipPath = $tempDir.DIRECTORY_SEPARATOR.$filename;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Gagal membuat arsip ZIP.');
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($storagePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        $added = 0;
        foreach ($files as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $filePath = $file->getRealPath();
            $relativePath = str_replace('\\', '/', substr($filePath, strlen($storagePath) + 1));
            $zip->addFile($filePath, $relativePath);
            $added++;
        }

        if ($added === 0) {
            $zip->addFromString('.gitkeep', '');
        }

        $zip->close();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Mengunduh backup storage public',
            "Arsip {$added} berkas dari storage/app/public ({$filename}).",
            'info',
        );

        return response()->download($zipPath, $filename)->deleteFileAfterSend(true);
    }

    public function importStorage(BackupRequest $request)
    {
        if (! class_exists(ZipArchive::class)) {
            return redirect()
                ->route('pengaturan.backup.index')
                ->with('error', 'Ekstensi PHP ZipArchive tidak tersedia pada server.');
        }

        $file = $request->file('storage_file');
        $storagePath = storage_path('app/public');
        File::ensureDirectoryExists($storagePath);

        $zip = new ZipArchive();
        if ($zip->open($file->getRealPath()) !== true) {
            return redirect()
                ->route('pengaturan.backup.index')
                ->with('error', 'Berkas ZIP tidak valid atau rusak.');
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entryName = str_replace('\\', '/', (string) $zip->getNameIndex($i));
            if (str_contains($entryName, '../') || str_starts_with($entryName, '/')) {
                $zip->close();

                return redirect()
                    ->route('pengaturan.backup.index')
                    ->with('error', 'Arsip ZIP mengandung path yang tidak aman.');
            }
        }

        $zip->extractTo($storagePath);
        $extractedCount = $zip->numFiles;
        $zip->close();

        ActivityLog::record(
            Auth::user(),
            'security',
            'Memulihkan storage public',
            "Ekstrak {$extractedCount} item dari {$file->getClientOriginalName()} ke storage/app/public.",
            'warning',
        );

        return redirect()
            ->route('pengaturan.backup.index')
            ->with('success', "Storage public berhasil dipulihkan ({$extractedCount} item diekstrak).");
    }

    /**
     * @return array<int, string>
     */
    private function getTableNames(string $driver): array
    {
        if ($driver === 'mysql' || $driver === 'mariadb') {
            $rows = DB::select('SHOW TABLES');

            return array_values(array_map(fn (object $row) => (string) array_values((array) $row)[0], $rows));
        }

        if ($driver === 'sqlite') {
            $rows = DB::select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name");

            return array_values(array_map(fn (object $row) => (string) $row->name, $rows));
        }

        return [];
    }

    private function buildSqlDump(string $driver, string $databaseName): string
    {
        $pdo = DB::connection()->getPdo();
        $tables = $this->getTableNames($driver);

        $lines = [
            '-- SIUISKU Database Backup',
            '-- Database: '.$databaseName,
            '-- Generated at: '.now()->toDateTimeString(),
            '',
        ];

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $lines[] = 'SET FOREIGN_KEY_CHECKS=0;';
            $lines[] = '';
        } elseif ($driver === 'sqlite') {
            $lines[] = 'PRAGMA foreign_keys=OFF;';
            $lines[] = '';
        }

        foreach ($tables as $table) {
            $quotedTable = $driver === 'sqlite' ? '"'.str_replace('"', '""', $table).'"' : '`'.str_replace('`', '``', $table).'`';
            $lines[] = "DROP TABLE IF EXISTS {$quotedTable};";

            if ($driver === 'mysql' || $driver === 'mariadb') {
                $create = DB::selectOne("SHOW CREATE TABLE {$quotedTable}");
                $createValues = array_values((array) $create);
                $lines[] = ($createValues[1] ?? '').';';
            } elseif ($driver === 'sqlite') {
                $create = DB::selectOne("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ?", [$table]);
                if ($create && filled($create->sql)) {
                    $lines[] = $create->sql.';';
                }
            }

            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                $rowArray = (array) $row;
                $columns = array_map(
                    fn (string $col) => $driver === 'sqlite' ? '"'.str_replace('"', '""', $col).'"' : '`'.str_replace('`', '``', $col).'`',
                    array_keys($rowArray)
                );
                $values = array_map(function (mixed $value) use ($pdo): string {
                    if ($value === null) {
                        return 'NULL';
                    }

                    return $pdo->quote((string) $value);
                }, array_values($rowArray));

                $lines[] = 'INSERT INTO '.$quotedTable.' ('.implode(', ', $columns).') VALUES ('.implode(', ', $values).');';
            }

            $lines[] = '';
        }

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $lines[] = 'SET FOREIGN_KEY_CHECKS=1;';
        } elseif ($driver === 'sqlite') {
            $lines[] = 'PRAGMA foreign_keys=ON;';
        }

        return implode("\n", $lines)."\n";
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function inspectDirectory(string $path): array
    {
        if (! is_dir($path)) {
            return [0, 0];
        }

        $count = 0;
        $bytes = 0;

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $count++;
                $bytes += $file->getSize();
            }
        }

        return [$count, $bytes];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }

        $units = ['KB', 'MB', 'GB', 'TB'];
        $size = $bytes / 1024;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return number_format($size, 2).' '.$units[$unitIndex];
    }
}
