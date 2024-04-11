var elements = document.getElementsByClassName("snippet-footer");
Array.from(elements).forEach((element) => {
    element.addEventListener("click", (e) => {
        if(e.target.classList.contains("up-vote") || e.target.classList.contains("down-vote")) {
            let action = "";
            if (e.target.classList.contains("up-vote"))
                action = "like";
            else if (e.target.classList.contains("down-vote"))
                action = "dislike";

            fetch('http://localhost/snippetmanager/php/likeSystem.php?postID=' + e.target.parentNode.parentNode.dataset.id + '&actionType=' + action)
                .then(response => response.text())
                .then(text => { return JSON.parse(text); })
                .then(data => {
                    let snippetScoreElement = e.target.parentNode.parentNode.querySelector(".snippet-score");
                    snippetScoreElement.innerHTML = data.snippet_score;
                    e.target.classList.add("active");
                    if (action == "like") {
                        e.target.nextElementSibling.nextElementSibling.classList.remove("active");
                    } else if (action == "dislike") {
                        e.target.previousElementSibling.previousElementSibling.classList.remove("active");
                    }
                });
        }
    });
});