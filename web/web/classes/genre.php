<?php 
$filepath = realpath(dirname(__FILE__));
include_once($filepath."/../lib/database.php");
include_once($filepath."/../helpers/format.php");

class Genre {
    private $db;
    private $fm;

    public function __construct() {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function add($name) {
        $name = $this->fm->validation($name);
        $name = mysqli_real_escape_string($this->db->link, $name);
        if(empty($name)) {
            $msg = "<div class='alert alert-danger'>Phải điền đầy đủ thông tin!</div>";
            return $msg;
        } else {
            $query = "INSERT INTO genre(title) VALUES('$name')";
            $inserted_row = $this->db->insert($query);
            if($inserted_row) {
                $msg = "<div class='alert alert-success'>Thêm thể loại thành công!</div>";
                return $msg;
            } else {
                $msg = "<div class='alert alert-danger'>Thêm thể loại thất bại!</div>";
                return $msg;
            }
        }
    }

    public function edit($name, $id) {
        $name = $this->fm->validation($name);
        $name = mysqli_real_escape_string($this->db->link, $name);
        $id = mysqli_real_escape_string($this->db->link, $id);

        if(empty($name)) {
            $msg = "<div class='alert alert-danger'>Phải điền đầy đủ thông tin!</div>";
            return $msg;
        } else {
            $query = "UPDATE genre SET title='$name' WHERE id='$id'";
            $updated_row = $this->db->update($query);
            if($updated_row) {
                $msg = "<div class='alert alert-success'>Cập nhật thể loại thành công!</div>";
                return $msg;
            } else {
                $msg = "<div class='alert alert-danger'>Cập nhật thể loại thất bại!</div>";
                return $msg;
            }
        }
    }

    public function delete($id) {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $query = "DELETE FROM genre WHERE id='$id'";
        $deleted_row = $this->db->delete($query);
        if($deleted_row) {
            $msg = "<div class='alert alert-success'>Xóa thể loại thành công!</div>";
            return $msg;
        } else {
            $msg = "<div class='alert alert-danger'>Xóa thể loại thất bại!</div>";
            return $msg;
        }
    }

    public function getGenreById($id) {
        $query = "SELECT * FROM genre WHERE id='$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getAllGenres() {
        $query = "SELECT * FROM genre ORDER BY title";
        $result = $this->db->select($query);
        return $result;
    }

    public function getCountGenres() {
        $query = "SELECT COUNT(*) AS Total FROM genre";
        $result = $this->db->select($query);
        return $result;
    }

    public function searchGenres($search) {
        $query = "SELECT * FROM genre WHERE title LIKE '%$search%'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getLinkGenre($id, $type) {
        if($type == 'movie') {
            $query = "SELECT * FROM link_film_genre WHERE movieId = '$id'";
            $result = $this->db->select($query);
            return $result;
        } else {
            $query = "SELECT * FROM link_film_genre WHERE seriesId = '$id'";
            $result = $this->db->select($query);
            return $result;
        }
    }

    public function getGenreByLinkGenre($id, $type) {
        if($type == 'movie') {
            $query = "SELECT * FROM genre WHERE id IN (SELECT genreId FROM link_film_genre WHERE movieId = '$id')";
            $result = $this->db->select($query);
            return $result;
        } else {
            $query = "SELECT * FROM genre WHERE id IN (SELECT genreId FROM link_film_genre WHERE seriesId = '$id')";
            $result = $this->db->select($query);
            return $result;
        }
    }

    public function getMovieByGenreId($id) {
        $query = "SELECT B.* 
                    FROM link_film_genre A
                    INNER JOIN movie B ON A.movieId = B.id
                    WHERE A.genreId = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getSeriesByGenreId($id) {
        $query = "SELECT B.* 
                    FROM link_film_genre A
                    INNER JOIN series B ON A.seriesId = B.id
                    WHERE A.genreId = '$id'";
        $result = $this->db->select($query);
        return $result;
    }
}
?>