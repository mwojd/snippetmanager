<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>snippet manager</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <div class="search">
                <input type="text" name="search" id="search-bar" placeholder="Search...">
            </div>
            <!-- profile -->
            <div class="profile">
                    <button class="profile-button">Profile</button>
            </div>
        </div>
        <div class="main">
            <div class="main-child"> <!-- this is where the snippets will be displayed -->
            <!-- example of a code snippet -->
                <div class="snippet-post">
                    <div class="snippet-header">
                        <span class="snippet-title">title</span>
                        <span class="snippet-author">author</span>
                    </div>
                    <div class="snippet-body">
                        <div class="snippet-code">code</div>
                    </div>
                    <div class="snippet-footer">
                        <button class="up-vote">arrow up</button>
                        <button class="comments">comm</button>
                        <button class="down-vote">arrow down</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>