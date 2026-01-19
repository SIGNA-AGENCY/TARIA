<?php
declare(strict_types=1);

/**
 * TARIA Node Builder
 * Atomic folder clone: template -> temp -> final
 * - Validates node name
 * - Blocks symlinks
 * - Writes node.json with password_hash
 */

const TARIA_TEMPLATE_DIR = '/var/www/html/_iso/taria-v.0.1.0/';
const TARIA_SITES_DIR    = '/var/www/html/_sites';

function taria_validate_node_name(string $name): array {
  $name = strtolower(trim($name));
  if ($name === '') return [false, '', 'INVALID_NAME'];

  // Allow: a-z 0-9 dash, 3-32 chars
  if (!preg_match('/^[a-z0-9][a-z0-9\-]{1,30}[a-z0-9]$/', $name)) {
    return [false, '', 'INVALID_NAME'];
  }

  // Basic reserved words
  $reserved = ['www','admin','api','root','assets','static','cdn','mail','ftp','ssh','taria','signa'];
  if (in_array($name, $reserved, true)) {
    return [false, '', 'NAME_RESERVED'];
  }

  return [true, $name, 'OK'];
}

function taria_join(string $base, string $child): string {
  $base = rtrim($base, '/');
  $child = ltrim($child, '/');
  return $base . '/' . $child;
}

function taria_mkdir(string $path): void {
  if (is_dir($path)) return;
  if (!mkdir($path, 0755, true) && !is_dir($path)) {
    throw new RuntimeException('MKDIR_FAILED');
  }
}

function taria_copy_tree(string $src, string $dst, array &$stats): void {
  $src = rtrim($src, '/');
  $dst = rtrim($dst, '/');

  $it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
  );

  foreach ($it as $item) {
    $srcPath = (string)$item;
    $rel = substr($srcPath, strlen($src));
    $target = $dst . $rel;

    // Block symlinks (safer)
    if (is_link($srcPath)) {
      continue;
    }

    if ($item->isDir()) {
      if (!is_dir($target)) {
        if (!mkdir($target, 0755, true) && !is_dir($target)) {
          throw new RuntimeException('DIR_CREATE_FAILED');
        }
      }
      continue;
    }

    // Files
    $dir = dirname($target);
    if (!is_dir($dir)) {
      if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('DIR_CREATE_FAILED');
      }
    }

    if (!copy($srcPath, $target)) {
      throw new RuntimeException('FILE_COPY_FAILED');
    }

    // Preserve mode if possible
    @chmod($target, fileperms($srcPath) & 0777);

    $stats['files']++;
    $stats['bytes'] += (int)@filesize($target);
  }
}

/**
 * Builds a node folder under /var/www/html/_sites/<name>
 * Returns: [ok(bool), data(array), code(string)]
 */
function taria_build_node(string $name, string $email, string $password): array {
  $t0 = microtime(true);

  if (!is_dir(TARIA_TEMPLATE_DIR)) return [false, [], 'TEMPLATE_MISSING'];
  if (!is_dir(TARIA_SITES_DIR)) return [false, [], 'SITES_DIR_MISSING'];

  [$ok, $safeName, $code] = taria_validate_node_name($name);
  if (!$ok) return [false, [], $code];

  $finalDir = taria_join(TARIA_SITES_DIR, $safeName);
  if (file_exists($finalDir)) return [false, [], 'NAME_UNAVAILABLE'];

  // Unique temp build dir
  $rand = bin2hex(random_bytes(6));
  $tmpDir = taria_join(TARIA_SITES_DIR, ".building-{$safeName}-{$rand}");

  $stats = ['files' => 0, 'bytes' => 0];

  try {
    taria_mkdir($tmpDir);

    // Clone template into tmp
    taria_copy_tree(rtrim(TARIA_TEMPLATE_DIR, '/'), $tmpDir, $stats);

    // Write node metadata (hash password)
    $node = [
      'node'            => $safeName,
      'email'           => $email,
      'created_at'      => gmdate('c'),
      'template'        => basename(rtrim(TARIA_TEMPLATE_DIR, '/')),
      'template_path'   => rtrim(TARIA_TEMPLATE_DIR, '/'),
      'password_hash'   => password_hash($password, PASSWORD_DEFAULT),
      'engine_version'  => '0.1.0',
    ];

    $nodeJson = json_encode($node, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($nodeJson === false) throw new RuntimeException('NODE_JSON_FAILED');

    if (file_put_contents(taria_join($tmpDir, 'node.json'), $nodeJson) === false) {
      throw new RuntimeException('NODE_WRITE_FAILED');
    }

    // Atomic move into place
    if (!rename($tmpDir, $finalDir)) {
      throw new RuntimeException('ATOMIC_RENAME_FAILED');
    }

    $ms = (int)round((microtime(true) - $t0) * 1000);

    return [true, [
      'node'  => $safeName,
      'path'  => $finalDir,
      'files' => $stats['files'],
      'bytes' => $stats['bytes'],
      'ms'    => $ms,
    ], 'OK'];

  } catch (Throwable $e) {
    // Best-effort cleanup
    if (is_dir($tmpDir)) {
      taria_rrmdir($tmpDir);
    }
    return [false, ['error' => $e->getMessage()], 'BUILD_FAILED'];
  }
}

function taria_rrmdir(string $dir): void {
  if (!is_dir($dir)) return;

  $it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST
  );

  foreach ($it as $item) {
    $path = (string)$item;
    if ($item->isDir()) @rmdir($path);
    else @unlink($path);
  }
  @rmdir($dir);
}
