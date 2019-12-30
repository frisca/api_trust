<?php

class AllModel extends CI_Model {
    function getDataAll($table, $where){
        $this->db->where($where);
        return $this->db->get($table);
    }

    function getAll($table){
        return $this->db->get($table);
    }

    function getDataById($table, $nama_condition, $id){
        $this->db->where($nama_condition, $id);
        return $this->db->get($table);
    }

    function saveData($table, $data){
        $this->db->insert($table, $data);
        return true;
    }

    function updateData($table, $data, $nama_condition, $id){
        $this->db->where($nama_condition, $id);
        $this->db->update($table, $data);
        return true;
    }

    function deleteData($table, $nama_condition, $id){
        $this->db->where($nama_condition, $id);
        $this->db->delete($table);
        return true;
    }

    function getDataRows($table, $where){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($where);
        return $this->db->get();
    }

    function getDataByDesc($table, $field, $sort, $limit){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->order_by($field,$sort);
        $this->db->limit($limit);
        return $this->db->get();
    }

    function getDataLaporan(){
        $this->db->select('*,sum(sisa_etol) as total');
        $this->db->from('t_rc_e_card_detail');
        $this->db->group_by('e_card_code');
        return $this->db->get();
    }

    function getLaporanMobil(){
        $query = " select b.tanggal, sum(b.car_out) as jmlh_mobil_keluar, sum(b.car_in) as jmlh_mobil_kembali, 
                (select count(car_no) - sum(b.car_out) as total_mobil from t_rc_car) as total_mobil
                FROM 
                ( SELECT o.out_dt as tanggal, count(o.out_no) as car_out, '' as car_in
                    FROM t_rc_car_out o 
                    GROUP BY tanggal
                    UNION ALL
                    SELECT i.in_dt as tanggal, '' as car_out, COUNT(i.in_no) as car_in 
                    FROM t_rc_car_in i GROUP BY tanggal
                ) as b
                group by b.tanggal";
        $result = $this->db->query($query);
        return $result;
    }

    function getMobilKeluar(){
        $query = "select a.*, b.no_plat as no_plat from t_rc_car_out a
        left join t_rc_car b on b.car_no = a.car_no";
        $result = $this->db->query($query);
        return $result;
    }

    function getMobilKembali(){
        $query = "select a.*, b.no_plat as no_plat, o.km_awal, o.out_dt as tanggal_keluar from t_rc_car_in a
        left join t_rc_car b on b.car_no = a.car_no
        left join t_rc_car_out o on o.out_no = a.out_no";
        $result = $this->db->query($query);
        return $result;
    }
}