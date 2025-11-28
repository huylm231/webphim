<?php 
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../helpers/Format.php');

class Movie {
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
        $video_url = mysqli_real_escape_string($this->db->link, $data['video_url']);
        $poster = mysqli_real_escape_string($this->db->link, $data['poster']);
        $quality = mysqli_real_escape_string($this->db->link, $data['quality']);
        $popular = mysqli_real_escape_string($this->db->link, $data['popular']);
        $categories = $data['categories'];
        if(!is_array($categories)) {
            $categories = explode(',', $categories);
        }
        if($title == "" || $description == "" || $releaseYear == "" || $duration == "" || $video_url == "" || $poster == "") {
            return "<div class='alert alert-danger'>Phải điền đầy đủ thông tin!</div>";
        }

        $query = "INSERT INTO movie (title, `description`, releaseYear, runtime, linkVideo, photo, quality, popular) 
                VALUES ('$title', '$description', '$releaseYear', '$duration', '$video_url', '$poster', '$quality', '$popular')";
        $result = $this->db->insert($query);
        if(!$result) {
            return "<div class='alert alert-danger'>Thêm phim thất bại!</div>";
        }
        $query1 = "SELECT * FROM movie 
                    WHERE title = '$title'
                    AND releaseYear = '$releaseYear' 
                    AND runtime = '$duration' 
                    AND linkVideo = '$video_url' 
                    AND photo = '$poster'";
        $result1 = $this->db->select($query1);
        if($result1) {
            while($row = $result1->fetch_assoc())
                $movieId = $row['id'];
        }
        if(!empty($categories)) {
            foreach($categories as $category) {
                $query = "INSERT INTO link_film_genre (genreId, movieId) VALUES ('$category', '$movieId')";
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
        $video_url = mysqli_real_escape_string($this->db->link, $data['video_url']);
        $poster = mysqli_real_escape_string($this->db->link, $data['poster']);
        $quality = mysqli_real_escape_string($this->db->link, $data['quality']);
        $popular = mysqli_real_escape_string($this->db->link, $data['popular']);
        $categories = $data['categories'];
        if(!is_array($categories)) {
            $categories = explode(',', $categories);
        }
        if($title == "" || $description == "" || $releaseYear == "" || $duration == "" || $video_url == "" || $poster == "") {
            return "<div class='alert alert-danger'>Phải điền đầy đủ thông tin!</div>";
        }
        $query = "UPDATE movie SET 
                    title = '$title', 
                    `description` = '$description', 
                    releaseYear = '$releaseYear', 
                    runtime = '$duration', 
                    linkVideo = '$video_url', 
                    photo = '$poster', 
                    quality = '$quality', 
                    popular = '$popular' 
                    WHERE id = '$id'";
        $result = $this->db->update($query);
        if($result) {
            $query1 = "DELETE FROM link_film_genre WHERE movieId = '$id'";
            $this->db->delete($query1);
            if(!empty($categories)) {
                foreach($categories as $category) {
                    $query = "INSERT INTO link_film_genre (genreId, movieId) VALUES ('$category', '$id')";
                    $this->db->insert($query);
                }
            }
            return "<div class='alert alert-success'>Cập nhật phim thành công!</div>";
        }
        return "<div class='alert alert-danger'>Cập nhật phim thất bại!</div>";
    }

    public function deleteMovie($id) {
        $query = "DELETE FROM movie WHERE id = '$id'";
        $result = $this->db->delete($query);
        if($result) {
            $query1 = "DELETE FROM link_film_genre WHERE movieId = '$id'";
            $result1 = $this->db->delete($query1);
            if($result1) {
                $msg = "<div class='alert alert-success'>Xóa phim thành công!</div>";
                return $msg;
            }
        }
        $msg = "<div class='alert alert-danger'>Xóa phim thất bại!</div>";
        return $msg;
    }

    public function getPopularMovies() {
        $query = "SELECT * FROM movie WHERE popular='1' ORDER BY id DESC LIMIT 6";
        $result = $this->db->select($query);
        return $result;
    }

    public function getNewMovies() {
        $query = "SELECT * FROM movie ORDER BY id DESC LIMIT 8";
        $result = $this->db->select($query);
        return $result;
    }

    public function getMovieTopViews() {
        $query = "SELECT * FROM movie ORDER BY view DESC LIMIT 7";
        $result = $this->db->select($query);
        return $result;
    }

    public function getMovieById($id) {
        $query = "SELECT * FROM movie WHERE id = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getMovieByYear($year) {
        $query = "SELECT * FROM movie WHERE releaseYear = '$year'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getAllMovies() {
        $query = "SELECT * FROM movie ORDER BY id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function getCountMovies() {
        $query = "SELECT COUNT(*) AS Total FROM movie";
        $result = $this->db->select($query);
        return $result;
    }

    public function getRecentMovies() {
        $query = "SELECT * FROM movie ORDER BY id DESC LIMIT 3";
        $result = $this->db->select($query);
        return $result;
    }

    public function searchMovies($keyword) {
        $keyword = mysqli_real_escape_string($this->db->link, $keyword);
        $query = "SELECT * FROM movie 
                 WHERE title LIKE '%$keyword%' 
                 OR description LIKE '%$keyword%'
                 ORDER BY id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function updatePopular($id, $popular) {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $popular = mysqli_real_escape_string($this->db->link, $popular);
        $query = "UPDATE movie SET popular = '$popular' WHERE id = '$id'";
        $result = $this->db->update($query);
        return $result;
    }
}
?>