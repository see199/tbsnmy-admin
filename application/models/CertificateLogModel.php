<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateLogModel extends Model
{
    protected $table         = 'certificate_logs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['created_at', 'event_type', 'year', 'nric', 'found', 'certificate_id', 'ip', 'user_agent'];

    /** Logging must never break the page. */
    public function record(string $type, int $year, string $nric, bool $found, ?int $certId, ?string $ip, ?string $ua): void
    {
        try {
            $this->insert([
                'created_at'     => date('Y-m-d H:i:s'),
                'event_type'     => $type,
                'year'           => $year,
                'nric'           => $nric,
                'found'          => $found ? 1 : 0,
                'certificate_id' => $certId,
                'ip'             => $ip,
                'user_agent'     => $ua !== null ? mb_substr($ua, 0, 255) : null,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'certificate log failed: ' . $e->getMessage());
        }
    }

    public function recentMisses(?string $ip, int $seconds): int
    {
        return $this->where('event_type', 'search')
            ->where('found', 0)
            ->where('ip', $ip)
            ->where('created_at >=', date('Y-m-d H:i:s', time() - $seconds))
            ->countAllResults();
    }
}
