<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table         = 'certificates';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['year', 'group_code', 'nric', 'name', 'cert_type', 'award_level', 'pdf_url'];

    public function currentYear(): int
    {
        $y = config(\Config\Certificate::class)->year;
        if ($y) {
            return (int) $y;
        }
        $row = $this->selectMax('year')->first();

        return (int) ($row['year'] ?? date('Y'));
    }

    /** Award certificate first, then participation. */
    public function findByNric(string $nric, int $year): array
    {
        return $this->where(['nric' => $nric, 'year' => $year])
            ->orderBy("FIELD(cert_type,'award','participation')", '', false)
            ->findAll();
    }
}
