var character = document.getElementById("character");
var obstacle = document.getElementById("obstacle");
var score = 0;
var highScore = 0;
var incrementScore;

function logout()
{
	// Rediriger vers la page de déconnexion
    window.location.href = "logout.php"; 
}

function Start()
{
	highScore = 0;
	obstacle.classList.add("obstacle_movement");

	function jump()
	{
		// Check if animation has already been attributed to our character
		if(character.classList != "jump")
		{
			// Add jump animation
			character.classList.add("jump");
			// Remove jump animation after few seconds
			setTimeout(function() 
			{
				character.classList.remove("jump");
			},400);
		}	
	}

	// Allow player to jump when press spacebar
	document.addEventListener("keydown", function(event)
	{
		jump();
	});

	var checkDead = setInterval(function()
	{
		// Get current Y character position
		var characterTop = parseInt(window.getComputedStyle(character).getPropertyValue("top"));

		// Get current X character position
		let obstacleLeft = parseInt(window.getComputedStyle(obstacle).getPropertyValue("left"));
	    
	    // Detect collision
	    if(obstacleLeft < 40 && obstacleLeft >- 40 && characterTop >= 150)
	    {
	    	// Arrête l'incrémentation du score
	    	clearInterval(incrementScore);
	        alert("Game Over ! Score: " + score);

	        // Get player's record
	        if (score > highScore) 
			{
			    highScore = score;
			    document.getElementById("highScore").innerHTML = highScore;
			    // Appel de la fonction pour enregistrer le meilleur score
			    savehighScoreScore(highScore);
			}
	        resetGame();
	        clearInterval(checkDead); 
	    }		
	}, 10);

	incrementScore = setInterval(function () {
        score ++;
        document.getElementById("score").innerHTML = score;
    }, 100);  
    
}

function resetGame() 
{
	obstacle.classList.remove("obstacle_movement");
	score = 0;
	document.getElementById("score").innerHTML = score;
	highScore = highScore;
}

/*
function savehighScoreScore(highScoreScore) {
    var data = { score: highScoreScore };

    fetch("save_score.php", { // Appel vers le fichier PHP
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (response.ok) {
                console.log("Le meilleur score a été enregistré avec succès !");
            } else {
                console.log("Erreur lors de l'enregistrement du meilleur score.");
            }
        })
        .catch(error => {
            console.log("Erreur lors de la requête pour enregistrer le meilleur score :", error);
        });
}

*/


// IIFE (Immediately Invoked Function Expression) 
//    = (Expression de fonction invoquée immédiatement)

