<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../../classes/movie.php');
include_once($filepath . '/../../classes/series.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$type = isset($_POST['type']) ? strtolower(trim($_POST['type'])) : '';
$popular = isset($_POST['popular']) ? (int)$_POST['popular'] : 0;

if (!$id || !$type) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

try {
    if ($type === 'movie') {
        $movie = new Movie();
        $result = $movie->updatePopular($id, $popular);
    } else {
        $series = new Series();
        $result = $series->updatePopular($id, $popular);
    }

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update popular status']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 