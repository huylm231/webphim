<?php 
$filepath = realpath(dirname(__FILE__));
include_once($filepath."/../lib/database.php");
include_once($filepath."/../helpers/format.php");

class Episode {
    private $db;
    private $fm;

    public function __construct() {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function add($data, $seriesId) {
        $title = mysqli_real_escape_string($this->db->link, $data['title']);
        $number_season = mysqli_real_escape_string($this->db->link, $data['season_number']);
        $episode_number = mysqli_real_escape_string($this->db->link, $data['episode_number']);
        $duration = mysqli_real_escape_string($this->db->link, $data['episode_duration']);
        $linkVideo = mysqli_real_escape_string($this->db->link, $data['video_url']);
        
        $query = "SELECT * FROM episode WHERE seriesId = '$seriesId' AND season_number = '$number_season' AND ep = '$episode_number'";
        $result = $this->db->select($query);
        if($result) {
            $msg = "<div class='alert alert-danger'>Tập phim đã tồn tại!</div>";
            return $msg;
        }

        if(empty($title) || empty($number_season) || empty($episode_number) || empty($duration) || empty($linkVideo)) {
            $msg = "<div class='alert alert-danger'>Phải điền đầy đủ thông tin!</div>";
            return $msg;
        } else {
            $query = "INSERT INTO episode(`seriesId`, `title`, `season_number`, `ep`, `runtime`, `linkVideo`) 
                    VALUES('$seriesId', '$title', '$number_season', '$episode_number', '$duration', '$linkVideo')";
            $inserted_row = $this->db->insert($query);
            if($inserted_row) {
                $msg = "<div class='alert alert-success'>Thêm tập phim thành công!</div>";
                return $msg;
            } else {
                $msg = "<div class='alert alert-danger'>Thêm tập phim thất bại!</div>";
                return $msg;
            }
        }
    }

    public function edit($id, $data) {
        $title = mysqli_real_escape_string($this->db->link, $data['title']);
        $number_season = mysqli_real_escape_string($this->db->link, $data['season_number']);
        $episode_number = mysqli_real_escape_string($this->db->link, $data['episode_number']);
        $duration = mysqli_real_escape_string($this->db->link, $data['episode_duration']);
        $linkVideo = mysqli_real_escape_string($this->db->link, $data['video_url']);
        
        if(empty($title) || empty($number_season) || empty($episode_number) || empty($duration) || empty($linkVideo)) {
            $msg = "<div class='alert alert-danger'>Phải điền đầy đủ thông tin!</div>";
            return $msg;
        } else {
            $query = "UPDATE episode SET
                        title = '$title',
                        season_number = '$number_season',
                        ep = '$episode_number',
                        runtime = '$duration',
                        linkVideo = '$linkVideo'
                        WHERE id = '$id'";
            $updated_row = $this->db->update($query);
            if($updated_row) {
                $msg = "<div class='alert alert-success'>Cập nhật tập phim thành công!</div>";
                return $msg;
            } else {
                $msg = "<div class='alert alert-danger'>Cập nhật tập phim thất bại!</div>";
                return $msg;
            }
        }
    }

    public function delGenerById($id) {
        $query = "DELETE FROM episode WHERE id = '$id'";
        $deleted_row = $this->db->delete($query);
        if($deleted_row) {
            $msg = "<span class='success'>Xóa tập phim thành công!</span>";
            return $msg;
        } else {
            $msg = "<span class='error'>Xóa tập phim thất bại!</span>";
            return $msg;
        }
    }

    public function getFilmBySeriesId($seriesId, $ep) {
        $query = "SELECT * FROM episode WHERE seriesId = '$seriesId' AND ep = '$ep'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getFilmById($id) {
        $query = "SELECT * FROM episode WHERE id = '$id'";
        $result = $this->db->select($query);
        return $result;
    }
    
    public function getEpisodeBySeason($seriesId, $season) {
        $query = "SELECT * FROM episode WHERE seriesId = '$seriesId' AND season_number = '$season'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getNumberOfEpisodes($seriesId) {
        $query = "SELECT COUNT(*) as total FROM episode WHERE seriesId = '$seriesId'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getNumberOfEpisodesBySeason($id, $season) {
        $query = "SELECT COUNT(*) as total FROM episode WHERE seriesId = '$id' AND season_number = '$season'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getSeasion($id) {
        $query = "SELECT DISTINCT season_number FROM episode WHERE seriesId = '$id'";
        $result = $this->db->select($query);
        return $result;
    }
}