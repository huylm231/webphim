$(document).ready(function() {
    // Handle episode selection
    $('.episode-item').on('click', function() {
        const videoUrl = $(this).data('video');
        const episodeId = $(this).data('id');
        
        // Update video player
        $('#videoPlayer').attr('src', videoUrl);
        
        // Update active episode
        $('.episode-item').removeClass('active');
        $(this).addClass('active');
        
        // Update current episode info in HTML
        const episodeTitle = $(this).find('.episode-title').text();
        const seasonEpisode = $(this).find('.season-episode').text();
        
        $('#currentEpisodeTitle').text(episodeTitle);
        $('#currentEpisodeInfo').text(seasonEpisode);
        
        // Record in watch history if user is logged in
        const userId = $('#userData').data('user-id');
        const movieId = $('#movieData').data('movie-id');
        
        if (userId) {
            $.ajax({
                url: BASE_URL + '/user/ajax/update_history.php',
                type: 'POST',
                data: {
                    user_id: userId,
                    movie_id: movieId,
                    episode_id: episodeId,
                    duration: 0
                },
                success: function(response) {
                    console.log('Watch history updated');
                }
            });
        }
    });
    
    // Handle video time tracking for watch history
    const videoElement = document.getElementById('videoPlayer');
    if (videoElement) {
        let lastUpdated = 0;
        
        videoElement.addEventListener('timeupdate', function() {
            const currentTime = Math.floor(videoElement.currentTime);
            
            // Only update every 30 seconds to reduce server load
            if (currentTime - lastUpdated >= 30) {
                lastUpdated = currentTime;
                
                const userId = $('#userData').data('user-id');
                const movieId = $('#movieData').data('movie-id');
                const episodeId = $('.episode-item.active').data('id') || null;
                
                if (userId) {
                    $.ajax({
                        url: BASE_URL + '/user/ajax/update_history.php',
                        type: 'POST',
                        data: {
                            user_id: userId,
                            movie_id: movieId,
                            episode_id: episodeId,
                            duration: currentTime
                        },
                        success: function(response) {
                            console.log('Watch progress updated');
                        }
                    });
                }
            }
        });
    }
    
    // Form validations for login/register
    $('#registerForm').on('submit', function(e) {
        const password = $('#password').val();
        const confirmPassword = $('#confirmPassword').val();
        
        if (password !== confirmPassword) {
            e.preventDefault();
            $('#passwordError').text('Passwords do not match').show();
        }
    });
    
    // AJAX search suggestions
    let searchTimeout;
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: BASE_URL + '/user/ajax/search_suggestions.php',
                    type: 'GET',
                    data: { q: query },
                    success: function(response) {
                        $('#searchSuggestions').html(response).show();
                    }
                });
            }, 300);
        } else {
            $('#searchSuggestions').hide();
        }
    });
    
    // Handle admin file uploads with preview
    $('#posterUpload').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#posterPreview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});

// Globally define BASE_URL for AJAX requests
const BASE_URL = document.location.origin + '/movie-website'; // Update this to match your config.php