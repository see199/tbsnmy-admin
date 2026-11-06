<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate_log_model extends CI_Model
{
    /** Logging must never break the page. */
    public function record($type, $year, $nric, $found, $cert_id, $ip, $ua)
    {
        $debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        $this->db->insert('certificate_logs', array(
            'created_at'     => date('Y-m-d H:i:s'),
            'event_type'     => $type,
            'year'           => (int) $year,
            'nric'           => $nric,
            'found'          => $found ? 1 : 0,
            'certificate_id' => $cert_id,
            'ip'             => $ip,
            'user_agent'     => substr((string) $ua, 0, 255),
        ));
        $this->db->db_debug = $debug;
    }

    public function recent_misses($ip, $seconds)
    {
        return $this->db
            ->where('event_type', 'search')
            ->where('found', 0)
            ->where('ip', $ip)
            ->where('created_at >=', date('Y-m-d H:i:s', time() - $seconds))
            ->count_all_results('certificate_logs');
    }
}
