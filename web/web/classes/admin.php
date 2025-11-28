<?php 
$filepath = realpath(dirname(__FILE__));
include_once($filepath."/../lib/database.php");
include_once($filepath."/../helpers/format.php");

class Admin {
    private $db;
    private $fm;

    public function __construct() {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function add($data) {
        $name = mysqli_real_escape_string($this->db->link, $data['username']);
        $email = mysqli_real_escape_string($this->db->link, $data['email']);
        $password = mysqli_real_escape_string($this->db->link, md5($data['password']));
        $confirm_password = mysqli_real_escape_string($this->db->link, md5($data['confirm_password']));
        if(empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            $msg = "<div class='alert alert-danger'><strong>Lỗi!</strong> Phải điền đầy đủ thông tin!</div>";
            return $msg;
        }
        if($password != $confirm_password) {
            $msg = "<div class='alert alert-danger'><strong>Lỗi!</strong> Mật khẩu và xác nhận mật khẩu không khớp!</div>";
            return $msg;
        }
        $queryCheck = "SELECT * FROM admin WHERE email='$email'";
        $resultCheck = $this->db->select($queryCheck);
        if($resultCheck) {
            $msg = "<div class='alert alert-danger'><strong>Lỗi!</strong> Email đã tồn tại!</div>";
            return $msg;
        }
        $query = "INSERT INTO admin (`name`, email, `password`) VALUES ('$name', '$email', '$password')";
        $result = $this->db->insert($query);
        if($result) {
            $msg = "<div class='alert alert-success'><strong>Thành công!</strong> Thêm admin thành công!</div>";
            return $msg;
        } else {
            $msg = "<div class='alert alert-danger'><strong>Lỗi!</strong> Thêm admin thất bại!</div>";
            return $msg;
        }

    }

    public function loginAdmin($email, $password) { 
        $email = $this->fm->validation($email);
        $password = $this->fm->validation($password);
        $email = mysqli_real_escape_string($this->db->link, $email);
        $password = mysqli_real_escape_string($this->db->link, $password);
        if(empty($email) || empty($password)) {
            $alter = "Email hoặc mật khẩu không được để trống!";
            return $alter;
        } else {
            $query = "SELECT * 
                        FROM admin 
                        WHERE email='$email' 
                        AND `password`='$password' 
                        LIMIT 1";
            $result = $this->db->select($query);
            if($result) {
                $value = $result->fetch_assoc();
                Session::set("admin", true);
                Session::set("id", $value["id"]);
                Session::set("name", $value["name"]);
                header("Location:index.php");
                return "thành công";
            } else {
                $alter = "Email hoặc mật khẩu không khớp!";
                return $alter;
            }
        }
    }

    public function changePassword($id, $password, $newpassword) {
        $password = $this->fm->validation($password);
        $queryCheck = "SELECT * FROM admin WHERE id='$id' AND `password`='$password'";
        $resultCheck = $this->db->select($queryCheck);
        if($resultCheck) {
            $newpassword = $this->fm->validation($newpassword);
            $query = "UPDATE admin SET `password`='$newpassword'";
            $result = $this->db->update($query);
            if($result) {
                return "<div class='alert alert-success'><strong>Thành công!</strong> Đổi mật khẩu thành công</div>";
            }
        } else return "<div class='alert alert-danger'><strong>Lỗi!</strong> Mật khẩu không khớp!</div>";
    }

    public function getAdminByID($id) {
        $query = "SELECT * FROM admin WHERE id='$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function show() {
        $query = "SELECT * FROM admin";
        $result = $this->db->select($query);
        return $result;
    }

    public function update($data) {
        $id = Session::get('id');
        $name = mysqli_real_escape_string($this->db->link, $data['username']);
        $email = mysqli_real_escape_string($this->db->link, $data['email']);
        if(empty($name) || empty($email)) {
            $msg = "<div class='alert alert-danger'><strong>Lỗi!</strong> Phải điền đầy đủ thông tin!</div>";
            return $msg;
        } else {
            $query = "UPDATE admin SET `name`='$name', email='$email' WHERE id='$id'";
            $result = $this->db->update($query);
            if($result) {
                $msg = "<div class='alert alert-success'><strong>Thành công!</strong> Cập nhật thông tin thành công!</div>";
                return $msg;
            } else {
                $msg = "<div class='alert alert-danger'><strong>Lỗi!</strong> Cập nhật thông tin thất bại!</div>";
                return $msg;
            }
        }
    }

    public function searchAdmins($search) {
        $query = "SELECT * FROM admin WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
        $result = $this->db->select($query);
        return $result;
    }
    
    public function recentAdmin() {
        $query = "SELECT * FROM admin LIMIT 6";
        $result = $this->db->select($query);
        return $result;
    }
}

?>