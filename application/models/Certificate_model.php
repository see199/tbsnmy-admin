<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate_model extends CI_Model
{
    public function current_year()
    {
        $this->config->load('certificate', TRUE);
        $y = $this->config->item('year', 'certificate');
        if ($y) {
            return (int) $y;
        }
        $row = $this->db->select_max('year')->get('certificates')->row_array();

        return ($row && $row['year']) ? (int) $row['year'] : (int) date('Y');
    }

    /** Award certificate first, then participation. */
    public function find_by_nric($nric, $year)
    {
        return $this->db
            ->where(array('nric' => $nric, 'year' => $year))
            ->order_by("FIELD(cert_type,'award','participation')", '', FALSE)
            ->get('certificates')->result_array();
    }

    public function find($id)
    {
        return $this->db->get_where('certificates', array('id' => (int) $id))->row_array();
    }
}
