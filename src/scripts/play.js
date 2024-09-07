function trackPlay(audioElement){ 
    fetch('../../trackplay.php')
        .then(response => response.json())
        .then(data => {
            if (data.success){
                console.log('Play count updated')
            } else { 
                console.log(data.message || 'Failed to update play count')
                audioElement.pause();
                audioElement.currentTime = 0;
                audioElement.controls = false;
                audioElement.insertAdjacentHTML('afterend', '<p>You have reached the maximum number of plays. Please log in to continue listening.</p>');
            }
        })
        .catch(error => {
            console.log('Error updating play count: ', error)
        })
}