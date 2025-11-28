<?php 
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../helpers/Format.php');

class Series {
    private $db;
    private $fm;

    public function __construct() {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function add($data) {
        $title = mysqli_real_escape_string($this->db->link, $data['title']);
        $description = mysqli_real_escape_string($this->db->link, $data['description']);
        $releaseYear = mysqli_real_escape_string($this->db->link, $data['releaseYear']);
        $duration = mysqli_real_escape_string($this->db->link, $data['duration']);
        $poster = mysqli_real_escape_string($this->db->link, $data['poster']);
        $quality = mysqli_real_escape_string($this->db->link, $data['quality']);
        $popular = mysqli_real_escape_string($this->db->link, $data['popular']);
        $categories = $data['categories'];
        if(!is_array($categories)) {
            $categories = explode(',', $categories);
        }
        if($title == "" || $description == "" || $releaseYear == "" || $duration == "" || $poster == "") {
            return "<div class='alert alert-danger'>Phải điền đầy đủ thông tin!</div>";
        }
        $query = "INSERT INTO series (title, `description`, releaseYear, runtime, photo, quality, popular) 
                VALUES ('$title', '$description', '$releaseYear', '$duration', '$poster', '$quality', '$popular')";
        $result = $this->db->insert($query);
        if(!$result) {
            return "<div class='alert alert-danger'>Thêm phim thất bại!</div>";
        }
        $query1 = "SELECT * FROM series 
                    WHERE title = '$title' 
                    AND releaseYear = '$releaseYear' 
                    AND runtime = '$duration' 
                    AND photo = '$poster'
                    AND quality = '$quality'
                    AND popular = '$popular'";
        $result1 = $this->db->select($query1);
        if($result1) {
            while($row = $result1->fetch_assoc())
                $seriesId = $row['id'];
        }
        if(!empty($categories)) {
            foreach($categories as $category) {
                $query = "INSERT INTO link_film_genre (genreId, seriesId) VALUES ('$category', '$seriesId')";
                $this->db->insert($query);
            }
        }
        return "<div class='alert alert-success'>Thêm phim thành công!</div>";
    }

    public function edit($data, $id) {
        $title = mysqli_real_escape_string($this->db->link, $data['title']);
        $description = mysqli_real_escape_string($this->db->link, $data['description']);
        $releaseYear = mysqli_real_escape_string($this->db->link, $data['releaseYear']);
        $duration = mysqli_real_escape_string($this->db->link, $data['duration']);
        $poster = mysqli_real_escape_string($this->db->link, $data['poster']);
        $quality = mysqli_real_escape_string($this->db->link, $data['quality']);
        $popular = mysqli_real_escape_string($this->db->link, $data['popular']);
        $categories = $data['categories'];
        if(!is_array($categories)) {
            $categories = explode(',', $categories);
        }
        if($title == "" || $description == "" || $releaseYear == "" || $duration == "" || $poster == "") {
            return "<div class='alert alert-danger'>Phải điền đầy đủ thông tin!</div>";
        }
        $query = "UPDATE series SET 
                    title = '$title', 
                    `description` = '$description', 
                    releaseYear = '$releaseYear', 
                    runtime = '$duration', 
                    photo = '$poster', 
                    quality = '$quality', 
                    popular = '$popular' 
                    WHERE id = '$id'";
        $result = $this->db->update($query);
        if($result) {
            $query1 = "DELETE FROM link_film_genre WHERE seriesId = '$id'";
            $this->db->delete($query1);
            if(!empty($categories)) {
                foreach($categories as $category) {
                    $query = "INSERT INTO link_film_genre (genreId, seriesId) VALUES ('$category', '$id')";
                    $this->db->insert($query);
                }
            }
            return "<div class='alert alert-success'>Cập nhật phim thành công!</div>";
        }
        return "<div class='alert alert-danger'>Cập nhật phim thất bại!</div>";
    }

    public function deleteSeries($id) {
        $query = "DELETE FROM series WHERE id = '$id'";
        $result = $this->db->delete($query);
        if($result) {
            $query1 = "DELETE FROM link_film_genre WHERE seriesId = '$id'";
            $result1 = $this->db->delete($query1);
            if($result1) {
                $msg = "<div class='alert alert-success'>Xóa phim thành công!</div>";
                return $msg;
            }
        }
        $msg = "<div class='alert alert-danger'>Không xóa được phim</div>";
        return $msg;
    }

    public function getPopularSeries() {
        $query = "SELECT * FROM series ORDER BY id DESC LIMIT 8";
        $result = $this->db->select($query);
        return $result;
    }

    public function getSeriesById($id) {
        $query = "SELECT * FROM series WHERE id = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getSeriesByYear($year) {
        $query = "SELECT * FROM series WHERE releaseYear = '$year'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getAllSeries() {
        $query = "SELECT * FROM series ORDER BY id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function getCountSeries() {
        $query = "SELECT COUNT(*) as Total FROM series";
        $result = $this->db->select($query);
        return $result;
    }

    public function getRecentSeries() {
        $query = "SELECT * FROM series ORDER BY id DESC LIMIT 3";
        $result = $this->db->select($query);
        return $result;
    }

    public function searchSeries($keyword) {
        $keyword = mysqli_real_escape_string($this->db->link, $keyword);
        $query = "SELECT * FROM series 
                 WHERE title LIKE '%$keyword%' 
                 OR description LIKE '%$keyword%'
                 ORDER BY id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function updatePopular($id, $popular) {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $popular = mysqli_real_escape_string($this->db->link, $popular);
        $query = "UPDATE series SET popular = '$popular' WHERE id = '$id'";
        $result = $this->db->update($query);
        return $result;
    }
}
?>